{* $Id$ *}

{title help="Shoutbox"}{tr}Shoutbox{/tr}{/title}

{if $tiki_p_admin_shoutbox eq 'y'}
<h2>{tr}Change shoutbox general settings{/tr}</h2>
<form action="tiki-shoutbox.php" method="post">
<table class="normal">
<tr>
  <td class="odd">{tr}auto-link urls{/tr}</td>
  <td class="odd">
    <input type="checkbox" name="shoutbox_autolink" value="on"{if $prefs.shoutbox_autolink eq 'y'} checked="checked"{/if} />
  </td>
</tr>
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="shoutbox_admin" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
{/if}

{if $tiki_p_post_shoutbox eq 'y'}
<h2>{tr}Post or edit a message{/tr}</h2>
{if $msg}
<div class="simplebox highlight">{$msg}</div>
{/if}
{js_maxlength textarea=message maxlength=255}
<form action="tiki-shoutbox.php" method="post" onsubmit="return verifyForm(this);">
<input type="hidden" name="msgId" value="{$msgId|escape}" />
<table class="normal">
<tr><td class="formcolor">{tr}Message{/tr}:</td><td class="formcolor"><textarea rows="4" cols="60" name="message">{$message|escape}</textarea></td></tr>
{if $prefs.feature_antibot eq 'y' && $user eq ''}
{include file='antibot.tpl' td_style="formcolor"}
{/if}
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
{/if}

<h2>{tr}Messages{/tr}</h2>

{include file='find.tpl'}

{section name=user loop=$channels}
<div class="shoutboxmsg">
<b><a href="tiki-user_information.php?view_user={$channels[user].user}">{$channels[user].user}</a></b>, {$channels[user].timestamp|tiki_long_date}, {$channels[user].timestamp|tiki_long_time}

{if $tiki_p_admin_shoutbox eq 'y' || $channels[user].user == $user }
  <a href="tiki-shoutbox.php?find={$find}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].msgId}" class="link">{icon _id='cross' alt='{tr}Delete{/tr}'}</a>
  <a href="tiki-shoutbox.php?find={$find}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;msgId={$channels[user].msgId}" class="link">{icon _id='page_edit' alt='{tr}Edit{/tr}'}</a>
{/if}
<br />
{$channels[user].message}
</div>
{/section}

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset}{/pagination_links}
