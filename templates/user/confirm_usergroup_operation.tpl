{extends "layout_view.tpl"}

{block name="title"}
	{title}{$title}{/title}
{/block}

{block name="content"}
	<h5>{tr _0=$readableAction _1=$group}%0 the following user from group %1?{/tr}</h5>
	<ul>
		<li>{$assignUser}</li>
	</ul>
	<br>
	<form method="post" action="tiki-assignuser.php" class="form-horizontal form-inline no-ajax" id="group_operation_form">
		{ticket}
		<p>{tr}Please confirm this operation by typing your password{/tr}</p>
		<input id="group_operation_password" type="password" name="confirmpassword" placeholder="{tr}Password{/tr}" class="form-control flex-fill" required>
		<input type="hidden" value="{$group}" name="group">
		<input type="hidden" value="{$assignUser}" name="assign_user">
		<input type="hidden" value="{$maxRecords}" name="maxRecords">
		<input type="hidden" value="{$offset}" name="offset">
		<input type="hidden" value="{$sortMode}" name="sort_mode">
		<input type="hidden" value="{$action}" name="action">
		<button type="submit" style="display: none;"></button>
	</form>
	{* Don't warn on leaving page if the modal is closed without saving *}
	{jq}
		$(".modal.fade.show").one("hide.bs.modal", function () {window.needToConfirm=false;});
		$('#submit_usergroup_operation').on('click', function() {
			$('#group_operation_form button[type="submit"]').click();
		});

		(function() {
			$('#group_operation_password').focus();
		})();
	{/jq}
{/block}

{block name=buttons append}
	<button id="submit_usergroup_operation" type="submit" class="btn btn-primary">{tr _0=$readableAction}%0{/tr}</button>
{/block}