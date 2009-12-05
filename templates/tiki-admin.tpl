{* $Id$ *}
{popup_init src="lib/overlib.js"}
{title help="$helpUrl"}{tr}{$admintitle}{/tr}{/title}

{if $prefs.feature_search_preferences eq 'y'}
<form method="post" action="">
	{remarksbox type="note" title="{tr}Development Notice{/tr}"}
		{tr}Unless a significant amount of preferences are documented and use dynamic preferences before the 4.0 release, this search feature will become disabled by default.{/tr}
	{/remarksbox}
	<p>
		<label>Configuration search: <input type="text" name="lm_criteria" value="{$lm_criteria|escape}"/>
		<input type="submit" value="{tr}Search{/tr}"/></label>
	</p>
</form>
{if $lm_searchresults}
	<form method="post" action="">
		<hr class="clear"/>
		{foreach from=$lm_searchresults item=prefName}
			{preference name=$prefName}
		{/foreach}
		<input type="submit" value="{tr}Change{/tr}" class="clear"/>
		<input type="hidden" name="lm_criteria" value="{$lm_criteria|escape}"/>
		<hr class="clear"/>
	</form>
{/if}
{/if}

<div id="pageheader">
{* bother to display this only when breadcrumbs are on *}
{*
{if $prefs.feature_breadcrumbs eq 'y'}
    {breadcrumbs type="trail" loc="page" crumbs=$crumbs}
    {breadcrumbs type="pagetitle" loc="page" crumbs=$crumbs}
{/if}
*}
{if $db_requires_update}
	{remarksbox type="errors" title="{tr}Database Version Problem{/tr}"}
	{tr}Your database requires an update to match the current TikiWiki version. Please proceed to <a href="tiki-install.php">the installer</a>. Using Tiki with an incorrect database version usually provoke errors.{/tr}
	{tr}If you have shell (SSH) access, you can also use the following, on the command line, from the root of your Tiki installation:{/tr} php installer/shell.php
	{/remarksbox}
{/if}
{*{tr}{$description}{/tr}*}
</div>
{* Determines which page to include using "page" GET parameter. Default : list-sections
Add a value in first check when you create a new admin page. *}
{if in_array($adminpage, array("features", "general", "login", "wiki",
"gal", "fgal", "cms", "polls", "search", "blogs", "forums", "faqs",
"trackers", "webmail", "rss", "directory", "userfiles", "maps",
"metatags", "performance", "security", "wikiatt", "score", "community", "messages",
"calendar", "intertiki", "kaltura", "freetags", "gmap",
"i18n", "wysiwyg", "copyright", "category", "module", "look", "textarea",
"multimedia", "ads", "profiles", "semantic", "plugins", "webservices",
'sefurl', 'connect'))}
  {assign var="include" value=$smarty.get.page}
{else}
  {assign var="include" value="list-sections"}
{/if}
{if $include != "list-sections"}
  <div class="simplebox adminanchors clearfix" >{include file='tiki-admin-include-anchors.tpl'}</div>
{/if}

{if $prefs.tiki_needs_upgrade eq 'y'}
<div class="simplebox highlight">{tr}A new version of Tikiwiki, <b>{$prefs.tiki_release}</b>, is available. You are currently running <b>{$tiki_version}</b>. Please visit <a href="http://tikiwiki.org/Download">http://tikiwiki.org/Download</a>.{/tr}</div>
{/if}

{if $tikifeedback}
	{remarksbox type="note" title="{tr}Note{/tr}"}
		{cycle values="odd,even" print=false}
		{tr}The following list of changes has been applied:{/tr}
		<ul>
		{section name=n loop=$tikifeedback}
			<li class="{cycle}">
				<p>
			{if $tikifeedback[n].st eq 0}
				{icon _id=delete alt="{tr}disabled{/tr}" style="vertical-align: middle"}
			{elseif $tikifeedback[n].st eq 1}
				{icon _id=accept alt="{tr}enabled{/tr}" style="vertical-align: middle"}
			{elseif $tikifeedback[n].st eq 2}
				{icon _id=accept alt="{tr}changed{/tr}" style="vertical-align: middle"}
			{else}
				{icon _id=information alt="{tr}information{/tr}" style="vertical-align: middle"}
			{/if}
					{if $tikifeedback[n].st ne 3}{tr}preference{/tr} {/if}<strong>{tr}{$tikifeedback[n].mes|stringfix}{/tr}</strong><br />
					{if $tikifeedback[n].st ne 3}(<em>{tr}preference name:{/tr}</em> {$tikifeedback[n].name}){/if}
				</p>
			</li>
		{/section}
		</ul>
	{/remarksbox}
{/if}
{if $pagetop_msg}
	{remarksbox type="note" title="{tr}Note{/tr}"}
		{$pagetop_msg}
	{/remarksbox}
{/if}

{include file="tiki-admin-include-$include.tpl"}

<br style="clear:both" />
{remarksbox type="tip" title="{tr}Crosslinks to other features and settings{/tr}"}

	{tr}Other sections{/tr}:<br />
	{if $prefs.feature_sheet eq 'y'} <a href="tiki-sheets.php">{tr}Spreadsheet{/tr}</a> {/if}
	{if $prefs.feature_newsletters eq 'y'} <a href="tiki-admin_newsletters.php">{tr}Newsletters{/tr}</a> {/if}
	{if $prefs.feature_surveys eq 'y'} <a href="tiki-admin_surveys.php">{tr}Surveys{/tr}</a> {/if}
	{if $prefs.feature_quizzes eq 'y'} <a href="tiki-edit_quiz.php">{tr}Quizzes{/tr}</a> {/if}
	{if $prefs.feature_integrator eq 'y'} <a href="tiki-admin_integrator.php">{tr}Integrator{/tr}</a> {/if}
	{if $prefs.feature_html_pages eq 'y'} <a href="tiki-admin_html_pages.php">{tr}HTML pages{/tr}</a> {/if}
	{if $prefs.feature_shoutbox eq 'y'} 
		<a href="tiki-shoutbox.php">{tr}Shoutbox{/tr}</a>
		<a href="tiki-admin_shoutbox_words.php">{tr}Shoutbox Words{/tr}</a> 
	{/if}
	{if $prefs.feature_live_support eq 'y'} <a href="tiki-live_support_admin.php">{tr}Live Support{/tr}</a> {/if}
	{* TODO: to be fixed {if $prefs.feature_debug_console eq 'y'} <a href="javascript:toggle("debugconsole")">{tr}(debug){/tr}</a> 
	{/if} *}
	{if $prefs.feature_contact eq 'y'} <a href="tiki-contact.php">{tr}Contact us{/tr}</a> {/if}
	<hr />

	{tr}Administration features{/tr}:<br />
	<a href="tiki-adminusers.php">{tr}Users{/tr}</a> 
	<a href="tiki-admingroups.php">{tr}Groups{/tr}</a> 
	<a href="tiki-admin_security.php">{tr}Security{/tr}</a> 
	<a href="tiki-admin_system.php">{tr}TikiCache/System{/tr}</a> 
	<a href="tiki-syslog.php">{tr}SysLogs{/tr}</a> 
	<a href="tiki-phpinfo.php">{tr}phpinfo{/tr}</a> 
	<a href="tiki-mods.php">{tr}Mods{/tr}</a>
	{if $prefs.feature_banning eq 'y'}<a href="tiki-admin_banning.php">{tr}Banning{/tr}</a> {/if}
	{if $prefs.lang_use_db eq 'y'}<a href="tiki-edit_languages.php">{tr}Edit Languages{/tr}</a> {/if}
	{if $prefs.feature_pagelist eq 'y'}<a href="tiki-admin_pagelist.php">{tr}Page List{/tr}</a>{/if}
	<hr />

	{tr}Transversal features{/tr} ({tr}which apply to more than one section{/tr}):<br />
	<a href="tiki-admin_notifications.php">{tr}Mail Notifications{/tr}</a> 
	<hr />

	{tr}Navigation features{/tr}:<br />
	<a href="tiki-admin_menus.php">{tr}Menus{/tr}</a> 
	<a href="tiki-admin_modules.php">{tr}Modules{/tr}</a>
	{if $prefs.feature_categories eq 'y'} <a href="tiki-admin_categories.php">{tr}Categories{/tr}</a> {/if}
	{if $prefs.feature_featuredLinks eq 'y'}<a href="tiki-admin_links.php">{tr}Links{/tr}</a>{/if}
	<hr />

	{tr}Look & feel{/tr} ({tr}themes{/tr}):<br />
	{if $prefs.feature_theme_control eq 'y'} <a href="tiki-theme_control.php">{tr}Theme Control{/tr}</a> {/if}
	{if $prefs.feature_edit_templates eq 'y'} <a href="tiki-edit_templates.php">{tr}Edit Templates{/tr}</a> {/if}
	{if $prefs.feature_editcss eq 'y'} <a href="tiki-edit_css.php">{tr}Edit CSS{/tr}</a> {/if}
	{if $prefs.feature_mobile eq 'y'} <a href="tiki-mobile.php">{tr}Mobile{/tr}</a> {/if}
	<hr />

	{tr}Text area features{/tr} ({tr}features you can use in all text areas, like wiki pages, blogs, articles, forums, etc{/tr}):<br />
	<a href="tiki-admin_cookies.php">{tr}Cookies{/tr}</a> 
	{if $prefs.feature_hotwords eq 'y'} <a href="tiki-admin_hotwords.php">{tr}Hotwords{/tr}</a> {/if}
	<a href="tiki-list_cache.php">{tr}External Pages Cache{/tr}</a> 
	<a href="tiki-admin_toolbars.php">{tr}Toolbars{/tr}</a> 
	<a href="tiki-admin_content_templates.php">{tr}Content Templates{/tr}</a> 
	<a href="tiki-admin_dsn.php">{tr}DSN{/tr}</a> 
	{if $prefs.feature_dynamic_content eq 'y'}<a href="tiki-list_contents.php">{tr}Dynamic Content{/tr}</a> {/if}
	<a href="tiki-admin_external_wikis.php">{tr}External Wikis{/tr}</a> 
	{if $prefs.feature_mailin eq 'y'}<a href="tiki-admin_mailin.php">{tr}Mail-in{/tr}</a> {/if}
	<hr />

	{tr}Stats &amp; banners{/tr}:<br />
	{if $prefs.feature_stats eq 'y'} <a href="tiki-stats.php">{tr}Stats{/tr}</a> {/if}
	{if $prefs.feature_referer_stats eq 'y'} <a href="tiki-referer_stats.php">{tr}Referer Stats{/tr}</a> {/if}
	{if $prefs.feature_search eq 'y' and $prefs.feature_search_stats eq 'y'} <a href="tiki-search_stats.php">{tr}Search Stats{/tr}</a>  {/if}
	{if $prefs.feature_banners eq 'y'} <a href="tiki-list_banners.php">{tr}Banners{/tr}</a> {/if}
{/remarksbox}
