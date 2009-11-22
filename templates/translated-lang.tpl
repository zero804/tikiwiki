{* displays a cell with the languages of the translation set *}
	{if isset($trads) && (count($trads) > 1 || $trads[0].langName)}
		{if $td eq 'y'}<td style="vertical-align:top;text-align: left; width:42px;">{/if}
		{if isset($verbose) && $verbose eq 'y'}{tr}The main text of this page is available in the following languages{/tr}:{/if}
			{if isset($type) && $type == 'article'}
				<form action="tiki-read_article.php" method="get">
				<select name="articleId" onchange="this.form.submit()">
					{section name=i loop=$trads}
					<option value="{$trads[i].objId}">{$trads[i].langName}</option>
					{/section}
				</select>
				</form>
			{else} {* get method to have the param in the url *}
				<script type="text/javascript">
				<!--//--><![CDATA[//><!--
				{if $beingStaged == 'y'}
					var page_to_translate = "{$approvedPageName}";
				{else}
					var page_to_translate = "{$page}";
				{/if}
				{literal}
				function quick_switch_language( element )
				{
					var index = element.selectedIndex;
					var option = element.options[index];

					if( option.value == "-" )
						return;
					else if( option.value == "_translate_" ) {
						element.form.action = "tiki-edit_translation.php";
						element.value = page_to_translate;					
						element.form.submit();
					} else if( option.value == "_all_" ) {
						element.form.action = "tiki-all_languages.php";
						element.value = page_to_translate;
						element.form.submit();
				 	} else if (option.text.charAt(option.text.length - 1) == "*") {
				 		element.form.machine_translate_to_lang.value = element.form.page.options[element.form.page.selectedIndex].value;
						element.value = page_to_translate;
						element.form.submit();				 		
					} else
						element.form.submit();
				}
				{/literal}
				//--><!]]>
				</script>
				<form action="tiki-index.php" method="get">
				{if $prefs.feature_machine_translation eq 'y'}
				<input type="hidden" name="machine_translate_to_lang" value="" />
				{/if}
				<select name="page" onchange="quick_switch_language( this )">
					{if $prefs.feature_machine_translation eq 'y'}
					<option value="Human Translations" disabled="disabled" style="color:black;font-weight:bold">Human Translations</option>
					{/if}
					{section name=i loop=$trads}
					<option value="{$trads[i].objName|escape}">{$trads[i].langName}</option>
					{/section}
					{if $prefs.feature_machine_translation eq 'y'}
					<option value="Machine Translations" disabled="disabled" style="color:black;font-weight:bold">Machine Translations</option>
					{section name=i loop=$langsCandidatesForMachineTranslation}
					<option value="{$langsCandidatesForMachineTranslation[i].lang}">{$langsCandidatesForMachineTranslation[i].langName} *</option>
					{/section}
					{/if}
					{if $prefs.feature_multilingual_one_page eq 'y'}
					<option value="-">---</option>
					<option value="_all_"{if basename($smarty.server.PHP_SELF) eq 'tiki-all_languages.php'} selected="selected"{/if}>{tr}All{/tr}</option>
					{/if}
					{if $tiki_p_edit eq 'y'}
					<option value="-">---</option>
					<option value="_translate_">{tr}Translate{/tr}</option>
					{/if}
				</select>
				</form>
			{/if}

		{if $td eq 'y'}</td>{/if}
	{/if}
