{* $Id$ *}
{jq notonready=true}
function capLock(e){
 kc = e.keyCode?e.keyCode:e.which;
 sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
 if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
  document.getElementById('divCapson').style.visibility = 'visible';
 else
  document.getElementById('divCapson').style.visibility = 'hidden';
}
{/jq}
{if !isset($tpl_module_title)}{assign var=tpl_module_title value="{tr}Log in{/tr}"}{/if}{* Left for performance, since tiki-login_scr.php includes this template directly. *}
{tikimodule error=$module_params.error title=$tpl_module_title name="login_box" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
    {if $user}
      <div>{tr}Logged in as:{/tr} <span style="white-space: nowrap">{$user|userlink}</span></div>
      <div style="text-align: center;">
				{button href="tiki-logout.php" _text="{tr}Log out{/tr}"}
			</div>
      {if $tiki_p_admin eq 'y'}
        <form action="{if $prefs.https_login eq 'encouraged' || $prefs.https_login eq 'required' || $prefs.https_login eq 'force_nocheck'}{$base_url_https}{/if}{$prefs.login_url}" method="post"{if $prefs.desactive_login_autocomplete eq 'y'} autocomplete="off"{/if}>
         <fieldset>
          <legend>{tr}Switch User{/tr}</legend>
          <label for="login-switchuser">{tr}Username:{/tr}</label>
          <input type="hidden" name="su" value="1" />
		  {if $prefs.feature_help eq 'y'}
			{help url="Switch+User" desc="{tr}Help{/tr}" desc="{tr}Switch User:{/tr}{tr}Enter user name and click 'Switch'.<br />Useful for testing permissions.{/tr}"}
		  {/if}
          <input type="text" name="username" id="login-switchuser" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" />
          <div style="text-align: center"><button type="submit" name="actsu">{tr}Switch{/tr}</button></div>
		  {jq}$("#login-switchuser").tiki("autocomplete", "username"){/jq}
         </fieldset>
        </form>
      {/if}
	  {if $prefs.auth_method eq 'openid' and $openid_userlist|@count gt 1}
        <form method="get" action="tiki-login_openid.php">
		  <fieldset>
		  	<legend>{tr}Switch user{/tr}</legend>
			<select name="select">
			{foreach item=username from=$openid_userlist}
				<option{if $username eq $user} selected="selected"{/if}>{$username}</option>
			{/foreach}
			</select>
			<input type="hidden" name="action" value="select"/>
			<input type="submit" value="{tr}Go{/tr}"/>
		  </fieldset>
		</form>
	  {/if}
      {elseif $prefs.auth_method eq 'cas' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php?cas=y">{tr}Log in through CAS{/tr}</a></b>
		{if $prefs.cas_skip_admin eq 'y'}
		<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Log in as admin{/tr}</a>
      {/if}
      {elseif $prefs.auth_method eq 'shib' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php">{tr}Log in through Shibboleth{/tr}</a></b>
		{if $prefs.shib_skip_admin eq 'y'}
		<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Log in as admin{/tr}</a>
      {/if}
    {else}
     <form name="loginbox" action="{if $prefs.https_login eq 'encouraged' || $prefs.https_login eq 'required' || $prefs.https_login eq 'force_nocheck'}{$base_url_https}{/if}{$prefs.login_url}" method="post" {if $prefs.feature_challenge eq 'y'}onsubmit="doChallengeResponse()"{/if}{if $prefs.desactive_login_autocomplete eq 'y'} autocomplete="off"{/if}> 
     {if $prefs.feature_challenge eq 'y'}
     <script type='text/javascript' src="lib/md5.js"></script>   
     {literal}
     <script type='text/javascript'>
     <!--
     function doChallengeResponse() {
       hashstr = document.loginbox.user.value +
       document.loginbox.pass.value +
       document.loginbox.email.value;
       str = document.loginbox.user.value + 
       MD5(hashstr) +
       document.loginbox.challenge.value;
       document.loginbox.response.value = MD5(str);
       document.loginbox.pass.value='';
       /*
       document.login.password.value = "";
       document.logintrue.username.value = document.login.username.value;
       document.logintrue.response.value = MD5(str);
       document.logintrue.submit();
       */
       document.loginbox.submit();
       return false;
     }
     // -->
    </script>
    {/literal}
     <input type="hidden" name="challenge" value="{$challenge|escape}" />
     <input type="hidden" name="response" value="" />
     {/if}
	 {if !empty($urllogin)}<input type="hidden" name="url" value="{$urllogin|escape}" />{/if}
        <fieldset>
          <legend>{tr}Log in as{/tr}&hellip;</legend>
		  {if !empty($error_login)}
			{remarksbox type='errors' title="{tr}Error{/tr}"}
				{if $error_login == -5 {*USER_NOT_FOUND (define does not work on old php)*}}{tr}Invalid username{/tr}
				{elseif $error_login == -3 {*PASSWORD_INCORRECT*}}{tr}Invalid password{/tr}
				{else}{$error_login|escape}{/if}
			{/remarksbox}
		  {/if}
            <div><label for="login-user">{if $prefs.login_is_email eq 'y'}{tr}Email:{/tr}{else}{tr}Username:{/tr}{/if}</label><br />
		{if $loginuser eq ''}
              <input type="text" name="user" id="login-user" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" {if !empty($error_login)} value="{$error_user|escape}"{/if} />
	  <script type="text/javascript">document.getElementById('login-user').focus();</script>
		{else}
		      <input type="hidden" name="user" id="login-user" value="{$loginuser}" /><b>{$loginuser}</b>
		{/if}</div>
		<script type="text/javascript">document.getElementById('login-user').focus();</script>
          {if $prefs.feature_challenge eq 'y'} <!-- quick hack to make challenge/response work until 1.8 tiki auth overhaul -->
          <div><label for="login-email">{tr}eMail:{/tr}</label><br />
          <input type="text" name="email" id="login-email" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" /></div>
          {/if}
          <div><label for="login-pass">{tr}Password:{/tr}</label><br />
          <input onkeypress="capLock(event)" type="password" name="pass" id="login-pass" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" />
		  <div id="divCapson" style="visibility:hidden">{icon _id=error style="vertical-align:middle"} {tr}CapsLock is on.{/tr}</div>
		  </div>
          {if $prefs.rememberme ne 'disabled'}
            {if $prefs.rememberme eq 'always'}
              <input type="hidden" name="rme" id="login-remember-module-input" value="on" />
            {else}
              <div style="text-align: center"><input type="checkbox" name="rme" id="login-remember-module" value="on" /><label for="login-remember-module">{tr}Remember me{/tr}</label> ({tr}for{/tr} {if $prefs.remembertime eq 300}5 {tr}minutes{/tr}{elseif $prefs.remembertime eq 900}15 {tr}minutes{/tr}{elseif $prefs.remembertime eq 1800}30 {tr}minutes{/tr}{elseif $prefs.remembertime eq 3600}1 {tr}hour{/tr}{elseif $prefs.remembertime eq 7200}2 {tr}hours{/tr}{elseif $prefs.remembertime eq 36000}10 {tr}hours{/tr}{elseif $prefs.remembertime eq 72000}20 {tr}hours{/tr}{elseif $prefs.remembertime eq 86400} 1 {tr}day{/tr}{elseif $prefs.remembertime eq 604800}1 {tr}week{/tr}{elseif $prefs.remembertime eq 2629743}1 {tr}month{/tr}{elseif $prefs.remembertime eq 31556926}1 {tr}year{/tr}{/if})
			  </div>
            {/if}
          {/if}
          <div style="text-align: center"><input class="button submit" type="submit" name="login" value="{tr}Log in{/tr}" /></div>
       </fieldset>
          
          {if $prefs.forgotPass eq 'y' and $prefs.allowRegister eq 'y' and $prefs.change_password eq 'y'}
            <div>[&nbsp;<a class="linkmodule" href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}Register{/tr}</a> | <a class="linkmodule" href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my password{/tr}</a>&nbsp;]</div>
          {/if}
          {if $prefs.forgotPass eq 'y' and $prefs.allowRegister ne 'y' and $prefs.change_password eq 'y'}
            <div><a class="linkmodule" href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my password{/tr}</a></div>
          {/if}
          {if ($prefs.forgotPass ne 'y' or $prefs.change_password ne 'y') and $prefs.allowRegister eq 'y'}
            <div><a class="linkmodule" href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}Register{/tr}</a></div>
          {/if}
          {if ($prefs.forgotPass ne 'y' or $prefs.change_password ne 'y') and $prefs.allowRegister ne 'y'}
          &nbsp;
          {/if}
          {if $prefs.feature_switch_ssl_mode eq 'y' && ($prefs.https_login eq 'allowed' || $prefs.https_login eq 'encouraged')}
          <div>
            <a class="linkmodule" href="{$base_url_http}{$prefs.login_url}" title="{tr}Click here to login using the default security protocol{/tr}">{tr}Standard{/tr}</a> |
            <a class="linkmodule" href="{$base_url_https}{$prefs.login_url}" title="{tr}Click here to login using a secure protocol{/tr}">{tr}Secure{/tr}</a>
          </div>
          {/if}
          {if $prefs.feature_show_stay_in_ssl_mode eq 'y' && $show_stay_in_ssl_mode eq 'y'}
                <div><label for="login-stayssl">{tr}Stay in SSL mode:{/tr}</label>?
                <input type="checkbox" name="stay_in_ssl_mode" id="login-stayssl" {if $stay_in_ssl_mode eq 'y'}checked="checked"{/if} /></div>
          {/if}
	{* This is needed as unchecked checkboxes are not sent. The other way of setting hidden field with same name is potentially non-standard *}
	<input type="hidden" name="stay_in_ssl_mode_present" value="y" />
      {if $prefs.feature_show_stay_in_ssl_mode neq 'y' || $show_stay_in_ssl_mode neq 'y'}
        <input type="hidden" name="stay_in_ssl_mode" value="{$stay_in_ssl_mode|escape}" />
      {/if}
      
			{if $use_intertiki_auth eq 'y'}
				<select name='intertiki'>
					<option value="">{tr}local account{/tr}</option>
					<option value="">-----------</option>
					{foreach key=k item=i from=$intertiki}
					<option value="{$k}">{$k}</option>
					{/foreach}
				</select>
			{/if}
      </form>
    {/if}
	{if $prefs.auth_method eq 'openid' and !$user and (!isset($registration) || $registration neq 'y')}
		<form method="get" action="tiki-login_openid.php">
			<fieldset>
				<legend>{tr}OpenID Log in{/tr}</legend>
				<input class="openid_url" type="text" name="openid_url"/>
				<input type="submit" value="{tr}Go{/tr}"/>
				<a class="linkmodule tikihelp" target="_blank" href="http://doc.tiki.org/OpenID">{tr}What is OpenID?{/tr}</a>
			</fieldset>
		</form>
	{/if}
{/tikimodule}
