{* $Id$ *}

<form action="tiki-admin.php?page=messages" method="post" name="messages">
	<input type="hidden" name="ticket" value="{$ticket|escape}">
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" class="btn btn-default" name="messagesprefs" value="{tr}Change preferences{/tr}" />
	</div>

	<fieldset class="admin">
		<legend>{tr}Activate the feature{/tr}</legend>
		{preference name=feature_messages visible="always"}
	</fieldset>

	<fieldset class="admin">
		<legend>{tr}Settings{/tr}</legend>

		{preference name=allowmsg_by_default}
		{preference name=allowmsg_is_optional}
		{preference name=messu_mailbox_size}
		{preference name=messu_archive_size}
		{preference name=messu_sent_size}
		{preference name=user_selector_realnames_messu}
		{preference name=messu_truncate_internal_message}

	</fieldset>
	<div class="heading input_submit_container" style="text-align: center">
		<input type="submit" class="btn btn-default" name="messagesprefs" value="{tr}Change preferences{/tr}" />
	</div>
</form>
