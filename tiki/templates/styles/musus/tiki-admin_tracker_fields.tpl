<a class="pagetitle" href="tiki-admin_tracker_fields.php?trackerId={$trackerId}">{tr}Admin tracker{/tr}: {$tracker_info.name}</a><br /><br />
<a href="tiki-list_trackers.php" class="linkbut">{tr}List trackers{/tr}</a>
{if $tiki_p_admin_trackers eq 'y'}
<a href="tiki-admin_trackers.php" class="linkbut">{tr}Admin trackers{/tr}</a>
<a href="tiki-admin_trackers.php?trackerId={$trackerId}" class="linkbut">{tr}Edit this tracker{/tr}</a>
{/if}
<a href="tiki-view_tracker.php?trackerId={$trackerId}" class="linkbut">{tr}View this tracker items{/tr}</a>
<br /><br />
<h2>{tr}Edit tracker fields{/tr}</h2>
<form action="tiki-admin_tracker_fields.php" method="post">
<input type="hidden" name="fieldId" value="{$fieldId|escape}" />
<input type="hidden" name="trackerId" value="{$trackerId|escape}" />
<table>
<tr><td>{tr}Name{/tr}:</td><td><input type="text" name="name" value="{$name|escape}" /></td></tr>
<tr><td>{tr}Type{/tr}:
<div id='trkflddropdown' {if $type eq 'd'}style="display:inline;"{else}style="display:none;"{/if}>{tr}(Dropdown options : list of items separated with commas){/tr}:</div>
<div id='trkfldimage' {if $type eq 'i'}style="display:inline;"{else}style="display:none;"{/if}>{tr}(Image options : xSize,ySize indicated in pixels){/tr}:</div>
</td><td>
<select name="type" id='trkfldtype' onchange="javascript:chgTrkFld();">
<option value="c" {if $type eq 'c'}selected="selected"{/if}>{tr}checkbox{/tr}</option>
<option value="t" {if $type eq 't'}selected="selected"{/if}>{tr}text field{/tr}</option>
<option value="a" {if $type eq 'a'}selected="selected"{/if}>{tr}textarea{/tr}</option>
<option value="d" {if $type eq 'd'}selected="selected"{/if}>{tr}drop down{/tr}</option>
<option value="u" {if $type eq 'u'}selected="selected"{/if}>{tr}user selector{/tr}</option>
<option value="g" {if $type eq 'g'}selected="selected"{/if}>{tr}group selector{/tr}</option>
<option value="f" {if $type eq 'f'}selected="selected"{/if}>{tr}date and time{/tr}</option>
<option value="i" {if $type eq 'i'}selected="selected"{/if}>{tr}image{/tr}</option>
</select>
<div id='trkfldoptions' {if $type eq 'd' or $type eq 'i'}style="display:inline;"{else}style="display:none;"{/if}><input type="text" name="options" value="{$options|escape}" /></div>
</td></tr>
<tr><td>{tr}Is column visible when listing tracker items?{/tr}</td><td><input type="checkbox" name="isTblVisible" {if $isTblVisible eq 'y'}checked="checked"{/if} /></td></tr>
<tr><td>{tr}Column links to edit/view item?{/tr}</td><td><input type="checkbox" name="isMain" {if $isMain eq 'y'}checked="checked"{/if} /></td></tr>
<tr><td >&nbsp;</td><td><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
<h2>{tr}Tracker fields{/tr}</h2>
<div align="center">
<table class="findtable">
<tr><td>{tr}Find{/tr}</td>
   <td>
   <form method="get" action="tiki-admin_tracker_fields.php">
     <input type="text" name="find" value="{$find|escape}" />
     <input type="submit" value="{tr}find{/tr}" name="search" />
     <input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
   </form>
   </td>
</tr>
</table>
<table>
<tr>
<th><a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}name{/tr}</a></th>
<th><a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'type_desc'}type_asc{else}type_desc{/if}">{tr}type{/tr}</a></th>
<th><a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'isMain_desc'}isMain_asc{else}isMain_desc{/if}">{tr}isMain{/tr}</a></th>
<th><a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'isTblVisible_desc'}isTblVisible_asc{else}isTblVisible_desc{/if}">{tr}Tbl vis{/tr}</a></th>
<th>{tr}action{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$channels}
<tr>
<td class="{cycle advance=false}">{$channels[user].name}</td>
<td class="{cycle advance=false}">{$channels[user].type}</td>
<td class="{cycle advance=false}">{$channels[user].isMain}</td>
<td class="{cycle advance=false}">{$channels[user].isTblVisible}</td>
<td class="{cycle}">
   <a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].fieldId}">{tr}remove{/tr}</a>
   <a href="tiki-admin_tracker_fields.php?trackerId={$trackerId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;fieldId={$channels[user].fieldId}">{tr}edit{/tr}</a>
</td>
</tr>
{/section}
</table>
<br />
<div class="mini">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-admin_tracker_fields.php?find={$find}&amp;trackerId={$trackerId}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-admin_tracker_fields.php?find={$find}&amp;trackerId={$trackerId}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}next{/tr}</a>]
{/if}
{if $direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$maxRecords}
<a class="prevnext" href="tiki-admin_tracker_fields.php?find={$find}&amp;trackerId={$trackerId}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>