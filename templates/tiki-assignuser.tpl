{* $Id$ *}

{assign var=escuser value=$assign_user|escape:url}
{title}{tr _0=$assign_user}Assign User %0 to Groups{/tr}{/title}

<div class="t_navbar btn-group form-group row">
	{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
		{button href="tiki-admingroups.php" class="btn btn-primary" _text="{tr}Admin groups{/tr}"}
	{/if}
	{if $tiki_p_admin eq 'y' or $tiki_p_admin_users eq 'y'}
		{button href="tiki-adminusers.php" class="btn btn-primary" _text="{tr}Admin users{/tr}"}
	{/if}

	{button href="tiki-user_preferences.php?view_user=$assign_user" class="btn btn-primary" _text="{tr}User Preferences{/tr}"}
	{button href="tiki-user_information.php?view_user=$assign_user" class="btn btn-primary" _text="{tr}User Information{/tr}"}

</div>

{if $prefs.feature_intertiki eq 'y' and $prefs.feature_intertiki_import_groups eq 'y'}
	{remarksbox type="warning" title="{tr}Warning{/tr}"}
		{tr}Since this Tiki site is in slave mode and imports groups, the master groups will be automatically reimported at each login{/tr}
	{/remarksbox}
{/if}

<h2>{tr}User Information{/tr}</h2>
	<form class="form-horizontal">
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">{tr}Login{/tr}</label>
			<div class="col-sm-7">
				{$user_info.login|escape}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">{tr}Email{/tr}</label>
			<div class="col-sm-7">
				{$user_info.email}
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">{tr}Groups{/tr}</label>
			<div class="col-sm-7">
				{foreach from=$user_info.groups item=what key=grp name=groups}
					{if $what eq 'included'}<i>{/if}{$grp|escape}{if $what eq 'included'}</i>{/if}
					{if $grp != "Anonymous" && $grp != "Registered" and $what neq 'included'}
						<a href="{bootstrap_modal controller=user action=manage_groups checked=$user_info.login|escape groupremove=$grp|escape}">
							{icon name='remove' style="vertical-align:middle"}
						</a>
					{/if}{if !$smarty.foreach.groups.last},{/if}&nbsp;&nbsp;
				{/foreach}
			</div>
		</div>
	</form>
	<form method="post" action="tiki-assignuser.php{if $assign_user}?assign_user={$assign_user|escape:'url'}{/if}" class="form-horizontal">
		<div class="form-group row">
			<label class="col-sm-3 col-form-label">{tr}Default Group{/tr}</label>
			<div class="col-sm-6">
				<select name="defaultgroup" class="form-control">
					<option value=""></option>
					{foreach from=$user_info.groups key=name item=included}
						<option value="{$name|escape}" {if $name eq $user_info.default_group}selected="selected"{/if}>{$name|escape}</option>
					{/foreach}
				</select>
				<input type="hidden" value="{$user_info.login|escape}" name="login">
				<input type="hidden" value="{$prefs.maxRecords}" name="maxRecords">
				<input type="hidden" value="{$offset}" name="offset">
				<input type="hidden" value="{$sort_mode}" name="sort_mode">
			</div>
			<div class="col-sm-1">
				<input type="submit" class="btn btn-primary" value="{tr}Set{/tr}" name="set_default">
			</div>
		</div>
	</form>
<br>
<div align="left"><h2>{tr _0=$assign_user|escape}Assign User %0 to Groups{/tr}</h2></div>

{include file='find.tpl' find_show_num_rows='y'}

<form method="post" action="tiki-assignuser.php{if $assign_user}?assign_user={$assign_user|escape:'url'}{/if}">
	<div class="{if $js}table-responsive{/if}"> {*the table-responsive class cuts off dropdown menus *}
		<table class="table table-striped table-hover">
			<tr>
				<th><a href="tiki-assignuser.php?{if $assign_user}assign_user={$assign_user|escape:url}&amp;{/if}offset={$offset}&amp;maxRecords={$prefs.maxRecords}&amp;sort_mode={if $sort_mode eq 'groupName_desc'}groupName_asc{else}groupName_desc{/if}">{tr}Name{/tr}</a></th>
				<th><a href="tiki-assignuser.php?{if $assign_user}assign_user={$assign_user|escape:url}&amp;{/if}offset={$offset}&amp;maxRecords={$prefs.maxRecords}&amp;sort_mode={if $sort_mode eq 'groupDesc_desc'}groupDesc_asc{else}groupDesc_desc{/if}">{tr}Description{/tr}</a></th>
				<th>{tr}Expiration{/tr}</th>
				<th></th>
			</tr>

			{section name=user loop=$users}
				{if $users[user].groupName != 'Anonymous'}
					<tr>
						<td class="text">
							{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
								<a class="link" href="tiki-admingroups.php?group={$users[user].groupName|escape:"url"}{if $prefs.feature_tabs ne 'y'}#2{/if}" title="{tr}Edit{/tr}">
							{/if}
							{$users[user].groupName|escape}
							{if $tiki_p_admin eq 'y'}
								</a>
							{/if}
						</td>
						<td class="text">{tr}{$users[user].groupDesc|escape}{/tr}</td>
						<td>{if isset($dates[$users[user].groupName]) && !empty($dates[$users[user].groupName]['expire'])}
							<input type="text" name="new_{$users[user].id}" value="{$dates[$users[user].groupName]['expire']|tiki_short_datetime:'':'n'|escape}" />
							<input type="hidden" name="old_{$users[user].id}" value="{$dates[$users[user].groupName]['expire']|tiki_short_datetime:'':'n'|escape}" />

						{/if}</td>
						<td class="action">
							{actions}
								{strip}
									{if $users[user].what ne 'real'}
										<action>
											<a href="{bootstrap_modal controller=group action=add_user group=$users[user].groupName|escape user=$assign_user|escape}">
												{icon name='add' _menu_text='y' _menu_icon='y' alt="{tr}Assign{/tr}"}
											</a>
										</action>
									{elseif $users[user].groupName ne "Registered"}
										<action>
											<a href="{bootstrap_modal controller=user action=manage_groups checked=$user_info.login groupremove=$users[user].groupName|escape}">
												{icon name='remove' _menu_text='y' _menu_icon='y' alt="{tr}Unassign{/tr}"}
											</a>
										</action>
									{/if}
								{/strip}
							{/actions}
						</td>
					</tr>
				{/if}
			{/section}
		</table>
	</div>
	<input type="submit" class="btn btn-primary" name="save" value="{tr}Save{/tr}" />
</form>
{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}
