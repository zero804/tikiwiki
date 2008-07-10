{*Smarty template*}

{title help="User+Files"}{tr}User Files{/tr}{/title}

{if $prefs.feature_ajax ne 'y' && $prefs.feature_mootools ne 'y'}
<!-- this bar is created by a ref to {include file=tiki-mytiki_bar.tpl} :) -->
{include file=tiki-mytiki_bar.tpl}
{/if}

<div style="text-align:center;">
  <div style="height:20px; width:200px; border:1px solid black; background-color:#666666; text-align:left; margin:0 auto;">
    <div style="background-color:red; height:100%; width:{$cellsize}px;"> 
    </div>
  </div>
  {if $user neq admin}
    <small>{tr}Used space:{/tr} {$percentage}% {tr}up to{/tr} {$limitmb} Mb</small>
  {else}
    <small>{tr}Used space:{/tr} {tr}no limit for admin{/tr}</small>
  {/if}
</div>
<br />
<h2>{tr}User Files{/tr}</h2>
<form action="tiki-userfiles.php" method="post">
<table class="normal">
<tr>
<th style="text-align:center;" class="heading">&nbsp;</th>
<th class="heading"><a class="tableheading" href="tiki-userfiles.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'filename_desc'}filename_asc{else}filename_desc{/if}">{tr}Name{/tr}</a></th>
<th class="heading"><a class="tableheading" href="tiki-userfiles.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Created{/tr}</a></th>
<th style="text-align:right;" class="heading"><a class="tableheading" href="tiki-userfiles.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'filesize_desc'}filesize_asc{else}filesize_desc{/if}">{tr}Size{/tr}</a></th>
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$channels}
<tr>
<td style="text-align:center;" class="{cycle advance=false}">
<input type="checkbox" name="userfile[{$channels[user].fileId}]" />
</td>
<td class="{cycle advance=false}">{$channels[user].filename|iconify} <a class="link" href="tiki-download_userfile.php?fileId={$channels[user].fileId}">{$channels[user].filename}</a></td>
<td class="{cycle advance=false}">{$channels[user].created|tiki_short_datetime}</td>
<td style="text-align:right;" class="{cycle}">{$channels[user].filesize|kbsize}</td>
</tr>
{sectionelse}<tr><td class="odd" colspan="4">{tr}No records found.{/tr}</td></tr>
{/section}
</table>
{if $channels|@count ge '1'}
{tr}Perform action with checked:{/tr} <input type="submit" name="delete" value="{tr}Delete{/tr}" />
{/if}
</form>
<div class="mini">
<div align="center">
{if $prev_offset >= 0}
[<a class="prevnext" href="tiki-userfiles.php?find={$find}&amp;offset={$prev_offset}&amp;sort_mode={$sort_mode}">{tr}Prev{/tr}</a>]&nbsp;
{/if}
{tr}Page{/tr}: {$actual_page}/{$cant_pages}
{if $next_offset >= 0}
&nbsp;[<a class="prevnext" href="tiki-userfiles.php?find={$find}&amp;offset={$next_offset}&amp;sort_mode={$sort_mode}">{tr}Next{/tr}</a>]
{/if}
{if $prefs.direct_pagination eq 'y'}
<br />
{section loop=$cant_pages name=foo}
{assign var=selector_offset value=$smarty.section.foo.index|times:$prefs.maxRecords}
<a class="prevnext" href="tiki-userfiles.php?find={$find}&amp;offset={$selector_offset}&amp;sort_mode={$sort_mode}">
{$smarty.section.foo.index_next}</a>&nbsp;
{/section}
{/if}
</div>
</div>
<br />
<h2>{tr}Upload file{/tr}</h2>
<form enctype="multipart/form-data" action="tiki-userfiles.php" method="post">
  <table class="normal">
    <tr>
      <td class="formcolor">{tr}Upload file{/tr}:</td>
      <td class="formcolor">
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000000" /><input size="60" name="userfile1" type="file" /><br />
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000000" /><input size="60" name="userfile2" type="file" /><br />
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000000" /><input size="60" name="userfile3" type="file" /><br />
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000000" /><input size="60" name="userfile4" type="file" /><br />
        <input type="hidden" name="MAX_FILE_SIZE" value="10000000000000" /><input size="60" name="userfile5" type="file" /><br />
        <input style="font-size:9px;" type="submit" name="upload" value="{tr}Upload{/tr}" />
      </td>
    </tr>
  </table>
</form>
