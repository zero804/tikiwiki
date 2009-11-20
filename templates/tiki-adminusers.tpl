{* $Id$ *}
{popup_init src="lib/overlib.js"}

{title help="Users+Management" admpage="login" url="tiki-adminusers.php"}{tr}Admin Users{/tr}{/title}

<div class="navbar">
	{if $tiki_p_admin eq 'y'} {* only full admins can manage groups, not tiki_p_admin_users *}
		{button href="tiki-admingroups.php" _text="{tr}Admin Groups{/tr}"}
	{/if}
	{button _text="{tr}Admin Users{/tr}"}
	{if $userinfo.userId}
		{button href="?add=1" _text="{tr}Add a New User{/tr}"}
	{/if}
</div>

{if $prefs.feature_intertiki eq 'y' and ($prefs.feature_intertiki_import_groups eq 'y' or $prefs.feature_intertiki_import_preferences eq 'y')}
	{remarksbox type="warning" title="{tr}Warning{/tr}"}
		{if $prefs.feature_intertiki_import_groups eq 'y'}{tr}Since this tiki site is in slave mode and import groups, the master groups will be automatically reimported at each login{/tr}{/if}
		{if $prefs.feature_intertiki_import_preferences eq 'y'}{tr}Since this tiki site is in slave mode and import preferences, the master user preferences will be automatically reimported at each login{/tr}{/if}
	{/remarksbox}
{/if}

{if $tikifeedback}
	{remarksbox type="feedback" title="{tr}Feedback{/tr}"}
	{section name=n loop=$tikifeedback}
	{tr}{$tikifeedback[n].mes|escape}{/tr}
	<br />
	{/section}{/remarksbox}
{/if}

{if $added != "" or $discarded != "" or $discardlist != ''}
	{remarksbox type="feedback" title="Batch Upload Results"}
		{tr}Updated users:{/tr} {$added}
		{if $discarded != ""}- {tr}Rejected users:{/tr} {$discarded}{/if}
		<br />
		<br />
		{if $discardlist != ''}
			<table class="normal">
				<tr>
					<th>{tr}Username{/tr}</th>
					<th>{tr}Reason{/tr}</th>
				</tr>
				{section name=reject loop=$discardlist}
					<tr>
						<td class="odd">{$discardlist[reject].login}</td>
						<td class="odd">{$discardlist[reject].reason}</td>
					</tr>
				{/section}
			</table>
		{/if}

		{if $errors}
			<br />
			{section name=ix loop=$errors}
				{$errors[ix]}<br />
			{/section}
		{/if}
	{/remarksbox}
{/if}

{tabset name='tabs_adminuers'}

{* ---------------------- tab with list -------------------- *}
{tab name="{tr}Users{/tr}"}
	<h2>{tr}Users{/tr}</h2>

	<form method="get" action="tiki-adminusers.php">
		<table class="findtable">
			<tr>
				<td><label for="find">{tr}Find{/tr}</label></td>
				<td><input type="text" id="find" name="find" value="{$find|escape}" /></td>
				<td><input type="submit" value="{tr}Find{/tr}" name="search" /></td>
				<td><label for="numrows">{tr}Number of displayed rows{/tr}</label></td>
				<td><input type="text" size="4" id="numrows" name="numrows" value="{$numrows|escape}" /></td>
			</tr>
			<tr>
				<td colspan="2"></td>
				<td colspan="3">
					<a href="javascript:toggleBlock('search')" class="link">{icon _id='add' alt='{tr}more{/tr}'}&nbsp;{tr}More Criteria{/tr}</a>
				</td>
			</tr>
		</table>
		{jq}$jq("#find").tiki("autocomplete", "username"){/jq}

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
				</tr>
				<tr>
					<td><label for="filterEmail">{tr}Email{/tr}</label></td>
					<td><input type="text" id="filterEmail" name="filterEmail" value="{$filterEmail}" /></td>
				</tr>
			</table>

			<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
		</div>
	</form>

	{if $cant > $numrows or !empty($initial)}
		{initials_filter_links}
	{/if}

	<form name="checkform" method="post" action="{$smarty.server.PHP_SELF}{if $group_management_mode ne 'y' and $set_default_groups_mode ne 'y' and $email_mode ne 'y'}#multiple{/if}">
		<table class="normal">
			<tr>
				<th class="auto">
					{if $users}
					   {select_all checkbox_names='checked[]'}
					{/if}
				</th>
				<th>{self_link _sort_arg='sort_mode' _sort_field='login'}{tr}User{/tr}{/self_link}</th>
				{if $prefs.login_is_email neq 'y'}
					<th>{self_link _sort_arg='sort_mode' _sort_field='email}{tr}Email{/tr}{/self_link}</th>
				{/if}
				<th>{self_link _sort_arg='sort_mode' _sort_field='currentLogin'}{tr}Last login{/tr}{/self_link}</th>
				<th colspan="2">{tr}Groups{/tr}</th>
				<th>{tr}Action{/tr}</th>
			</tr>
			{cycle print=false values="even,odd"}
			{section name=user loop=$users}
				{capture assign=username}{$users[user].user|escape}{/capture}
				<tr class="{cycle}">
					<td class="thin">
						{if $users[user].user ne 'admin'}
							<input type="checkbox" name="checked[]" value="{$users[user].user|escape}" {if $users[user].checked eq 'y'}checked="checked" {/if}/>
						{/if}
					</td>

					<td>
						<a class="link" href="tiki-adminusers.php?offset={$offset}&amp;numrows={$numrows}&amp;sort_mode={$sort_mode}&amp;user={$users[user].userId}{if $prefs.feature_tabs ne 'y'}#2{/if}" title="{tr}Edit Account Settings:{/tr} {$users[user].user|username}">{$users[user].user|username}</a>
					</td>

					{if $prefs.login_is_email ne 'y'}
						<td>{$users[user].email}</td>
					{/if}

					<td>
						{if $users[user].currentLogin eq ''}
							{tr}Never{/tr} <em>({tr}Registered{/tr} {$users[user].age|duration_short} {tr}ago{/tr})</em>
						{else}
							{$users[user].currentLogin|dbg|tiki_long_datetime}
						{/if}
				
						{if $users[user].waiting eq 'u'}
							<br />
							{tr}Need to validate email{/tr}
						{/if}
					</td>

					<td class="thin">
						<a class="link" href="tiki-assignuser.php?assign_user={$users[user].user|escape:url}" title="{tr}Assign to group{/tr}">{icon _id='group_key' alt="{tr}Assign{/tr} `$username` {tr}to groups{/tr} "}</a>
					</td>

					<td>
						{foreach from=$users[user].groups key=grs item=what name=gr}
							<div style="white-space:nowrap">
								{if $grs != "Anonymous" and ($tiki_p_admin eq 'y' || in_array($grs, $all_groups))}
									{if $what ne 'included' and $grs != "Registered"}
										{self_link _class='link' user=$users[user].user action='removegroup' group=$grs _icon='cross' _title="{tr}Remove{/tr} `$username` {tr}from{/tr} $grs"}{/self_link}
									{else}
										{icon _id='bullet_white'}
									{/if}
									{if $what eq 'included'}<i>{/if}
									{if $tiki_p_admin eq 'y'}
										<a class="link" {$link_style} href="tiki-admingroups.php?group={$grs|escape:"url"}" title={if $what eq 'included'}"{tr}Edit Included Group{/tr}"{else}"{tr}Edit Group:{/tr} {$grs|escape}"{/if}>
									{/if}
									{$grs|escape}
									{if $tiki_p_admin eq 'y'}
										</a>
									{/if}									
									{if $what eq 'included'}</i>{/if}
									{if $grs eq $users[user].default_group}<small>({tr}default{/tr})</small>{/if}
									{if !$smarty.foreach.gr.last}<br />{/if}
								{/if}
							</div>
						{/foreach}
					</td>

					<td>
						{if $prefs.feature_userPreferences eq 'y' || $user eq 'admin'}
							{self_link _class="link" user=`$users[user].userId` _icon="page_edit" _title="{tr}Edit Account Settings:{/tr} `$username`"}{/self_link}
							<a class="link" href="tiki-user_preferences.php?userId={$users[user].userId}" title="{tr}Change user preferences:{/tr} {$username}">{icon _id='wrench' alt="{tr}Change user preferences:{/tr} `$username`"}</a>
						{/if}

						{if $tiki_p_admin eq 'y' or $users[user].user eq $user or $users[user].user_information neq 'private'}
							<a class="link" href="tiki-user_information.php?userId={$users[user].userId}" title="{tr}User Information:{/tr} {$username}">{icon _id='help' alt="{tr}User Information:{/tr} `$username`"}</a>
						{/if}
	
						{if $users[user].user ne 'admin'}
							<a class="link" href="{$smarty.server.PHP_SELF}?{query action=delete user=$users[user].user}" title="{tr}Delete{/tr}">{icon _id='cross' alt='{tr}Delete{/tr}'}</a>
							{if $users[user].valid && $users[user].waiting eq 'a'}
								<a class="link" href="tiki-login_validate.php?user={$users[user].user|escape:url}&amp;pass={$users[user].valid|escape:url}" title="{tr}Validate user:{/tr} {$users[user].user|username}">{icon _id='accept' alt="{tr}Validate user:{/tr} `$username`"}</a>
							{/if}
							{* {if $users[user].waiting eq 'u'}
								<a class="link" href="tiki-confirm_user_email.php?user={$users[user].user|escape:url}&amp;pass={$users[user].provpass|md5|escape:url}" title="{tr}Confirm user email:{/tr} {$users[user].user|username}">{icon _id='email_go' alt="{tr}Confirm user email:{/tr} `$username`"}</a>
							{/if} *}
							{if $prefs.email_due > 0 and $users[user].waiting ne 'u' and $users[user].waiting ne 'a'}
								<a class="link" href="tiki-adminusers.php?user={$users[user].user|escape:url}&amp;action=email_due" title="{tr}Invalid email{/tr}">{icon _id='email_cross' alt="{tr}Invalid email{/tr}"}</a>
							{/if}
						{/if}
					</td>
				</tr>
			{sectionelse}
				<tr class="odd">
					<td colspan="8">{tr}No records found.{/tr}</td>
				</tr>
			{/section}
		
			<tr>
				<td class="form" colspan="18">
					<a name="multiple"></a>
					{if $users}
						<p align="left"> {*on the left to have it close to the checkboxes*}
							{if $group_management_mode neq 'y' && $set_default_groups_mode neq 'y' && $email_mode neq 'y'}
								<label>{tr}Perform action with checked:{/tr}
								<select name="submit_mult">
									<option value="" selected="selected">-</option>
									<option value="remove_users" >{tr}Remove{/tr}</option>
									{if $prefs.feature_wiki_userpage == 'y'}
										<option value="remove_users_with_page">{tr}Remove Users and their Userpages{/tr}</option>
									{/if}
									<option value="assign_groups" >{tr}Manage Group Assignments{/tr}</option>
									<option value="set_default_groups">{tr}Set Default Groups{/tr}</option>
									{if $prefs.feature_wiki == 'y'}
										<option value="emailChecked">{tr}Send a wiki page by Email{/tr}</option>
									{/if}
								</select>
								</label>
								<input type="submit" value="{tr}OK{/tr}" />
							{elseif $group_management_mode eq 'y'}
								<select name="group_management">
									<option value="add">{tr}Assign selected to{/tr}</option>
									<option value="remove">{tr}Remove selected from{/tr}</option>
								</select></label>
								<label>{tr}the following groups:{/tr}
								<br />
								<select name="checked_groups[]" multiple="multiple" size="20">
									{section name=ix loop=$all_groups}
										{if $all_groups[ix] != 'Anonymous' && $all_groups[ix] != 'Registered'}
										<option value="{$all_groups[ix]|escape}">{$all_groups[ix]|escape}</option>
										{/if}
									{/section}
								</select></label>
								<br />
								<input type="submit" value="{tr}OK{/tr}" />
								<div class="simplebox">{tr}Tip: Hold down CTRL to select multiple{/tr}</div>
							{elseif $set_default_groups_mode eq 'y'}
								<label>{tr}Set the default group of the selected users to:{/tr}
								<br />
								<select name="checked_group" size="20">
									{section name=ix loop=$all_groups}
										{if $all_groups[ix] != 'Anonymous'}
										<option value="{$all_groups[ix]|escape}" />{$all_groups[ix]|escape}</option>
										{/if}
									{/section}
								</select></label>
								<br />
								<input type="submit" value="{tr}OK{/tr}" />
								<input type="hidden" name="set_default_groups" value="{$set_default_groups_mode}" />
							{elseif $email_mode eq 'y'}
								<label>{tr}Template wiki page{/tr} 
								<input type="text" name="wikiTpl" /></label>
								<br />
								<label>{tr}bcc{/tr} 
								<input type="text" name="bcc" /></label>
								<input type="submit" value="{tr}OK{/tr}" />
								<input type="hidden" name="emailChecked" value="{$email_mode}" />
							{/if}
						</p>
					{/if}
				</td>
			</tr>
		</table>

		<input type="hidden" name="find" value="{$find|escape}" />
		<input type="hidden" name="numrows" value="{$numrows|escape}" />
		<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
		<input type="hidden" name="offset" value="{$offset|escape}" />
	</form>

	{pagination_links cant=$cant step=$numrows offset=$offset}{/pagination_links}
{/tab}


{* ---------------------- tab with form -------------------- *}
<a name="2" ></a>
{if $userinfo.userId}
	{capture assign=add_edit_user_tablabel}{tr}Edit user{/tr} <i>{$userinfo.login|escape}</i>{/capture}
{else}
	{assign var=add_edit_user_tablabel value='{tr}Add a New User{/tr}'}
{/if}

{tab name=$add_edit_user_tablabel}
	{if $userinfo.userId}
		<h2>{tr}Edit user:{/tr} {$userinfo.login|escape}</h2>
		{if $userinfo.login ne 'admin'}
			{assign var=thisloginescaped value=$userinfo.login|escape:'url'}
			{button href="tiki-assignuser.php?assign_user=$thisloginescaped" _text="{tr}Assign user to Groups{/tr}"}
		{/if}
	{else}
		<h2>{tr}Add a New User{/tr}</h2>
	{/if}
	<form action="tiki-adminusers.php" method="post" enctype="multipart/form-data" name="RegForm" autocomplete="off">
		<table class="normal">
			<tr class="formcolor">
				<td><label for="name">
					{if $prefs.login_is_email eq 'y'}
						{tr}Email:{/tr}
					{else}
						{tr}User:{/tr}
					{/if}
					</label>
				</td>
				<td>
					{if $userinfo.login neq 'admin'}
						<input type="text" id="name" name="name" value="{$userinfo.login|escape}" />
						<br /> 
						{if $prefs.login_is_email eq 'y'}
							<em>{tr}Use the email as username{/tr}.</em>
						{elseif $prefs.lowercase_username eq 'y'} 
							<em>{tr}Lowercase only{/tr}</em>.
						{/if}
						<br />
						{if $userinfo.userId}
							<p>
								{icon _id=exclamation alt="{tr}Warning{/tr}" style="vertical-align:middle"} 
								<em>{tr}Warning: changing the username could require the user to change his password (for user registered with an old tikiwiki&lt;=1.8){/tr}</em>
							</p>
							{if $prefs.feature_intertiki_server eq 'y'}
								<i>{tr}Warning: it will mess with slave intertiki sites that use this one as master{/tr}</i>
							{/if}
						{/if}
					{else}
						<input type="hidden" name="name" value="{$userinfo.login|escape}" />{$userinfo.login}
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
				<tr class="formcolor">
					<td colspan="2">
						<b>{tr}No password is required{/tr}</b>
						<br />
						<i>{tr}Tikiwiki is configured to delegate the password managment to LDAP.{/tr}</i>
					</td>
				</tr>
			{elseif empty($userinfo) || $tiki_p_admin_users eq 'y' || $userinfo.login eq $user}
				<tr class="formcolor">
					<td><label for="pass1">{tr}Password:{/tr}</label></td>
					<td>
						<input type="password" name="pass" id="pass1" onkeyup="runPassword(this.value, 'mypassword');" />
						<div style="float:right;width:150px;margin-left:5px;">
							<div id="mypassword_text"></div>
							<div id="mypassword_bar" style="font-size: 5px; height: 2px; width: 0px;"></div> 
						</div>
						<br />
						{if $prefs.min_pass_length > 1}
							<em>{tr}Minimum {$prefs.min_pass_length} characters long{/tr}</em>. 
						{/if}
						{if $prefs.pass_chr_num eq 'y'}
							<em>{tr}Password must contain both letters and numbers{/tr}</em>.
						{/if}
						{if ! ( $prefs.auth_method eq 'ldap' and ( $prefs.ldap_create_user_tiki eq 'n' or $prefs.ldap_skip_admin eq 'y' ) and $prefs.ldap_create_user_ldap eq 'n' ) }
							<p>
								<div>
									{button href="#" _onclick="genPass('genepass','pass1','pass2');runPassword(document.RegForm.genpass.value, 'mypassword');" _text="{tr}Generate a password{/tr}"}
									<input id='genepass' name="genpass" type="text" />
								</div>
							</p>
						{/if}
					</td>
				</tr>
				<tr class="formcolor">
					<td><label for="pass2">{tr}Repeat Password:{/tr}</label></td>
					<td><input type="password" name="pass2" id="pass2" /></td>
				</tr>
				{if $userinfo.login neq 'admin' and empty($userinfo.userId)}
					<tr class="formcolor">
						<td>&nbsp;</td>
						<td>
							<label><input type="checkbox" name="pass_first_login"{if $userinfo.pass_confirm eq '0'} checked="checked"{/if} /> 
							{tr}User must change password at first login{/tr}.</label>
						</td>
					</tr>
				{/if}
			{/if}
			
			{if $prefs.login_is_email neq 'y'}
				<tr class="formcolor">
					<td><label for="email">{tr}Email:{/tr}</label></td>
					<td>
						<input type="text" id="email" name="email" size="30" value="{$userinfo.email|escape}" />
					</td>
				</tr>
			{/if}
			{if $userinfo.login neq 'admin'}
				<tr class="formcolor">
					<td>&nbsp;</td>
					<td>
						<label><input type="checkbox" name="need_email_validation" {if ($userinfo.login eq '' and ($prefs.validateUsers eq 'y' or $prefs.validateRegistration eq 'y')) or $userinfo.provpass neq ''}checked="checked" {/if}/> 
						{tr}Send an email to the user in order to allow him to validate his account.{/tr}</label> 
						
						{if empty($prefs.sender_email)}<br /><span class="highlight">{tr}You need to set <a href="tiki-admin.php?page=general">Sender Email</a>{/tr}</span>{/if}						

					</td>
				</tr>
			{/if}
			{if $userinfo.userId != 0}
				<tr class="formcolor">
					<td>{tr}Created:{/tr}</td>
					<td>{$userinfo.created|tiki_long_datetime}</td>
				</tr>
				{if $userinfo.login neq 'admin'}
					<tr class="formcolor">
						<td>{tr}Registered:{/tr}</td>
						<td>{if $userinfo.registrationDate}{$userinfo.registrationDate|tiki_long_datetime}{/if}</td>
					</tr>
				{/if}
				<tr class="formcolor">
					<td>{tr}Last Login:{/tr}</td>
					<td>
						{if $userinfo.lastLogin}
							{$userinfo.lastLogin|tiki_long_datetime|default:'Never'}
						{/if}
					</td>
				</tr>
			{/if}
			<tr class="formcolor">
				<td>&nbsp;</td>
				<td>
					{if $userinfo.userId}
						<input type="hidden" name="user" value="{$userinfo.userId|escape}" />
						<input type="hidden" name="edituser" value="1" />
						<input type="submit" name="submit" value="{tr}Save{/tr}" />
					{else}
						<input type="submit" name="newuser" value="{tr}Add{/tr}" />
					{/if}
				</td>
			</tr>
		</table>
		<br />
		<br />

		{if $prefs.userTracker eq 'y'}
			{if $userstrackerid and $usersitemid}
				{tr}User tracker item : {$usersitemid}{/tr} 
				{button href="tiki-view_tracker_item.php?trackerId=$userstrackerid&amp;itemId=$usersitemid&amp;show=mod" _text="{tr}Edit Item{/tr}"}
			{/if}
			<br />
			<br />
		{/if}
	</form>
{/tab}

{* ---------------------- tab with upload -------------------- *}
{tab name="{tr}Import/Export{/tr}"}
	<h2>{tr}Batch upload (CSV file):{/tr}</h2>

	<form action="tiki-adminusers.php" method="post" enctype="multipart/form-data">
		<table class="normal">
			<tr class="formcolor">
				<td>
					<label for="csvlist">{tr}CSV File:{/tr} </label>
					<a {popup text='login,password,email,groups&lt;br /&gt;user1,password1,email1,&quot;group1,group2&quot;&lt;br /&gt;user2, password2,email2'}>{icon _id='help'}</a>
				</td>
				<td>
					<input type="file" id="csvlist" name="csvlist"/>
					<br />
					<label><input type="radio" name="overwrite" value="y" checked="checked" />&nbsp;{tr}Overwrite{/tr}</label>
					<br />
					<label><input type="radio" name="overwrite" value="c"/>&nbsp;{tr}Overwrite but keep the previous login if the login exists in another case{/tr}</label>
					<br />
					<label><input type="radio" name="overwrite" value="n" />&nbsp;{tr}Don't overwrite{/tr}</label>
					<br />
					<label>{tr}Overwrite groups:{/tr} <input type="checkbox" name="overwriteGroup" /></label>
                    <br />
                    <label>{tr}User must change password at first login:{/tr} <input type="checkbox" name="forcePasswordChange" /></label>
				</td>
			</tr>
			<tr class="formcolor">
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="batch" value="{tr}Add{/tr}" />
				</td>
			</tr>
		</table>
	</form>
	{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}You can export users of a group in <a href="tiki-admingroups.php">admin->groups->a_group</a>{/tr}{/remarksbox}
{/tab}

{/tabset}
