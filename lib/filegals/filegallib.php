<?php
// $Id: /cvsroot/tikiwiki/tiki/lib/filegals/filegallib.php,v 1.76.2.10 2008-03-16 00:07:11 nyloth Exp $

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

class FileGalLib extends TikiLib {
	function FileGalLib($db) {
		$this->TikiLib($db);
	}

	function isPodCastGallery($galleryId, $gal_info=null) {
		if (empty($gal_info))
			$gal_info = $this->get_file_gallery_info((int)$galleryId);
		if (($gal_info["type"]=="podcast") || ($gal_info["type"]=="vidcast")) {
			return true;
		} else {
			return false;
		}
	}

	function remove_file($fileInfo, $user, $galInfo='') {
		global $prefs, $smarty;

		if ($podCastGallery = $this->isPodCastGallery($galleryId, $galInfo)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}
		
		if ($fileInfo['path']) {
			unlink ($savedir . $fileInfo['path']);
		}
		$archives = $this->get_archives($fileInfo['fileId']);
		foreach ($archives['data'] as $archive) {
			if ($archive['path']) {
				unlink ($savedir . $archive['path']);
			}
			$this->remove_object('file', $archive['fileId']);
		}

		$query = 'delete from `tiki_files` where `fileId`=? or `archiveId`=?';
		$result = $this->query($query,array($fileInfo['fileId'], $fileInfo['fileId']));
		$this->remove_object('file', $fileInfo['fileId']);

		//Watches
		$this->notify($fileInfo['galleryId'], $fileInfo['name'], $fileInfo['filename'], '', 'remove file', $user);
		if ($prefs['feature_actionlog'] == 'y') {
			global $logslib; include_once('lib/logs/logslib.php');
			$logslib->add_action('Removed', $fileInfo['fileId'].'/'.$fileInfo['filename'], 'file', '');
		}
		return true;
	}

	function insert_file($galleryId, $name, $description, $filename, $data, $size, $type, $creator, $path, $comment='', $author, $created='', $lockedby=NULL) {
	  global $prefs, $tikilib, $smarty, $user;

		$name = strip_tags($name);
		if ($podCastGallery = $this->isPodCastGallery($galleryId)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}
		if (($prefs['fgal_use_db'] == 'n') || ($podCastGallery)) {
			if (function_exists('md5_file')) {
				$checksum = md5_file($savedir . $path);
			} else {
				$checksum = md5(implode('', file($savedir . $path)));
			}
		} else {
			$checksum = md5($data);
		}
		$description = strip_tags($description);

		if ( $prefs['fgal_allow_duplicates'] != 'y' ) {
			$fgal_query = 'select count(*) from `tiki_files` where `hash`=?';
			$fgal_vars = array($checksum);
			if ( $prefs['fgal_allow_duplicates'] == 'different_galleries' ) {
				$fgal_query .= ' and `galleryId`=?';
				$fgal_vars[] = $galleryId;
			}
			if ( $this->getOne($fgal_query, $fgal_vars) > 0 ) return false;
		}

		$search_data = '';
		if ($prefs['fgal_enable_auto_indexing'] != 'n') {
			$search_data = $this->get_search_text_for_data($data,$path,$type, $galleryId);
			if ($search_data === false)
				return false;
		}
		if ( empty($created) ) $created = $this->now;
		$query = "insert into `tiki_files`(`galleryId`,`name`,`description`,`filename`,`filesize`,`filetype`,`data`,`user`,`created`,`hits`,`path`,`hash`,`search_data`,`lastModif`,`lastModifUser`, `comment`, `author`, `lockedby`)
                          values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$result = $this->query($query,array($galleryId,trim($name),$description,$filename,$size,$type,$data,$creator,$created,0,$path,$checksum,$search_data,(int)$this->now,$user,$comment, $author, $lockedby));
		$query = "update `tiki_file_galleries` set `lastModif`=? where `galleryId`=?";
		$result = $this->query($query,array((int) $this->now,$galleryId));
		$query = "select max(`fileId`) from `tiki_files` where `created`=?";
		$fileId = $this->getOne($query,array((int) $created));

		if ($prefs['feature_score'] == 'y') {
		    $this->score_event($user, 'fgallery_new_file');
		}

		if ($prefs['feature_actionlog'] == 'y') {
			global $logslib; include_once('lib/logs/logslib.php');
			$logslib->add_action('Uploaded', $galleryId, 'file gallery', "fileId=$fileId&amp;add=$size");
		}

		if ( $prefs['feature_search'] == 'y' && $prefs['feature_search_fulltext'] != 'y' && $prefs['search_refresh_index_mode'] == 'normal' && ( $prefs['fgal_asynchronous_indexing'] != 'y' || ! isset($_REQUEST['fast']) ) ) {
			require_once('lib/search/refresh-functions.php');
			refresh_index('files', $fileId);
		}

		//Watches
		$smarty->assign('galleryId', $galleryId);
                $smarty->assign('fname', $name);
                $smarty->assign('filename', $filename);
                $smarty->assign('fdescription', $description);

		$this->notify($galleryId, $name, $filename, $description, 'upload file', $user);

		return $fileId;
	}

	function set_file_gallery($file, $gallery) {
		$query = "update `tiki_files` set `galleryId`=? where `fileId`=?";

		$this->query($query,array($gallery,$file));
	}

	function remove_file_gallery($id, $galleryId=0) {
		global $prefs;

		if (empty($galleryId)) {
			$info = $this->get_file_info($id);
			$galleryId = $info['galleryId'];
		}

		if ($podCastGallery = $this->isPodCastGallery($galleryId)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}

		$query = "select `path` from `tiki_files` where `galleryId`=?";
		$result = $this->query($query,array($id));

		while ($res = $result->fetchRow()) {
			$path = $res["path"];

			if ($path) {
				@unlink ($savedir . $path);
			}
		}

		$query = "delete from `tiki_file_galleries` where `galleryId`=?";
		$result = $this->query($query,array($id));
		$query = "delete from `tiki_files` where `galleryId`=?";
		$result = $this->query($query,array($id));
		$this->remove_object('file gallery', $id);
		return true;
	}

	function get_file_gallery_info($id) {
		$query = "select * from `tiki_file_galleries` where `galleryId`=?";

		$result = $this->query($query,array((int) $id));
		$res = $result->fetchRow();
		return $res;
	}

	function move_file_gallery($galleryId, $new_parent_id) {
		if ( (int)$galleryId <= 0 || (int)$new_parent_id != 0 ) return false;

		return $this->query(
			'update `tiki_file_galleries` set `parentId`=? where `galleryId`=?',
			array((int)$new_parent_id, (int)$galleryId)
		);
	}

	function replace_file_gallery($fgal_info) {

		global $prefs;
		// Default values
		if (!isset($fgal_info['visible']))  $fgal_info['visible'] = 'y';
		if (!isset($fgal_info['type']))  $fgal_info['type'] = 'default';
		if (!isset($fgal_info['parentId']))  $fgal_info['parentId'] = -1;
		if (!isset($fgal_info['lockable']))  $fgal_info['lockable'] = 'n';
		if (!isset($fgal_info['show_lockedby']))  $fgal_info['show_lockedby'] = 'y';
		if (!isset($fgal_info['archives']))  $fgal_info['archives'] = -1;
		if (!isset($fgal_info['show_modified']))  $fgal_info['show_modified'] = 'n';
		if (!isset($fgal_info['show_creator']))  $fgal_info['show_creator'] = 'n';
		if (!isset($fgal_info['show_author']))  $fgal_info['show_author'] = 'n';

		// if the user is admin or the user is the same user and the gallery exists
		// then replace if not then create the gallary if the name is unused.
		$name = strip_tags($name);

		$description = strip_tags($description);
		if ($sort_mode == 'created_desc') {
			$sort_mode = null;
		}

		if ($fgal_info['galleryId'] > 0) {
			$query = "update `tiki_file_galleries` set `name`=?, `maxRows`=?,
			`description`=?, `lastModif`=?, `public`=?, `visible`=?, `show_icon`=?,
			`show_id`=?, `show_name`=?, `show_description`=?, `show_size`=?,
			`show_created`=?, `show_hits`=?, `max_desc`=?, `type`=?, `parentId`=?,
			`user`=?, `lockable`=?, `show_lockedby`=?, `archives`=?, `sort_mode`=?,
			`show_modified`=?, `show_creator`=?, `show_author`=?, `subgal_conf`=?,
			`show_last_user`=?, `show_comment`=?, `show_files`=?, `show_explorer`=?,
			`show_path`=? where `galleryId`=?";

			$bindvars=array(trim($fgal_info['name']), (int) $fgal_info['maxRows'],
			$fgal_info['description'], (int) $this->now, $fgal_info['public'],
			$fgal_info['visible'], $fgal_info['show_icon'], $fgal_info['show_id'],
			$fgal_info['show_name'], $fgal_info['show_description'],
			$fgal_info['show_size'], $fgal_info['show_created'],
			$fgal_info['show_hits'], (int) $fgal_info['max_desc'],
			$fgal_info['type'], $fgal_info['parentId'], $fgal_info['user'],
			$fgal_info['lockable'], $fgal_info['show_lockedby'],
			$fgal_info['archives'], $fgal_info['sort_mode'],
			$fgal_info['show_modified'], $fgal_info['show_creator'],
			$fgal_info['show_author'], $fgal_info['subgal_conf'],
			$fgal_info['show_last_user'], $fgal_info['show_comment'],
			$fgal_info['show_files'], $fgal_info['show_explorer'],
			$fgal_info['show_path'], (int)$fgal_info['galleryId']);

			$result = $this->query($query,$bindvars);

			$query = "update `tiki_objects` set `name`=?, `description`=? where
				`type`=? and `itemId`=?";
			$bindvars = array($fgal_info['name'],$fgal_info['description'],'file
				gallery',(int)$fgal_info['galleryId']);
			$this->query($query,$bindvars);
			$galleryId = $fgal_info['galleryId'];
		} else {
			// Create a new record
			$query = "insert into `tiki_file_galleries`(`name`, `description`,
			`created`, `user`, `lastModif`, `maxRows`, `public`, `hits`, `visible`,
			`show_id`, `show_icon`, `show_name`, `show_description`, `show_created`,
			`show_hits`, `max_desc`, `type`, `parentId`, `lockable`, `show_lockedby`,
			`archives`, `sort_mode`, `show_modified`, `show_creator`, `show_author`,
			`subgal_conf`, `show_last_user`, `show_comment`, `show_files`,
			`show_explorer`, `show_path`) values (?,?,?,?,?,?,?,?,?,
			?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

			$bindvars=array($fgal_info['name'], $fgal_info['description'], (int)
			$this->now, $fgal_info['user'], (int) $this->now, (int)
			$fgal_info['maxRows'], $fgal_info['public'], 0, $fgal_info['visible'],
			$fgal_info['show_id'], $fgal_info['show_icon'], $fgal_info['show_name'],
			$fgal_info['show_description'], $fgal_info['show_created'],
			$fgal_info['show_hits'], (int) $fgal_info['max_desc'],
			$fgal_info['type'], $fgal_info['parentId'], $fgal_info['lockable'],
			$fgal_info['show_lockedby'], $fgal_info['archives'],
			$fgal_info['sort_mode'], $fgal_info['show_modified'],
			$fgal_info['show_creator'], $fgal_info['show_author'],
			$fgal_info['subgal_conf'], $fgal_info['show_last_user'],
			$fgal_info['show_comment'], $fgal_info['show_files'],
			$fgal_info['show_explorer'], $fgal_info['show_path']);

			$result = $this->query($query,$bindvars);
			$galleryId = $this->getOne("select max(`galleryId`) from
			`tiki_file_galleries` where `name`=? and
			`lastModif`=?",array($fgal_info['name'],(int) $this->now));

			if ($prefs['feature_score'] == 'y') {
			    $this->score_event($user, 'fgallery_new');
			}
		}

		if ( $prefs['feature_search'] == 'y' && $prefs['feature_search_fulltext'] != 'y' && $prefs['search_refresh_index_mode'] == 'normal' ) {
			require_once('lib/search/refresh-functions.php');
			refresh_index('file_galleries', $galleryId);
		}

		// event_handler($action,$object_type,$object_id,$options);
		return $galleryId;
	}

	function process_batch_file_upload($galleryId, $file, $user, $description) {
		global $prefs;

		include_once ('lib/pclzip.lib.php');
		include_once ('lib/mime/mimelib.php');
		$extract_dir = 'temp/'.basename($file).'/';
		mkdir($extract_dir);
		$archive = new PclZip($file);
		$archive->extract($extract_dir);
		unlink($file);
		$files = array();
		$h = opendir($extract_dir);
		$gal_info = $this->get_file_gallery_info($galleryId);
		if ($podCastGallery = $this->isPodCastGallery($galleryId, $gal_info)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}

		while (($file = readdir($h)) !== false) {
			if ($file != '.' && $file != '..' && is_file($extract_dir.'/'.$file)) {
				$files[] = $file;

				// check filters
				$upl = 1;

				if (!empty($prefs['fgal_match_regex'])) {
					if (!preg_match('/'.$prefs['fgal_match_regex'].'/', $file, $reqs))
						$upl = 0;
				}

				if (!empty($prefs['fgal_nmatch_regex'])) {
					if (preg_match('/'.$prefs['fgal_nmatch_regex'].'/', $file, $reqs))
						$upl = 0;
				}

				if (!($fp = fopen($extract_dir.$file, "rb"))) {
					$smarty->assign('msg', tra('Cannot open this file:'). "temp/$file");
					$smarty->display("error.tpl");
					die;
				}
				$data = '';
				$fhash = '';

				if (($prefs['fgal_use_db'] == 'n') || ($podCastGallery)) {
					$fhash = md5($name = $file);

					@$fw = fopen($savedir . $fhash, "wb");

					if (!$fw) {
						$smarty->assign('msg', tra('Cannot write to this file:'). $fhash);

						$smarty->display("error.tpl");
						die;
					}
				}
				while (!feof($fp)) {
					if (($prefs['fgal_use_db'] == 'y') && (!$podCastGallery)) {
						$data .= fread($fp, 8192 * 16);
					} else {
						$data = fread($fp, 8192 * 16);

						fwrite($fw, $data);
					}
				}

				fclose ($fp);

				if (($prefs['fgal_use_db'] == 'n') || ($podCastGallery)) {
					fclose ($fw);

					$data = '';
				}

				$size = filesize($extract_dir.$file);
				$name = $file;
				$type = tiki_get_mime($extract_dir.$file);
				$fileId = $this->insert_file($galleryId, $name, $description, $name, $data, $size, $type, $user, $fhash);
				unlink ($extract_dir.$file);
			}
		}

		closedir ($h);
		rmdir($extract_dir);
	}

	// Added by LeChuck, May 2, 2003
	function get_file_info($id) {
		$query = "select * from `tiki_files` where `fileId`=?";

		$result = $this->query($query,array($id));
		$res = $result->fetchRow();
		return $res;
	}

	function update_file($id, $name, $description, $user, $comment = NULL, $reindex = true) {

		// Update the fields in the database
		$name = strip_tags($name);

		$bindvars = array($name, $description, (int)$this->now, $user);
		if ( $comment === NULL ) {
			$comment_set = '';
		} else {
			$comment_set = ', `comment`=?';
			$bindvars[] = $comment;
		}
		$bindvars[] = $id;

		$description = strip_tags($description);
		$query = 'UPDATE `tiki_files` SET `name`=?, `description`=?, `lastModif`=?, `lastModifUser`=?'.$comment_set.' WHERE `fileId`=?';
		$result = $this->query($query, $bindvars);

		// Get the gallery id for the file and update the last modified field
		$galleryId = $this->getOne('SELECT `galleryId` FROM `tiki_files` WHERE `fileId`=?', array($id));

		if ( $galleryId ) {
			$query = 'UPDATE `tiki_file_galleries` SET `lastModif`=? WHERE `galleryId`=?';
			$this->query($query, array($this->now, $galleryId));
		}

		global $prefs;
		if ( $reindex && $prefs['feature_search'] == 'y' && $prefs['feature_search_fulltext'] != 'y' && $prefs['search_refresh_index_mode'] == 'normal' ) {
			require_once('lib/search/refresh-functions.php');
			refresh_index('files', $id);
		}

		return $result;
	}

	function replace_file($id, $name, $description, $filename, $data, $size, $type, $creator, $path, $comment='', $gal_info, $didFileReplace, $author='', $created='', $lockedby=NULL) {
	  global $prefs, $tikilib, $user;

		// Update the fields in the database
		$name = strip_tags($name);

		if ($podCastGallery = $this->isPodCastGallery($gal_info['galleryId'], $gal_info)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}

		if (($prefs['fgal_use_db'] == 'n') || ($podCastGallery)) {
			if (function_exists('md5_file')) {
				if (!($checksum = md5_file($savedir . $path)))
					$checksum = '';
			} else {
				$checksum = md5(implode('', file($savedir . $path)));
			}
		} else {
			$checksum = md5($data);
		}

		$description = strip_tags($description);

		$search_data = '';
		if ($prefs['fgal_enable_auto_indexing'] != 'n') {
			$search_data = $this->get_search_text_for_data($data,$path,$type, $gal_info['galleryId']);
			if ($search_data === false)
				return false;
		}
		$oldPath = $this->getOne("select `path` from `tiki_files` where `fileId`=?",array($id));

		if ( $gal_info['archives'] == -1 || ! $didFileReplace ) { // no archive

			$query = "update `tiki_files` set `name`=?, `description`=?, `filename`=?, `filesize`=?, `filetype`=?, `data`=?, `lastModifUser`=?, `lastModif`=?, `path`=?, `hash`=?, `search_data`=?, `author`=?, `user`=?, `lockedby`=?  where `fileId`=?";
			if ( ! ( $result = $this->query($query,array(trim($name),$description,$filename,$size,$type,$data,$user,(int)$this->now,$path,$checksum,$search_data,$author,$creator,$lockedby, $id)) ) ) {
				return false;
			}

			if ( $didFileReplace && !empty($oldPath) ) {
				unlink($savedir . $oldPath);
			}

			if ( $prefs['feature_search'] == 'y' && $prefs['feature_search_fulltext'] != 'y' && $prefs['search_refresh_index_mode'] == 'normal' && ( $prefs['fgal_asynchronous_indexing'] != 'y' || ! isset($_REQUEST['fast']) ) ) {
				require_once('lib/search/refresh-functions.php');
				refresh_index('files', $id);
			}

		} else { //archive the old file : change archive_id, take away from indexation and categorization

			// Insert and index (for search) the new file
			$idNew = $this->insert_file($gal_info['galleryId'], $name, $description, $filename, $data, $size, $type, $creator, $path, $comment, $author, $created, $lockedby);

			if ($gal_info['archives'] > 0) {
				$archives = $this->get_archives($id, 0, -1, 'created_asc');
				if ($archives['cant'] >= $gal_info['archives']) {
					$nb = $archives['cant'] - $gal_info['archives'] + 1;
					$query = "delete from `tiki_files` where `fileId` in (".implode(',', array_fill(0, $nb, '?')).")";
					for ($i = 0; $i < $nb; ++$i) {
						$bindvars[] = $archives['data'][$i]['fileId'];
						if ( $archives['data'][$i]['path'] ) {
							unlink ($savedir . $archives['data'][$i]['path']);
						}
					}
					$this->query($query, $bindvars);
				}
			}
			$query = "update `tiki_files` set `archiveId`=?, `search_data`=?,`user`=?, `lockedby`=? where `archiveId`=? or `fileId`=?";
			$this->query($query,array($idNew, '',$creator,NULL, $id, $id));

			if ($prefs['feature_categories'] == 'y') {
				global $categlib; require_once('lib/categories/categlib.php');
				$categlib->uncategorize_object('file', $id);
			}

			$id = $idNew;
		}

		if ($gal_info['galleryId']) {
			$query = "update `tiki_file_galleries` set `lastModif`=? where `galleryId`=?";

			$this->query($query,array($this->now,$gal_info['galleryId']));
		}

		return $id;
	}

	function change_file_handler($mime_type,$cmd) {
		$found = $this->getOne("select `mime_type` from `tiki_file_handlers` where `mime_type`=?",array($mime_type));

		if ($found) {
			$query = "update `tiki_file_handlers` set `cmd`=? where `mime_type`=?";
			$result = $this->query($query,array($cmd,$mime_type));
		}
		else {
			$query = "insert into `tiki_file_handlers` (`mime_type`,`cmd`) values (?,?)";
			$result = $this->query($query,array($mime_type,$cmd));
		}	
		
		return $result;
	}
	
	function delete_file_handler($mime_type) {
		if ($mime_type == 'default')
			return false;
			
		$query = "delete from `tiki_file_handlers` where `mime_type`=?";
		$result = $this->query($query,array($mime_type));
		return (($result) ? true : false);
	}

	function get_file_handlers() {
		$query = "select * from `tiki_file_handlers`";
		$result = $this->query($query);
		$fileParseApps = array();
		while ($row = $result->fetchRow()) {
			$fileParseApps[$row['mime_type']] = $row['cmd'];
		}
		
		return $fileParseApps;
	}

	function reindex_all_files_for_search_text() {
		$query = "select fileId, filename, filesize, filetype, data, path, galleryId from `tiki_files` where `archiveId`=?";
		$result = $this->query($query, array(0));
		$rows = array();
		while($row = $result->fetchRow()) {
			$rows[] = $row;
		}
		
		foreach($rows as $row) {
			$search_text = $this->get_search_text_for_data($row['data'],$row['path'],$row['filetype'], $row['galleryId']);
			if ($search_text!==false) {
				$query = "update `tiki_files` set `search_data`=? where `fileId`=?";
				$result = $this->query($query,array($search_text,$row['fileId']));
			}
		}
		include_once("lib/search/refresh-functions.php");
		refresh_index('files');
	}

	function get_search_text_for_data($data,$path,$type, $galleryId) {
		global $prefs;
		
		if (!isset($data) && !isset($path)) {
			return false;
		}

		if ($podCastGallery = $this->isPodCastGallery($galleryId)) {
			$savedir=$prefs['fgal_podcast_dir'];
		} else {
			$savedir=$prefs['fgal_use_dir'];
		}
		
		$fileParseApps = $this->get_file_handlers();

		$parseApp = '';
		if (array_key_exists($type,$fileParseApps))
			$parseApp = $fileParseApps[$type];
		elseif (array_key_exists('default',$fileParseApps))
			$parseApp = $fileParseApps['default'];

		if (empty($parseApp))
			return '';
			
		if (empty($path)) {	
			$tmpfname = tempnam("/tmp", "wiki_");
			$tmpFile = fopen($tmpfname,'w');
			if ($tmpFile === false)
				return false;
				
			if (fwrite($tmpFile,$data) === false)
				return false;
			fflush($tmpFile);
			fclose($tmpFile);
		}
		else {
			$tmpfname = $savedir . $path;
		}
		
		$cmd = str_replace('%1',$tmpfname,$parseApp);
		$handle = popen("$cmd","r");
		if ($handle === false) {
			if (empty($path))
				@unlink($tmpfname);
			return false;
		}
			
		$contents = '';
		while (!feof($handle)) {
			$contents .= fread($handle, 8192);
		}
		fclose($handle);
		
		if (empty($path))
			@unlink($tmpfname);
				
		return $contents;
	}

	function notify ($galleryId, $name, $filename, $description, $action, $user) {
		global $prefs;
                if ($prefs['feature_user_watches'] == 'y') {
                        //  Deal with mail notifications.
                        include_once('lib/notifications/notificationemaillib.php');
                        $foo = parse_url($_SERVER["REQUEST_URI"]);
                        $machine = $this->httpPrefix(). dirname( $foo["path"]);
			$galleryName = $this->getOne("select `name` from `tiki_file_galleries` where `galleryId`=?",array($galleryId));

                        sendFileGalleryEmailNotification('file_gallery_changed', $galleryId, $galleryName, $name, $filename, $description, $action, $user);
                }
	}
	/* lock a file */
	function lock_file($fileId, $user) {
		$query = 'update `tiki_files` set `lockedby`=? where `fileId`=?';
		$this->query($query, array($user, $fileId));
	}
	/* unlock a file */
	function unlock_file($fileId) {
		$query = 'update `tiki_files` set `lockedby`=? where `fileId`=?';
		$this->query($query, array(NULL, $fileId));		
	}
	/* get archives of a file */
	function get_archives($fileId, $offset=0, $maxRecords=-1, $sort_mode='created_desc', $find='') {
		return $this->get_files($offset, $maxRecords, $sort_mode, $find, $fileId, true, false, false, true, false, false, false, false, '', false, true);
	}
	function duplicate_file_gallery($galleryId, $name, $description = '') {
		global $user;
		$info = $this->get_file_gallery_info($galleryId);
		$info['user'] = $user;
		$info['galleryId'] = 0;
		$info['description'] = $description;
		$info['name'] = $name;
		$newGalleryId = $this->replace_file_gallery($info);
		return $newGalleryId;
	}

	function get_download_limit( $fileId )
	{
		global $tikilib;
		return (int) $tikilib->get_preference( "fgal_{$fileId}_hit_limit" );
	}

	function set_download_limit( $fileId, $limit )
	{
		global $tikilib;
		$limit = (int) $limit;
		$pref = "fgal_{$fileId}_hit_limit";

		if( $limit <= 0 )
			$tikilib->delete_preference( $pref );
		else
			$tikilib->set_preference( $pref, $limit );
	}
}
global $dbTiki;
$filegallib = new FileGalLib($dbTiki);

?>
