{tikimodule error=$module_params.error title=$tpl_module_title name=$tpl_module_name flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle type=$module_type}
{if !empty($module_params.menu_id)}<div class="clearfix {$module_params.menu_class}" id="{$module_params.menu_id}">{/if}
{menu params=$module_params}
{if !empty($module_params.menu_id)}</div>{/if}
{/tikimodule}
