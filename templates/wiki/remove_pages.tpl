{extends 'layout_view.tpl'}
{block name="title"}
	{title}{$title|escape}{/title}
{/block}
{block name="content"}
	{include file='access/include_items.tpl'}
	{$iname = ''}
	{if $extra.version === 'last'}
		{$iname = 'all'}
		{$idesc = 'all versions'}
	{elseif $extra.version === 'all'}
		{$iname = 'last'}
		{$idesc = 'last version only'}
	{/if}
        {if isset($included_by)}
                {remarksbox type='Warning' title="{tr}Warning{/tr}"}
                        {tr}The following item(s) include page(s) being deleted and will break.{/tr}
		        {include file='tiki-edit-page-included_by.tpl'}
                {/remarksbox}
        {/if}
	<form id='confirm-action' class='confirm-action' action="{service controller="$confirmController" action="$confirmAction"}" method="post">
		{$div_checkbox_redirect_display = 'block'}
		{if !empty($iname) && !$extra.one}
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input" type="checkbox" name="{$iname}" onclick="$('#div_checkbox_redirect').toggle(); if (!this.checked) $('#div_redirect').hide(); return true;"> {tr}Remove {$idesc}{/tr}
				</label>
			</div>
			{$div_checkbox_redirect_display = 'none'}
		{/if}
		{if $extra.version === 'all'}
			{$div_checkbox_redirect_display = 'block'}
		{/if}
		{include file='access/include_hidden.tpl'}
		{if $prefs.feature_wiki_pagealias eq 'y'}
			<div class="form-check" id="div_checkbox_redirect" style="display:{$div_checkbox_redirect_display};">
					<label class="form-check-label">
						<input class="form-check-input" type='checkbox' id='create_redirect' name='create_redirect' value='y' onclick="$('#div_redirect').toggle();return true;" > {tr}Create redirect{/tr}
						<a tabindex="0" target="_blank" data-toggle="popover" data-trigger="hover" title="Create a 301 Redirect" data-content="{tr}Create a 301 Redirect ('moved permanently') to specified page. An SEO-friendly, automatic redirect from the page being deleted to the designated new page (ex.: for search engines or users that may have bookmarked the page being deleted){/tr}">
							{icon name='information'}
						</a>
					</label>
			</div>
			<div id="div_redirect" class="form-group row" style="display:none;">
				<div class="col-sm-2">
					<label for="destpage" class="col-sm-2">{tr}Redirect to:{/tr}</label>
				</div>
				<div class="col-sm-10">
					{jq}$("#destpage").tiki("autocomplete", "pagename");{/jq}
					<input type='text' id='destpage' name='destpage' class="form-control" value=''>
				</div>
			</div>
		{/if}
		{include file='access/include_submit.tpl'}
	</form>
{/block}
