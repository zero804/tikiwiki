{title help="Cookie"}{tr}Admin cookies{/tr}{/title}

{remarksbox type="tip" title="{tr}Tip{/tr}"}
	{tr}To use cookie in a text area (Wiki page, etc), a <a class="alert-link" href="tiki-admin_modules.php">module</a> or a template, use {literal}{cookie}{/literal}.{/tr}
{/remarksbox}

<h2>{tr}Create/edit cookies{/tr}</h2>
<form action="tiki-admin_cookies.php" method="post" class="form-horizontal" role="form">
	{ticket}
	<input type="hidden" name="cookieId" value="{$cookieId|escape}">
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="cookie">{tr}Cookie{/tr}</label>
		<div class="col-sm-9">
			<input type="text" maxlength="255" class="form-control" id="cookie" name="cookie" value="{$cookie|escape}">
		</div>
	</div>
	<div class="form-group text-center">
		<input type="submit" class="btn btn-primary" name="save" value="{tr}Save{/tr}" onclick="checkTimeout()">
	</div>
</form>

<h2>{tr}Upload Cookies from textfile{/tr}</h2>
<form enctype="multipart/form-data" action="tiki-admin_cookies.php" method="post" class="form-horizontal" role="form">
	{ticket}
	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="cookie">{tr}Upload from disk{/tr}</label>
		<div class="col-sm-9">
			<input type="hidden" name="MAX_FILE_SIZE" value="1000000000">
			<input name="userfile1" type="file" class="form-control">
		</div>
	</div>
	<div class="form-group text-center">
		<input
			type="submit"
			class="btn btn-primary btn-sm"
			name="upload"
			value="{tr}Upload{/tr}"
			onclick="checkTimeout()"
		>
	</div>
</form>

<h2>{tr}Cookies{/tr}</h2>
{if $channels}
	<div class="t_navbar">
		{button href="?removeall=1" _icon_name="trash" _text="{tr}Remove all cookies{/tr}" _onclick="confirmSimple(event, '{tr}Remove all cookies?{/tr}', '{ticket mode=get}')"}
	</div>
{/if}

{if $channels or ($find ne '')}
	{include file='find.tpl'}
{/if}
<div class="{if $js}table-responsive{/if}"> {* table-responsive class cuts off css drop-down menus *}
	<table class="table table-striped table-hover">
		<tr>
			<th>
				<a href="tiki-admin_cookies.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'cookieId_desc'}cookieId_asc{else}cookieId_desc{/if}">{tr}ID{/tr}</a>
			</th>
			<th>
				<a href="tiki-admin_cookies.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'cookie_desc'}cookie_asc{else}cookie_desc{/if}">{tr}cookie{/tr}</a>
			</th>
			<th width="15%"></th>
		</tr>
		{cycle values="odd,even" print=false advance=false}
		{section name=user loop=$channels}
			<tr>
				<td class="id">{$channels[user].cookieId}</td>
				<td class="text">{$channels[user].cookie|escape}</td>
				<td class="action">
					{actions}
						{strip}
							<action>
								<a href="tiki-admin_cookies.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;cookieId={$channels[user].cookieId}">
									{icon name='edit' _menu_text='y' _menu_icon='y' alt="{tr}Edit{/tr}"}
								</a>
							</action>
							<action>
								<a href="tiki-admin_cookies.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].cookieId}" onclick="confirmSimple(event, '{tr}Remove cookie?{/tr}', '{ticket mode=get}')">
									{icon name='remove' _menu_text='y' _menu_icon='y' alt="{tr}Remove{/tr}"}
								</a>
							</action>
						{/strip}
					{/actions}
				</td>
			</tr>
		{sectionelse}
			{norecords _colspan=3}
		{/section}
	</table>
</div>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}
