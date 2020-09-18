{extends "layout_view.tpl"}

{block name="title"}
	{title}{$title}{/title}
{/block}

{block name="content"}
	<form method="post" action="{service controller=ml action=model_args}">
		<input type="hidden" name="class" value="{$class|escape}">
		{foreach $args as $arg}
		<div class="form-group row">
			<label class="col-form-label col-sm-4">{$arg.name|escape} ({$arg.arg_type})</label>
			<div class="col-sm-8">
				{if $arg.input_type eq 'text'}
					<input class="form-control" type="text" name="args[{$arg.name|escape}]" value="{$arg.value|escape}">
				{elseif $arg.input_type eq 'tokenizer'}
					<select class="form-control " name="args[{$arg.name|escape}]">
						{foreach $tokenizers.classes as $tokenizer}
							<option value="{$tokenizers.path}\{$tokenizer|escape}" {if $arg.value eq $tokenizers.path|cat:'\\'|cat:$tokenizer}selected{/if}>{$tokenizer|escape}</option>
						{/foreach}
					</select>
				{else}
					TODO
				{/if}
			</div>
		</div>
		{foreachelse}
		<p>No arguments available.</p>
		{/foreach}
		<div class="form-group submit">
			<div class="col-sm-9 offset-sm-3">
				<input type="submit" class="btn btn-primary" value="{tr}Submit{/tr}">
			</div>
		</div>
	</form>
{/block}
