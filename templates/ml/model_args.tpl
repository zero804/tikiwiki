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
				{elseif $arg.input_type eq 'rubix'}
					{if strstr($arg.arg_type,  'Tokenizers')}
						{assign var="classes" value=$tokenizers}
					{elseif strstr($arg.arg_type, 'Trees')}
						{assign var="classes" value=$trees}
					{elseif strstr($arg.arg_type, 'Kernels')}
						{assign var="classes" value=$kernels}
					{else}
						{assign var="classes" value=[]}
					{/if}
					{if $classes}
						<select class="form-control ml-class" name="args[{$arg.name|escape}][class]" data-path="{$arg.name|escape}" data-href="{service controller=ml action=model_args}">
							<option value=''>Default</option>
							{foreach $classes.classes as $tokenizer}
								<option value="{$classes.path}\{$tokenizer|escape}">{$tokenizer|escape}</option>
							{/foreach}
						</select>
					{else}
						<input class="form-control ml-class" type="text" name="args[{$arg.name|escape}][class]" data-path="{$arg.name|escape}" data-href="{service controller=ml action=model_args}">
					{/if}
					<textarea name="args[{$arg.name|escape}][args]" class="d-none">{$arg.args}</textarea>
				{else}
					{tr}Not Supported{/tr}
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
