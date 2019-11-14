<div class="form-row align-items-center">
	{*prepend*}
	{if $field.options_map.prepend}
	<div class="col-auto">
			<span class="formunit">{$field.options_map.prepend|escape}&nbsp;</span>
	</div>
		{/if}
	<div class="col-auto"> {* Prevent input from overflowing in narrow screens *}
		<input type="number" class="currency_number  numeric form-control" name="{$field.ins_id|escape}"
	{if $field.options_map.size}size="{$field.options_map.size|escape}" maxlength="{$field.options_map.size|escape}"{/if}
	value="{$field.amount|escape}" id="{$field.ins_id|escape}">
	</div>

	<div class="col-auto">
		{if $data.currencies}
			<select name="{$field.ins_id|escape}_currency" id="{$field.ins_id|escape}_currency" class="form-control">
			  <option value=""></option>
				{foreach from=$data.currencies item=c}
					<option value="{$c}" {if $c eq $field.currency}selected{/if}>{$c}</option>
				{/foreach}
			</select>
		{/if}

	{if $data.error}
	  {$data.error}
	{/if}
	</div>
{*append*}
	{if $field.options_map.append}
		<div class="col-auto">
		<span class="formunit">&nbsp;{$field.options_map.append|escape}</span>
		</div>
	{/if}
</div>
