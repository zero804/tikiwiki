{remarksbox type="tip" title="{tr}Tip{/tr}"}{tr}You can see users rank by score in the module users_rank, for that go to{/tr} "<a class="rbox-link" href="tiki-admin_modules.php">{tr}Admin modules{/tr}</a>".{/remarksbox}

<form action="tiki-admin.php?page=score" method="post">
	<input type="hidden" name="ticket" value="{$ticket|escape}">
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" class="btn btn-default btn-sm" name="scoreevents" value="{tr}Save{/tr}" />
	</div>

<fieldset class="table">
	<legend>{tr}Activate the feature{/tr}</legend>
	{preference name=feature_score visible="always"}
</fieldset>

<fieldset class="table">
	<legend>{tr}Expire score older than certain days{/tr}</legend>
	{preference name=feature_score_expday visible="always"}
</fieldset>	

<fieldset class="table">
<legend>{tr}Settings{/tr}</legend>
<table class="table">
<tr>
  <td style="padding-left:5px"></td>
  <td></td>
  <td><b>{tr}Points{/tr}</b></td>
  <td><b>{tr}Expiration{/tr}</b></td>
  <td><b>{tr}Valid Object IDs{/tr}</b></td>
</tr>

{section name=e loop=$events}
{if $events[e].category != $categ}
  {assign var=categ value=$events[e].category}
<tr>
  <td colspan="4"><b>{tr}{$events[e].category}{/tr}</b></td>
</tr>
{/if}

<tr>
  <td></td>
  <td>{tr}{$events[e].description}{/tr}</td>
  <td>
    <input style="width: 150px;" type="text" size="3" name="events[{$events[e].event}][score]" value="{$events[e].score}" />
  </td>
  <td>
    <input style="width: 150px;" type="text" size="4" name="events[{$events[e].event}][expiration]" value="{$events[e].expiration}" />
  </td>
  <td>
    <input style="width: 150px;" size="3" name="events[{$events[e].event}][validObjectIds]" value="{if $events[e].validObjectIds}{$events[e].validObjectIds}{else}0{/if}" />
  </td>
</tr>
{/section}
</table>
</fieldset>
	<div class="heading input_submit_container" style="text-align: center">
		<input type="submit" class="btn btn-default btn-sm" name="scoreevents" value="{tr}Save{/tr}" />
	</div>
</form>
