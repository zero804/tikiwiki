{* $Id$ *}

{title help="Users+Management" admpage="login" url="tiki-adminusers.php"}{tr}Admin Users{/tr}{/title}

<div class="navbar">
	{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
		{button href="tiki-admingroups.php" _text="{tr}Admin Groups{/tr}"}
	{/if}
	{button _text="{tr}Admin Users{/tr}"}
	{if $tiki_p_admin eq 'y'}
	{button href="tiki-objectpermissions.php" _text="{tr}Manage permissions{/tr}"}
	{/if}
	{if isset($userinfo.userId)}
		{button href="?add=1" _text="{tr}Add a New User{/tr}"}
	{/if}
	{if $prefs.feature_invite eq 'y' and $tiki_p_invite eq 'y'}
		{button href="tiki-list_invite.php" _text="{tr}Invitation List{/tr}"}
	{/if}
</div>

{if $prefs.feature_intertiki eq 'y' and ($prefs.feature_intertiki_import_groups eq 'y' or $prefs.feature_intertiki_import_preferences eq 'y')}
	{remarksbox type="warning" title="{tr}Warning{/tr}"}
		{if $prefs.feature_intertiki_import_groups eq 'y'}{tr}Since this Tiki site is in slave mode and imports groups, the master groups will be automatically reimported at each login{/tr}{/if}
		{if $prefs.feature_intertiki_import_preferences eq 'y'}{tr}Since this Tiki site is in slave mode and imports preferences, the master user preferences will be automatically reimported at each login{/tr}{/if}
	{/remarksbox}
{/if}

{if $tikifeedback}
	{remarksbox type="feedback" title="{tr}Feedback{/tr}"}
	{section name=n loop=$tikifeedback}
	{tr}{$tikifeedback[n].mes|escape}{/tr}
	<br>
	{/section}{/remarksbox}
{/if}

{if !empty($added) or !empty($discarded) or !empty($discardlist)}
	{remarksbox type="feedback" title="{tr}Batch Upload Results{/tr}"}
		{tr}Updated users:{/tr}  {$added}
		{if $discarded != ""}- {tr}Rejected users:{/tr} {$discarded}{/if}
		<br>
		<br>
		{if $discardlist != ''}
			<table class="table normal">
				<tr>
					<th>{tr}Username{/tr}</th>
					<th>{tr}Reason{/tr}</th>
				</tr>
				{section name=reject loop=$discardlist}
					<tr class="odd">
						<td class="username">{$discardlist[reject].login}</td>
						<td class="text">{$discardlist[reject].reason}</td>
					</tr>
				{/section}
			</table>
		{/if}

		{if $errors}
			<br>
			{section name=ix loop=$errors}
				{$errors[ix]}<br>
			{/section}
		{/if}
	{/remarksbox}
{/if}

{tabset name='tabs_adminuers'}

{* ---------------------- tab with list -------------------- *}
{tab name="{tr}Users{/tr}"}
	<h2>{tr}Users{/tr}</h2>
	{if !$tsOn}
		<form method="get" action="tiki-adminusers.php">
			<table class="findtable">
				<tr>
					<td><label for="find">{tr}Find{/tr}</label></td>
					<td><input type="text" id="find" name="find" value="{$find|escape}"></td>
					<td><input type="submit" class="btn btn-default" value="{tr}Find{/tr}" name="search"></td>
					<td><label for="numrows">{tr}Number of displayed rows{/tr}</label></td>
					<td><input type="text" size="4" id="numrows" name="numrows" value="{$numrows|escape}"></td>
				</tr>
				<tr>
					<td colspan="2"></td>
					<td colspan="3">
						<a href="javascript:toggleBlock('search')" class="link">{icon _id='add' alt="{tr}more{/tr}"}&nbsp;{tr}More Criteria{/tr}</a>
					</td>
				</tr>
			</table>
			{autocomplete element='#find' type='username'}

			<div id="search" {if $filterGroup or $filterEmail}style="display:block;"{else}style="display:none;"{/if}>
				<table class="findtable">
					<tr>
						<td><label for="filterGroup">{tr}Group (direct){/tr}</label></td>
						<td>
							<select name="filterGroup" id="filterGroup">
								<option value=""></option>
								{section name=ix loop=$all_groups}
									{if $all_groups[ix] != 'Registered' && $all_groups[ix] != 'Anonymous'}
										<option value="{$all_groups[ix]|escape}" {if $filterGroup eq $all_groups[ix]}selected{/if}>{$all_groups[ix]|escape}</option>
									{/if}
								{/section}
							</select>
						</td>
						<td><label for="filterEmailNotConfirmed">{tr}Email not confirmed{/tr}</label></td>
						<td><input id="filterEmailNotConfirmed" name="filterEmailNotConfirmed" type="checkbox"{if !empty($smarty.request.filterEmailNotConfirmed)} checked="checked"{/if}></td>
						<td><label for="filterNeverLoggedIn">{tr}Never logged in{/tr}</label></td>
						<td><input id="filterNeverLoggedIn" name="filterNeverLoggedIn" type="checkbox"{if !empty($smarty.request.filterNeverLoggedIn)} checked="checked"{/if}></td>
					</tr>
					<tr>
						<td><label for="filterEmail">{tr}Email{/tr}</label></td>
						<td><input type="text" id="filterEmail" name="filterEmail" value="{$filterEmail}"></td>
						<td><label for="filterNotValidated">{tr}User not validated{/tr}</label></td>
						<td><input id="filterNotValidated" name="filterNotValidated" type="checkbox"{if !empty($smarty.request.filterNotValidated)} checked="checked"{/if}></td>
						<td></td>
						<td></td>
					</tr>
				</table>

				<input type="hidden" name="sort_mode" value="{$sort_mode|escape}">
			</div>
		</form>
	{/if}
	{if ($cant > $numrows or !empty($initial)) && !$tsOn}
		{initials_filter_links}
	{/if}

	<form name="checkform" method="post" action="{$smarty.server.PHP_SELF}">
		<div id="usertable" {if $tsOn}style="visibility: hidden"{/if}>
			<table id="usertable_table" class="table normal">
				{* Note: for any changes in the logic determining which columns are shown, corresponding changes will
				need to be made in the getTableSettings function at /lib/core/Table/Settings/Adminusers.php *}
				<thead>
					<tr>
						<th {if $prefs.mobile_mode eq "y"}style="width:40px;"{else}class="auto"{/if}>
							{if $users}
							   {select_all checkbox_names='checked[]'}
							{/if}
						</th>
						<th>{self_link _sort_arg='sort_mode' _sort_field='login'}{tr}User{/tr}{/self_link}</th>
						{if $prefs.login_is_email neq 'y'}
							<th>{self_link _sort_arg='sort_mode' _sort_field='email'}{tr}Email{/tr}{/self_link}</th>
						{/if}
						{if $prefs.auth_method eq 'openid'}
							<th>{self_link _sort_arg='sort_mode' _sort_field='openID'}{tr}OpenID{/tr}{/self_link}</th>
						{/if}
						<th>{self_link _sort_arg='sort_mode' _sort_field='currentLogin'}{tr}Last login{/tr}{/self_link}</th>
						<th>{self_link _sort_arg='sort_mode' _sort_field='created'}{tr}Registered{/tr}{/self_link}</th>
						<th>{tr}Groups{/tr}</th>
						<th>{tr}Actions{/tr}</th>
					</tr>
				</thead>
				<tbody>
				{cycle print=false values="even,odd"}
				{section name=user loop=$users}
					{if $users[user].editable}
						{capture assign=username}{$users[user].user|escape}{/capture}
						<tr class="{cycle}">
							<td class="checkbox">
								{if $users[user].user ne 'admin'}
									<input type="checkbox" name="checked[]" value="{$users[user].user|escape}" {if isset($users[user].checked) && $users[user].checked eq 'y'}checked="checked" {/if}>
								{/if}
							</td>

							<td class="username">
								{capture name=username}{$users[user].user|username}{/capture}
								<a class="link" href="tiki-adminusers.php?offset={$offset}&amp;numrows={$numrows}&amp;sort_mode={$sort_mode}&amp;user={$users[user].userId}{if $prefs.feature_tabs ne 'y'}#2{/if}" title="{tr}Edit Account Settings:{/tr} {$smarty.capture.username}">
								   {$users[user].user|escape}
								</a>
								{if $prefs.user_show_realnames eq 'y' and $smarty.capture.username ne $users[user].user}
									<div class="subcomment">
										{$smarty.capture.username|escape}
									</div>
								{/if}
							</td>

							{if $prefs.login_is_email ne 'y'}
								<td class="email">{$users[user].email}</td>
							{/if}
							{if $prefs.auth_method eq 'openid'}
								<td class="text">{$users[user].openid_url|default:"{tr}N{/tr}"}</td>
							{/if}
							<td class="text">
								{if $users[user].currentLogin eq ''}
									{capture name=when}{$users[user].age|duration_short}{/capture}
									{tr}Never{/tr} <em>({tr _0=$smarty.capture.when}Registered %0 ago{/tr})</em>
								{else}
									{$users[user].currentLogin|tiki_short_datetime}
								{/if}

								{if $users[user].waiting eq 'u'}
									<br>
									{tr}Need to validate email{/tr}
								{/if}
							</td>
							<td class="text">
								{$users[user].registrationDate|tiki_short_datetime}
							</td>

							<td class="text">
								{foreach from=$users[user].groups key=grs item=what name=gr}
									<div style="white-space:nowrap">
										{if $grs != "Anonymous" and ($tiki_p_admin eq 'y' || in_array($grs, $all_groups))}
											{if $what eq 'included'}<i>{/if}
											{if $tiki_p_admin eq 'y'}
												<a class="link" {if isset($link_style)}{$link_style}{/if} href="tiki-admingroups.php?group={$grs|escape:"url"}" title={if $what eq 'included'}"{tr}Edit Included Group{/tr}"{else}"{tr}Edit Group:{/tr} {$grs|escape}"{/if}>
											{/if}
											{$grs|escape}
											{if $tiki_p_admin eq 'y'}
												</a>
											{/if}
											{if $what eq 'included'}</i>{/if}
											{if $grs eq $users[user].default_group}<small>({tr}default{/tr})</small>{/if}
											{if $what ne 'included' and $grs != "Registered"}
												{capture assign=grse}{$grs|escape}{/capture}
												{capture assign=title}{tr _0=$username _1=$grse}Remove %0 from %1{/tr}{/capture}{*FIXME*}
												{self_link _class='link' user=$users[user].user action='removegroup' group=$grs _icon='cross' _title=$title}{/self_link}
											{else}
												{icon _id='bullet_white'}
											{/if}
											{if !$smarty.foreach.gr.last}<br>{/if}
										{/if}
									</div>
								{/foreach}
							</td>

							<td class="action">
								{if $prefs.mobile_mode eq "y"}<div class="actions" data-role="controlgroup" data-type="horizontal">{/if} {* mobile *}
								<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-assignuser.php?assign_user={$users[user].user|escape:url}" title="{tr}Assign to group{/tr}">{capture assign=alt}{tr _0=$username}Assign %0 to groups{/tr}{/capture}{*FIXME*}{icon _id='group_key' alt=$alt}</a> {* mobile *}
								
								<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-user_preferences.php?userId={$users[user].userId}" title="{tr _0=$username}Change user preferences: %0{/tr}">{capture assign=alt}{tr _0=$username}Change user preferences: %0{/tr}{/capture}{icon _id='wrench' alt=$alt}</a> {* mobile *}
								
								<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="{query _type='relative' user=$users[user].userId}" title="{tr _0=$username}Edit Account Settings: %0{/tr}">{capture assign=alt}{tr _0=$username}Edit Account Settings: %0{/tr}{/capture}{*FIXME*}{icon _id='page_edit' alt=$alt}</a> {* mobile *}
								
								{if $prefs.feature_userPreferences eq 'y' || $user eq 'admin'}
									<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-user_preferences.php?userId={$users[user].userId}" title="{tr _0=$username}Change user preferences: %0{/tr}">{capture assign=alt}{tr _0=$username}Change user preferences: %0{/tr}{/capture}{icon _id='wrench' alt=$alt}</a> {* mobile *}
								{/if}
								{if $users[user].user eq $user or $users[user].user_information neq 'private' or $tiki_p_admin eq 'y'}
									{capture assign=title}{tr _0=$username}User Information: %0{/tr}{/capture}{*FIXME*}
									<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-user_information.php?userId={$users[user].userId}" title="{$title}"{if $users[user].user_information eq 'private'} style="opacity:0.5;"{/if}>{icon _id='help' alt=$title}</a> {* mobile *}
								{/if}

								{if $users[user].user ne 'admin'}
									<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="{$smarty.server.PHP_SELF}?{query action=delete user=$users[user].user}" title="{tr}Delete{/tr}">{icon _id='cross' alt="{tr}Delete{/tr}"}</a> {* mobile *}
									{if $users[user].waiting eq 'a'}
										<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-login_validate.php?user={$users[user].user|escape:url}&amp;pass={$users[user].valid|escape:url}" title="{tr _0=$users[user].user|username}Validate user: %0{/tr}">{capture assign=alt}{tr _0=$users[user].user|username}Validate user: %0{/tr}{/capture}{*FIXME*}{icon _id='accept' alt=$alt}</a> {* mobile *}
									{/if}
									{if $users[user].waiting eq 'u'}
										<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-confirm_user_email.php?user={$users[user].user|escape:url}&amp;pass={$users[user].provpass|md5|escape:url}" title="{tr _0=$users[user].user|username}Confirm user email: %0{/tr}">{capture assign=alt}{tr _0=$username}Confirm user email: %0{/tr}{/capture}{*FIXME*}{icon _id='email_go' alt=$alt}</a> {* mobile *}
									{/if}
									{if $prefs.email_due > 0 and $users[user].waiting ne 'u' and $users[user].waiting ne 'a'}
										<a class="link" {if $prefs.mobile_mode eq "y"}data-role="button" data-inline="true" {/if}href="tiki-adminusers.php?user={$users[user].user|escape:url}&amp;action=email_due" title="{tr}Invalidate email{/tr}">{icon _id='email_cross' alt="{tr}Invalidate email{/tr}"}</a> {* mobile *}
									{/if}
								{/if}
								{if !empty($users[user].openid_url)}
									{self_link userId=$users[user].userId action='remove_openid' _title="{tr}Remove link with OpenID account{/tr}" _icon="img/icons/openid_remove"}{/self_link}
								{/if}
								{if $prefs.mobile_mode eq "y"}</div>{/if} {* mobile *}
							</td>
						</tr>
					{/if}
				{sectionelse}
					{norecords _colspan=8}
				{/section}
				</tbody>
			</table>
			<table>
				<tr>
					<td colspan="18">
						{if $users}
							<p align="left"> {*on the left to have it close to the checkboxes*}
								<div id="submit_mult">
									<label>{tr}Perform action with checked:{/tr}
										<select class="submit_mult" name="submit_mult">
											<option value="" selected="selected">-</option>
											<option value="remove_users" >{tr}Remove{/tr}</option>
											{if $prefs.feature_wiki_userpage == 'y'}
												<option value="remove_users_with_page">{tr}Remove users and their userpages{/tr}</option>
											{/if}
											<option value="assign_groups" >{tr}Change group assignments{/tr}</option>
											<option value="set_default_groups">{tr}Set default groups{/tr}</option>
											{if $prefs.feature_wiki == 'y'}
												<option value="emailChecked">{tr}Send wiki page content by email{/tr}</option>
											{/if}
										</select>
									</label>
									<button type="submit" style="display: none" class="btn btn-default submit_mult">{tr}OK{/tr}</button>
								</div>
							</p>
							<div id="gm" style="display:none">
								<h4>{tr}Change group assignments for selected users{/tr}</h4>
								<select class="gm" name="group_management" disabled="disabled">
									<option value="add">{tr}Assign selected to{/tr}</option>
									<option value="remove">{tr}Remove selected from{/tr}</option>
								</select></label>
								<label>{tr}the following groups:{/tr}</label>
									<br>
									<select name="checked_groups[]" multiple="multiple" size="20">
										{section name=ix loop=$all_groups}
											{if $all_groups[ix] != 'Anonymous' && $all_groups[ix] != 'Registered'}
												<option value="{$all_groups[ix]|escape}">{$all_groups[ix]|escape}</option>
											{/if}
										{/section}
									</select>
								<br>
								<button type="submit" class="btn btn-default gm" disabled="disabled">{tr}OK{/tr}</button>
								<button type="button" style="display: none" class="btn btn-default cancel-choice">{tr}Cancel{/tr}</button>
								{if $prefs.jquery_ui_chosen neq 'y'}{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}Use Ctrl+Click to select multiple options{/tr}{/remarksbox}{/if}
							</div>
							<div id="dg" style="display:none">
								<h4>{tr}Set default groups for selected users{/tr}</h4>
								<label>{tr}Set the default group of the selected users to:{/tr}
									<br>
									<select class="dg" name="checked_group" disabled="disabled" size="20">
										{section name=ix loop=$all_groups}
											{if $all_groups[ix] != 'Anonymous'}
												<option value="{$all_groups[ix]|escape}">{$all_groups[ix]|escape}</option>
											{/if}
										{/section}
									</select></label>
								<br>
								<button type="submit" disabled="disabled" class="btn btn-default btn-sm dg">{tr}OK{/tr}</button>
								<button type="button" style="display: none" class="btn btn-default cancel-choice">{tr}Cancel{/tr}</button>
								<input type="hidden" class="dg" disabled="disabled" name="set_default_groups" value="y">
							</div>
							<div id="emc" style="display:none">
								<h4>{tr}Send wiki page content by email to selected users{/tr}</h4>
								<table>
									<tr>
										<td><label>{tr}Wiki page to use for email content{/tr}</label></td>
										<td>
											<input class="emc" type="text" disabled="disabled" name="wikiTpl">
											<span class="tikihelp" title="{tr}Template wiki page:
												The wiki page must have a page description, which is used as the subject of the email.
												Enable the page descriptions feature at Admin Home > Wiki.{/tr}" style="">
												<img src="img/icons/information.png" alt="" width="16" height="16" class="icon">
											</span>
										</td>
									</tr>
									<tr>
										<td><label>{tr}Bcc{/tr}</label></td>
										<td>
											<input class="emc" disabled="disabled" type="text" name="bcc">
											<span class="tikihelp" title="{tr}Bcc: Enter a valid email to send a blind copy to (optional).{/tr}" style="">
												<img src="img/icons/information.png" alt="" width="16" height="16" class="icon">
											</span>
										</td>
									</tr>
									<tr>
										<td colspan="2" style="display: block;margin-left:auto;margin-right: auto">
											<button type="submit" disabled="disabled" class="btn btn-default btn-sm emc">{tr}OK{/tr}</button>
											<button type="button" style="display: none" class="btn btn-default cancel-choice">{tr}Cancel{/tr}</button>
											<input class="emc" disabled="disabled" type="hidden" name="emailChecked" value="y">
										</td>
									</tr>
								</table>
							</div>
{jq}
	$('select.submit_mult').change(function() {
		if ($.inArray(this.value, ['assign_groups', 'set_default_groups', 'emailChecked']) > -1) {
			$('div#submit_mult').hide();
			$('.submit_mult').prop('disabled', true).trigger("chosen:updated");
			$('button.cancel-choice').show();
			if (this.value == 'assign_groups') {
				$('div#gm').show();
				$('.gm').prop('disabled', false).trigger("chosen:updated");
			} else if (this.value == 'set_default_groups') {
				$('div#dg').show();
				$('.dg').prop('disabled', false).trigger("chosen:updated");
			} else if (this.value == 'emailChecked') {
				$('div#emc').show();
				$('.emc').prop('disabled', false).trigger("chosen:updated");
			}
		} else if ($.inArray(this.value, ['remove_users', 'remove_users_with_page']) > -1) {
			$('button.submit_mult').show();
		}
	});

	$('button.cancel-choice').click(function() {
		$('div#gm, div#dg, div#emc').hide();
		$('.gm, dg, .emc').prop('disabled', true).trigger("chosen:updated");
		$('.submit_mult').prop('disabled', false).trigger("chosen:updated");
		$('select.submit_mult').val('').trigger("chosen:updated");
		$('div#submit_mult').show();
		$('button.cancel-choice').hide();
	});
{/jq}
						{/if}
					</td>
				</tr>
			</table>
		</div>
		<input type="hidden" name="find" value="{$find|escape}">
		<input type="hidden" name="numrows" value="{$numrows|escape}">
		<input type="hidden" name="sort_mode" value="{$sort_mode|escape}">
		<input type="hidden" {if $tsOn}id="{$ts_offsetid|escape}" {/if}name="offset" value="{$offset|escape}">
		<input type="hidden" {if $tsOn}id="{$ts_countid|escape}" {/if}name="count" value="{$cant|escape}">
	</form>
	{if !$tsOn}
		{pagination_links cant=$cant step=$numrows offset=$offset}{/pagination_links}
	{/if}
{/tab}


{* ---------------------- tab with form -------------------- *}
<a name="2" ></a>
{if isset($userinfo.userId) && $userinfo.userId}
	{capture assign=add_edit_user_tablabel}{tr}Edit user{/tr} <i>{$userinfo.login|escape}</i>{/capture}
{else}
	{assign var=add_edit_user_tablabel value="{tr}Add a New User{/tr}"}
{/if}

{tab name=$add_edit_user_tablabel}
	{if isset($userinfo.userId) && $userinfo.userId}
		<h2>{tr}Edit user:{/tr} {$userinfo.login|escape}</h2>
		{if $userinfo.login ne 'admin' and $userinfo.editable}
			{assign var=thisloginescaped value=$userinfo.login|escape:'url'}
			{button href="tiki-assignuser.php?assign_user=$thisloginescaped" _text="{tr}Assign user to Groups{/tr}"}
		{/if}
	{else}
		<h2>{tr}Add a New User{/tr}</h2>
	{/if}
	{if $userinfo.editable}
		<form action="tiki-adminusers.php" method="post" enctype="multipart/form-data" name="RegForm" autocomplete="off">
			<table class="formcolor">
				<tr>
					<td><label for='login'>
						{if $prefs.login_is_email eq 'y'}
							{tr}Email:{/tr}
						{else}
							{tr}User:{/tr}
						{/if}
						</label>
					</td>
					<td>
						{if $userinfo.login neq 'admin'}
							<input type="text" id='login' name='login' value="{$userinfo.login|escape}">
							<br> 
							{if $prefs.login_is_email eq 'y'}
								<em>{tr}Use the email as username{/tr}.</em>
							{elseif $prefs.lowercase_username eq 'y'} 
								<em>{tr}Lowercase only{/tr}</em>.
							{/if}
							<br>
							{if isset($userinfo.userId) && $userinfo.userId}
								<p>
									{icon _id='exclamation' alt="{tr}Warning{/tr}" style="vertical-align:middle"} 
									<em>{tr}Warning: changing the username could require the user to change his password (for user registered with an old Tiki&lt;=1.8){/tr}</em>
								</p>
								{if $prefs.feature_intertiki_server eq 'y'}
									<i>{tr}Warning: it will mess with slave intertiki sites that use this one as master{/tr}</i>
								{/if}
							{/if}
						{else}
							<input type="hidden" name='login' value="{$userinfo.login|escape}">{$userinfo.login}
						{/if}
					</td>
				</tr>
				{*
					No need to specify user password or to ask him to change it, if :
					--> Tiki is using the Tiki + PEAR Auth systems
					--> AND Tiki won't create the user in the Tiki auth system
					--> AND Tiki won't create the user in the ldap 
				*}
				{if $prefs.auth_method eq 'ldap' and ( $prefs.ldap_create_user_tiki eq 'n' or $prefs.ldap_skip_admin eq 'y' ) and $prefs.ldap_create_user_ldap eq 'n' and $userinfo.login neq 'admin' and $auth_ldap_permit_tiki_users eq 'n'}
					<tr>
						<td colspan="2">
							<b>{tr}No password is required{/tr}</b>
							<br>
							<i>{tr}Tiki is configured to delegate the password managment to LDAP.{/tr}</i>
						</td>
					</tr>
				{else}
					<tr>
						<td><label for="pass1">{tr}Password:{/tr}</label>{if !isset($userinfo.userId) or !$userinfo.userId}<br>({tr}required{/tr}){/if}</td>
						<td>
							<input type="password" name="pass" id="pass1" onkeyup="runPassword(this.value, 'mypassword');checkPasswordsMatch('#pass2', '#pass1', '#mypassword2_text')">
							<div style="float:right;margin-left:5px;">
								<div id="mypassword_text"></div>
								<div id="mypassword_bar" style="font-size: 5px; height: 2px; width: 0px;"></div> 
							</div>
							<br>
							{include file='password_help.tpl'}
						</td>
					</tr>
					<tr>
						<td><label for="pass2">{tr}Repeat Password:{/tr}</label>{if !isset($userinfo.userId) or !$userinfo.userId}<br>({tr}required{/tr}){/if}</td>
						<td>
							<input type="password" name="pass2" id="pass2" onkeyup="checkPasswordsMatch('#pass2', '#pass1', '#mypassword2_text')">
							<div style="float:right;margin-left:5px;">
								<div id="mypassword2_text"></div>
							</div>
						</td>
					</tr>
					{if ! ( $prefs.auth_method eq 'ldap' and ( $prefs.ldap_create_user_tiki eq 'n' or $prefs.ldap_skip_admin eq 'y' ) and $prefs.ldap_create_user_ldap eq 'n' )}
						<tr><td>&nbsp;</td><td>
							<input id='genepass' name="genepass" type="text" tabindex="0" style="display: none">
							{jq}
								$("#genPass span").click(function () {
									$('#pass1, #pass2').val('');
									$('#mypassword_text, #mypassword2_text').hide();
									$("#genepass").show();
								});
								$("#pass1, #pass2").change(function () {
									$('#mypassword_text, #mypassword2_text').show();
									document.RegForm.genepass.value='';
									$("#genepass").hide();
								});
							{/jq}
							<span id="genPass">{button href="#" _onclick="genPass('genepass');runPassword(document.RegForm.genepass.value, 'mypassword');checkPasswordsMatch('#pass2', '#pass1', '#mypassword2_text');return false;" _text="{tr}Generate a password{/tr}"}</span>
						</td></tr>
					{/if}
					{if $userinfo.login neq 'admin' && $prefs.change_password neq 'n'}
						<tr>
							<td>&nbsp;</td>
							<td>
								<label><input type="checkbox" name="pass_first_login"{if isset($userinfo.pass_confirm) && $userinfo.pass_confirm eq '0'} checked="checked"{/if}{if !empty($userinfo.login)}disabled{/if}>
								{tr}User must change password at next login{/tr}.</label>
							</td>
						</tr>
					{/if}
				{/if}
				
				{if $prefs.login_is_email neq 'y'}
					<tr>
						<td><label for="email">{tr}Email:{/tr}</label></td>
						<td>
							<input type="text" id="email" name="email" size="30" value="{$userinfo.email|escape}">
						</td>
					</tr>
				{/if}
				{if $userinfo.login neq 'admin' and ($prefs.validateUsers eq 'y' or $prefs.validateRegistration eq 'y')}
					<tr>
						<td>&nbsp;</td>
						<td>
							<label><input type="checkbox" name="need_email_validation" {if ($userinfo.login eq '' and ($prefs.validateUsers eq 'y' or $prefs.validateRegistration eq 'y')) or $userinfo.provpass neq ''}checked="checked" {/if}> 
							{tr}Send an email to the user in order to allow him to validate his account.{/tr}</label> 
							
							{if empty($prefs.sender_email)}<br><span class="highlight">{tr}You need to set <a href="tiki-admin.php?page=general">Sender Email</a>{/tr}</span>{/if}						
	
						</td>
					</tr>
				{/if}
				{if isset($userinfo.userId) && $userinfo.userId != 0}
					{if $userinfo.created neq $userinfo.registrationDate}
						<tr>
							<td>{tr}Created:{/tr}</td>
							<td>{$userinfo.created|tiki_long_datetime}</td>
						</tr>
					{/if}
					<tr>
						<td>{tr}Registered:{/tr}</td>
						<td>{if $userinfo.registrationDate}{$userinfo.registrationDate|tiki_long_datetime}{/if}</td>
					</tr>
					<tr>
						<td>{tr}Pass confirmed:{/tr}</td>
						<td>
							{if isset($userinfo.pass_confirm) && $userinfo.pass_confirm}
								{$userinfo.pass_confirm|tiki_long_datetime|default:'Never'}
							{/if}
						</td>
					</tr>
					{if $prefs.email_due > 0}
						<tr>
							<td style="white-space: nowrap;">{tr}Email confirmed:{/tr}</td>
							<td>
								{if $userinfo.email_confirm}
									({tr _0=$userinfo.daysSinceEmailConfirm}%0 days ago{/tr})
								{else}
									{tr}Never{/tr}
								{/if}
							</td>
						</tr>
					{/if}
					<tr>
						<td>{tr}Current Login:{/tr}</td>
						<td>
							{if $userinfo.currentLogin}
								{$userinfo.currentLogin|tiki_long_datetime|default:'Never'}
							{/if}
						</td>
					</tr>
					<tr>
						<td>{tr}Last Login:{/tr}</td>
						<td>
							{if $userinfo.lastLogin}
								{$userinfo.lastLogin|tiki_long_datetime|default:'Never'}
							{/if}
						</td>
					</tr>
				{/if}
				<tr>
					<td>&nbsp;</td>
					<td>
						{if isset($userinfo.userId) && $userinfo.userId}
							<input type="hidden" name="user" value="{$userinfo.userId|escape}">
							<input type="hidden" name="edituser" value="1">
							<input type="submit" class="btn btn-default" name="save" value="{tr}Save{/tr}">
						{else}
							<input type="submit" class="btn btn-default" name="newuser" value="{tr}Add{/tr}">
						{/if}
					</td>
				</tr>
			</table>
			<br>
			<br>
	
			{if $prefs.userTracker eq 'y'}
				{if $userstrackerid and $usersitemid}
					{tr}User tracker item : {$usersitemid}{/tr} 
					{button href="tiki-view_tracker_item.php?trackerId=$userstrackerid&amp;itemId=$usersitemid&amp;show=mod" _text="{tr}Edit Item{/tr}"}
				{/if}
				<br>
				<br>
			{/if}
		</form>
	{else}
		{tr}You do not have permission to edit this user{/tr}
	{/if}
{/tab}

{* ---------------------- tab with upload -------------------- *}
{tab name="{tr}Import{/tr}"}
	<h2>{tr}Batch upload (CSV file):{/tr}</h2>

	<form action="tiki-adminusers.php" method="post" enctype="multipart/form-data">
		{ticket}
		<table class="formcolor">
			<tr>
				<td>
					<label for="csvlist">
						{tr}CSV File:{/tr}
						{help url="Users+Management#Adding_new_users_in_bulk" desc="{tr}CSV file layout:{/tr} {tr}login,password,email,groups,default_group,realName<br>user1,pass1,email1,group1,group1<br>user2,pass2,email2,\"group1,group2\",group1{/tr}<br><br>{tr}Only login, password, email are mandatory.Use an empty password for automatic password generation. Use same login and email if the login use email. Groups are separated by comma. With group name with comma, double the comma.{/tr}"}
					</label>
				</td>
				<td>
					<input type="file" id="csvlist" name="csvlist">
					<br>
					<label><input type="radio" name="overwrite" value="y">&nbsp;{tr}Overwrite{/tr}</label>
					<br>
					<label><input type="radio" name="overwrite" value="n" checked="checked">&nbsp;{tr}Don't overwrite{/tr}</label>
					<br>
					<label>{tr}Overwrite groups:{/tr} <input type="checkbox" name="overwriteGroup"></label>
                    <br>
					<label>{tr}Create groups:{/tr} <input type="checkbox" name="createGroup"></label>
                    <br>
					{if $prefs.change_password neq 'n'}
                     <label>{tr}User must change password at first login:{/tr} <input type="checkbox" name="forcePasswordChange"></label>
					<br>
					{/if}
                    <label>{tr}Send an email to the user in order to allow him to validate his account.{/tr} <input type="checkbox" name="notification"></label>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" class="btn btn-default" name="batch" value="{tr}Add{/tr}">
				</td>
			</tr>
		</table>
	</form>
	{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
		{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}You can export users of a group by clicking on that group at <a href="tiki-admingroups.php">admin->groups</a>{/tr}{/remarksbox}
	{/if}
{/tab}

{/tabset}
