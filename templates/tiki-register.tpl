<h2>{tr}Register as a new user{/tr}</h2>
<br />
{if $prefs.feature_ajax eq 'y'}
  <script src="lib/registration/register_ajax.js" type="text/javascript"></script>
{/if}

{if $showmsg eq 'y'}
<div class="simplebox highlight">
{$msg}
</div>

{elseif $userTrackerData}
{$userTrackerData}

{elseif $email_valid eq 'n'}
{icon _id=error style="vertical-align:middle" align="left"} {tr}Your email could not be validated; make sure you email is correct and click register below.{/tr}<br />
  <form action="tiki-register.php" method="post">
    <input type="text" name="email" value="{$smarty.post.email}"/>
    <input type="hidden" name="name" value="{$smarty.post.name}"/>
    <input type="hidden" name="pass" value="{$smarty.post.pass}"/>
    <input type="hidden" name="regcode" value="{$smarty.post.regcode}"/>
    <input type="hidden" name="novalidation" value="yes"/>
    {if $chosenGroup}<input type="hidden" name="chosenGroup" value="{$smarty.post.chosenGroup}" />{/if}
    <input type="submit" name="register" value="{tr}Register{/tr}" />
  </form>

{else}

  <form action="tiki-register.php" method="post"> <br />
    <table class="normal">


      <tr><td class="formcolor">{if $prefs.login_is_email eq 'y'}{tr}Email{/tr}{else}{tr}Username{/tr}{/if}:</td>
      <td class="formcolor">
        <input style="float:left" type="text" name="name" id="name"
	  {if $prefs.feature_ajax eq 'y'}onKeyUp="return check_name()"{/if}/>&nbsp;
          {if $prefs.feature_ajax eq'y'}<div id="checkfield" style="float:left"></div>{/if}
		{if $prefs.login_is_email eq 'y'} 
		<em>{tr}Use your email as login{/tr}</em>.
		{else}
	{if $prefs.min_username_length > 1}<em>{tr}Minimum {$prefs.min_username_length} characters long{/tr}</em>. {/if}
	  {if $prefs.lowercase_username eq 'y'} <em>{tr}Lowercase only{/tr}</em>.{/if}</td>
		{/if}
      </tr>

      {if $prefs.useRegisterPasscode eq 'y'}
        <tr><td class="formcolor">{tr}Passcode to register (not your user password){/tr}:</td>
	<td class="formcolor"><input type="password" name="passcode" /></td></tr>
      {/if}
 
      <tr><td class="formcolor">{tr}Password{/tr}:</td>
      <td class="formcolor"><input style="float:left" id='pass1' type="password" name="pass"
        {if $prefs.feature_ajax eq 'y'}onKeyUp="check_pass()"{/if}/>&nbsp;
	  {if $prefs.feature_ajax ne 'y' and $prefs.min_pass_length > 1}<em>{tr}Minimum {$prefs.min_pass_length} characters long{/tr}</em>. {/if}
	  {if $prefs.feature_ajax ne 'y' and $prefs.pass_chr_num eq 'y'}<em>{tr}Password must contain both letters and numbers{/tr}</em>.{/if}
	  </td>
      </tr>

      <tr><td class="formcolor">{tr}Repeat password{/tr}:</td>
      <td class="formcolor"><input style="float:left" id='pass2' type="password" name="passAgain"
        {if $prefs.feature_ajax eq'y'}onKeyUp="check_pass()"{/if}/>{if $prefs.feature_ajax eq'y'}<div style="float:left" id="checkpass"></div>{/if}</td>
      </tr>

{if $prefs.login_is_email ne 'y'}
      <tr><td class="formcolor">{tr}Email{/tr}:</td>
      <td class="formcolor"><input style="float:left" type="text" id="email" name="email"
        {if $prefs.validateUsers eq 'y' and $prefs.feature_ajax eq 'y'}onKeyUp="return check_mail()"{/if}/>{if $prefs.feature_ajax eq'y'}<div id="checkmail" style="float:left"></div>{/if}&nbsp;
        {if $prefs.validateUsers eq 'y' and $prefs.validateEmail ne 'y'}
        <em>{tr}A valid email is mandatory to register{/tr}</em>.{/if}</td>
      </tr>
{/if}
      {* Custom fields *}
      {section name=ir loop=$customfields}
        {if $customfields[ir].show}
          <tr><td class="form">{tr}{$customfields[ir].label}{/tr}:</td>
            <td class="form"><input type="{$customfields[ir].type}" name="{$customfields[ir].prefName}" value="{$customfields[ir].value}" size="{$customfields[ir].size}" /></td>
          </tr>
        {/if}
      {/section}
      
      {* Groups *}
      {if isset($theChoiceGroup)}
        <input type="hidden" name="chosenGroup" value="{$theChoiceGroup|escape}" />
      {elseif $listgroups}
        <tr><td class="formcolor">{tr}Select your group{/tr}</td><td class="formcolor">
        {foreach item=gr from=$listgroups}
          {if $gr.registrationChoice eq 'y'}<input type="radio" name="chosenGroup" value="{$gr.groupName|escape}">{if $gr.groupDesc}{tr}{$gr.groupDesc}{/tr}{else}{$gr.groupName}{/if}</input><br />{/if}
        {/foreach}</td></tr>
      {/if}
      {if $prefs.rnd_num_reg eq 'y'}{include file='antibot.tpl'}{/if}

      <tr><td class="formcolor">&nbsp;</td>
      <td class="formcolor"><input type="submit" name="register" value="{tr}Register{/tr}" /></td>
      </tr>
    </table>
  </form>
<br /><div class="simplebox">
{icon _id=information style="vertical-align:middle" align="left"}{tr}NOTE: Make sure to whitelist this domain to prevent registration emails being canned by your spam filter!{/tr}
</div>
  <br />

  {if $prefs.generate_password eq 'y'}
    <table class="normal">
      <tr><td class="formcolor"><a class="link" href="javascript:genPass('genepass','pass1','pass2');">{tr}Generate a password{/tr}</a></td>
        <td class="formcolor"><input id='genepass' type="text" /></td>
      </tr>
    </table>
  {/if}
{/if}
