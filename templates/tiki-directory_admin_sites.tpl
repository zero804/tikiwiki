{* $Id$ *}

{title help="Directory" url="tiki-directory_admin_sites.php?parent=$parent"}{tr}Admin sites{/tr}{/title}

{include file=tiki-directory_admin_bar.tpl}

<h2>{tr}Parent directory category{/tr}:</h2>
<form name="path" method="post" action="tiki-directory_admin_sites.php">
	<select name="parent" onchange="javascript:path.submit();">
		<option value="0" {if $parent eq 0}selected="selected"{/if}>{tr}All{/tr}</option>
		{section name=ix loop=$categs}
			<option value="{$categs[ix].categId|escape}" {if $parent eq $categs[ix].categId}selected="selected"{/if}>{$categs[ix].path}</option>
		{/section}
	</select>
	<input type="submit" name="go" value="{tr}Go{/tr}" />
</form>

{* Dislay a form to add or edit a site *}
<h2>{if $siteId}{tr}Edit a site{/tr}{else}{tr}Add a site{/tr}{/if}</h2>
<form action="tiki-directory_admin_sites.php" method="post">
	<input type="hidden" name="parent" value="{$parent|escape}" />
	<input type="hidden" name="siteId" value="{$siteId|escape}" />
	<table class="normal">
		<tr>
			<td class="formcolor">{tr}Name{/tr}:</td>
			<td class="formcolor"><input type="text" name="name" value="{$info.name|escape}" /></td>
		</tr>
		<tr>
			<td class="formcolor">{tr}Description{/tr}:</td>
	    <td class="formcolor"><textarea rows="5" cols="60" name="description">{$info.description|escape}</textarea></td>
		</tr>
	  <tr>
		  <td class="formcolor">{tr}URL{/tr}:</td>
			<td class="formcolor"><input type="text" name="url" value="{$info.url|escape}" /></td>
	  </tr>
	  <tr>
		  <td class="formcolor">{tr}Directory Categories{/tr}:</td>
			<td class="formcolor">
		    <select name="siteCats[]" multiple="multiple" size="4">
			    {section name=ix loop=$categs}
			      <option value="{$categs[ix].categId|escape}" {if $categs[ix].belongs eq 'y'}selected="selected"{/if}>{$categs[ix].path}</option>
					{/section}
				</select>
				{if $categs|@count ge '2'}
					{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use Ctrl+Click to select multiple categories.{/tr}{/remarksbox}
				{/if}
			</td>
		</tr>
		
		{if $prefs.directory_country_flag eq 'y'}
			<tr>
				<td class="formcolor">{tr}Country{/tr}:</td>
				<td class="formcolor">
					<select name="country">
						{section name=ux loop=$countries}
							<option value="{$countries[ux]|escape}" {if $info.country eq $countries[ux]}selected="selected"{/if}>{$countries[ux]}</option>
						{/section}
					</select>
				</td>
			</tr>
		{/if}
  
		<tr>
			<td class="formcolor">{tr}Is valid{/tr}:</td>
			<td class="formcolor"><input name="isValid" type="checkbox" {if $info.isValid eq 'y'}checked="checked"{/if} /></td>
		</tr>
		<tr>
			<td class="formcolor">&nbsp;</td>
			<td class="formcolor"><input type="submit" name="save" value="{tr}Save{/tr}" /></td>
		</tr>
	</table>
</form>

<h2>{tr}Sites{/tr}</h2>
{* Display the list of categories (items) using pagination *}
{* Links to edit, remove, browse the categories *}
<form action="tiki-directory_admin_sites.php" method="post">
	<table class="normal">
		<tr>
			<th> </th>
			<th>
				<a href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'name_desc'}name_asc{else}name_desc{/if}">{tr}Name{/tr}</a>
			</th>
			<th>
				<a href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'url_desc'}url_asc{else}url_desc{/if}">{tr}URL{/tr}</a>
			</th>
			{if $prefs.directory_country_flag eq 'y'}
				<th>
					<a href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'country_desc'}country_asc{else}country_desc{/if}">{tr}Country{/tr}</a>
				</th>
			{/if}
			<th>
				<a href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}Hits{/tr}</a>
			</th>
			<th>
				<a href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'isValid_desc'}isValid_asc{else}isValid_desc{/if}">{tr}Valid{/tr}</a>
			</th>
			<th>{tr}Action{/tr}</th>
		</tr>
		{cycle values="odd,even" print=false}
		{section name=user loop=$items}
			<tr>
				<td class="{cycle advance=false}">
					<input type="checkbox" name="remove[]" value="{$items[user].siteId}" />
				</td>
				<td class="{cycle advance=false}">{$items[user].name}</td>
				<td class="{cycle advance=false}"><a href="{$items[user].url}" target="_new">{$items[user].url}</a></td>
				{if $prefs.directory_country_flag eq 'y'}
					<td class="{cycle advance=false}">
						<img src='img/flags/{$items[user].country}.gif' alt='{$items[user].country}'/>
					</td>
				{/if}
				<td class="{cycle advance=false}">{$items[user].hits}</td>
				<td class="{cycle advance=false}">{$items[user].isValid}</td>
				<td class="{cycle advance=false}">
					<a class="link" href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;siteId={$items[user].siteId}">{icon _id='page_edit'}</a>
					<a class="link" href="tiki-directory_admin_sites.php?parent={$parent}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$items[user].siteId}">{icon _id='cross' alt='{tr}Remove{/tr}'}</a>
				</td>
			</tr>
			<tr>
				<td class="{cycle advance=false}">&nbsp;</td>
				<td class="{cycle}" colspan="6">
					<i>
						{tr}Directory Categories{/tr}:{assign var=fsfs value=1}
						{section name=ii loop=$items[user].cats}
							{if $fsfs}{assign var=fsfs value=0}{else}, {/if}
							{$items[user].cats[ii].path}
						{/section}
					</i>
				</td>
			</tr>
		{sectionelse}
			<tr>
				<td class="odd" colspan="{if $prefs.directory_country_flag eq 'y'}7{else}6{/if}">
					{tr}No records found.{/tr}
				</td>
			</tr>
		{/section}
	</table>
	{if $items}
		{tr}Perform action with selected:{/tr} <input type="submit" name="groupdel" value=" {tr}Delete{/tr} " />
	{/if}
</form>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}
