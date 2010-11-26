<div class="adminoptionbox{if isset($smarty.request.highlight) and $smarty.request.highlight eq $p.preference} highlight{/if}">
	<label for="{$p.id|escape}">{$p.name|escape}:</label>
	{if is_array( $p.value )}
		<input name="{$p.preference|escape}" id="{$p.id|escape}" value="{$p.value|@implode:$p.separator|escape}" size="{$p.size|default:40|escape}" type="text" />
	{else}
		<input name="{$p.preference|escape}" id="{$p.id|escape}" value="{$p.value|escape}" size="{$p.size|default:40|escape}" type="text" />
	{/if}
	{$p.detail|escape}
	{include file=prefs/shared-flags.tpl}
	{if $p.shorthint}
		<em>{$p.shorthint|simplewiki}</em>
	{/if}
	{if $p.hint}
		<br/><em>{$p.hint|simplewiki}</em>
	{/if}
	{include file=prefs/shared-dependencies.tpl}
</div>
