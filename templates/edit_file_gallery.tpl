{* $Id$ *}
{if $tiki_p_create_file_galleries eq 'y' or ($user eq $gal_info.user and $gal_info.type eq 'user' and $tiki_p_userfiles)}
	{if isset($individual) and $individual eq 'y'}
		{permission_link mode=button type="file gallery" permType="file galleries" id=$galleryId title=$name label="{tr}There are individual permissions set for this file gallery{/tr}"}
	{/if}
		<form class="form-horizontal" role="form" action="{$smarty.server.PHP_SELF}?{query}" method="post">
			<input type="hidden" name="galleryId" value="{$galleryId|escape}">
			<input type="hidden" name="filegals_manager" {if isset($filegals_manager)}value="{$filegals_manager}"{/if}>

			<div class="row"> {* 100% width, padding: 0 -15px *}
                <div class="form-group col-lg-12 clearfix"> {* bottom margin: 20px; padding: 0 15px; clear float *}
                    <div class="pull-right"> {* float: right; *}
        				<input type="submit" class="btn btn-primary" value="{tr}Save{/tr}" name="edit">
	        			&nbsp;
			        	<input type="checkbox" name="viewitem" checked="checked"> {tr}View inserted gallery{/tr}
                    </div>
                </div>
			</div>
			{tabset name="list_file_gallery"}
				{tab name="{tr}Properties{/tr}"}
                    <h2>{tr}Properties{/tr}</h2>
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">{tr}Name{/tr}</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                {if $galleryId eq $treeRootId or $gal_info.type eq 'user'}
								    <b>{tr}{$gal_info.name}{/tr}</b>
								    <input type="hidden" name="name" value="{$gal_info.name|escape}">
						    	{else}
							    	<input type="text" size="50" id="name" name="name" value="{$gal_info.name|escape}">
								    <span class="help-block">{tr}Required for podcasts{/tr}.</span>
						    	{/if}
                            </p>
						</div>
                    </div>
					{if $prefs.feature_file_galleries_templates eq 'y'}
                        <div class="form-group">
                            <label for="fgal_template" class="col-sm-4 control-label">{tr}Template{/tr}</label>
                            <div class="col-sm-8">
							    <select name="fgal_template" id="fgal_template">
								    <option value=""{if !isset($templateId) or $templateId eq ""} selected="selected"{/if}>{tr}None{/tr}</option>
								    {foreach from=$all_templates key=key item=item}
									    <option value="{$item.id}"{if $gal_info.template eq $item.id} selected="selected"{/if}>{$item.label|escape}</option>
							    	{/foreach}
								    {jq}
$('#fgal_template').change( function() {
	var otherTabs = $('span.tabinactive');
	var otherParams = $('#description').parents('tr').nextAll('tr');

	if ($(this).val() != '') {
		// Select template, hide parameters
		otherTabs.hide();
		otherParams.hide();
	} else {
		// No template, show parameters
		otherTabs.show();
		otherParams.show();
	}
}).change();
								    {/jq}
								</select>
						    </div>
                        </div>
					{/if}
                    <div class="form-group">
                        <label for="fgal_type" class="col-sm-4 control-label">{tr}Type{/tr}</label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
					    	    {if $galleryId eq $treeRootId or $gal_info.type eq 'user'}
								    {if $gal_info.type eq 'system'}
									    {tr}System{/tr}
							    	{elseif $gal_info.type eq 'user'}
								    	{tr}User{/tr}
								    {else}
									    {tr _0=$gal_info.type}Other (%0){/tr}
						    		{/if}
							    	<input type="hidden" name="fgal_type" value="{$gal_info.type}">
						    	{else}
							    	<select name="fgal_type" id="fgal_type">
							    		<option value="default" {if $gal_info.type eq 'default'}selected="selected"{/if}>{tr}Any file{/tr}</option>
								    	<option value="podcast" {if $gal_info.type eq 'podcast'}selected="selected"{/if}>{tr}Podcast (audio){/tr}</option>
								    	<option value="vidcast" {if $gal_info.type eq 'vidcast'}selected="selected"{/if}>{tr}Podcast (video){/tr}</option>
								    </select>
						    	{/if}
                            </p>
						</div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-4 control-label">{tr}Description{/tr}</label>
                        <div class="col-sm-8">
        			       	<textarea rows="5" cols="40" id="description" name="description" style="width:100%">{$gal_info.description|escape}</textarea>
			        		<span class="help-block">{tr}Required for podcasts{/tr}.</span>
                        </div>
                    </div>
                    <div class="form-group">
                            <label for="visible" class="col-sm-4 control-label">{tr}Gallery is visible to non-admin users{/tr}.</label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <input type="checkbox" id="visible" name="visible" {if $gal_info.visible eq 'y'}checked="checked"{/if}>
                                </div>
                            </div>
                    </div>
                    <div class="form-group">
        			    <label for="public" class="col-sm-4 control-label">{tr}Gallery is unlocked{/tr}.</label>
                        <div class="col-sm-8">
                            <div class="checkbox">
                                <input type="checkbox" id="public" name="public" {if isset($gal_info.public) and $gal_info.public eq 'y'}checked="checked"{/if}>
                            </div>
				        	<span class="help-block">{tr}Unless this option is checked the Gallery is locked and only the owner can upload - if it is checked other users with upload permission can add files to the gallery{/tr}.</span>
                        </div>
				    </div>
					{if $tiki_p_admin_file_galleries eq 'y' or $gal_info.type neq 'user'}
                        <div class="form-group">
                            <label for="backlinkPerms" class="col-sm-4 control-label">{tr}Perms of the backlinks are checked to view a file{/tr}</label>
                            <div class="col-sm-8">
                                <div class="checkbox">
							        <input type="checkbox" id="backlinkPerms" name="backlinkPerms" {if $gal_info.backlinkPerms eq 'y'}checked="checked"{/if}>
							    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="lockable" class="col-sm-4 control-label">{tr}Files can be locked at download{/tr}.</label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <input type="checkbox" id="lockable" name="lockable" {if $gal_info.lockable eq 'y'}checked="checked"{/if}>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="archives" class="col-sm-4 text-right">{tr}Maximum number of archives for each file{/tr}</label>
                            <div class="col-sm-8">
						        <input size="5" type="text" id="archives" name="archives" value="{$gal_info.archives|escape}">
							    <br>
							    <em>{tr}Use{/tr} 0={tr}unlimited{/tr}, -1={tr}none{/tr}.</em>
							    {if $galleryId neq $treeRootId}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="parentId" class="col-sm-4 control-label">{tr}Parent gallery{/tr}</label>
                            <div class="col-sm-8">
               				    <select name="parentId" id="parentId">
			            		    <option value="{$treeRootId}"{if $parentId eq $treeRootId} selected="selected"{/if}>{tr}none{/tr}</option>
						            {foreach from=$all_galleries key=key item=item}
									    {if $galleryId neq $item.id}
									        <option value="{$item.id}"{if $parentId eq $item.id} selected="selected"{/if}>{$item.label|escape}</option>
									    {/if}
									{/foreach}
								</select>
					{else}
					    <input type="hidden" name="parentId" value="{$parentId|escape}">
					{/if}
				</div>
			</div>
						    {/if}
						    {if $tiki_p_admin eq 'y' or $tiki_p_admin_file_galleries eq 'y'}
                                <div class="form-group">
                                    <label for="user" class="col-sm-4 text-right">{tr}Owner of the gallery{/tr}</label>
                                    <div class="col-sm-8">
    								    {user_selector user=$creator id='user'}
                                    </div>
							    </div>

							{if $prefs.fgal_quota_per_fgal eq 'y'}
                                <div class="form-group">
                                    <label for="quota" class="col-sm-4 control-label">{tr}Quota{/tr}</label>
                                    <div class="col-sm-8">
										<input type="text" id="quota" name="quota" value="{$gal_info.quota}" size="5">{tr}Mb{/tr} <span class="help-block">{tr}(0 for unlimited){/tr}</span>
										{if $gal_info.usedSize}<br>{tr}Used:{/tr} {$gal_info.usedSize|kbsize}{/if}
										{if !empty($gal_info.quota)}
											{capture name='use'}
												{math equation="round((100*x)/(1024*1024*y))" x=$gal_info.usedSize y=$gal_info.quota}
											{/capture}
											{quotabar length='100' value=$smarty.capture.use}
										{/if}
										{if !empty($gal_info.maxQuota)}<br>{tr}Max:{/tr} {$gal_info.maxQuota} {tr}Mb{/tr}{/if}
										{if !empty($gal_info.minQuota)}<br>{tr}Min:{/tr} {$gal_info.minQuota|string_format:"%.2f"} {tr}Mb{/tr}{/if}
									</div>
								</div>
							{/if}

							{if $prefs.feature_groupalert eq 'y'}
                                <div class="form-group">
                                    <label for="groupforAlert" class="col-sm-4 control-label">{tr}Group of users alerted when file gallery is modified{/tr}</label>
                                    <div class="col-sm-8">
										<select id="groupforAlert" name="groupforAlert">
											<option value="">&nbsp;</option>
											{foreach key=k item=i from=$groupforAlertList}
												<option value="{$k}" {$i}>{$k}</option>
											{/foreach}
										</select>
									</div>
								</div>
                                <div class="form-group">
                                    <label for="showeachuser" class="col-sm-4 control-label">{tr}Allows each user to be selected for small groups{/tr}</label>
                                    <div class="col-sm-8">
                                        <div class="checkbox">
                                            <input type="checkbox" name="showeachuser" id="showeachuser" {if $showeachuser eq 'y'}checked="checked"{/if}>
                                        </div>
                                    </div>
								</div>
							{/if}
						{/if}
                        <div class="form-group">
                            <label for="image_max_size_x" class="col-sm-4 text-right">{tr}Maximum width for images in gallery{/tr}</label>
                            <div class="col-sm-8">
    							<input size="5" type="text" name="image_max_size_x" id="image_max_size_x" value="{$gal_info.image_max_size_x|escape}"> px
								<br>
								<span class="help-block">{tr}If an image is wider than this, it will be resized.{/tr} {tr}Attention: In this case, the original image will be lost.{/tr} (0={tr}unlimited{/tr})</span>
							</div>
						</div>
                        <div class="form-group">
                            <label for="image_max_size_y" class="col-sm-4 text-right">{tr}Maximum height for images in gallery{/tr}</label>
                            <div class="col-sm-8">
								<input size="5" type="text" name="image_max_size_y" id="image_max_size_y" value="{$gal_info.image_max_size_y|escape}"> px
								<span class="help-block">{tr}If an image is higher than this, it will be resized.{/tr} {tr}Attention: In this case, the original image will be lost.{/tr} (0={tr}unlimited{/tr})</span>
							</div>
						</div>
                        <div class="form-group">
                            <label for="wiki_syntax" class="col-sm-4 text-right">{tr}Wiki markup to enter when image selected from "file gallery manager"{/tr}</label>
                            <div class="col-sm-8">
								<input size="80" type="text" name="wiki_syntax" id="wiki_syntax" value="{$gal_info.wiki_syntax|escape}">
								<br>
								<span class="help-block">{tr}The default is {/tr}"{literal}{img fileId="%fileId%" thumb="y" rel="box[g]"}{/literal}")</span>
								<span class="help-block">{tr}Field names will be replaced when enclosed in % chars. e.g. %fileId%, %name%, %filename%, %description%{/tr}</span>
								<span class="help-block">{tr}Attributes will be replaced when enclosed in % chars. e.g. %tiki.content.url% for remote file URLs{/tr}</span>
							</div>
						</div>

		 				{include file='categorize.tpl'}

				{*	</div> *}
				{/tab}

<!-- display properties -->
				{tab name="{tr}Display Properties{/tr}"}
            <div class="form-group">
                <label for="sortorder" class="col-sm-4 text-right">{tr}Default sort order{/tr}</label>
                <div class="col-sm-8">
					<select name="sortorder" id="sortorder">
						{foreach from=$options_sortorder key=key item=item}
							<option value="{$item|escape}" {if $sortorder == $item} selected="selected"{/if}>{$key}</option>
						{/foreach}
					</select>
					<br>
					<input type="radio" id="sortdirection2" name="sortdirection" value="asc" {if $sortdirection == 'asc'}checked="checked"{/if}>
						<label for="sortdirection2">{tr}Ascending{/tr}</label>
						<br>
						<input type="radio" id="sortdirection1" name="sortdirection" value="desc" {if $sortdirection == 'desc'}checked="checked"{/if}>
						<label for="sortdirection1">{tr}Descending{/tr}</label>
				</div>
            </div>
            <div class="form-group">
				<label for="max_desc" class="col-sm-4 text-right">{tr}Max description display size{/tr}</label>
                <div class="col-sm-8">
				    <input type="text" id="max_desc" name="max_desc" value="{$max_desc|escape}">
				</div>
            </div>
            <div class="form-group">
			    <label for="maxRows" class="col-sm-4 text-right">{tr}Max rows per page{/tr}</label>
                <div class="col-sm-8">
                    <input type="text" id="maxRows" name="maxRows" value="{$maxRows|escape}">
                </div>
		    </div>
            <div class="form-group">
                <label for="">{tr}Select which items to display when listing galleries{/tr}</label>
				{include file='fgal_listing_conf.tpl'}
			</div>
				{/tab}
			{/tabset}
            <div class="form-group">
                <div class="pull-right">
    			    <input type="submit" class="btn btn-primary" value="{tr}Save{/tr}" name="edit">
                </div>
            </div>
		</form>
{/if}
