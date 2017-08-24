{* $Id$ *}
{jq}
	$("#genPass").click(function () {
		var passcodeId = $("input[name=registerPasscode]").attr('id');
		genPass(passcodeId);
		return false
	});
{/jq}
<form action="tiki-admin.php?page=login" class="admin form-horizontal" method="post" name="LogForm" enctype="multipart/form-data">
	{include file='access/include_ticket.tpl'}
	<div class="t_navbar margin-bottom-md">
		{button href="tiki-admingroups.php" _type="text" _class="btn btn-link tips" _icon_name="group" _text="{tr}Groups{/tr}" _title=":{tr}Group Administration{/tr}"}
		{button href="tiki-adminusers.php" _type="text" _class="btn btn-link tips" _icon_name="user" _text="{tr}Users{/tr}" _title=":{tr}User Administration{/tr}"}
		{permission_link mode=text label="{tr}Permissions{/tr}"}
		{include file='admin/include_apply_top.tpl'}
	</div>
	{tabset name="admin_login"}
		{tab name="{tr}General Preferences{/tr}"}
			<br>
			{preference name=auth_method}
			{preference name=feature_intertiki}
			<fieldset>
				<legend>{tr}Registration{/tr} &amp; {tr}Log in{/tr}</legend>
				{preference name=allowRegister}
				<div class="adminoptionboxchild" id="allowRegister_childcontainer">
					{preference name=validateUsers}
					{preference name=validateEmail}
					{preference name=validateRegistration}
					<div class="adminoptionboxchild" id="validateRegistration_childcontainer">
						{preference name=validator_emails size="80"}
					</div>
					{preference name=useRegisterPasscode}
					<div class="adminoptionboxchild" id="useRegisterPasscode_childcontainer">
						{preference name=registerPasscode}
						<div class="col-sm-8 col-sm-offset-4">
							<span id="genPass">
								{button href="#" _onclick="" _text="{tr}Generate a passcode{/tr}"}
							</span>
						</div>
						{preference name=showRegisterPasscode}
					</div>
					{preference name=registerKey}
					{if $gd_lib_found neq 'y'}
						{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
							{tr}Requires PHP GD library{/tr}.
						{/remarksbox}
					{/if}
					{preference name=generate_password}
					{preference name=http_referer_registration_check}
					<fieldset>
						<legend>{tr}CAPTCHA{/tr}</legend>
						{preference name=feature_antibot}
						<div class="adminoptionboxchild" id="feature_antibot_childcontainer">
							{preference name=captcha_wordLen}
							{preference name=captcha_width}
							{preference name=captcha_noise}
							{preference name=recaptcha_enabled}
							<div class="adminoptionboxchild" id="recaptcha_enabled_childcontainer">
								{preference name=recaptcha_pubkey}
								{preference name=recaptcha_privkey}
								{preference name=recaptcha_theme}
								{preference name=recaptcha_version}
							</div>
							{preference name=captcha_questions_active}
							<div class="adminoptionboxchild" id="captcha_questions_active_childcontainer">
								{preference name=captcha_questions}
							</div>
					</fieldset>
					<legend>{tr}Group and tracker login settings{/tr}</legend>
					<div class="adminoptionbox form-group">
						<label for="registration_choices" class="col-sm-4 control-label">{tr}Users can select a group to join at registration:{/tr}</label>
						<div class="col-sm-8 adminoptionlabel">
							<select id="registration_choices" name="registration_choices[]" multiple="multiple" size="5" class="form-control">
								{foreach key=g item=gr from=$listgroups}
									{if $gr.groupName ne 'Anonymous'}
										<option value="{$gr.groupName|escape}" {if $gr.registrationChoice eq 'y'} selected="selected"{/if}>{$gr.groupName|truncate:"52"|escape}</option>
									{/if}
								{/foreach}
							</select>
							<div class="help-block">{tr}By default, new users automatically join the Registered group{/tr}.</div>
						</div>
					</div>
					{preference name=user_must_choose_group}
					{preference name=url_after_validation}
				</div>
				{preference name=userTracker}
				<div class="adminoptionboxchild" id="userTracker_childcontainer">
					{preference name=feature_userWizardDifferentUsersFieldIds}
					<div class="adminoptionboxchild" id="feature_userWizardDifferentUsersFieldIds_childcontainer">
						{preference name=feature_userWizardUsersFieldIds}
					</div>
					{preference name=user_register_prettytracker}
					<div class="adminoptionboxchild" id="user_register_prettytracker_childcontainer">
						{preference name=user_register_prettytracker_tpl}
						{preference name=user_register_prettytracker_hide_mandatory}
					</div>
					{preference name=user_register_prettytracker_output}
					<div class="adminoptionboxchild" id="user_register_prettytracker_output_childcontainer">
						{preference name=user_register_prettytracker_outputwiki}
						{preference name=user_register_prettytracker_outputtowiki}
					</div>
					{preference name=user_trackersync_trackers}
					{preference name=user_trackersync_realname}
					{preference name=user_trackersync_groups}
					{preference name=user_trackersync_geo}
					{preference name=user_trackersync_lang}
					{preference name=user_tracker_auto_assign_item_field}
				</div>
				{preference name=user_force_avatar_upload}
				{preference name=tracker_force_fill}
				<div class="adminoptionboxchild" id="tracker_force_fill_childcontainer">
					{preference name=tracker_force_tracker_id}
					{preference name=tracker_force_mandatory_field}
					{preference name=tracker_force_tracker_fields}
				</div>	
				{preference name=groupTracker}
				<legend>{tr}Other login settings{/tr}</legend>
				{preference name=email_due}
				{preference name=unsuccessful_logins}
				{preference name=unsuccessful_logins_invalid}
				{preference name=eponymousGroups}
				{preference name=desactive_login_autocomplete}
				{preference name=login_multiple_forbidden}
				{preference name=login_grab_session}
				{preference name=session_protected}
				{preference name=https_login}
				{preference name=login_http_basic}
				<div class="adminoptionboxchild https_login_childcontainer allowed encouraged force_nocheck required">
					{preference name=feature_show_stay_in_ssl_mode}
					{preference name=feature_switch_ssl_mode}
					{preference name=http_port}
					{preference name=https_port}
					{preference name=https_external_links_for_users}
				</div>
				<fieldset>
					<legend>{tr}Cookies{/tr}</legend>
					{preference name=rememberme}
					<div class="adminoptionboxchild rememberme_childcontainer all always">
						{preference name=remembertime}
						{preference name=cookie_refresh_rememberme}
					</div>
					{preference name=cookie_name}
					{preference name=cookie_domain}
					{preference name=cookie_path}
					<hr>
					<legend>{tr}Cookie consent{/tr}</legend>
					{preference name=cookie_consent_feature}
					<div class="adminoptionboxchild" id="cookie_consent_feature_childcontainer">
						{preference name=cookie_consent_name}
						{preference name=cookie_consent_expires}
						{preference name=cookie_consent_description}
						{preference name=cookie_consent_question}
						{preference name=cookie_consent_alert}
						{preference name=cookie_consent_button}
						{preference name=cookie_consent_mode}
						{preference name=cookie_consent_dom_id}
						{preference name=cookie_consent_disable}
					</div>
				</fieldset>
				{preference name=feature_banning}
			</fieldset>
			<fieldset>
				<legend>{tr}Username{/tr}</legend>
				{preference name=login_is_email mode=invert}
				{preference name=login_is_email_obscure}
				{preference name=user_unique_email}
				{preference name=login_allow_email}
				<div class="adminoptionboxchild" id="login_is_email_childcontainer">
					{preference name=min_username_length}
					{preference name=max_username_length}
					{preference name=lowercase_username}
				</div>
				{preference name=username_pattern}
				{preference name=login_autogenerate}
			</fieldset>
			<fieldset>
				<legend>{tr}Password{/tr}</legend>
				{preference name=forgotPass}
				{preference name=change_password}
				{preference name=pass_chr_num}
				{preference name=pass_chr_case}
				{preference name=pass_chr_special}
				{preference name=pass_repetition}
				{preference name=pass_blacklist}
				{preference name=pass_diff_username}
				{preference name=min_pass_length}
				{preference name=pass_due}
			</fieldset>
			<fieldset>
				<div class="form-group">
					<div class="col-sm-8 col-sm-offset-4">
						{button href="?page=login&amp;refresh_email_group=y" _class="timeout" _text="{tr}Assign users to groups by matching email patterns{/tr}"}
						<div class="help-block">{tr}An email pattern must be defined in the settings for at least one group for this to produce any results.{/tr}</div>
					</div>
				</div>
			</fieldset>
		{/tab}

		{tab name="{tr}Remote Tiki Autologin{/tr}"}
			<br>
			<fieldset>
				{preference name=login_autologin}
				{preference name=login_autologin_user}
				{preference name=login_autologin_group}
				{preference name=login_autologin_createnew}
				{preference name=login_autologin_allowedgroups}
				{preference name=login_autologin_syncgroups}
				{preference name=login_autologin_logoutremote}
				{preference name=login_autologin_redirectlogin}
				{preference name=login_autologin_redirectlogin_url}
			</fieldset>
		{/tab}

		{tab name="{tr}LDAP{/tr}"}
			<br>
			<fieldset>
				<legend>LDAP {help url="Login+Authentication+Methods"}</legend>
				{if $prefs.auth_method ne 'ldap'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to LDAP for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{preference name=ldap_create_user_tiki}
				{preference name=ldap_create_user_ldap}
				{preference name=ldap_skip_admin}
				{preference name=auth_ldap_permit_tiki_users}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP bind settings{/tr}{help url="LDAP+Authentication"}</legend>
				{preference name=auth_ldap_host}
				{preference name=auth_ldap_port}
				{preference name=auth_ldap_debug}
				{preference name=auth_ldap_ssl}
				{preference name=auth_ldap_starttls}
				{preference name=auth_ldap_type}
				{preference name=auth_ldap_scope}
				{preference name=auth_ldap_version}
				{preference name=auth_ldap_basedn}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP user{/tr}</legend>
				{preference name=auth_ldap_userdn}
				{preference name=auth_ldap_userattr}
				{preference name=auth_ldap_useroc}
				{preference name=auth_ldap_nameattr}
				{preference name=auth_ldap_countryattr}
				{preference name=auth_ldap_emailattr}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP admin{/tr}</legend>
				<input type="password" style="display:none" name="auth_ldap_adminuser_autocomplete_off"> {* This is now required so the browser don't store the user's login here *}
				{preference name=auth_ldap_adminuser}
				<input type="password" style="display:none" name="auth_ldap_adminpass_autocomplete_off"> {* This is now required so the browser don't store the user's password here *}
				{preference name=auth_ldap_adminpass}
			</fieldset>
		{/tab}

		{tab name="{tr}LDAP external groups{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}LDAP external groups{/tr}</legend>
				{preference name=auth_ldap_group_external}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP bind settings{/tr}{help url="LDAP+Authentication"}</legend>
				{preference name=auth_ldap_group_host}
				{preference name=auth_ldap_group_port}
				{preference name=auth_ldap_group_debug}
				{preference name=auth_ldap_group_ssl}
				{preference name=auth_ldap_group_starttls}
				{preference name=auth_ldap_group_type}
				{preference name=auth_ldap_group_scope}
				{preference name=auth_ldap_group_version}
				{preference name=auth_ldap_group_basedn}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP user{/tr}</legend>
				{preference name=auth_ldap_group_userdn}
				{preference name=auth_ldap_group_userattr}
				{preference name=auth_ldap_group_corr_userattr}
				{preference name=auth_ldap_group_useroc}
				{preference name=syncGroupsWithDirectory}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP group{/tr}</legend>
				{preference name=auth_ldap_groupdn}
				{preference name=auth_ldap_groupattr}
				{preference name=auth_ldap_groupdescattr}
				{preference name=auth_ldap_groupoc}
				{preference name=syncUsersWithDirectory}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP group member - if group membership can be found in group attributes{/tr}</legend>
				{preference name=auth_ldap_memberattr}
				{preference name=auth_ldap_memberisdn}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP user group - if group membership can be found in user attributes{/tr}</legend>
				{preference name=auth_ldap_usergroupattr}
				{preference name=auth_ldap_groupgroupattr}
			</fieldset>
			<fieldset>
				<legend>{tr}LDAP admin{/tr}</legend>
				{preference name=auth_ldap_group_adminuser}
				{preference name=auth_ldap_group_adminpass}
			</fieldset>
		{/tab}

		{tab name="{tr}PAM{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}PAM{/tr} {help url="AuthPAM" desc="{tr}PAM{/tr}"}</legend>
				{if $prefs.auth_method ne 'pam'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to PAM for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{preference name=pam_create_user_tiki}
				{preference name=pam_skip_admin}
				{preference name=pam_service}
			</fieldset>
		{/tab}

		{tab name="{tr}Shibboleth{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}Shibboleth{/tr}{help url="AuthShib" desc="{tr}Shibboleth Authentication {/tr}"}</legend>
				{if $prefs.auth_method ne 'shib'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to Shibboleth for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{preference name=shib_create_user_tiki}
				{preference name=shib_skip_admin}
				{preference name=shib_affiliation}
				{preference name=shib_usegroup}
				<div class="adminoptionboxchild" id="shib_usegroup_childcontainer">
					{preference name=shib_group}
				</div>
			</fieldset>
		{/tab}

		{tab name="{tr}SAML2{/tr}"}
			<fieldset>
				<legend>{tr}SAML2{/tr}{help url="SAML" desc="{tr}based on Onelogin's php-saml {/tr}"}</legend>
				{if $prefs.auth_method ne 'saml'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to SAML for these changes to take effect{/tr}
					{/remarksbox}
				{/if}

				<fieldset>
					<legend>{tr}Identity provider settings{/tr}</legend>
					{preference name=saml_idp_entityid}
					{preference name=saml_idp_sso}
					{preference name=saml_idp_slo}
					{preference name=saml_idp_x509cert}
				</fieldset>
				<fieldset>
					<legend>{tr}Options{/tr}</legend>
						{preference name=saml_options_autocreate}
						{preference name=saml_options_sync_group}
						{preference name=saml_options_slo}
						{preference name=saml_options_skip_admin}
						{preference name=saml_option_account_matcher}
						{preference name=saml_option_default_group}
						{preference name=saml_option_login_link_text}
				</fieldset>
				<fieldset>
					<legend>{tr}Attribute mapping{/tr}</legend>
						{preference name=saml_attrmap_username}
						{preference name=saml_attrmap_mail}
						{preference name=saml_attrmap_group}
				</fieldset>
				<fieldset>
					<legend>{tr}Group mapping{/tr}</legend>
						{preference name=saml_groupmap_admins}
						{preference name=saml_groupmap_registered}
				</fieldset>
				<fieldset>
					<legend>{tr}Advanced settings{/tr}</legend>
						{preference name=saml_advanced_debug}
						{preference name=saml_advanced_strict}
						{preference name=saml_advanced_sp_entity_id}
						{preference name=saml_advanced_nameidformat}
						{preference name=saml_advanced_requestedauthncontext}
						{preference name=saml_advanced_nameid_encrypted}
						{preference name=saml_advanced_authn_request_signed}
						{preference name=saml_advanced_logout_request_signed}
						{preference name=saml_advanced_logout_response_signed}
						{preference name=saml_advanced_metadata_signed}
						{preference name=saml_advanced_want_message_signed}
						{preference name=saml_advanced_want_assertion_signed}
						{preference name=saml_advanced_want_assertion_encrypted}
						{preference name=saml_advanced_retrieve_parameters_from_server}
						{preference name=saml_advanced_sp_x509cert}
						{preference name=saml_advanced_sp_privatekey}
						{preference name=saml_advanced_sign_algorithm}
				</fieldset>
			</fieldset>
		{/tab}

		{tab name="{tr}CAS{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}CAS (central authentication service){/tr}{help url="CAS+Authentication"}</legend>
				{if $prefs.auth_method ne 'cas'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to CAS for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{preference name='cas_create_user_tiki'}
				{preference name='cas_autologin'}
				{preference name='cas_skip_admin'}
				{preference name='cas_show_alternate_login'}
				{preference name='cas_force_logout'}
				{preference name='cas_version'}
				<fieldset>
					<legend>{tr}CAS server{/tr}</legend>
					{preference name='cas_hostname' label="{tr}CAS Server Name{/tr}"}
					{preference name='cas_port' label="{tr}CAS Server Port{/tr}"}
					{preference name='cas_path' label="{tr}CAS Server Path{/tr}"}
					{preference name='cas_extra_param' label="{tr}CAS Extra Parameter{/tr}"}
					{preference name='cas_authentication_timeout'}
				</fieldset>
			</fieldset>
		{/tab}

		{tab name="{tr}phpBB{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}phpBB{/tr}{help url="phpBB+Authentication" desc="{tr}phpBB User Database Authentication {/tr}"}</legend>
				{if $prefs.auth_method ne 'phpbb'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to phpBB for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{if $prefs.allowRegister ne 'n'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must turn Users can register off for phpBB Authentication to function properly{/tr}
					{/remarksbox}
				{/if}
				{preference name=auth_phpbb_create_tiki}
				{preference name=auth_phpbb_skip_admin}
				{preference name=auth_phpbb_disable_tikionly}
				{preference name=auth_phpbb_version}
				{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
					{tr}MySql only (for now){/tr}
				{/remarksbox}
				{preference name=auth_phpbb_dbhost}
				{preference name=auth_phpbb_dbuser}
				{preference name=auth_phpbb_dbpasswd}
				{preference name=auth_phpbb_dbname}
				{preference name=auth_phpbb_table_prefix}
			</fieldset>
		{/tab}

		{tab name="{tr}Web Server{/tr}"}
			<br>
			<fieldset>
				<legend>{tr}Web server{/tr}{help url="External+Authentication#Web_Server_HTTP_" desc="{tr}Web Server Authentication {/tr}"}</legend>
				{if $prefs.auth_method ne 'ws'}
					{remarksbox type="warning" title="{tr}Warning{/tr}" close="n"}
						{tr}You must change the Authentication Method to Web Server for these changes to take effect{/tr}
					{/remarksbox}
				{/if}
				{preference name='auth_ws_create_tiki'}
			</fieldset>
		{/tab}


{tab name="{tr}Password Blacklist{/tr}"}
		<br>
			<fieldset>
				<legend>{tr}Password{/tr}</legend>

				{preference name=pass_blacklist_file}

				<legend>{tr}Password blacklist tools{/tr}</legend>

				<div class="form-group">
					<h3>Upload Word List for Processing</h3>
					<p>Words currently indexed: {$num_indexed}</p>

					<p>You may create custom blacklists to better fit your needs. Start by uploading a word list.
						Then reduce that list to something that applies to your specific configuration and needs with the tools that appear below.</p>

					<p>Raw password files can be obtained from <a href="https://github.com/danielmiessler/SecLists/tree/master/Passwords" target="_blank">Daniel Miessler's Collection</a>.
						Tiki's defaut password blacklist files were generated from Missler's top 1 million password file.</p>

						<input type="file" name="passwordlist" accept="text/plain" />
						Use 'LOAD DATA INFILE': <input type="checkbox" name="loaddata" /> {help desc="Allows much larger files to be uploaded, but requires MySQL on localhost with extra permissions."}<br>
						<input type="submit" value="Create or Replace Word Index" name="uploadIndex" class="btn btn-primary btn-sm timeout">
						{help desc="Text files with one word per line accepted.
						The word list will be converted to all lowe case. Duplicate entries will be removed.
						Typically passwords lists should be arranged with the most commonly used passwords first."}<br>
						<input type="submit" value="Delete Temporary Index" name="deleteIndex" class="btn btn-primary btn-sm timeout">
						{help desc="It is recommended that you delete indexed passwords from your database after your done generating your password lists.
						They can take up quite a lot of space and serve no pourpose after processing is complete."}

						<p>Blacklist Currently Using: {$file_using}</p>
						{if $num_indexed}
							<h3>Generate and Save a Password Blacklist{help desc="Saving places a text file with the generated passwords in your storage/pass_blacklists folder and enables it
							as an option for use. Fields default to the password standards set in tiki. You should not have to change these, unless you plan on changing your password
							requirements in the future."}</h3>
							Number of passwords (limit): <input type="number" name="limit" value="{$limit}" />
							{help desc="This sets the number of passwords that your blacklist will use. The words from the begining of of the file will be selected over the lower,
										so if you have a list of words arranged with the most common at the top, it will select only the most common works to blacklist.
										Typical usage ranges between 1,000 & 10,000, although many more could be used. Twitter blacklists 396."}<br>
							Minmum Password Length: <input type="number" name="length" value="{$length}" />
							{help desc="The minimum password length for your password. This will filter out any password that has an illegal length."}<br>
							Require Numbers &amp; Letters: <input type="checkbox" name="charnum" {if $charnum}checked{/if} />
							{help desc="If checked, will filter out any password that does not have both upper and lower case letters."}<br>
							Require Special Characters: <input type="checkbox" name="special" {if $special}checked{/if} />
							{help desc="If checked, will filter out any passwords that do not have special characters."}<br>
							<input type="submit" value="Save & Set as Default" name="saveblacklist" class="btn btn-primary btn-sm timeout">
							<input type="submit" value="View Password List" name="viewblacklist" class="btn btn-primary btn-sm timeout" formtarget="_blank">
						{/if}
				</div>
			</fieldset>
		{/tab}
	{/tabset}

{include file='admin/include_apply_bottom.tpl'}
</form>



