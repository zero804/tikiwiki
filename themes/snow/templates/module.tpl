{* $Id$ *}
{* Module layout with controls *}
{if !isset($module_position)}{assign var=module_position value=''}{/if}
{if !isset($module_ord)}{assign var=module_ord value=''}{/if}
{capture name=name}{$module_name|replace:"+":"_"|cat:$module_position|cat:$module_ord|escape}{/capture}
{if !empty($module_params.topclass)}<div class="{$module_params.topclass}">{/if}
{if $module_nobox neq 'y'}
	{if !isset($moduleId)}{assign var=moduleId value=' '}{/if}
	<div id="module_{$moduleId}" class="panel panel-default box-{$module_name}{if $module_type eq 'cssmenu'} cssmenubox{/if} module"{if !empty($tpl_module_style)} style="{$tpl_module_style}"{/if}>
	{if $module_decorations ne 'n'}
        <div class="panel-heading">
		<h3 class="panel-title" {if !empty($module_params.bgcolor)} style="background-color:{$module_params.bgcolor};"{/if}>
		{if $module_notitle ne 'y'}
			<span class="moduletitle">{$module_title}</span>
		{/if}
		{if $module_flip eq 'y' and $prefs.javascript_enabled ne 'n'}
			<span class="moduleflip" id="moduleflip-{$smarty.capture.name}">
				<a title="{tr}Toggle module contents{/tr}" class="flipmodtitle close" href="javascript:icntoggle('mod-{$smarty.capture.name}','module.png');">
					{icon id="icnmod-"|cat:$smarty.capture.name class="flipmodimage" _id="module" alt="[{tr}Toggle{/tr}]"}
				</a>
			</span>
			{if $prefs.menus_items_icons eq 'y'}
				<span class="moduleflip moduleflip-vert" id="moduleflip-vert-{$smarty.capture.name}">
					<a title="{tr}Toggle module contents{/tr}" class="flipmodtitle" href="javascript:flip_class('main','minimize-modules-left','maximize-modules');icntoggle('mod-{$smarty.capture.name}','vmodule.png');">
						{icon name="icnmod-"|cat:$smarty.capture.name class="flipmodimage" _id="trans" alt="[{tr}Toggle Vertically{/tr}]" _defaultdir="img"}
					</a>
				</span>
			{/if}
		{/if}
        </h3></div>
	{elseif $module_notitle ne 'y'}{* means when module decorations are set to 'n' don't render the panel-heading wrapper as above *}
		{if $module_flip eq 'y' and $prefs.javascript_enabled ne 'n'}
	<h3 class="panel-title" ondblclick="javascript:icntoggle('mod-{$smarty.capture.name}','module.png');"{if !empty($module_params.color)} style="color:{$module_params.color};"{/if}>
		{else}
	<h3 class="panel-title"{if !empty($module_params.color)} style="color:{$module_params.color};"{/if}>
		{/if}
		{$module_title}
		{if $module_flip eq 'y' and $prefs.javascript_enabled ne 'n'}
			<span class="moduleflip" id="moduleflip-{$smarty.capture.name}">
				<a title="{tr}Toggle module contents{/tr}" class="flipmodtitle" href="javascript:icntoggle('mod-{$smarty.capture.name}','module.png');">
					{icon name="icnmod-"|cat:$smarty.capture.name class="flipmodimage" _id="module" alt="[{tr}Toggle{/tr}]"}
				</a>
			</span>
		{/if}
	</h3>
	{/if}
		<div id="mod-{$smarty.capture.name}" style="display: {if !isset($module_display) or $module_display}block{else}none{/if};{$module_params.style}" class="clearfix panel-body{if !empty($module_params.class)} {$module_params.class}{/if}">
{else}{* $module_nobox eq 'y' *}
		<div id="module_{$moduleId}" style="{$module_params.style}{$tpl_module_style}" class="module{if !empty($module_params.class)} {$module_params.class}{/if} box-{$module_name} clearfix">
			<div id="mod-{$smarty.capture.name}">
{/if}
{$module_content}
{$module_error}
{if $module_nobox neq 'y'}
		</div>XX
			{* Module controls when module in a box *}
			{if $user and $prefs.user_assigned_modules == 'y' and $prefs.feature_modulecontrols eq 'y' && ($module_position === 'left' || $module_position === 'right')}
				<form action="{$current_location|escape}" method="post" class="modcontrols">
					<input type="hidden" name="redirect" value="1">
					<div class="pull-right">
						<button
							type="submit"
							name="mc_up"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move up{/tr}"
						>
							{icon name="up"}
						</button>
						<button
							type="submit"
							name="mc_down"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move down{/tr}"
						>
							{icon name="down"}
						</button>
						<button
							type="submit"
							name="mc_move"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move to opposite side{/tr}"
						>
							{icon name="move"}
						</button>
						<button
							type="submit"
							name="mc_unassign"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Unassign{/tr}"
						>
							{icon name="remove"}
						</button>
					</div>
				</form>
			{/if}
	</div>
{else}
			{* Module controls when no module box *}
			{if $user and $prefs.user_assigned_modules == 'y' and $prefs.feature_modulecontrols eq 'y' && ($module_position === 'left' || $module_position === 'right')}
				<form action="{$current_location|escape}" method="post" class="modcontrols">
					<input type="hidden" name="redirect" value="1">
					<div class="pull-right">
						<button
							type="submit"
							name="mc_up"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move up{/tr}"
						>
							{icon name="up"}
						</button>
						<button
							type="submit"
							name="mc_down"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move down{/tr}"
						>
							{icon name="down"}
						</button>
						<button
							type="submit"
							name="mc_move"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Move to opposite side{/tr}"
						>
							{icon name="move"}
						</button>
						<button
							type="submit"
							name="mc_unassign"
							value="{$moduleId}"
							class="tips btn btn-link"
							title=":{tr}Unassign{/tr}"
						>
							{icon name="remove"}
						</button>
					</div>
				</form>
			{/if}
		</div>
	</div>
{/if}
{if !empty($module_params.topclass)}</div>{/if}
