{* $Id: *}
{extends 'layout_view.tpl'}
{block name="title"}
	{title admpage="i18n"}{$title|escape}{/title}
{/block}
{block name="navigation"}
	{if $tiki_p_edit_languages}
		<div class="t_navbar mb-4 clearfix">
			<a class="btn btn-link tips" href="{service controller=language action=manage_custom_translations}" title="{tr}Customized String Translations{/tr}:{tr}Manage custom translations{/tr}">
				{icon name="file-code-o"} {tr}Custom Translations{/tr}
			</a>
			{if $prefs.lang_use_db eq "y"}
				{button _type="link" _class="tips" href="tiki-edit_languages.php" _icon_name="edit" _text="{tr}Edit languages{/tr}" _title="{tr}Edit languages{/tr}:{tr}Edit, export and import languages{/tr}"}
			{/if}
			{if $prefs.freetags_multilingual eq 'y'}
				{button _type="link" _class="tips" href="tiki-freetag_translate.php" _icon_name="tags" _text="{tr}Translate Tags{/tr}" _title=":{tr}Translate tags{/tr}"}
			{/if}
		</div>
	{/if}
{/block}
{block name="content"}
	<form action="{service controller=language action=upload}" method="post" role="form" class="form" enctype="multipart/form-data">
		<div class="form-group row">
			<label for="language" class="col-form-label">
				{tr}Language{/tr}
			</label>
			<select id="language" class="translation_action form-control" name="language">
				{section name=ix loop=$languages}
					<option value="{$languages[ix].value|escape}" {if $language eq $languages[ix].value}selected="selected"{/if}>{$languages[ix].name}</option>
				{/section}
			</select>
		</div>
		<div class="form-group row">
			<label class="col-form-label" for="file_type">
				{tr}Process Type{/tr}
			</label>
			<select name="process_type" class="translation_action form-control">
				{foreach from=$process_types key=type_key item=type_name}
					<option value="{$type_key|escape}">
						{$type_name}
					</option>
				{/foreach}
			</select>
			<span class="form-text">
				{tr}Select how the uploaded translations should be processed{/tr}
			</span>
		</div>
		<div class="form-group row">
			<label class="col-form-label" for="language_file">
				{tr}File{/tr}
			</label>
			<input name="language_file" type="file" required="required">
		</div>
		<div class="submit text-center">
			<input type="hidden" name="confirm" value="1">
			<input type="submit" class="btn btn-primary" name="upload_language_file" value="{tr}Upload{/tr}">
		</div>
	</form>
{/block}