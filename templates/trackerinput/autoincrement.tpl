{if $item.itemId}
	<div{if $field.options_map.prepend or $field.options_map.append} class="input-group"{/if}>
		{if $field.options_map.prepend}
			<span class="input-group-addon">{$field.options_map.prepend}&nbsp;</span>
		{/if}
		<input type="text" class="form-control" value="{$field.value|escape}" disabled="disabled">
		{if $field.options_map.append}
			<span class="input-group-addon">&nbsp;{$field.options_map.append}</span>
		{/if}
	</div>
{else}
	{tr}(automatically generated after item creation){/tr}
{/if}
