{* $Id$ *}
{strip}
{if $user}
<div id="siteloginbar" class="logged-in">
	{$user|userlink} | <a href="tiki-logout.php" title="{tr}Logout{/tr}">{tr}Logout{/tr}</a>
{else}
<div id="siteloginbar">
	{if $user}
		{$user|userlink} | <a href="tiki-logout.php" title="{tr}Logout{/tr}">{tr}Logout{/tr}</a>
	{elseif $prefs.auth_method eq 'cas' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php?cas=y">{tr}Login through CAS{/tr}</a></b>
		{if $prefs.cas_skip_admin eq 'y'}
			<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Login as admin{/tr}</a>
		{/if}
	{elseif $prefs.auth_method eq 'shib' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php">{tr}Login through Shibboleth{/tr}</a></b>
		{if $prefs.shib_skip_admin eq 'y'}
			<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Login as admin{/tr}</a>
		{/if}
	{else}
		<form class="forms" name="loginbox" action="tiki-login.php" method="post">
					{if $prefs.allowRegister eq 'y'}
				<div class="register">
					<a href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}Register{/tr}</a>
				</div>
			{/if}	
			<label for="sl-login-user">{if $prefs.login_is_email eq 'y'}{tr}Email{/tr}{else}{tr}User{/tr}{/if}:</label>
			<input type="text" name="user" id="sl-login-user" />
			<label for="sl-login-pass">{tr}Password{/tr}:</label>
			<input type="password" name="pass" id="sl-login-pass" size="10" />
			<input class="wikiaction" type="submit" name="login" value="{tr}Login{/tr}" />
			{*<div>*}
			{if $prefs.rememberme eq 'always'}<input type="hidden" name="rme" value="on" />
			{elseif $prefs.rememberme eq 'all'}
				<div class="rme">
					<label for="login-remember">{tr}Remember me{/tr}</label><input type="checkbox" name="rme" id="login-remember" value="on" checked="checked" />
				</div>
			{/if}
			{if $prefs.change_password eq 'y' and $prefs.forgotPass eq 'y'}
				<div class="pass">
					 <a href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my password{/tr}</a>
				</div>
			{/if}
	
		{*	</div>*}
		</form>
	{/if}
{/if}
</div>
{/strip}
