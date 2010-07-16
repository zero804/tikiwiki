{* $Id$ *}
{if $tiki_p_create_file_galleries eq 'y'}
	{if $individual eq 'y'}
		<br /><a class="fgallink" href="tiki-objectpermissions.php?objectName={$name|escape:"url"}&amp;objectType=file+gallery&amp;permType=file+galleries&amp;objectId={$galleryId}">{tr}There are individual permissions set for this file gallery{/tr}</a>
	{/if}
	<div>
		<form class="admin" action="{$smarty.server.PHP_SELF}?{query}" method="post">
			<input type="hidden" name="galleryId" value="{$galleryId|escape}" />
			<input type="hidden" name="filegals_manager" value="{$filegals_manager}" />


			<div class="input_submit_container" style="text-align: right"><input type="submit" value="{tr}Save{/tr}" name="edit" />&nbsp;<input type="checkbox" name="viewitem" checked="checked"/> {tr}View inserted gallery{/tr}</div>
				{tabset name="list_file_gallery"}
					{tab name="{tr}Properties{/tr}"}
						<table class="normal">
							<tr>
								<td class="formcolor"><label for="name">{tr}Name{/tr}:</label></td>
								<td class="formcolor">
									{if $galleryId eq $prefs.fgal_root_id}
										<b>{tr}File Galleries{/tr}</b>
										<input type="hidden" name="name" value="{$gal_info.name|escape}" />
									{else}
										<input type="text" size="50" id="name" name="name" value="{$gal_info.name|escape}" style="width:100%"/><br/><em>{tr}Required for podcasts{/tr}.</em>
									{/if}
								</td>
							</tr>
							<tr>
								<td class="formcolor"><label for="fgal_type">{tr}Type{/tr}:</label></td>
								<td class="formcolor">
									{if $galleryId eq $prefs.fgal_root_id}
										{tr}System{/tr}
										<input type="hidden" name="fgal_type" value="system" />
									{else}
										<select name="fgal_type" id="fgal_type">
											<!-- TODO: make this a configurable list read from database -->
											<option value="default" {if $gal_info.type eq 'default'}selected="selected"{/if}>{tr}Any file{/tr}</option>
											<option value="podcast" {if $gal_info.type eq 'podcast'}selected="selected"{/if}>{tr}Podcast (audio){/tr}</option>
											<option value="vidcast" {if $gal_info.type eq 'vidcast'}selected="selected"{/if}>{tr}Podcast (video){/tr}</option>
										</select>
									{/if}
							</td>
						</tr>
						<tr><td class="formcolor"><label for="description">{tr}Description{/tr}:</label></td><td class="formcolor"><textarea rows="5" cols="40" id="description" name="description" style="width:100%">{$gal_info.description|escape}</textarea><br/><em>{tr}Required for podcasts{/tr}.</em></td></tr>
						<tr><td class="formcolor"><label for="visible">{tr}Gallery is visible to non-admin users{/tr}.<label></td><td class="formcolor"><input type="checkbox" id="visible" name="visible" {if $gal_info.visible eq 'y'}checked="checked"{/if} /></td></tr>

						<tr><td class="formcolor"><label for="public">{tr}Gallery is public{/tr}.</label></td><td class="formcolor"><input type="checkbox" id="public" name="public" {if $gal_info.public eq 'y'}checked="checked"{/if}/><br /><em>{tr}Any user with permission (not only the gallery owner) can upload files{/tr}.</em></td></tr>
						<tr><td class="formcolor"><label for="backlinkPerms">{tr}Perms of the backlinks are checked to view a file{/tr}</label></td><td class="formcolor"><input type="checkbox" id="backlinkPerms" name="backlinkPerms" {if $gal_info.backlinkPerms eq 'y'}checked="checked"{/if}/></td></tr>
						<tr><td class="formcolor"><label for="lockable">{tr}Files can be locked at download{/tr}.</label> </td><td class="formcolor"><input type="checkbox" id="lockable" name="lockable" {if $gal_info.lockable eq 'y'}checked="checked"{/if}/></td></tr>
						<tr>
							<td class="formcolor"><label for="archives">{tr}Maximum number of archives for each file{/tr}:</label> </td>
							<td class="formcolor"><input size="5" type="text" id="archives" name="archives" value="{$gal_info.archives|escape}" /><br /><em>{tr}Use{/tr} 0={tr}unlimited{/tr}, -1={tr}none{/tr}.</em>
								{if ! isset($smarty.request.parentId) and $galleryId neq $prefs.fgal_root_id}
							</td>
						</tr>
						<tr>
							<td class="formcolor"><label for="parentId">{tr}Parent gallery{/tr}:</label></td>
							<td class="formcolor">
								<select name="parentId" id="parentId">
									<option value="{$prefs.fgal_root_id}"{if $parentId eq $prefs.fgal_root_id} selected="selected"{/if}>{tr}none{/tr}</option>
									{foreach from=$all_galleries key=key item=item}
										{if $galleryId neq $item.id}
										<option value="{$item.id}"{if $parentId eq $item.id} selected="selected"{/if}>{$item.label|escape}</option>
										{/if}
									{/foreach}
								</select>
								{else}
								<input type="hidden" name="parentId" value="{$parentId|escape}" />
								{/if}
							</td>
						</tr>

						{if $tiki_p_admin eq 'y' or $tiki_p_admin_file_galleries eq 'y'}
							<tr>
								<td class="formcolor"><label for="user">{tr}Owner of the gallery{/tr}:</label></td>
								<td class="formcolor">
									{user_selector user=$creator id='user'}
								</td>
							</tr>

							{if $prefs.fgal_quota_per_fgal eq 'y'}
								<tr>
									<td class="formcolor">{tr}Quota{/tr}</td>
									<td class="formcolor">
										<input type="text" id="quota" name="quota" value="{$gal_info.quota}" size="5" />{tr}Mb{/tr} <i>{tr}(0 for unlimited){/tr}</i>{if $gal_info.usedSize}<br />{tr}Used:{/tr} {$gal_info.usedSize|kbsize}{/if}
										{if !empty($gal_info.quota)}{capture name='use'}{math equation="round((100*x)/(1024*1024*y))" x=$gal_info.usedSize y=$gal_info.quota}{/capture}{quotabar length='100' value=`$smarty.capture.use`}{/if}
										{if !empty($gal_info.maxQuota)}<br />{tr}Max:{/tr} {$gal_info.maxQuota} {tr}Mb{/tr}{/if}
										{if !empty($gal_info.minQuota)}<br />{tr}Min:{/tr} {$gal_info.minQuota} {tr}Mb{/tr}{/if}
									</td>
								</tr>
							{/if}

							{if $prefs.feature_groupalert eq 'y'}
								<tr>
									<td class="formcolor">{tr}Group of users alerted when file gallery is modified{/tr}</td>
									<td class="formcolor">
										<select id="groupforAlert" name="groupforAlert">
											<option value="">&nbsp;</option>
											{foreach key=k item=i from=$groupforAlertList}
												<option value="{$k}" {$i}>{$k}</option>
											{/foreach}
										</select>
									</td>
								</tr>

								<tr>
									<td class="formcolor">{tr}Allows to select each user for small groups{/tr}</td>
									<td class="formcolor"><input type="checkbox" name="showeachuser" {if $showeachuser eq 'y'}checked="checked"{/if}/ ></td>
								</tr>
							{/if}

						{/if}

						<tr><td class="formcolor">{tr}Maximum width for images in gallery{/tr}:</td><td class="formcolor"><input size="5" type="text" name="image_max_size_x" value="{$gal_info.image_max_size_x|escape}" /> px<br /><i>{tr}If an image is wider than this, it will be resized.{/tr} {tr}Attention: In this case, the original image will be lost.{/tr} (0={tr}unlimited{/tr})</i></td></tr>
						<tr><td class="formcolor">{tr}Maximum height for images in gallery{/tr}:</td><td class="formcolor"><input size="5" type="text" name="image_max_size_y" value="{$gal_info.image_max_size_y|escape}" /> px<br /><i>{tr}If an image is higher than this, it will be resized.{/tr} {tr}Attention: In this case, the original image will be lost.{/tr} (0={tr}unlimited{/tr})</i></td></tr>

		 				{include file='categorize.tpl'}

					</table>
				{/tab}

<!-- display properties -->
				{tab name="{tr}Display Properties{/tr}"}
					<table class="normal">
						<tr>
							<td class="formcolor"><label for="sortorder">{tr}Default sort order{/tr}:</label></td>
							<td class="formcolor">
								<select name="sortorder" id="sortorder">
									{foreach from=$options_sortorder key=key item=item}
										<option value="{$item|escape}" {if $sortorder == $item} selected="selected"{/if}>{$key}</option>
									{/foreach}
								</select><br />
								<input type="radio" id="sortdirection1" name="sortdirection" value="desc" {if $sortdirection == 'desc'}checked="checked"{/if} /><label for="sortdirection1">{tr}Descending{/tr}</label><br />
								<input type="radio" id="sortdirection2" name="sortdirection" value="asc" {if $sortdirection == 'asc'}checked="checked"{/if} /><label for="sortdirection2">{tr}Ascending{/tr}</label>
							</td>
						</tr>
						<tr>
							<td class="formcolor"><label for="max_desc">{tr}Max description display size{/tr}:</label></td>
							<td class="formcolor"><input type="text" id="max_desc" name="max_desc" value="{$max_desc|escape}" /></td>
						</tr>
						<tr><td class="formcolor"><label for="maxRows">{tr}Max rows per page{/tr}:</label></td><td class="formcolor"><input type="text" id="maxRows" name="maxRows" value="{$maxRows|escape}" /></td></tr>
						<tr>
							<td class="formcolor" colspan="2">{tr}Select which items to display when listing galleries{/tr}: 
								<table>
									{include file='fgal_listing_conf.tpl'}
								</table>
							</td>
						</tr>
					</table>
				{/tab}
			{/tabset}
			<input type="submit" value="{tr}Save{/tr}" name="edit" />
		</form>
	</div>
	<br />
{/if}
