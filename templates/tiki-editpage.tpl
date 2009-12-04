{* $Id$ *}

{if $prefs.feature_ajax == 'y'}
  <script type="text/javascript" src="lib/wiki/wiki-ajax.js"></script>
{/if}

{if $page|lower neq 'sandbox'}
	{remarksbox type='tip' title='{tr}Tip{/tr}'}
	{tr}This edit session will expire in{/tr} <span id="edittimeout">{math equation='x / y' x=$edittimeout y=60}</span> {tr}minutes{/tr}. {tr}<strong>Preview</strong> or <strong>Save</strong> your work to restart the edit session timer.{/tr}
	{/remarksbox}
{/if}
	
{if $translation_mode eq 'n'}
	{if $beingStaged eq 'y' and $prefs.wikiapproval_hideprefix == 'y'}{assign var=pp value=$approvedPageName}{else}{assign var=pp value=$page}{/if}
	{title}{if isset($hdr) && $prefs.wiki_edit_section eq 'y'}{tr}Edit Section{/tr}{else}{tr}Edit{/tr}{/if}: {$pp|escape}{if $pageAlias ne ''}&nbsp;({$pageAlias|escape}){/if}{/title}
{else}
   {title}{tr}Update '{$page|escape}'{/tr}{/title}
{/if}
   
{if $beingStaged eq 'y'}
	<div class="tocnav">{icon _id=information style="vertical-align:middle" align="left"} 
		{if $approvedPageExists}
			{tr}You are editing the staging copy of the approved version of this page. Changes will be merged in after approval.{/tr}
		{else}
			{tr}This is a new staging page that has not been approved before.{/tr}
		{/if}
			{if $outOfSync eq 'y'}
				{tr}The current staging copy may contain changes that have yet to be approved.{/tr}
			{/if}
		{if $lastSyncVersion}
			<a class="link" href="tiki-pagehistory.php?page={$page|escape:'url'}&amp;diff2={$lastSyncVersion}" target="_blank">{tr}View changes since last approval.{/tr}</a>
		{/if}
	</div>
{/if}
{if $needsStaging eq 'y'}
	<div class="tocnav">
		{icon _id=information style="vertical-align:middle" align="left"} 
		{tr}You are editing the approved copy of this page.{/tr}
		{if $outOfSync eq 'y'}
			{tr}There are currently changes in the staging copy that have yet to be approved.{/tr}
		{/if}
		{tr}Are you sure you do not want to edit{/tr} <a class="link" href="tiki-editpage.php?page={$stagingPageName|escape:'url'}">{tr}the staging copy{/tr}</a> {tr}instead?{/tr}
	</div>
{/if}
{if isset($data.draft)}
	{tr}Draft written on{/tr} {$data.draft.lastModif|tiki_long_time}<br/>
	{if $data.draft.lastModif < $data.lastModif}
		<b>{tr}Warning: new versions of this page have been made after this draft{/tr}</b>
	{/if}
{/if}
{if $page|lower eq 'sandbox'}
	{remarksbox type='tip' title='{tr}Tip{/tr}'}
		{tr}The SandBox is a page where you can practice your editing skills, use the preview feature to preview the appearance of the page, no versions are stored for this page.{/tr}
	{/remarksbox}
{/if}
{if $category_needed eq 'y'}
	{remarksbox type='Warning' title='{tr}Warning{/tr}'}
	<div class="highlight"><em class='mandatory_note'>{tr}A category is mandatory{/tr}</em></div>
	{/remarksbox}
{/if}
{if $contribution_needed eq 'y'}
	{remarksbox type='Warning' title='{tr}Warning{/tr}'}
	<div class="highlight"><em class='mandatory_note'>{tr}A contribution is mandatory{/tr}</em></div>
	{/remarksbox}
{/if}
{if $likepages}
	<div>
		{tr}Perhaps you are looking for:{/tr}
		{if $likepages|@count < 0}
			<ul>
				{section name=back loop=$likepages}
					<li>
						<a href="{$likepages[back]|sefurl}" class="wiki">{$likepages[back]|escape}</a>
					</li>
				{/section}
			</ul>
		{else}
			<table class="normal"><tr>
				{cycle name=table values=',,,,</tr><tr>' print=false advance=false}
				{section name=back loop=$likepages}
					<td><a href="{$likepages[back]|sefurl}" class="wiki">{$likepages[back]|escape}</a></td>{cycle name=table}
				{/section}
			</tr></table>
		{/if}
	</div>
{/if}

{if $preview && $translation_mode eq 'n'}
	{include file='tiki-preview.tpl'}
{/if}
{if $diff_style}
<div style="overflow:auto;height:200px;">
	{include file='pagehistory.tpl'}
</div>
{/if}
<form  enctype="multipart/form-data" method="post" action="tiki-editpage.php?page={$page|escape:'url'}" id='editpageform' name='editpageform'>
	{if $diff_style}
		<select name="diff_style">
		
			{if $diff_style eq "htmldiff"}
				<option value="htmldiff" selected="selected">html</option>
			{else}
				<option value="htmldiff">html</option>
			{/if}
			{if $diff_style eq "inlinediff"}
				<option value="inlinediff" selected="selected">text</option>
			{else}
				<option value="inlinediff">text</option>
			{/if}   
		</select>
		<input type="submit" class="wikiaction tips" title="{tr}Edit wiki page{/tr}|{tr}Change the style used to display differences to be translated.{/tr}" name="preview" value="{tr}Change diff styles{/tr}" onclick="needToConfirm=false;" />
	{/if}
	
	{if $page_ref_id}<input type="hidden" name="page_ref_id" value="{$page_ref_id}" />{/if}
	{if isset($hdr)}<input type="hidden" name="hdr" value="{$hdr}" />{/if}
	{if isset($cell)}<input type="hidden" name="cell" value="{$cell}" />{/if}
	{if isset($pos)}<input type="hidden" name="pos" value="{$pos}" />{/if}
	{if $current_page_id}<input type="hidden" name="current_page_id" value="{$current_page_id}" />{/if}
	{if $add_child}<input type="hidden" name="add_child" value="true" />{/if}
	
	{if ( $preview && $staging_preview neq 'y' ) or $prefs.wiki_actions_bar eq 'top' or $prefs.wiki_actions_bar eq 'both'}
		<div class='top_actions'>
			{include file='wiki_edit_actions.tpl'}
		</div>
	{/if}
	
	<table class="normal">
		
		
		<tr class="formcolor">
			<td colspan="2">
				{tabset name='tabs_editpage'}
					{tab name="{tr}Edit page{/tr}"}
						{textarea}{$pagedata}{/textarea}
						{if $page|lower neq 'sandbox'}
							<fieldset>
								<label for="comment">{tr}Edit Comment{/tr}: {if $prefs.feature_help eq 'y'}{help url='Editing+Wiki+Pages' desc='{tr}Edit comment: Enter some text to describe the changes you are currently making{/tr}'}{/if}</label>
								<input style="width:98%;" class="wikiedit" type="text" id="comment" name="comment" value="{$commentdata|escape}" />
								{if $show_watch eq 'y'}
									<label for="watch">{tr}Monitor this page{/tr}:</label>
									<input type="checkbox" id="watch" name="watch" value="1"{if $watch_checked eq 'y'} checked="checked"{/if} />
								{/if}						
							</fieldset>
							{if $wysiwyg neq 'y' and $prefs.feature_wiki_pictures eq 'y' and $tiki_p_upload_picture eq 'y' and $prefs.feature_filegals_manager neq 'y'}
								<fieldset>
									<legend>{tr}Upload picture{/tr}:</legend>
									<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
									<input type="hidden" name="hasAlreadyInserted" value="" />
									<input type="hidden" name="prefix" value="/img/wiki_up/{if $tikidomain}{$tikidomain}/{/if}" />
									<input name="picfile1" type="file" onchange="javascript:insertImgFile('editwiki','picfile1','hasAlreadyInserted','img')"/>
									<div id="new_img_form"></div>
									<a href="javascript:addImgForm()" onclick="needToConfirm = false;">{tr}Add another image{/tr}</a>
								</fieldset>
							{/if}
				
						{/if}
					{/tab}
					{if $prefs.feature_categories eq 'y' and $tiki_p_modify_object_categories eq 'y' and count($categories) gt 0}
						{tab name="{tr}Categories{/tr}"}
							{if $categIds}
								{section name=o loop=$categIds}
									<input type="hidden" name="cat_categories[]" value="{$categIds[o]}" />
								{/section}
								<input type="hidden" name="categId" value="{$categIdstr}" />
								<input type="hidden" name="cat_categorize" value="on" />
								
								{if $prefs.feature_wiki_categorize_structure eq 'y'}
									{tr}Categories will be inherited from the structure top page{/tr}
								{/if}
							{else}
								{if $page|lower ne 'sandbox'}
									{include file='categorize.tpl' notable='y'}
								{/if}{* sandbox *}
							{/if}
						{/tab}
					{/if}
					{if !empty($showToolsTab)}
						{tab name="{tr}Tools{/tr}"}
							{if $prefs.feature_wiki_templates eq 'y' and $tiki_p_use_content_templates eq 'y'}
								<fieldset>
									<legend>{tr}Apply template{/tr}:</legend>
									<select id="templateId" name="templateId" onchange="javascript:document.getElementById('editpageform').submit();" onclick="needToConfirm = false;">
										<option value="0">{tr}none{/tr}</option>
										{section name=ix loop=$templates}
										<option value="{$templates[ix].templateId|escape}" {if $templateId eq $templates[ix].templateId}selected="selected"{/if}>{tr}{$templates[ix].name|escape}{/tr}</option>
										{/section}
									</select>
									{if $tiki_p_edit_content_templates eq 'y'}
										<a style="align=right;" href="tiki-admin_content_templates.php" class="link" onclick="needToConfirm = true;">{tr}Admin Content Templates{/tr}</a>
									{/if}
								</fieldset>
							{/if}
							{if $prefs.feature_wiki_usrlock eq 'y' && ($tiki_p_lock eq 'y' || $tiki_p_admin_wiki eq 'y')}
								<fieldset>
									<legend>{tr}Lock this page{/tr}</legend>
									<input type="checkbox" id="lock_it" name="lock_it" {if $lock_it eq 'y'}checked="checked"{/if}/>
								</fieldset>
							{/if}
							{if $prefs.wiki_comments_allow_per_page neq 'n'}
								<fieldset>
									<legend>{tr}Allow comments on this page{/tr}</legend>
									<input type="checkbox" id="comments_enabled" name="comments_enabled" {if $comments_enabled eq 'y'}checked="checked"{/if}/>
								</fieldset>
							{/if}
				
							{if $prefs.feature_wiki_replace eq 'y' and $wysiwyg neq 'y'}
								<script type="text/javascript">
	<!--//--><![CDATA[//><!--
	{literal}
	function searchrep() {
		c = document.getElementById('caseinsens')
		s = document.getElementById('search')
		r = document.getElementById('replace')
		t = document.getElementById('editwiki')
		var opt = 'g';
		if (c.checked == true) {
			opt += 'i'
		}
		var str = t.value
		var re = new RegExp(s.value,opt)
		t.value = str.replace(re,r.value)
	}
	{/literal}
	//--><!]]>
								</script>
								<fieldset>
									<legend>{tr}Regex search {/tr}:</legend>
									<input style="width:100;" class="wikiedit" type="text" id="search"/>
									<label>{tr}Replace with{/tr}:
									<input style="width:100;" class="wikiedit" type="text" id="replace"/></label>
									<label><input type="checkbox" id="caseinsens" />{tr}Case Insensitivity{/tr}</label>
									<input type="button" value="{tr}Replace{/tr}" onclick="javascript:searchrep();">
								</fieldset>
							{/if}
							{if $prefs.wiki_spellcheck eq 'y'}
								<fieldset>
									<legend>{tr}Spellcheck{/tr}:</legend>
									<input type="checkbox" id="spellcheck"name="spellcheck" {if $spellcheck eq 'y'}checked="checked"{/if}/>
								</fieldset>
							{/if}
							{if $prefs.feature_wiki_allowhtml eq 'y' and $tiki_p_use_HTML eq 'y' and $wysiwyg neq 'y'}
								<fieldset>
									<legend>{tr}Allow HTML{/tr}:</legend>
									<input type="checkbox" id="allowhtml" name="allowhtml" {if $allowhtml eq 'y'}checked="checked"{/if}/>
								</fieldset>
							{/if}
							{if $prefs.feature_wiki_import_html eq 'y'}
								<fieldset>
									<legend>{tr}Import HTML{/tr}:</legend>
									<input class="wikiedit" type="text" id="suck_url" name="suck_url" value="{$suck_url|escape}" />&nbsp;
									<input type="submit" class="wikiaction" name="do_suck" value="{tr}Import{/tr}" onclick="needToConfirm=false;" />&nbsp;
									<label><input type="checkbox" name="parsehtml" {if $parsehtml eq 'y'}checked="checked"{/if}/>&nbsp;
									{tr}Try to convert HTML to wiki{/tr}. </label>
								</fieldset>
							{/if}
							
							{if $tiki_p_admin_wiki eq 'y' && $prefs.feature_wiki_import_page eq 'y'}
								<fieldset>
									<legend>{tr}Import page{/tr}:</legend>
									<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
									<input id="userfile1" name="userfile1" type="file" />
									{if $prefs.feature_wiki_export eq 'y' and $tiki_p_admin_wiki eq 'y'}
										<a href="tiki-export_wiki_pages.php?page={$page|escape:"url"}&amp;all=1" class="link">{tr}export all versions{/tr}</a>
									{/if}
								</fieldset>
							{/if}
							
							{if $wysiwyg neq 'y'}
								{if $prefs.feature_wiki_attachments == 'y' and ($tiki_p_wiki_attach_files eq 'y' or $tiki_p_wiki_admin_attachments eq 'y')}
									<fieldset>
										<legend>{tr}Upload file{/tr}:</legend>
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
										<input type="hidden" name="hasAlreadyInserted2" value="" />
										<input type="hidden" id="page2" name="page2" value="{$page}" />
										<input name="userfile2" type="file" id="attach-upload" />
										 <label>{tr}Comment{/tr}:<input type="text" name="attach_comment" maxlength="250" id="attach-comment" /></label>
										<input type="submit" class="wikiaction" name="attach" value="{tr}Attach{/tr}" onclick="javascript:needToConfirm=false;insertImgFile('editwiki','userfile2','hasAlreadyInserted2','file', 'page2', 'attach_comment'); return true;" />
									</fieldset>
								{/if}
	
								{if $prefs.feature_wiki_screencasts eq 'y' && $tiki_p_upload_screencast eq 'y'}
									<fieldset>
										<legend>{tr}Upload Screencast{/tr}:</legend>
										<form id="screencast-upload-form" enctype="multipart/form-data" method="post" action="tiki-upload_screencast_ajax.php" target="screencast-loader">
											<input type="hidden" name="MAX_FILE_SIZE" value="{$prefs.feature_wiki_screencasts_max_size}" />
											<div id="screencast-error" class="simplebox highlight" {if count($screencasts_errors) < 1}style="display: none;"{/if}>
												{if count($screencasts_errors) >= 1}
													<ol>
														{foreach from=$screencasts_errors item=screencasts_error}
															<li>{$screencasts_error}</li>
														{/foreach}
													</ol>
												{/if}
											</div>
											<div id="screencast-add-wrapper">
												<script type="text/javascript">
													{literal}
													function screencastError(fileType, error) {
													  if ( fileType == 'flash' ) {
														if ( error == "400")
														  return "{/literal}{tr}Incorrect file extension was used for your flash screencast, expecting .swf or .flv{/tr}{literal}";
														return "{/literal}{tr}An unexpected error occurred while uploading your flash screencast!{/tr}{literal}";
													  } else if ( fileType == 'ogg' ) {
														if ( error == "400")
														  return "{/literal}{tr}Incorrect file extension was used for your Ogg screencast, expecting .ogg{/tr}{literal}";
														if ( error == "NEEDS_FLASH")
														  return "{/literal}{tr}A flash screencast is mandatory!{/tr}{literal}";
														return "{/literal}{tr}An unexpected error occurred while uploading your Ogg screencast!{/tr}{literal}";
													  }
													}
													if ( !screencastThumbText ) { 
													  var screencastThumbText = "{/literal}{tr}Insert Screencast{/tr}{literal}";
													}
													var screencastNoPreview = "{/literal}{tr}Preview not possible{/tr}{literal}";
													{/literal}
												</script>
												<span id="screencast-add-form" class="screencast-add-form">
													<div class="screencast-input-wrapper">
														<label for="flash_screencast" class="screencast-input-label">{tr}Flash video (required){/tr}</label>
														<input name="flash_screencast[]" class="screencast-flash" type="file"/>
													</div>
													<div class="screencast-input-wrapper">
														<label for="ogg_screencast" class="screencast-input-label">{tr}Ogg video (optional){/tr}</label>
														<input name="ogg_screencast[]" class="screencast-ogg" type="file"/>
													</div>
												</span>
											</div>
											<input id="screencast-upload-now" type="submit" value="Upload"/>
											<a id="screencast-add-another">{tr}Add another screencast{/tr}</a> 
											<iframe id="screencast-loader" name="screencast-loader" style=""></iframe>
										</form>
									</fieldset>
									<fieldset id="screencast-insert-tr" {if count($screencasts_uploaded) < 1 }style="display:none;"{/if}>
										<legend>{tr}Insert Screencast{/tr}:</legend>
										<div id="screencast-insert-wrapper">
											<script type="text/javascript">
											  var thumb_videos = [{foreach from=$screencasts_uploaded item=screencast name=screencasts}"{$screencast}"{if !$smarty.foreach.screencasts.last},{/if}{/foreach}];
											</script>
										</div>
									</fieldset>
								{/if}
							{/if}
						{/tab}
					{/if}
					{if !empty($showPropertiesTab)}
						{tab name="{tr}Properties{/tr}"}
							{if $page|lower neq 'sandbox'}
								{if $prefs.wiki_feature_copyrights  eq 'y'}
									<fieldset>
										<legend>{tr}Copyright{/tr}:</legend>
										<table border="0">
											<tr class="formcolor">
												<td><label for="copyrightTitle">{tr}Title:{/tr}</label></td>
												<td><input size="40" class="wikiedit" type="text" id="copyrightTitle" name="copyrightTitle" value="{$copyrightTitle|escape}" /></td>
												{if !empty($copyrights)}
													<td rowspan="3"><a href="copyrights.php?page={$page|escape}">{tr}To edit the copyright notices{/tr}</a></td>
												{/if}
											</tr>
											<tr class="formcolor">
												<td><label for="copyrightYear">{tr}Year:{/tr}</label></td>
												<td><input size="4" class="wikiedit" type="text" id="copyrightYear" name="copyrightYear" value="{$copyrightYear|escape}" /></td>
											</tr>
											<tr class="formcolor">
												<td><label for="copyrightAuthors">{tr}Authors:{/tr}</label></td>
												<td><input size="40" class="wikiedit" id="copyrightAuthors" name="copyrightAuthors" type="text" value="{$copyrightAuthors|escape}" /></td>
											</tr>
										</table>
									</fieldset>
								{/if}
								{if $prefs.feature_freetags eq 'y' and $tiki_p_freetags_tag eq 'y'}
									<fieldset>
										<legend>{tr}Freetags{/tr}</legend>
										<table>
											{include file='freetag.tpl'}
										</table>
									</fieldset>
								{/if}
								{if $prefs.feature_wiki_icache eq 'y'}
									<fieldset>
										<legend>{tr}Cache{/tr}</legend>
									    <select id="wiki_cache" name="wiki_cache">
										    <option value="0" {if $prefs.wiki_cache eq 0}selected="selected"{/if}>0 ({tr}no cache{/tr})</option>
										    <option value="60" {if $prefs.wiki_cache eq 60}selected="selected"{/if}>1 {tr}minute{/tr}</option>
										    <option value="300" {if $prefs.wiki_cache eq 300}selected="selected"{/if}>5 {tr}minutes{/tr}</option>
										    <option value="600" {if $prefs.wiki_cache eq 600}selected="selected"{/if}>10 {tr}minute{/tr}</option>
										    <option value="900" {if $prefs.wiki_cache eq 900}selected="selected"{/if}>15 {tr}minutes{/tr}</option>
										    <option value="1800" {if $prefs.wiki_cache eq 1800}selected="selected"{/if}>30 {tr}minute{/tr}</option>
										    <option value="3600" {if $prefs.wiki_cache eq 3600}selected="selected"{/if}>1 {tr}hour{/tr}</option>
										    <option value="7200" {if $prefs.wiki_cache eq 7200}selected="selected"{/if}>2 {tr}hours{/tr}</option>
									    </select> 
									</fieldset>
								{/if}
								{if $prefs.feature_contribution eq 'y'}
									<fieldset>
										<legend>{tr}Contributions{/tr}</legend>
										<table>
											{include file='contribution.tpl'}
										</table>
									</fieldset>
								{/if}
								{if $prefs.feature_wiki_structure eq 'y'}
									<fieldset>
										<legend>{tr}Structures{/tr}</legend>
											<div id="showstructs">
												{if $showstructs|@count gt 0}
													<ul>
														{foreach from=$showstructs item=page_info }
															<li>{$page_info.pageName}{if !empty($page_info.page_alias)}({$page_info.page_alias}){/if}</li>
														{/foreach}  
													</ul>
												{/if}
											  
												{if $tiki_p_edit_structures eq 'y'}
													<a href="tiki-admin_structures.php">{tr}Manage structures{/tr} {icon _id='wrench'}</a>
												{/if}
											</div>
									</fieldset>	
								{/if}
								{if $prefs.wiki_feature_copyrights  eq 'y'}
									<fieldset>
										<legend>{tr}License{/tr}:</legend>
										<a href="{$prefs.wikiLicensePage|sefurl}">{tr}{$prefs.wikiLicensePage}{/tr}</a>
										{if $prefs.wikiSubmitNotice neq ""}
											{remarksbox type="note" title="{tr}Important{/tr}:"}
												<strong>{tr}{$prefs.wikiSubmitNotice}{/tr}</strong>
											{/remarksbox}
										{/if}
									</fieldset>
								{/if}
								{if $tiki_p_admin_wiki eq 'y' && $prefs.wiki_authors_style_by_page eq 'y'}
									<fieldset>
										<legend>{tr}Authors' style{/tr}</legend>
										{include file='wiki_authors_style.tpl' tr_class='formcolor' wiki_authors_style_site='y' style=''}
									</fieldset>
								{/if}
							{/if}{*end if sandbox *}
							{if $prefs.feature_wiki_description eq 'y' or $prefs.metatag_pagedesc eq 'y'}
								<fieldset>
									{if $prefs.metatag_pagedesc eq 'y'}
										<legend>{tr}Description (used for metatags){/tr}:</legend>
									{else}
										<legend>{tr}Description{/tr}:</legend>
									{/if}
									<input style="width:98%;" type="text" id="description" name="description" value="{$description|escape}" />
								</fieldset>
							{/if}
							{if $prefs.feature_wiki_footnotes eq 'y'}
								{if $user}
									<fieldset>
										<legend>{tr}My Footnotes{/tr}:</legend>
										<textarea id="footnote" name="footnote" rows="8" cols="42" style="width:98%;" >{$footnote|escape}</textarea>
									</fieldset>
								{/if}
							{/if}
							{if $prefs.feature_wiki_ratings eq 'y' and $tiki_p_wiki_admin_ratings eq 'y'}
								<fieldset>
									<legend>{tr}Use rating{/tr}:</legend>
									{if $poll_rated.info}
										<input type="hidden" name="poll_title" value="{$poll_rated.info.title|escape}" />
										<a href="tiki-admin_poll_options.php?pollId={$poll_rated.info.pollId}">{$poll_rated.info.title}</a>
										{assign var=thispage value=$page|escape:"url"}
										{assign var=thispoll_rated value=$poll_rated.info.pollId}
										{button href="?page=$thispage&amp;removepoll=$thispoll_rated" _text="{tr}Disable{/tr}"}
										{if $tiki_p_admin_poll eq 'y'}
											{button href="tiki-admin_polls.php" _text="{tr}Admin Polls{/tr}"}
										{/if}
									{else}
										{if count($polls_templates)}
											{tr}Type{/tr}
											<select name="poll_template">
												<option value="0">{tr}none{/tr}</option>
												{section name=ix loop=$polls_templates}
													<option value="{$polls_templates[ix].pollId|escape}"{if $polls_templates[ix].pollId eq $poll_template} selected="selected"{/if}>{tr}{$polls_templates[ix].title}{/tr}</option>
												{/section}
											</select>
											{tr}Title{/tr}
											<input type="text" name="poll_title" value="{$poll_title|escape}" size="22" />
										{else}
											{tr}There is no available poll template.{/tr}
											{if $tiki_p_admin_polls ne 'y'}
												{tr}You should ask an admin to create them.{/tr}
											{/if}
										{/if}
										{if count($listpolls)}
											{tr}or use{/tr}
											<select name="olpoll">
												<option value="">... {tr}an existing poll{/tr}</option>
												{section name=ix loop=$listpolls}
													<option value="{$listpolls[ix].pollId|escape}">{tr}{$listpolls[ix].title|default:"<i>... no title ...</i>"}{/tr} ({$listpolls[ix].votes} {tr}votes{/tr})</option>
												{/section}
											</select>
										{/if}
									{/if}
								</fieldset>
							{/if}
							{if $prefs.feature_multilingual eq 'y'}
								<fieldset>
									<legend>{tr}Language{/tr}:</legend>
									<select name="lang" id="lang">
										<option value="">{tr}Unknown{/tr}</option>
										{section name=ix loop=$languages}
											<option value="{$languages[ix].value|escape}"{if $lang eq $languages[ix].value or (not($data.page_id) and $lang eq '' and $languages[ix].value eq $prefs.language)} selected="selected"{/if}>{$languages[ix].name}</option>
										{/section}
									</select>
									{if $translationOf}
										<input type="hidden" name="translationOf" value="{$translationOf|escape}"/>
									{/if}
								</fieldset>
								{if $trads|@count > 1}
									<fieldset {if $prefs.feature_urgent_translation neq 'y' or $diff_style} style="display:none;"{/if}>
										<legend>{tr}Translation request{/tr}:</legend>
										<input type="hidden" name="lang" value="{$lang|escape}"/>
										<input type="checkbox" id="translation_critical" name="translation_critical" id="translation_critical"{if $translation_critical} checked="checked"{/if}/>
										<label for="translation_critical">{tr}Send urgent translation request.{/tr}</label>
										{if $diff_style}
											<input type="hidden" name="oldver" value="{$diff_oldver|escape}"/>
											<input type="hidden" name="newver" value="{$diff_newver|escape}"/>
											<input type="hidden" name="source_page" value="{$source_page|escape}"/>
										{/if}
									</fieldset>
								{/if}
							{/if}
						{/tab}
					{/if}
				{/tabset}
			</td>
		</tr>
		
		
		{if $page|lower ne 'sandbox'}
			{if $prefs.feature_antibot eq 'y' && $anon_user eq 'y'}
				{include file='antibot.tpl' tr_style="formcolor"}
			{/if}
		{/if}{* sandbox *}
		
		{if $prefs.wiki_actions_bar neq 'top'}
			<tr class="formcolor">
				<td colspan="2" style="text-align:center;">
					{include file='wiki_edit_actions.tpl'}
				</td>
			</tr>
		{/if}
	</table>
	{if $prefs.feature_wiki_allowhtml eq 'y' and $tiki_p_use_HTML eq 'y' and $wysiwyg eq 'y' and $allowhtml eq 'y'}
	  <input type="hidden" name="allowhtml" checked="checked"/>
	{/if}
</form>
{include file='tiki-page_bar.tpl'}
