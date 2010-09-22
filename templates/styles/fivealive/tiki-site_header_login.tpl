{* $Id$ *}
{* site header login form *}{*<div id="siteloginbar"><a href="tiki-register.php" class="register_link"><span>{tr}Register{/tr}</span></a><a href="tiki-login.php" class="login_link"><span>{tr}Log in{/tr}</span></a></div>*}
{strip}
{if $filegals_manager eq '' and $print_page ne 'y'}
	{if $prefs.feature_site_login eq 'y'}
		{if !empty($user)}
<div id="siteloginbar" class="logged-in">
	{$user|userlink} | <a href="tiki-logout.php" title="{tr}Log out{/tr}">{tr}Log out{/tr}</a>
{else}
<div id="siteloginbar">
	{if !empty($user)}
		{$user|userlink} | <a href="tiki-logout.php" title="{tr}Log out{/tr}">{tr}Log out{/tr}</a>
	{elseif $smarty.request.user neq 'admin' && $prefs.auth_method eq 'cas' && $showloginboxes neq 'y'}
		<strong><a href="tiki-login.php?cas=y">{tr}Log in through CAS{/tr}</a></strong>
		{if $prefs.cas_skip_admin eq 'y' && $prefs.cas_show_alternate_login eq 'y'}
			&nbsp;|&nbsp;{self_link _template='tiki-site_header_login.tpl' _title="{tr}Log in as admin{/tr}" _icon='user_red' _htmlelement='siteloginbar' user='admin'}{tr}Log in as admin{/tr}{/self_link}
		{/if}
	{elseif $smarty.request.user neq 'admin' && $prefs.auth_method eq 'shib' && $showloginboxes neq 'y'}
		<strong><a href="tiki-login.php">{tr}Log in through Shibboleth{/tr}</a></strong>
		{if $prefs.shib_skip_admin eq 'y'}
			&nbsp;|&nbsp;{self_link _template='tiki-site_header_login.tpl' _title="{tr}Log in as admin{/tr}" _icon='user_red' _htmlelement='siteloginbar' user='admin'}{tr}Log in as admin{/tr}{/self_link}
		{/if}
	{else}
		<form class="forms" id="loginbox" action="{if $prefs.https_login eq 'encouraged' || $prefs.https_login eq 'required' || $prefs.https_login eq 'force_nocheck'}{$base_url_https}{/if}{$prefs.login_url}" method="post" {if $prefs.feature_challenge eq 'y'}onsubmit="doChallengeResponse()"{/if}{if $prefs.desactive_login_autocomplete eq 'y'} autocomplete="off"{/if}>
			<fieldset><label for="sl-login-user">{if $prefs.login_is_email eq 'y'}{tr}Email{/tr}{else}{tr}Username{/tr}{/if}:</label>
			<input type="text" name="user" id="sl-login-user" />
			<label for="sl-login-pass">{tr}Password{/tr}:</label>
			<input type="password" name="pass" id="sl-login-pass" size="10" />
			<input class="wikiaction" type="submit" name="login" value="{tr}Log in{/tr}" /></fieldset>
			<div>
			{if $prefs.rememberme eq 'always'}<input type="hidden" name="rme" value="on" />
			{elseif $prefs.rememberme eq 'all'}
				<span class="rme">
					<label for="login-remember">{tr}Remember me{/tr}</label><input type="checkbox" name="rme" id="login-remember" value="on" checked="checked" />
				</span>
			{/if}
			{if $prefs.change_password eq 'y' and $prefs.forgotPass eq 'y'}
				<span class="pass">
					 <a href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my password{/tr}</a>
				</span>
			{/if}
			{if $prefs.allowRegister eq 'y'}
				<span class="register">
					&nbsp;<a href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}Register{/tr}</a>
				</span>
			{/if}		
			</div>
		</form>
	{/if}
{/if}
</div>
	{/if}
{/if}
{/strip}
