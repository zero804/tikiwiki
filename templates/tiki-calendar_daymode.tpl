<table cellpadding="0" cellspacing="0" border="0" id="caltable">
<tr><td width="42" class="heading">{tr}Hours{/tr}</td><td class="heading">{tr}Events{/tr}</td></tr>
{cycle values="odd,even" print=false}
{foreach key=k item=h from=$hours}
<tr><td width="42" class="{cycle advance=false}">{$h}{tr}h{/tr}</td>
<td class="{cycle}">
{section name=hr loop=$hrows[$h]}
{if ($prefs.calendar_view_tab eq "y" or $tiki_p_change_events eq "y") and $hrows[$h][hr].calname ne ""}<span  style="float:right;">
<a href="tiki-calendar_edit_item.php?viewcalitemId={$hrows[$h][hr].calitemId}"{if $prefs.feature_tabs ne "y"}#details{/if} title="{tr}Details{/tr}">{icon _id='magnifier' alt="{tr}Zoom{/tr}"}</a>
{if $hrows[$h][hr].modifiable eq "y"}
<a href="tiki-calendar_edit_item.php?calitemId={$hrows[$h][hr].calitemId}" title="{tr}Edit{/tr}">{icon _id='page_edit'}</a>
<a href="tiki-calendar_edit_item.php?calitemId={$hrows[$h][hr].calitemId}&amp;delete=1"  title="{tr}Remove{/tr}">{icon _id='cross' alt="{tr}Remove{/tr}"}</a>{/if}</span>
{/if}
<div {if $hrows[$h][hr].calname ne ""}class="Cal{$hrows[$h][hr].type} vevent"{/if}>
<abbr class="dtstart" title="{$hrows[$h][hr].startTimeStamp|isodate}">{$hours[$h]}:{$hrows[$h][hr].mins}</abbr> : {if $hrows[$h][hr].calname eq ""}{$hrows[$h][hr].type} : {/if}
{if $myurl eq "tiki-action_calendar.php"}
<a href="{$hrows[$h][hr].url}" class="url" title="{$hrows[$h][hr].web|escape}" class="linkmenu summary">{$hrows[$h][hr].name}</a>
{else}
<a href="tiki-calendar_edit_item.php?viewcalitemId={$hrows[$h][hr].calitemId}" class="linkmenu summary">{$hrows[$h][hr].name}</a>
{/if}
<span class="description">
{if $hrows[$h][hr].calname ne ""}{$hrows[$h][hr].parsedDescription}{else}{$hrows[$h][hr].description}{/if}
</span>
</div>
{/section}
</td></tr>
{/foreach}
</table>
