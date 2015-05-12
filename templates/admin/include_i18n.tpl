{jq}
	function updateList( active )
	{
		if( ! active )
		{
			var optionList = document.getElementById( 'available_languages_select' ).options;
			for( i in optionList )
				optionList[i].selected = false;
		}
	}
{/jq}

<form action="tiki-admin.php?page=i18n" method="post">
	<input type="hidden" name="ticket" value="{$ticket|escape}">
    <div class="row">
        <div class="form-group col-lg-12 clearfix">
            <div class="pull-right">
                <input type="submit" class="btn btn-primary btn-sm" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>

<input type="hidden" name="i18nsetup" />
{tabset name="admin_i18n"}
	{tab name="{tr}Internationalization{/tr}"}
        <h2>{tr}Internationalization{/tr}</h2>

{preference name=language}
{preference name=wiki_page_regex}
{preference name=default_mail_charset}

<div class="adminoptionbox">
	{preference name=feature_multilingual visible="always"}
<div class="adminoptionboxchild" id="feature_multilingual_childcontainer">	

	{preference name=feature_detect_language}
	{preference name=feature_best_language}
	{preference name=change_language}
	{preference name=restrict_language}
	
	<div class="adminoptionboxchild" id="restrict_language_childcontainer">
		{preference name=available_languages}
		{preference name=language_inclusion_threshold}
	</div>

	{preference name=show_available_translations}
	{preference name=feature_sync_language}
	{preference name=search_default_interface_language}
	{preference name=feature_translation}
	{preference name=feature_urgent_translation}
	{preference name=feature_translation_incomplete_notice}
	{preference name=feature_multilingual_one_page}
	{preference name=quantify_changes}
	
	{preference name=wiki_edit_minor}
	
	{preference name=feature_user_watches_translations}
	
	{preference name=feature_multilingual_structures}
	{preference name=freetags_multilingual}
	{preference name=category_i18n_sync}
	<div class="adminoptionboxchild category_i18n_sync_childcontainer blacklist whitelist required">
		{preference name=category_i18n_synced}
	</div>
	{preference name=wiki_dynvar_multilingual}
	{preference name=wikiplugin_tr}
	{preference name=wikiplugin_lang}
	{preference name=wikiplugin_translated}	
</div>

{preference name=lang_use_db}

{preference name=record_untranslated}
	
{preference name=feature_machine_translation}
<div class="adminoptionboxchild" id="feature_machine_translation_childcontainer">
	{preference name=lang_machine_translate_implementation}
	<div class="adminoptionboxchild lang_machine_translate_implementation_childcontainer google">
		{preference name=lang_google_api_key}
	</div>
	<div class="adminoptionboxchild lang_machine_translate_implementation_childcontainer bing">
		{preference name=lang_bing_api_client_id}
		{preference name=lang_bing_api_client_secret}
	</div>
	{preference name=lang_machine_translate_wiki}
</div>
	{preference name=feature_lang_nonswitchingpages}
	<div class="adminoptionboxchild" id="feature_lang_nonswitchingpages_childcontainer">
		{preference name=feature_lang_nonswitchingpages_names}
	</div>
</div>
{/tab}

{tab name="{tr}Babelfish links{/tr}"}
{*------------------------------- Babelfish ----------------------------- *}
    <h2>{tr}Babelfish links{/tr}</h2>

{preference name=feature_babelfish}
{preference name=feature_babelfish_logo}

{/tab}
{tab name="{tr}Customized String Translation{/tr}"}
{*----------------------------------- Custom translation --------------------*}
    <h2>{tr}Customized String Translation{/tr}</h2>
<div class="adminoptionbox">
	{if empty($custom_lang)}
		<select name="custom_lang" id="custom_lang_select">
			{section name=ix loop=$languages}
				<option value="{$languages[ix].value|escape}"
					{if (empty($custom_lang) && $languages[ix].value eq $prefs.site_language) || (!empty($custom_lang) && $languages[ix].value eq $custom_lang)} selected="selected"{/if}>
					{$languages[ix].name|escape}
				</option>
			{/section}
		</select>
		<input type="submit" class="btn btn-default btn-sm" name="custom" value="{tr}Edit{/tr}" />
	{else}
		{if !empty($custom_error)}
			{remarksbox title="{tr}Error{/tr}" type="error"}
				{if $custom_error eq 'param'}
					{tr}Incorrect param{/tr}
				{elseif $custom_error eq 'parse'}
					{tr}Syntax error{/tr}
				{else}
					{tr}Cannot open/write this file:{/tr} {$custom_file}. {tr}Custom translation will not be saved. Ask your administration to change the permission.{/tr}
				{/if}
			{/remarksbox}
		{/if}
		{if !empty($custom_ok)}
			{remarksbox title="{tr}ok{/tr}"}
				{tr}The file has been saved{/tr}
			{/remarksbox}
		{/if}
		<h2>
		{section name=ix loop=$languages}
			{if $languages[ix].value eq $custom_lang}{$languages[ix].name|escape}{/if}
		{/section}
		</h2>
		<input type="hidden" name="custom_lang" value="{$custom_lang|escape}" />
        <div class="table-responsive">
		<table class="table normal">
		<tr><th>{tr}English{/tr}</th><th>{tr}Translation{/tr}</th></tr>
		{if !empty($custom_translation)}
			{foreach from=$custom_translation key=cfrom item=cto}
				<tr><td><input type="text" name="from[]" value="{$cfrom|escape}"/></td><td><input type="text" name="to[]" value="{$cto|escape}"/></td></tr>
			{/foreach}
		{/if}
		{foreach from=$from key=i item=fr}
			<tr><td><input type="text" name="from[]" value="{$fr|escape}"/></td><td><input type="text" name="to[]" value="{$to.$i|escape}"/></td></tr>
		{/foreach}
		</table>
        </div>
		<input type="submit" class="btn btn-default btn-sm" name="custom_save" value="{tr}Save{/tr}" />
	{/if}
</div>
{/tab}
{/tabset}
    <br>{* I cheated. *}
    <div class="row">
        <div class="form-group col-lg-12 clearfix">
            <div class="text-center">
                <input type="submit" class="btn btn-primary btn-sm" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>
</form>
