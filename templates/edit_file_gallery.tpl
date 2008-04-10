{* $Id$ *}
{if $tiki_p_create_file_galleries eq 'y'}
  {if $individual eq 'y'}
  <br /><a class="fgallink" href="tiki-objectpermissions.php?objectName={$name|escape:"url"}&amp;objectType=file+gallery&amp;permType=file+galleries&amp;objectId={$galleryId}">{tr}There are individual permissions set for this file gallery{/tr}</a>
  {/if}
  <div>
    <form action="tiki-list_file_gallery.php{if $filegals_manager eq 'y'}?filegals_manager=y{/if}" method="post">
      <input type="hidden" name="galleryId" value="{$galleryId|escape}" />

       <span style="float:right; margin-bottom: -1em"><input type="submit" value="{tr}Save{/tr}" name="edit" />&nbsp;<input type="checkbox" name="viewitem" checked="checked"/> {tr}View inserted gallery{/tr}</span>

			<div class="tabs" style="clear: both;">
				<span id="tab1" class="tabmark tabactive"><a href="javascript:tikitabs(1,3);">{tr}Properties{/tr}</a></span>
				<span id="tab2" class="tabmark tabactive"><a href="javascript:tikitabs(2,3);">{tr}Display Properties{/tr}</a></span>
			</div>

			<div id="content1"  class="tabcontent" style="clear:both;display:block;">
      <table class="normal">
        <tr><td class="formcolor">{tr}Name{/tr}:<br/> ({tr}required field for podcasts{/tr})</td><td class="formcolor"><input type="text" size="50" name="name" value="{$name|escape}" style="width:100%"/></td></tr>
        <tr><td class="formcolor">{tr}Type{/tr}:</td><td class="formcolor">
          <select name="fgal_type">
            <!-- TODO: make this a configurable list read from database -->
            <option value="default" {if $fgal_type eq 'default'}selected="selected"{/if}>{tr}any file{/tr}</option>
            <option value="podcast" {if $fgal_type eq 'podcast'}selected="selected"{/if}>{tr}podcast (audio){/tr}</option>
            <option value="vidcast" {if $fgal_type eq 'vidcast'}selected="selected"{/if}>{tr}podcast (video){/tr}</option>
          </select>
        </td></tr>
        <tr><td class="formcolor">{tr}Description{/tr}:<br/>({tr}required field for podcasts{/tr})</td><td class="formcolor"><textarea rows="5" cols="40" name="description" style="width:100%">{$description|escape}</textarea></td></tr>
        <tr><td class="formcolor">{tr}Gallery is visible to non-admin users?{/tr}</td><td class="formcolor"><input type="checkbox" name="visible" {if $visible eq 'y'}checked="checked"{/if} /></td></tr>       

        <tr><td class="formcolor">{tr}This Gallery is Public{/tr}:</td><td class="formcolor"><input type="checkbox" name="public" {if $public eq 'y'}checked="checked"{/if}/></td></tr>
        <tr><td class="formcolor">{tr}The files can be locked at download:{/tr} </td><td class="formcolor"><input type="checkbox" name="lockable" {if $lockable eq 'y'}checked="checked"{/if}/></td></tr>
        <tr><td class="formcolor">{tr}Maximum number of archives for each file{/tr}: </td><td class="formcolor"><input size="5" type="text" name="archives" value="{$archives|escape}" /> <i>(0={tr}unlimited{/tr}) (-1={tr}none{/tr})</i></td></tr>
        <tr><td class="formcolor">{tr}Parent gallery{/tr}:</td><td class="formcolor">
          <select name="parentId">
            <option value="-1" {if $parentId == -1} selected="selected"{/if}>{tr}none{/tr}</option>
            {foreach from=$all_galleries key=key item=item}
              <option value="{$item.id}" {if $parentId == $item.id} selected="selected"{/if}>{$item.name|escape}</option>
            {/foreach}
          </select>
        </td></tr>
        {if $tiki_p_admin eq 'y' or $tiki_p_admin_file_galleries eq 'y'}
        <tr><td class="formcolor">{tr}Owner of the gallery{/tr}:</td><td class="formcolor">
          <select name="user">
          {section name=ix loop=$users}<option value="{$users[ix].login|escape}"{if $creator eq $users[ix].login} selected="selected"{/if}>{$users[ix].login|username}</option>{/section}
          </select>
        </td></tr>
        {/if}
        {include file='categorize.tpl'}

				</table>
				</div>
<!--display -->
			<div id="content2"  class="tabcontent" style="clear:both;display:none;">
				<table class="normal">
        <tr><td class="formcolor">{tr}Default sort order{/tr}:</td><td class="formcolor">
          <select name="sortorder">
          {foreach from=$options_sortorder key=key item=item}
            <option value="{$item|escape}" {if $sortorder == $item} selected="selected"{/if}>{$key}</option>
          {/foreach}
          </select>
          <input type="radio" name="sortdirection" value="desc" {if $sortdirection == 'desc'}checked="checked"{/if} />{tr}descending{/tr}
          <input type="radio" name="sortdirection" value="asc" {if $sortdirection == 'asc'}checked="checked"{/if} />{tr}ascending{/tr}
        </td></tr>
        <tr>
	  <td class="formcolor">{tr}Max description display size{/tr}</td>
          <td class="formcolor"><input type="text" name="max_desc" value="{$max_desc|escape}" /></td>
        </tr>
        <tr><td class="formcolor">{tr}Max Rows per page{/tr}:</td><td class="formcolor"><input type="text" name="maxRows" value="{$maxRows|escape}" /></td></tr>
        <tr><td class="formcolor">{tr}Listing configuration{/tr}</td><td class="formcolor">
          <table>
            {include file='fgal_listing_conf.tpl'}
          </table>
        </td></tr>
      </table>
			</div>
       <span style="float:right; margin-top: 10px;"><input type="submit" value="{tr}Save{/tr}" name="edit" /></span>
    </form>
  </div>
<br />
{/if}
