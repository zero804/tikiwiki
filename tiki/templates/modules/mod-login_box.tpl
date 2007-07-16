{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-login_box.tpl,v 1.52 2007-07-16 22:18:56 sylvieg Exp $ *}
{if $do_not_show_login_box ne 'y'}
{if !isset($tpl_module_title)}{assign var=tpl_module_title value="{tr}Login{/tr}"}{/if}
{tikimodule title=$tpl_module_title name="login_box" flip=$module_params.flip decorations=$module_params.decorations}

    {if $user}
      <div>{tr}Logged in as{/tr}: <span style="white-space: nowrap">{$user|userlink}</span></div>
      <p style="text-align: center"><a class="linkmodule linkbut" href="tiki-logout.php">{tr}logout{/tr}</a></p>
      {if $tiki_p_admin eq 'y'}
        <form action="{if $https_login eq 'encouraged' || $https_login eq 'required' || $https_login eq 'force_nocheck'}{$base_url_https}{/if}{$login_url}" method="post"{if $desactive_login_autocomplete eq 'y'} autocomplete="off"{/if}>
         <fieldset>
          <legend>{tr}Switch User{/tr}</legend>
          <label for="login-switchuser">{tr}User{/tr}:</label>
          <input type="hidden" name="su" value="1" />
          <input type="text" name="username" id="login-switchuser" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" />
         {if $feature_help eq 'y'}
          <sup><a class="linkmodule tikihelp" href="tiki-admin_modules.php"
          title="{tr}parameters{/tr}: $input_size {tr}applicable for this item{/tr}"><small><strong>?</strong></small></a></sup>
         {/if}
          <p style="text-align: center"><button type="submit" name="actsu">{tr}switch{/tr}</button></p>
         </fieldset>
        </form>
      {/if}
      {elseif $auth_method eq 'cas' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php?user">{tr}Login through CAS{/tr}</a></b>
		{if $cas_skip_admin eq 'y'}
		<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Login as admin{/tr}</a>
      {/if}
      {elseif $auth_method eq 'shib' && $showloginboxes neq 'y'}
		<b><a class="linkmodule" href="tiki-login.php">{tr}Login through Shibboleth{/tr}</a></b>
		{if $shib_skip_admin eq 'y'}
		<br /><a class="linkmodule" href="tiki-login_scr.php?user=admin">{tr}Login as admin{/tr}</a>
      {/if}
    {else}
     <form name="loginbox" action="{if $https_login eq 'encouraged' || $https_login eq 'required' || $https_login eq 'force_nocheck'}{$base_url_https}{/if}{$login_url}" method="post" {if $feature_challenge eq 'y'}onsubmit="doChallengeResponse()"{/if}{if $desactive_login_autocomplete eq 'y'} autocomplete="off"{/if}> 
     {if $feature_challenge eq 'y'}
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
        <fieldset>
          <legend>{tr}Login as{/tr}&hellip;</legend>
            <div><label for="login-user">{if $login_is_email eq 'y'}{tr}Email{/tr}{else}{tr}User{/tr}{/if}:</label><br />
		{if $loginuser eq ''}
              <input type="text" name="user" id="login-user" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" />
	  <script type="text/javascript">document.getElementById('login-user').focus();</script>
		{else}
		      <input type="hidden" name="user" id="login-user" value="{$loginuser}" /><b>{$loginuser}</b>
		{/if}</div>
		<script type="text/javascript">document.getElementById('login-user').focus();</script>
          {if $feature_challenge eq 'y'} <!-- quick hack to make challenge/response work until 1.8 tiki auth overhaul -->
          <div><label for="login-email">{tr}email{/tr}:</label><br />
          <input type="text" name="email" id="login-email" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" /></div>
          {/if}
          <div><label for="login-pass">{tr}Password{/tr}:</label><br />
          <input type="password" name="pass" id="login-pass" size="{if empty($module_params.input_size)}15{else}{$module_params.input_size}{/if}" /></div>
          {if $rememberme ne 'disabled'}
            {if $rememberme eq 'always'}
              <input type="hidden" name="rme" id="login-remember" value="on" />
            {else}
              <p style="text-align: center"><label for="login-remember">{tr}Remember me{/tr}</label> <input type="checkbox" name="rme" id="login-remember" value="on" /></p>
            {/if}
          {/if}
          <p style="text-align: center"><button type="submit" name="login">{tr}login{/tr}</button></p>
       </fieldset>
          
          {if $forgotPass eq 'y' and $allowRegister eq 'y' and $change_password eq 'y'}
            <p>[ <a class="linkmodule" href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}register{/tr}</a> | <a class="linkmodule" href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my pass{/tr}</a> ]</p>
          {/if}
          {if $forgotPass eq 'y' and $allowRegister ne 'y' and $change_password eq 'y'}
            <p><a class="linkmodule" href="tiki-remind_password.php" title="{tr}Click here if you've forgotten your password{/tr}">{tr}I forgot my password{/tr}</a></p>
          {/if}
          {if ($forgotPass ne 'y' or $change_password ne 'y') and $allowRegister eq 'y'}
            <p><a class="linkmodule" href="tiki-register.php" title="{tr}Click here to register{/tr}">{tr}register{/tr}</a></p>
          {/if}
          {if ($forgotPass ne 'y' or $change_password ne 'y') and $allowRegister ne 'y'}
          &nbsp;
          {/if}
          {if $feature_switch_ssl_mode eq 'y' && ($https_login eq 'allowed' || $https_login eq 'encouraged')}
          <p>
            <a class="linkmodule" href="{$base_url_http}{$login_url}" title="{tr}Click here to login using the default security protocol{/tr}">{tr}standard{/tr}</a> |
            <a class="linkmodule" href="{$base_url_https}{$login_url}" title="{tr}Click here to login using a secure protocol{/tr}">{tr}secure{/tr}</a>
          </p>
          {/if}
          {if $feature_show_stay_in_ssl_mode eq 'y' && $show_stay_in_ssl_mode eq 'y'}
                <p><label for="login-stayssl">{tr}stay in ssl mode{/tr}:</label>?
                <input type="checkbox" name="stay_in_ssl_mode" id="login-stayssl" {if $stay_in_ssl_mode eq 'y'}checked="checked"{/if} /></p>
          {/if}

      {if $feature_show_stay_in_ssl_mode neq 'y' || $show_stay_in_ssl_mode neq 'y'}
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
{/tikimodule}
{/if}