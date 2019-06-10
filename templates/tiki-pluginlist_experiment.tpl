{* $Id$ *}
{extends 'layout_edit.tpl'}
{title admpage="wiki" help="Using Wiki Pages#List_Pages"}{tr}Pages{/tr}{/title}

{block name=content}
	{if $tiki_p_edit == 'y'}
		{title help="Forums" url='./'}{tr}Experiment with plugin LIST{/tr}{/title}
		<form method="post" class="form-horizontal">
			<div class="form-group">
				<label for="comment">Plugin LIST content:</label>
				<textarea class="form-control" rows="5" name="editwiki" id="editwiki">{$listtext}</textarea>
			</div>
			<div class="col-sm-9">
				<input class="btn btn-primary " type="submit" name="quickedit" value="{tr}Test Plugin LIST{/tr}">
			</div>
		</form>
		<div class="row">
			<div class="col-sm-12">
				<hr>
				<div class="preview_contents">
					{$listparsed}
				</div>
				<hr>
			</div>
		</div>
	{/if}
{/block}
