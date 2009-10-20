{title help="Directory" url="tiki-directory_admin_related.php?parent=$parent"}{tr}Admin related directory categories{/tr}{/title}

{* Display the title using parent *}
{include file=tiki-directory_admin_bar.tpl}
{* Navigation bar to admin, admin related, etc *}

<h2>{tr}Parent category{/tr}:</h2>
{* Display the path adding manually the top category id=0 *}
<form name="path" method="post" action="tiki-directory_admin_related.php">
<select name="parent" onchange="javascript:path.submit();">
{section name=ix loop=$all_categs}
<option value="{$all_categs[ix].categId|escape}" {if $parent eq $all_categs[ix].categId}selected="selected"{/if}>{$all_categs[ix].path}</option>
{/section}
</select>
<input type="submit" name="go" value="{tr}Go{/tr}" />
</form>
<br />
<h2>{tr}Add a related category{/tr}</h2>
<form action="tiki-directory_admin_related.php" method="post">
<input type="hidden" name="parent" value="{$parent|escape}" />
<table class="normal">
  <tr>
    <td class="formcolor">{tr}Directory Category{/tr}:</td>
    <td class="formcolor">
    <select name="categId">
    {section name=ix loop=$categs}
      <option value="{$categs[ix].categId|escape}">{$categs[ix].path}</option>
    {/section}
    </select>
    </td>
  </tr>
  <tr>
    <td class="formcolor">{tr}Mutual{/tr}:</td>
    <td class="formcolor"><input type="checkbox" name="mutual" /></td>
  </tr>
  <tr>
    <td class="formcolor">&nbsp;</td>
    <td class="formcolor"><input type="submit" name="add" value="{tr}Save{/tr}" /></td>
  </tr>
</table>
</form>
<br />
<h2>{tr}Related categories{/tr}</h2>
{* Display the list of categories (items) using pagination *}
{* Links to edit, remove, browse the categories *}
<form action="tiki-directory_admin_related.php">
<input type="hidden" name="parent" value="{$parent|escape}" />
<input type="hidden" name="oldcategId" value="{$items[user].relatedTo|escape}" />
<table class="normal">
  <tr>
    <th>{tr}Directory Category{/tr}</th>
    <th>{tr}Action{/tr}</th>
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$items}
<tr>
<td class="{cycle advance=false}">
<select name="categId">
{section name=ix loop=$categs}
      <option value="{$categs[ix].categId|escape}" {if $categs[ix].categId eq $items[user].relatedTo}selected="selected"{/if}>{$categs[ix].path}</option>
{/section}
</select>
</td>
<td class="{cycle}">
<input type="submit" name="remove" value="{tr}Remove{/tr}" />
<input type="submit" name="update" value="{tr}Update{/tr}" />
</td>
</tr>
{sectionelse}<tr><td colspan="2" class="odd">{tr}No records found.{/tr}</td></tr>
{/section}
</table>
</form>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}
