{* $Id$ *}

<h1><a href="tiki-upload_file.php{if !empty($galleryId)}?galleryId={$galleryId}{if $editFileId}&amp;fileId={$editFileId}{/if}{/if}{if $filegals_manager eq 'y'}&amp;filegals_manager=y{/if}" class="pagetitle">{if $editFileId}{tr}Edit File:{/tr} {$fileInfo.filename}{else}{tr}Upload File{/tr}{/if}</a></h1>

{if count($galleries) > 0 || $editFileId}
	{if !empty($galleryId)}
	<div class="navbar"><a href="tiki-list_file_gallery.php?galleryId={$galleryId}{if $filegals_manager eq 'y'}&amp;filegals_manager=y{/if}" class="linkbut">{tr}Browse gallery{/tr}</a>{if count($uploads) > 0}
	<a href="#upload" class="linkbut" title="{tr}Upload File{/tr}">{tr}Upload File{/tr}</a>{/if}
	</div>
	{/if}

{if count($errors) > 0}
<div class="simplebox highlight">
<h2>{tr}Errors detected{/tr}</h2>
	{section name=ix loop=$errors}
		{$errors[ix]}<br />
	{/section}
</div>
{/if}

{if count($uploads) > 0}
	<h2>
	{if count($uploads) eq 1}
		{tr}The following file was successfully uploaded{/tr}:
	{else}
		{tr}The following files have been successfully uploaded{/tr}:
	{/if}
	</h2>

	<table border="0" cellspacing="4" cellpadding="4">
	{section name=ix loop=$uploads}
		<tr>
			<td class="{cycle values="odd,even"}" style="text-align: center">
				<img src="tiki-download_file.php?fileId={$uploads[ix].fileId}&amp;thumbnail=y" />
			</td>
			<td>
				<b>{$uploads[ix].name} ({$uploads[ix].size|kbsize:true})</b>
				<div class="button2">
					<a href="#" onclick="javascript:flip('uploadinfos{$uploads[ix].fileId}');flip('uploadinfos{$uploads[ix].fileId}_close','inline');return false;" class="linkbut">
						{tr}Additional Info{/tr}
						<span id="uploadinfos{$uploads[ix].fileId}_close" style="display:none">({tr}Hide{/tr})</span>
					</a>
				</div>
				<div style="display:none;" id="uploadinfos{$uploads[ix].fileId}">
					{tr}You can download this file using{/tr}: <a class="link" href="{$uploads[ix].dllink}">{$uploads[ix].dllink}</a><br />
					{tr}You can link to the file from a Wiki page using{/tr}: <div class="code">[tiki-download_file.php?fileId={$uploads[ix].fileId}|{$uploads[ix].name} ({$uploads[ix].size|kbsize:true})]</div>
					{tr}You can display an image in a Wiki page using{/tr}: <div class="code">&#x7b;img src="tiki-download_file.php?fileId={$uploads[ix].fileId}&amp;thumbnail=y" link="{$uploads[ix].dllink}" alt="{$uploads[ix].name} ({$uploads[ix].size|kbsize:true})"}</div>
					{tr}You can link to the file from an HTML page using{/tr}: <div class="code">&lt;a href="{$uploads[ix].dllink}"&gt;{$uploads[ix].name} ({$uploads[ix].size|kbsize:true})&lt;/a&gt;</div>
				</div>
			</td>
		</tr>
	{/section}
	</table>

<br />

<h2>{tr}Upload File{/tr}</h2>
{elseif $fileChangedMessage}
	<div align="center">
		<div class="wikitext">
		{$fileChangedMessage}
		</div>
	</div>
{/if}

{if !$editFileId}
	{if $prefs.feature_file_galleries_batch eq 'y'}
	{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Upload big files (e.g. PodCast files) here:{/tr} <a class="rbox-link" href="tiki-batch_upload_files.php?galleryId={$galleryId}{if $filegals_manager eq 'y'}&amp;filegals_manager=y{/if}">{tr}Directory batch{/tr}</a>{/remarksbox}
	{/if}
{elseif isset($fileInfo.lockedby) and $fileInfo.lockedby neq ''}
	{remarksbox type="note" title="{tr}Info{/tr}" icon="lock"}
	{if $user eq $fileInfo.lockedby}
		{tr}You locked the file{/tr}
	{else}
		{tr}The file is locked by {$fileInfo.lockedby}{/tr}
	{/if}
	{/remarksbox}
{/if}

	<div align="center">
	<form enctype="multipart/form-data" action="tiki-upload_file.php{if $filegals_manager eq 'y'}?filegals_manager=y{/if}" method="post">
	<table id="upload" class="normal">
	<tr><td class="formcolor">{tr}File Title{/tr}:</td><td class="formcolor"><input type="text" name="name" {if $fileInfo.name}value="{$fileInfo.name}"{/if} size="40" /> {if $gal_info.type eq "podcast" or $gal_info.type eq "vidcast"} ({tr}required field for podcasts{/tr}){/if}</td></tr>
	<tr><td class="formcolor">{tr}File Description{/tr}:</td><td class="formcolor"><textarea rows="5" cols="40" name="description">{if $fileInfo.description}{$fileInfo.description}{/if}</textarea> {if $gal_info.type eq "podcast" or $gal_info.type eq "vidcast"} ({tr}required field for podcasts{/tr}){/if}</td></tr>
	{if $editFileId}
		<input type="hidden" name="galleryId" value="{$galleryId}"/>
		<input type="hidden" name="fileId" value="{$editFileId}"/>
		<input type="hidden" name="lockedby" value="{$fileInfo.lockedby|escape}" \>
	{else}
	<tr><td class="formcolor">{tr}File Gallery{/tr}:</td><td class="formcolor"> 
	<select name="galleryId">
	{section name=idx loop=$galleries}
	{if ($galleries[idx].individual eq 'n') or ($galleries[idx].individual_tiki_p_upload_files eq 'y')}
	<option  value="{$galleries[idx].id|escape}" {if $galleries[idx].id eq $galleryId}selected="selected"{/if}>{$galleries[idx].name}</option>
	{/if}
	{/section}
	</select>{/if}</td></tr>
{include file=categorize.tpl}

{* File replacement is only here when the javascript upload action is not available in the file listing.
   This may be moved later in another specific place (e.g. simple popup) for non-javascript browsers
     since it is not really a "Property" of the file *}

{if $prefs.javascript_enabled neq 'y' || ! $editFileId}
	<tr><td class="formcolor">	{tr}Upload from disk:{/tr}</td>
	<td class="formcolor">
		{if $editFileId}{$fileInfo.filename|escape}<br />{/if}
		<input name="userfile1" type="file" />
		{if !$editFileId}<input name="userfile2" type="file" />
		<br />
		<input name="userfile3" type="file" />
		<input name="userfile4" type="file" />
		<br />
		<input name="userfile5" type="file" />
		<input name="userfile6" type="file" />{/if}
	</td></tr>
{/if}

	{if !$editFileId and $tiki_p_batch_upload_files eq 'y'}<tr><td class="formcolor">{tr}Batch upload{/tr}:</td><td class="formcolor">
	<input type="checkbox" name="isbatch" /><i>{tr}Unzip all zip files{/tr}</i></td></tr>
	{/if}

	{if $tiki_p_admin_file_galleries eq 'y'}
	<tr><td class="formcolor">{tr}Creator{/tr}:</td><td class="formcolor">
	<select name="user">
	{section name=ix loop=$users}<option value="{$users[ix].login|escape}"{if (isset($fileInfo) and $fileInfo.user eq $users[ix].login) or (!isset($fileInfo) and $user == $users[ix].login)}  selected="selected"{/if}>{$users[ix].login|username}</option>{/section}
	</select>
	</td></tr>
	{/if}

	{if $prefs.fgal_limit_hits_per_file eq 'y'}
	<tr><td class="formcolor">{tr}Maximum amount of downloads{/tr}:</td><td class="formcolor">
	<input type="text" name="hit_limit" value="{$hit_limit|default:0}"/>
	{tr}0 for no limit{/tr}
	</td></tr>
	{/if}

	{if $prefs.feature_file_galleries_author eq 'y'}
	<tr><td class="formcolor">{tr}Author if not the file creator{/tr}:</td><td class="formcolor"><input type="text" name="author" value="{$fileInfo.author|escape}" /></td></tr>
	{/if}

	{if $prefs.javascript_enabled neq 'y' && $editFileId}
		<tr><td class="formcolor">{tr}Comment{/tr}:</td><td  class="formcolor"><input type="text" name="comment" value="" size="40" /></td></tr>
	{/if}

	<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="upload" value="{if $editFileId}{tr}Save{/tr}{else}{tr}Upload{/tr}{/if}" />{if !empty($fileInfo.lockedby) and $user ne $fileInfo.lockedby}{icon _id="lock" class="" alt=""}<span class="attention">{tr}The file is locked by {$fileInfo.lockedby}{/tr}</span>{/if}
	
	<span class="rbox-data">
		{tr}Note: Maximum file size is limited to:{/tr} {$max_upload_size|kbsize:true:0}
		{if $tiki_p_admin eq 'y'}{icon _id='information' alt=$max_upload_size_comment style='vertical-align:middle;'}{/if}
	</span>
	
	</td></tr>
	</table>
	</form>
	</div>
	
{else}
	{icon _id=exclamation alt="{tr}Error{/tr}" style="vertical-align:middle;"} {tr}No gallery available.{/tr} {tr}You have to create a gallery first!{/tr}
	<p><a class="linkbut" href="tiki-file_galleries.php{if $filegals_manager eq 'y'}?filegals_manager=y{/if}">{tr}Create New Gallery{/tr}</a></p>
{/if}


