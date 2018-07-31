<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

define('SVN_MIN_VERSION', 1.3);
define('TIKISVN', 'https://svn.code.sf.net/p/tikiwiki/code');

/**
 * @param $relative
 * @return string
 */
function full($relative)
{
	return TIKISVN . "/$relative";
}

/**
 * @param $full
 * @return string
 */
function short($full)
{
	return substr($full, strlen(TIKISVN) + 1);
};

/**
 * @param $string string			String to output
 * @param $color string				The colour of the string
 * @return string					The formatted string to output to the console
 */
function color($string, $color)
{
	$avail = [
		'red' => 31,
		'green' => 32,
		'yellow' => 33,
		'blue' => 34,
		'purple' => 35,
		'cyan' => 36,
		'gray' => 37,
	];

	if (! isset($avail[$color])) {
		return $string;
	}

	return "\033[{$avail[$color]}m$string\033[0m";
}

/**
 * @param $message
 */
function error($message)
{
	die(color($message, 'red') . "\n");
}

/**
 * @param $message
 */
function info($message)
{
	echo color($message, 'blue') . "\n";
}

/**
 * @param $message
 */
function important($message)
{
	echo color($message, 'green') . "\n";
}

/**
 * @return bool
 */
function check_svn_version()
{
	return version_compare(trim(`svn --version --quiet 2> /dev/null`), SVN_MIN_VERSION, '>');
}

/**
 * @param $path
 * @return object
 */
function get_info($path)
{
	$esc = escapeshellarg($path);
	$info = @simplexml_load_string(`svn info --xml $esc 2> /dev/null`);

	return $info;
}

/**
 * @param $url
 * @return bool
 */
function is_valid_merge_destination($url)
{
	return is_trunk($url) || is_experimental($url);
}

/**
 * @param $destination
 * @param $source
 * @return bool
 */
function is_valid_merge_source($destination, $source)
{
	if (is_trunk($destination)) {
		return is_stable($source);
	}

	if (is_experimental($destination)) {
		return is_trunk($source);
	}

	return false;
}

/**
 * @param $branch
 * @return bool
 */
function is_valid_branch($branch)
{
	return is_stable($branch) || is_experimental($branch);
}

/**
 * @param $branch
 * @return bool
 */
function is_stable($branch)
{
	return dirname($branch) == full('branches')
		&& (preg_match("/^\d+\.[\dx]+$/", basename($branch)) || preg_match("/test$/", basename($branch)));
}

/**
 * @param $branch
 * @return bool
 */
function is_experimental($branch)
{
	return dirname($branch) == full('branches/experimental');
}

/**
 * @param $branch
 * @return bool
 */
function is_trunk($branch)
{
	return $branch == full('trunk');
}

/**
 * @param $localPath
 * @param bool $ignore_externals
 */
function update_working_copy($localPath, $ignore_externals = false)
{
	$localPath = escapeshellarg($localPath);
	$ignoreStr = $ignore_externals ? ' --ignore-externals' : '';
	`svn up $localPath$ignoreStr`;
}

/**
 * Indicates whether versioned files have been modified in the specified checkout
 *
 * @param $localPath string Path of the checkout
 * @return bool true if at least 1 versioned file has been modified, added or removed, false otherwise
 *
 * @see Similar function svn_files_identical()
 */
function has_uncommited_changes($localPath)
{
	$localPath = escapeshellarg($localPath);

	$dom = new DOMDocument;
	$dom->loadXML(`svn status --xml $localPath`);

	$xp = new DOMXPath($dom);
	$count = $xp->query("/status/target/entry/wc-status[@item = 'added' or @item = 'conflicted' or @item = 'deleted' or @item = 'modified' or @item = 'replaced']");

	return $count->length > 0;
}

/**
 * Get the number of changes in the specified checkout
 *
 * @param string $localPath    Path of the checkout
 * @return array               The files that differ (additions, removals and modifications) from the repository
 *
 *
 * @see Similar function has_uncommited_changes()
 */
function svn_files_differ($localPath)
{
	$localPath = escapeshellarg($localPath);

	$dom = new DOMDocument;
	$dom->loadXML(`svn status --xml $localPath`);
	$entries = [];

	foreach ($dom->getElementsByTagName('entry') as $entry) {
		// anything modified or added gets added to the excludes for secdb
		if ($entry->getElementsByTagName('wc-status')->length) {
			$entries[$entry->getAttribute('path')] = $entry->getElementsByTagName('wc-status')[0]->getAttribute('item');
		} else {
			$entries[$entry->getAttribute('path')] = '';
		}
	}

	return $entries;
}


/**
 * @param $localPath
 * @return DOMNodeList
 */
function get_conflicts($localPath)
{
	$localPath = escapeshellarg($localPath);

	$dom = new DOMDocument;
	$dom->loadXML(`svn status --xml $localPath`);

	$xp = new DOMXPath($dom);
	$list = $xp->query("/status/target/entry/wc-status[@item = 'conflicted']");

	return $list;
}

/**
 * @param $path
 * @param $source
 * @return int
 */
function find_last_merge($path, $source)
{
	$short = preg_quote(short($source), '/');
	$pattern = "/^\\[(MRG|BRANCH)\\].*$short'?\s+\d+\s+to\s+(\d+)/";

	$descriptorspec = [
		0 => ['pipe', 'r'],
		1 => ['pipe', 'w'],
	];

	$ePath = escapeshellarg($path);

	$process = proc_open("svn log --stop-on-copy " . TIKISVN, $descriptorspec, $pipes);
	$rev = 0;
	$c = 0;

	if (is_resource($process)) {
		$fp = $pipes[1];

		while (! feof($fp)) {
			$line = fgets($fp, 1024);

			if (preg_match($pattern, $line, $parts)) {
				$rev = (int) $parts[2];
				break;
			}
			$c++;
			if ($c > 100000) {
				error("[MRG] or [BRANCH] message for '$source' not found in 1000000 lines of logs, something has gone wrong...");
				break;
			}
		}

		fclose($fp);
		proc_close($process);
	}

	return $rev;
}

/**
 * @param $localPath
 * @param $source
 * @param $from
 * @param $to
 */
function merge($localPath, $source, $from, $to)
{
	$short = short($source);
	$source = escapeshellarg($source);
	$from = (int) $from;
	$to = (int) $to;
	passthru("svn merge $source -r$from:$to");

	$message = "[MRG] Automatic merge, $short $from to $to";
	file_put_contents('svn-commit.tmp', $message);
}

/**
 * @param $msg
 * @param bool $displaySuccess
 * @param bool $dieOnRemainingChanges
 * @return int
 */
function commit($msg, $displaySuccess = true, $dieOnRemainingChanges = true)
{
	$msg = escapeshellarg($msg);
	`svn ci -m $msg`;

	if ($dieOnRemainingChanges && has_uncommited_changes('.')) {
		error("Commit seems to have failed. Uncommited changes exist in the working folder.\n");
	}

	return (int) get_info('.')->entry->commit['revision'];
}

/**
 * @param $working
 * @param $source
 */
function incorporate($working, $source)
{
	$working = escapeshellarg($working);
	$source = escapeshellarg($source);

	passthru($command = "svn merge $working $source");
}

/**
 * @param $source
 * @param $branch
 * @param $revision
 * @return bool
 */
function branch($source, $branch, $revision)
{
	$short = short($branch);

	$file = escapeshellarg("$branch/tiki-index.php");
	$source = escapeshellarg($source);
	$branch = escapeshellarg($branch);
	$message = escapeshellarg("[BRANCH] Creation, $short 0 to $revision");
	`svn copy $source $branch -m $message`;

	$f = @simplexml_load_string(`svn info --xml $file`);

	return isset($f->entry);
}

/**
 * @param $localPath
 * @param $minRevision
 * @param string $maxRevision
 * @return bool
 */
function get_logs($localPath, $minRevision, $maxRevision = 'HEAD')
{
	if (empty($minRevision) || empty($maxRevision)) {
		return false;
	}
	$logs = `LANG=C svn log -r$maxRevision:$minRevision $localPath`;
	return $logs;
}
