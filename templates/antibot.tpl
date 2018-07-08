{* $Id$ *}
{if empty($user) || $user eq 'anonymous' || !empty($showantibot)}
	{$labelclass = 'col-md-3'}
	{$inputclass = 'col-md-9'}
	{$captchaclass = 'col-md-4 col-md-offset-3 margin-bottom-sm'}
	{if $form === 'register'}
		{$labelclass = 'col-sm-4'}
		{$inputclass = 'col-sm-8'}
		{$captchaclass = 'col-sm-4 col-sm-offset-4 margin-bottom-sm'}
	{/if}
	{if $form === 'moduleSubscribeNL'}
		{$labelclass = 'col-md-12'}
		{$inputclass = 'col-md-12'}
		{$captchaclass = 'col-md-12 margin-bottom-sm'}
	{/if}
	<div class="antibot">
		{if $captchalib->type eq 'recaptcha' || $captchalib->type eq 'recaptcha20'}
			<div class="form-group clearfix">
				<div class="{$captchaclass}">
					{$captchalib->render()}
				</div>
			</div>
		{elseif $captchalib->type eq 'questions'}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<div class="form-group row">
				<label class="{$labelclass} control-label">
					{$captchalib->render()}
					{if $showmandatory eq 'y' && $form ne 'register'} <strong class='mandatory_star text-danger tips' title=":{tr}This field is mandatory{/tr}">*</strong>{/if}
				</label>
				<div class="{if !empty($inputclass)}{$inputclass}{else}col-md-8 col-sm-9{/if}">
					<input class="form-control" type="text" maxlength="8" name="captcha[input]" id="antibotcode">
				</div>
			</div>
		{else}
			{* Default captcha *}
			<input type="hidden" name="captcha[id]" id="captchaId" value="{$captchalib->generate()}">
			<div class="form-group row">
				<label class="control-label {$labelclass}" for="antibotcode">{tr}Enter the code below{/tr}{if $showmandatory eq 'y' && $form ne 'register'}<strong class="mandatory_star text-danger tips' title=":{tr}This field is mandatory{/tr}""> *</strong>{/if}</label>
				<div class="{if !empty($inputclass)}{$inputclass}{else}col-md-8 col-sm-9{/if}">
					<input class="form-control" type="text" maxlength="8" name="captcha[input]" id="antibotcode">
				</div>
			</div>
			<div class="clearfix visible-md-block"></div>
			<div class="form-group row">
				<div class="{$captchaclass}">
					{if $captchalib->type eq 'default'}
						<img id="captchaImg" src="{$captchalib->getPath()}" alt="{tr}Anti-Bot verification code image{/tr}" height="50">
					{else}
						{* dumb captcha *}
						{$captchalib->render()}
					{/if}
				</div>
				{if $captchalib->type eq 'default'}
					<div class="col-sm-3">
						{button _id='captchaRegenerate' _class='' href='#antibot' _text="{tr}Try another code{/tr}" _icon_name="refresh" _onclick="generateCaptcha();return false;"}
					</div>
				{/if}
			</div>
		{/if}
	</div>

	{jq rank=1}
		function antibotVerification(element, rule) {
			if (!jqueryTiki.validate) return;

			var form = $(".antibot").parents('form');
			if (!form.data("validator")) {
				form.validate({});
			}
			element.rules( "add", rule);
		}
	{/jq}

	{if $captchalib->type eq 'recaptcha'}
		{jq rank=1}
			var existCondition = setInterval(function() {
				if ($('#recaptcha_response_field').length) {
					clearInterval(existCondition);
					antibotVerification($("#recaptcha_response_field"), {required: true});
				}
			}, 100); // wait for captcha to load

		{/jq}
	{elseif $captchalib->type eq 'recaptcha20'}
		{jq rank=1}
			var existCondition = setInterval(function() {
				if ($('#g-recaptcha-response').length) {
					clearInterval(existCondition);
					antibotVerification($("#g-recaptcha-response"), {required: true});
				}
			}, 100); // wait for captcha to load
		{/jq}
	{else}
		{jq rank=1}
			antibotVerification($("#antibotcode"), {
				required: true
			});
		{/jq}
	{/if}

{/if}
