{* $Id$ *}

{title help="User+Watches"}{tr}User Watches and preferences{/tr}{/title}

<h2>{tr}Report Preferences{/tr}</h2>
{if $prefs.feature_daily_report_watches eq 'y'}

{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use reports to summarise notifications about objects you are watching.{/tr}{/remarksbox}

<form action="tiki-user_reports.php" method="post" id='formi'>
	<input type="hidden" name="report_preferences" value="true"/>
	<p><input type="checkbox" name="use_daily_reports" value="true" {if $report_preferences != false}checked{/if}/> {tr}Use reports{/tr}</p>

	<p>
	{tr}Interval in witch you want to get the reports{/tr}
	<select name="interval">
			<option value="daily" {if $report_preferences.interval eq "daily"}selected{/if}>{tr}Daily{/tr}</option>
			<option value="weekly" {if $report_preferences.interval eq "weekly"}selected{/if}>{tr}Weekly{/tr}</option>
			<option value="monthly" {if $report_preferences.interval eq "monthly"}selected{/if}>{tr}Monthly{/tr}</option>
	</select>
	</p>
	
	<div style="float:left; margin-right: 50px;">
	    <input type="radio" name="view" value="short" {if $report_preferences.view eq "short"}checked{/if}> {tr}Short report{/tr}<br>
    	<input type="radio" name="view" value="detailed" {if $report_preferences.view eq "detailed" OR $report_preferences eq false}checked{/if}> {tr}Detailed report{/tr}<br>
	</div>
	<div style="float:left; margin-right: 50px;">
	    <input type="radio" name="type" value="html" {if $report_preferences.type eq "html" OR $report_preferences eq false}checked{/if}> {tr}HTML-Email{/tr}<br>
    	<input type="radio" name="type" value="plain" {if $report_preferences.type eq "plain"}checked{/if}> {tr}Plain text{/tr}<br>
    </div>
	<div>
		<input type="checkbox" name="always_email" value="1" {if $report_preferences.always_email eq 1 OR $report_preferences eq false}checked{/if}/> {tr}Send me an email also if nothing happened{/tr}
	</div>
	
	<p><input type="submit" name="submit" value=" {tr}Apply{/tr} "></p>
</form>
{else}
<p>{tr}Reports are disabled, only standard reporting is available{/tr}</p>
{/if}

<h2>{tr}Sites you are watching{/tr}</h2>

  {include file='tiki-mytiki_bar.tpl'}

{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use "watches" to monitor wiki pages or other objects.{/tr} {tr}Watch new items by clicking the {icon _id=eye} button on specific pages.{/tr}{/remarksbox}

{if $add_options|@count > 0}
<h2>{tr}Add Watch{/tr}</h2>
<form action="tiki-user_watches.php" method="post">
<table class="normal">
<tr>
<td class="formcolor">{tr}Event{/tr}:</td>
<td class="formcolor">
<select name="event" onchange="document.getElementById('lang_list').style.visibility = (this.value == 'wiki_page_in_lang_created') ? '' : 'hidden'">
	<option>{tr}Select event type{/tr}</option>
	{foreach key=event item=label from=$add_options}
		<option value="{$event|escape}">{$label|escape}</option>
	{/foreach}
</select>
</td>
</tr>
<tr id="lang_list" style="visibility: hidden">
	<td class="formcolor">{tr}Language{/tr}</td>
	<td class="formcolor">
		<select name="langwatch">
			{section name=ix loop=$languages}
				<option value="{$languages[ix].value|escape}">
				  {$languages[ix].name}
				</option>
			{/section}
		</select>
	</td>
</tr>
<tr><td class="formcolor">&nbsp;</td>
<td class="formcolor"><input type="submit" name="add" value="{tr}Add{/tr}" /></td>
</tr>
</table>
</form>
{/if}
<br />
<h2>{tr}Watches{/tr}</h2>
<form action="tiki-user_watches.php" method="post" id='formi'>
{tr}Show:{/tr}<select name="event" onchange="javascript:document.getElementById('formi').submit();">
<option value=""{if $smarty.request.event eq ''} selected="selected"{/if}>{tr}All watched events{/tr}</option>
{section name=ix loop=$events}
<option value="{$events[ix]|escape}" {if $events[ix] eq $smarty.request.event}selected="selected"{/if}>
	{if $events[ix] eq 'article_submitted'}
		{tr}A user submits an article{/tr}
	{elseif $events[ix] eq 'article_edited'}
		{tr}A user edited an article{/tr}
	{elseif $events[ix] eq 'article_deleted'}
		{tr}A user deleted an article{/tr}
	{elseif $events[ix] eq 'blog_post'}
		{tr}A user submits a blog post{/tr}
	{elseif $events[ix] eq 'forum_post_thread'}
		{tr}A user posts a forum thread{/tr}
	{elseif $events[ix] eq 'forum_post_topic'}
		{tr}A user posts a forum topic{/tr}
	{elseif $events[ix] eq 'wiki_page_changed'}
		{tr}A user edited a wiki page{/tr}
	{elseif $events[ix] eq 'wiki_page_in_lang_created'}
		{tr}A user created a wiki page in a language{/tr}
	{else}{$events[ix]}{/if}
</option>
{/section}
</select>
</form>
<br />
<form action="tiki-user_watches.php" method="post">
<table class="normal">
<tr>
{if $watches}
<th style="text-align:center;"></th>
{/if}
<th>{tr}Event{/tr}</th>
<th>{tr}Object{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=ix loop=$watches}
<tr>
{if $watches}
<td style="text-align:center;" class="{cycle advance=false}">
<input type="checkbox" name="watch[{$watches[ix].watchId}]" />
</td>
{/if}
<td class="{cycle advance=false}">
	{if $watches[ix].event eq 'article_submitted'}
		{tr}A user submits an article{/tr}
	{elseif $watches[ix].event eq 'article_edited'}
		{tr}A user edits an article{/tr}
	{elseif $watches[ix].event eq 'article_deleted'}
		{tr}A user deletes an article{/tr}
	{elseif $watches[ix].event eq 'blog_post'}
		{tr}A user submits a blog post{/tr}
	{elseif $watches[ix].event eq 'forum_post_thread'}
		{tr}A user posts a forum thread{/tr}
	{elseif $watches[ix].event eq 'forum_post_topic'}
		{tr}A user posts a forum topic{/tr}
	{elseif $watches[ix].event eq 'wiki_page_changed'}
		{tr}A user edited a wiki page{/tr}
	{elseif $watches[ix].event eq 'wiki_page_in_lang_created'}
		{tr}A user created a wiki page in a language{/tr}
	{/if}
	({$watches[ix].event})
</td>
<td class="{cycle}"><a class="link" href="{$watches[ix].url}">{tr}{$watches[ix].type}{/tr}: {$watches[ix].title}</a></td>
</tr>
{sectionelse}
<tr><td class="odd" colspan="2">{tr}No records found.{/tr}</td></tr>
{/section}
</table>
{if $watches}
{tr}Perform action with checked{/tr}: <input type="submit" name="delete" value=" {tr}Delete{/tr} ">
{/if}
</form>
