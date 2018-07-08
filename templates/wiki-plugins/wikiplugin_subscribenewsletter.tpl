{* $Id$ *}
{if $wpSubscribe eq 'y'}
	{if empty($subscribeThanks)}
		{tr}Subscription confirmed!{/tr}
	{else}
		{$subscribeThanks|escape}
	{/if}
{else}
	<form name="wpSubscribeNL" method="post" {if $inmodule ne 'moduleSubscribeNL'}class="form-horizontal"{/if}>
		<input type="hidden" name="wpNlId" value="{$subscribeInfo.nlId|escape}">
		{if empty($user)}
			{if !empty($wpError)}
				{remarksbox type='errors'}
						{$wpError|escape}
				{/remarksbox}
			{/if}
			<div class="form-group row">
				<label class="{if $inmodule}col-md-12{else}col-md-3{/if} control-label" for="wpEmail">{tr}Email{/tr} <strong class='mandatory_star text-danger tips' title=":{tr}This field is mandatory{/tr}">*</strong></label>
				<div class="{if $inmodule}col-md-12{else}col-md-9{/if}">
					<input type="email" class="form-control" id="wpEmail" name="wpEmail" value="{$subscribeEmail|escape}">
				</div>
			</div>
		{/if}
		{if !$user and $prefs.feature_antibot eq 'y'}
			{include file='antibot.tpl' antibot_table="y" showmandatory="y" form="$inmodule"}
		{/if}
		<div class="form-group text-center">
			{if empty($subcribeMessage)}
				<input type="submit" class="btn btn-default" name="wpSubscribe" value="{tr}Subscribe to the newsletter:{/tr} {$subscribeInfo.name}">
			{else}
				<input type="submit" class="btn btn-default" name="wpSubscribe" value="{$subcribeMessage|escape}">
			{/if}
		</div>
	</form>
{/if}
