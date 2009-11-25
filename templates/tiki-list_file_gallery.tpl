{* $Id$ *}

{popup_init src="lib/overlib.js"}

{title help="File+Galleries" admpage="fgal"}
  {strip}
    {if $edit_mode eq 'y'}
      {if $galleryId eq 0}
        {tr}Create a File Gallery{/tr}
      {else}
        {tr}Edit Gallery:{/tr} {if $galleryId eq $prefs.fgal_root_id}
		{tr}File Galleries{/tr}
	{else}
		{$name}
	{/if}
      {/if}
    {else}
      {if $galleryId eq $prefs.fgal_root_id}
        {tr}File Galleries{/tr}
      {else}
        {tr}Gallery:{/tr} {$name|escape}
      {/if}
    {/if}
  {/strip}
{/title}

{if $edit_mode neq 'y' and $gal_info.description neq ''}
  <div class="description">
    {$gal_info.description|escape|nl2br}
  </div>
{/if}

<div class="navbar">
{if $galleryId gt 0}

  {if $prefs.feature_group_watches eq 'y' and ( $tiki_p_admin_users eq 'y' or $tiki_p_admin eq 'y' )}
	<a href="tiki-object_watches.php?objectId={$galleryId|escape:"url"}&amp;watch_event=file_gallery_changed&amp;objectType=File+Gallery&amp;objectName={$gal_info.name|escape:"url"}&amp;objectHref={'tiki-list_file_gallery.php?galleryId='|cat:$galleryId|escape:"url"}" class="icon">{icon _id='eye_group' alt='{tr}Group Monitor{/tr}' align='right' hspace="1"}</a>
  {/if}

  {if $user and $prefs.feature_user_watches eq 'y'}
    {if $user_watching_file_gallery eq 'n'}
      {self_link galleryName=$name watch_event='file_gallery_changed' watch_object=$galleryId watch_action='add'}{icon _id='eye' align='right' alt="{tr}Monitor this Gallery{/tr}" hspace="1"}{/self_link}
    {else}
      {self_link galleryName=$name watch_event='file_gallery_changed' watch_object=$galleryId watch_action='remove'}{icon _id='no_eye' align='right' alt="{tr}Stop Monitoring this Gallery{/tr}" hspace="1"}{/self_link}
    {/if}
  {/if} 
  {if $prefs.rss_file_gallery eq 'y'}
    {if $gal_info.type eq "podcast" or $gal_info.type eq "vidcast"}
      <a href="tiki-file_gallery_rss.php?galleryId={$galleryId}&amp;ver=PODCAST">
      <img src='img/rss_podcast_80_15.png' alt="{tr}RSS feed{/tr}" title="{tr}RSS feed{/tr}"  align='right' /></a>
    {else}
      <a href="tiki-file_gallery_rss.php?galleryId={$galleryId}">{icon _id='feed' alt="{tr}RSS feed{/tr}" title="{tr}RSS feed{/tr}" align='right'}</a>
    {/if}
  {/if}
  {if $view eq 'browse'}
    {if $show_details eq 'y'}
      {self_link show_details='n'}{icon _id='no_information' align='right'}{/self_link}
    {else}
      {self_link show_details='y'}{icon _id='information' align='right'}{/self_link}
    {/if}
  {/if}

  {if $tiki_p_list_file_galleries eq 'y' or (!isset($tiki_p_list_file_galleries) and $tiki_p_view_file_gallery eq 'y')}
    {button _text="{tr}List Galleries{/tr}" href="?"}
  {/if}

  {if $tiki_p_create_file_galleries eq 'y' and $edit_mode ne 'y'}
    {button _text="{tr}Create a File Gallery{/tr}" href="?edit_mode=1&amp;parentId=$galleryId"}
  {/if}
  {if $tiki_p_create_file_galleries eq 'y' and $dup_mode ne 'y'}
    {button _text="{tr}Duplicate File Gallery{/tr}" dup_mode=1 galleryId=$galleryId}
  {/if}
  
  {if $tiki_p_admin_file_galleries eq 'y' or $user eq $gal_info.user}
    {if $edit_mode eq 'y' or $dup_mode eq 'y'}
      {button _text="{tr}Browse Gallery{/tr}" href="?galleryId=$galleryId"}
    {else}
      {button _text="{tr}Edit Gallery{/tr}" href="?edit_mode=1&amp;galleryId=$galleryId"}
    {/if}
  {/if}

  {if $edit_mode neq 'y' and $dup_mode neq 'y'}
    {if $view eq 'browse'}
      {button _text="{tr}List Gallery{/tr}" href="?view=list&amp;galleryId=$galleryId"}
    {else}
      {button _text="{tr}Browse Images{/tr}" href="?view=browse&amp;galleryId=$galleryId" _ajax='n'} {* no AJAX to make shadowbox work in browse view *}
    {/if}
  {/if}

  {if $tiki_p_assign_perm_file_gallery eq 'y'}
    {assign var=objectName value=$name|escape:"url"}
    {button _text="{tr}Permissions{/tr}" href="tiki-objectpermissions.php?objectName=$objectName&amp;objectType=file+gallery&amp;permType=file+galleries&amp;objectId=$galleryId"}
  {/if}
  
  {if $tiki_p_admin_file_galleries eq 'y' or $user eq $gal_info.user or $gal_info.public eq 'y'}
    {if $tiki_p_upload_files eq 'y'}
      {button _text="{tr}Upload File{/tr}" href="tiki-upload_file.php?galleryId=$galleryId"}
    {/if}
    {if $prefs.feature_file_galleries_batch eq "y" and $tiki_p_batch_upload_file_dir eq 'y'}
      {button _text="{tr}Directory Batch{/tr}" href="tiki-batch_upload_files.php?galleryId=$galleryId"}
    {/if}
  {/if}

{else}

  {if $edit_mode eq 'y' or $dup_mode eq 'y'}
    {button _text="{tr}List Galleries{/tr}" href='?'}
  {/if}
  {if $tiki_p_create_file_galleries eq 'y' and $edit_mode ne 'y'}
    {button _text="{tr}Create a File Gallery{/tr}" href="?edit_mode=1&amp;parentId=-1&amp;galleryId=0"}
  {/if}
  {if $tiki_p_upload_files eq 'y'}
  	  {button _text="{tr}Upload File{/tr}" href="tiki-upload_file.php"}
  {/if}

{/if}

{if $edit_mode neq 'y' and $prefs.fgal_show_slideshow eq 'y' and $gal_info.show_slideshow.value eq 'y'}
  {button _text="{tr}SlideShow{/tr}" href="#" _onclick="javascript:window.open('tiki-list_file_gallery.php?galleryId=$galleryId&amp;slideshow','','menubar=no,width=600,height=500,resizable=yes');"}
{/if}

</div>

{if $filegals_manager neq ''}
  {remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Be careful to set the right permissions on the files you link to{/tr}.{/remarksbox}
  <label for="keepOpenCbx">{tr}Keep gallery window open{/tr}</label>
  <input type="checkbox" name="keepOpenCbx" id="keepOpenCbx" onchange="keepOpenChanged(this);">
  <script type="text/javascript">{literal}
<!--//--><![CDATA[//><!--
function keepOpenChanged(cbx) {
	if (cbx.checked) {
		setCookie("fgalKeepOpen", "1");
	} else {
		setCookie("fgalKeepOpen", "");
	}
}
function checkClose() {
	var el = document.getElementById("keepOpenCbx");
	if (el) {
		if (!el.checked) {
			window.close();
		} else {
			window.blur();
		}
	}
}
if (getCookie("fgalKeepOpen")) {
	document.getElementById("keepOpenCbx").checked = "checked";
}
//--><!]]>
  {/literal}</script>

{/if}
{if isset($fileChangedMessage) and $fileChangedMessage neq ''}
  {remarksbox type="note" title="{tr}Note{/tr}"}
    {$fileChangedMessage}
    <form method="post" action="{$smarty.server.PHP_SELF}{if $filegals_manager neq ''}?filegals_manager={$filegals_manager|escape}{/if}">
      <input type="hidden" name="galleryId" value="{$galleryId|escape}" />
      <input type="hidden" name="fileId" value="{$fileId|escape}" />
      {tr}Your comment{/tr} ({tr}optional{/tr}): <input type="text" name="comment" size="40" />
      {icon _id='accept' _tag='input_image'}
    </form>
  {/remarksbox}
{/if}

{if $user and $prefs.feature_user_watches eq 'y'}
<div class="categbar" align="right">
  {if $category_watched eq 'y'}
    {tr}Watched by categories{/tr}:
    {section name=i loop=$watching_categories}
      {button _text=$watching_categories[i].name href="tiki-browse_categories.php?parentId=`$watching_categories[i].categId`"}
    {/section}
  {/if}			
</div>
{/if}

{if $fgal_diff|@count gt 0}
  {remarksbox type="note" title="{tr}Modifications{/tr}"}
    {foreach from=$fgal_diff item=fgp_prop key=fgp_name name=change}
      {tr}Property <b>{$fgp_name}</b> Changed{/tr}
    {/foreach}
  {/remarksbox}
{/if}

{if $edit_mode eq 'y'}

  {include file='edit_file_gallery.tpl'}

{elseif $dup_mode eq 'y'}

  {include file='duplicate_file_gallery.tpl'}

{else}
{if $files or ($find ne '')}
  {include file='find.tpl' find_show_num_rows = 'y'}
{/if}
{if $prefs.fgal_quota_show eq 'y' && $gal_info.quota}
	<div style="float:right">
		{capture name='use'}{math equation="round((100*x)/(1024*1024*y))" x=$gal_info.usedSize y=$gal_info.quota}{/capture}
		{quotabar length='100' value='$smarty.capture.use'}
	</div>
{/if}

  {include file='list_file_gallery.tpl'}

  {if $galleryId gt 0
    && $prefs.feature_file_galleries_comments == 'y'
    && (($tiki_p_read_comments  == 'y'
    && $comments_cant != 0)
    ||  $tiki_p_post_comments  == 'y'
    ||  $tiki_p_edit_comments  == 'y')}

    <div id="page-bar">
		  {include file='comments_button.tpl'}
    </div>

  {include file='comments.tpl'}
  {/if}
{/if}

{if $galleryId>0}
  {if $edited eq 'y'}
  <div class="wikitext">
    {tr}You can access the file gallery using the following URL{/tr}: <a class="fgallink" href="{$url}?galleryId={$galleryId}">{$url}?galleryId={$galleryId}</a>
  </div>
  {/if}
{/if}

