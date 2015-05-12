{* $Id$ *}
<form action="tiki-admin.php?page=workspace" method="post">
	<input type="hidden" name="ticket" value="{$ticket|escape}">
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" class="btn btn-default" name="workspacesetprefs" value="{tr}Change preferences{/tr}" />
	</div>
	{tabset}
		{tab name="{tr}Workspaces{/tr}"}

			<fieldset class="admin">
				<legend>{tr}Activate the feature{/tr}</legend>
				{preference name=workspace_ui visible="always"}
			</fieldset>

			<fieldset class="admin">
				<legend>{tr}Administration{/tr}</legend>
				<a class="button service-dialog" href="{service controller=workspace action=create}">{tr}Create a workspace{/tr}</a>

				<div id="template-list">
				</div>
				<a class="button service-dialog reload" href="{service controller=workspace action=add_template}">{tr}Add a workspace template{/tr}</a>
			</fieldset>

			<fieldset class="admin">
				<legend>{tr}Dependencies{/tr}</legend>
				{preference name=namespace_enabled}
				{preference name=namespace_separator}
				{preference name=feature_perspective}
				{preference name=feature_categories}
				{preference name=feature_wiki}
			</fieldset>

			<fieldset class="admin">
				<legend>{tr}Advanced{/tr}</legend>
				{preference name=workspace_root_category}
			</fieldset>

			{jq}
$('#tiki-center').on('click', '.service-dialog', function () {
	$(this).serviceDialog({
		title: $(this).text(),
		success: function () {
			if ($(this).is('.reload')) {
				$('#template-list').load($.service('workspace', 'list_templates'));
			}
		}
	});

	return false;
});
$('#template-list').load($.service('workspace', 'list_templates'));
			{/jq}
		{/tab}
		{tab name="{tr}Areas{/tr}"}

			<fieldset class="admin">
				<legend>{tr}Activate the feature{/tr}</legend>
				{preference name=feature_areas}
			</fieldset>
			{if isset($error)}
				{remarksbox type="warning" title="{tr}Error{/tr}"}{$error} {tr}Nothing was updated.{/tr}{/remarksbox}
			{/if}
			<fieldset class="admin">
				<legend>{tr}Areas{/tr}</legend>
				<table class="normal">
					<tr>
						<th>{tr}On{/tr}</th>
						<th colspan="2">{tr}Category{/tr}</th>
						<th>{tr}Perspectives{/tr}</th>
						<th>{tr}Exclusive{/tr}</th>
						<th>{tr}Share Common{/tr}</th>
						<th>{tr}Description{/tr}</th>
					</tr>
					{cycle values="odd,even" print=false}
					{if $areas|count}
						{foreach from=$areas item=area}
							<tr class="{cycle}{if $area.enabled neq 'y'} disabled{/if}">
								<td><input type="checkbox" name="enabled[{$area.categId}]"{if $area.enabled eq 'y'} checked="checked"{/if} class="enabledChecks"></td>
								<td>{$area.categId}</td>
								<td>{$area.categName}</td>
								<td>
									{foreach from=$area.perspectives item=persp}
										<a href="tiki-edit_perspective.php?action=edit&id={$persp.perspectiveId}" title="{tr}Edit perspective{/tr} {$persp.name}">{$persp.name}</a>{if not $persp@last},{/if}
									{/foreach}
								</td>
								<td><input type="checkbox" name="exclusive[{$area.categId}]"{if $area.exclusive eq 'y'} checked="checked"{/if}{if $area.enabled neq 'y'} disabled="disabled"{/if} class="otherChecks"></td>
								<td><input type="checkbox" name="share_common[{$area.categId}]"{if $area.share_common eq 'y'} checked="checked"{/if}{if $area.enabled neq 'y'} disabled="disabled"{/if} class="otherChecks"></td>
								<td>{$area.description}</td>
							</tr>
						{/foreach}
						{jq}
$(".enabledChecks").click(function() {
	var checked = ! $(this).prop("checked");
	$(".otherChecks", $(this).parents("tr:first")).each(function() {
		$(this).prop("disabled", checked);
	});
});
						{/jq}
					{else}
						<td class="{cycle}" colspan="7">{tr}No areas found. Click "Update Areas" to populate this list{/tr}</td>
					{/if}
				</table>
			</fieldset>
			{remarksbox type="info" title="{tr}Hint{/tr}"}{tr}This tab shows you an overview of categories affected by the areas feature. <br> More help here: <a href="http://doc.tiki.org/Areas" target="tikihelp">doc.tiki.org/Areas</a> {/tr}{/remarksbox}
			<div class="heading input_submit_container" style="text-align: center">
				<input type="submit" class="btn btn-default" name="update_areas" value="{tr}Update areas{/tr}" />
			</div>
			<div class="adminoptionboxchild" id="feature_areas_childcontainer">
				{preference name=areas_root}
			</div>

		{/tab}
	{/tabset}
	<div class="heading input_submit_container" style="text-align: center">
		<input type="submit" class="btn btn-default" name="workspacesetprefs" value="{tr}Change preferences{/tr}" />
	</div>
</form>
