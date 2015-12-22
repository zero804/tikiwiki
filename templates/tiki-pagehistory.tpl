{* $Id$ *}

{title admpage="wiki"}{tr}History:{/tr} {$page}{/title}

<div class="t_navbar margin-bottom-md">
	{assign var=thispage value=$page|escape:url}
	{button href="tiki-index.php?page=$thispage" class="btn btn-default" _text="{tr}View page{/tr}"}
	{if !isset($noHistory)}
		{if $show_all_versions eq "y"}
			{button _text="{tr}Show Edit Sessions{/tr}" show_all_versions="n" href="?clear_versions=1" _auto_args="*" class="btn btn-default"}
		{else}
			{button _text="{tr}Show All Versions{/tr}" show_all_versions="y" href="?clear_versions=1" _auto_args="*" class="btn btn-default"}
		{/if}
	{/if}
</div>

{if $preview}
	<h2>{tr}Preview of version:{/tr} {$preview}
		{if $info.version eq $preview}<small><small>{tr}(current){/tr}</small></small>{/if}
	</h2>
	{if $info.version ne $preview and $tiki_p_rollback eq 'y'}
		<div class="navbar">
			{self_link _script="tiki-rollback.php" page=$page version=$preview _title="{tr}Rollback{/tr}"}{tr}Rollback to this version{/tr}{/self_link}
		</div>
	{/if}
	<div>
		{if !isset($noHistory)}
			{if isset($show_all_versions) and $show_all_versions eq "n"}
				{pagination_links cant=$ver_cant offset=$smarty.request.preview_idx offset_arg="preview_idx" itemname="{tr}Session{/tr}" show_numbers="n"}{/pagination_links}
			{else}
				{pagination_links cant=$ver_cant offset=$smarty.request.preview_idx offset_arg="preview_idx" itemname="{tr}Version{/tr}" show_numbers="n" _keepall="n"}{/pagination_links}
			{/if}
		{/if}
	</div>
	{if (isset($flaggedrev_approval) and $flaggedrev_approval) and $tiki_p_wiki_approve eq 'y'}
		{remarksbox type=comment title="{tr}Content Approval{/tr}"}
			<form method="post" action="tiki-pagehistory.php?page={$page|escape:'url'}&amp;preview={$preview|escape:'url'}">
				{if $flaggedrev_preview_approved}
					<p>{tr}This revision is currently marked as approved.{/tr}<p>
					<div class="submit">
						<input type="hidden" name="unapprove" value="{$preview|escape}">
						<input type="submit" class="btn btn-default btn-sm" name="flaggedrev" value="{tr}Remove Approval{/tr}">
					</div>
				{else}
					<p>{tr}This revision has not been approved.{/tr}<p>
					<div class="submit">
						<input type="hidden" name="approve" value="{$preview|escape}">
						<input type="submit" class="btn btn-default btn-sm" name="flaggedrev" value="{tr}Approve Revision{/tr}">
					</div>
				{/if}
			</form>
		{/remarksbox}
	{/if}
	<div class="wikitext" id="page-data">
		{$previewd}
	</div>
{/if}

{if $source}
	<h2>
		{tr}Source of version:{/tr} {$source}
		{if $info.version eq $source}<small><small>{tr}(current){/tr}</small></small>{/if}
	</h2>
	{if $info.version ne $source and $tiki_p_rollback eq 'y'}
		<div class="navbar">{self_link _script="tiki-rollback.php" page=$page version=$source _title="{tr}Rollback{/tr}"}{tr}Rollback to this version{/tr}{/self_link}</div>
	{/if}
	<div>
		{if !isset($noHistory)}
			{if isset($show_all_versions) and $show_all_versions eq "n"}
				{pagination_links cant=$ver_cant offset=$smarty.request.source_idx offset_arg="source_idx" itemname="{tr}Session{/tr}" show_numbers="n"}{/pagination_links}
			{else}
				{pagination_links cant=$ver_cant offset=$smarty.request.source_idx offset_arg="source_idx" itemname="{tr}Version{/tr}" show_numbers="n" _keepall="n"}{/pagination_links}
			{/if}
		{/if}
	</div>
	<textarea data-codemirror="true" data-syntax='tiki' class="wikiedit readonly" style="width:100%;height:400px" readonly="readonly" id="page_source">{$sourced|escape}</textarea>
	{if $prefs.feature_jquery_ui eq "y" && $prefs.feature_syntax_highlighter neq "y"}{jq}$("#page_source").resizable();{/jq}{/if}
{/if}

{if (isset($flaggedrev_approval) and $flaggedrev_approval) and $tiki_p_wiki_approve eq 'y' and $flaggedrev_compare_approve}
	{remarksbox type=comment title="{tr}Content Approval{/tr}"}
		<form method="post" action="tiki-pagehistory.php?page={$page|escape:'url'}&amp;preview={$new.version|escape:'url'}">
			<p>{tr}This revision has not been approved.{/tr}<p>
			<div class="submit">
				<input type="hidden" name="approve" value="{$new.version|escape}">
				<input type="submit" class="btn btn-default btn-sm" name="flaggedrev" value="{tr}Approve Revision{/tr}">
			</div>
		</form>
	{/remarksbox}
{/if}

{include file='pagehistory.tpl'}

<hr style="clear: both;"/>

{if !isset($noHistory)}
	{if $preview || $source || $diff_style}<h2>{tr}History{/tr}</h2>{/if}
	<form id="pagehistory" class="form-horizontal" action="tiki-pagehistory.php?page={$page}" method="post">
		<input type="hidden" name="page" value="{$page|escape}">
		<input type="hidden" name="history_offset" value="{$history_offset}">
		<div class="clearfix">
			{if ($prefs.default_wiki_diff_style ne "old") and $history}
				<div class="input-group input-group-sm col-sm-5 pull-right">
					<select class="form-control" name="diff_style" id="diff_style_all"{if $prefs.javascript_enabled eq "y"} style="display: none"{/if}>
						<option value="htmldiff" {if $diff_style == "htmldiff"}selected="selected"{/if}>
							{tr}HTML diff{/tr}
						</option>
						<option value="sidediff" {if $diff_style == "sidediff"}selected="selected"{/if}>
							{tr}Side-by-side diff{/tr}
						</option>
						<option value="sidediff-char" {if $diff_style == "sidediff-char"}selected="selected"{/if}>
							{tr}Side-by-side diff by characters{/tr}
						</option>
						<option value="inlinediff" {if $diff_style == "inlinediff"}selected="selected"{/if}>
							{tr}Inline diff{/tr}
						</option>
						<option value="inlinediff-char" {if $diff_style == "inlinediff-char"}selected="selected"{/if}>
							{tr}Inline diff by characters{/tr}
						</option>
						<option value="sidediff-full" {if $diff_style == "sidediff-full"}selected="selected"{/if}>
							{tr}Full side-by-side diff{/tr}
						</option>
						<option value="sidediff-full-char" {if $diff_style == "sidediff-full-char"}selected="selected"{/if}>
							{tr}Full side-by-side diff by characters{/tr}
						</option>
						<option value="inlinediff-full" {if $diff_style == "inlinediff-full"}selected="selected"{/if}>
							{tr}Full inline diff{/tr}
						</option>
						<option value="inlinediff-full-char" {if $diff_style == "inlinediff-full-char"}selected="selected"{/if}>
							{tr}Full inline diff by characters{/tr}
						</option>
						<option value="unidiff" {if $diff_style == "unidiff"}selected="selected"{/if}>
							{tr}Unified diff{/tr}
						</option>
						<option value="sideview" {if $diff_style == "sideview"}selected="selected"{/if}>
							{tr}Side-by-side view{/tr}
						</option>
					</select>
					{if $prefs.javascript_enabled eq "y"}
						<select class="form-control" name="diff_style" id="diff_style_simple">
							<option value="htmldiff" {if $diff_style == "htmldiff"}selected="selected"{/if}>
								{tr}HTML diff{/tr}
							</option>
							<option value="sidediff" {if $diff_style == "sidediff"}selected="selected"{/if}>
								{tr}Side-by-side diff{/tr}
							</option>
						</select>
					{/if}
					{if $prefs.javascript_enabled eq "y"}
						<span class="input-group-btn">
							{button _text="{tr}Advanced{/tr}" _id="toggle_diffs" _ajax="n"}
						</span>
						{jq}
	$("a#toggle_diffs").click(function(e){
		e.preventDefault();
		if ($(this).text() == "{tr}Advanced{/tr}") {
			$(this).text("{tr}Simple{/tr}");
			if (jqueryTiki.chosen) {
				$("#diff_style_all").next(".chosen-container").show();
				$("#diff_style_simple").next(".chosen-container").hide();
				$("#diff_style_all").attr("name", "diff_style");
				$("#diff_style_simple").attr("name", "");
			} else {
				$("#diff_style_all").show().attr("name", "diff_style");
				$("#diff_style_simple").hide().attr("name", "");
			}
		} else {
			$(this).text("{tr}Advanced{/tr}");
			if (jqueryTiki.chosen) {
				$("#diff_style_all").next(".chosen-container").hide();
				$("#diff_style_simple").next(".chosen-container").show();
				$("#diff_style_all").attr("name", "");
				$("#diff_style_simple").attr("name", "diff_style");
			} else {
				$("#diff_style_all").hide().attr("name", "");
				$("#diff_style_simple").show().attr("name", "diff_style");
			}
		}
		return false;
	});
	if (jqueryTiki.chosen) {
		if ($("#diff_style_simple").html().indexOf("{{$diff_style}}") > -1) {
			$("#diff_style_all").next(".chosen-container").hide().attr("name", "");
		} else {
			$("#diff_style_simple").next(".chosen-container").hide();
		}
	}
	{{if $diff_style neq "htmldiff" and $diff_style neq "sidediff"}$("#toggle_diffs a").click();{/if}}
						{/jq}
					{/if}
				</div>
				<input type="hidden" name="show_all_versions" value="{$show_all_versions}">
			{/if}
			<div class="table-responsive">
				<table class="table table-condensed table-hover">
					<tr>
						{if $tiki_p_remove eq 'y'}<th><input type="submit" class="btn btn-warning btn-sm" name="delete" value="{tr}Delete{/tr}"></th>{/if}
						<th>{tr}Information{/tr}</th>
						{if $prefs.feature_contribution eq 'y'}<th>{tr}Contribution{/tr}</th>{/if}
						{if $prefs.feature_contribution eq 'y' and $prefs.feature_contributor_wiki eq 'y'}<th>{tr}Contributors{/tr}</th>{/if}
						<th>{tr}Version{/tr}</th>
						<th>{icon name="pencil" iclass="tips" ititle=":{tr}HTML or WYSIWYG{/tr}"}</th>
						<th>{tr}Action{/tr}</th>
						{if $prefs.default_wiki_diff_style != "old" and $history}
							<th colspan="2">
								<input type="submit" class="btn btn-default btn-sm" name="compare" value="{tr}Compare{/tr}">
							</th>
						{/if}
					</tr>
					<tr class="active">
						{if $history_offset eq 1}
							{if $tiki_p_remove eq 'y'}
								<td>&nbsp;</td>
							{/if}
							<td class="text-left">
								{$info.lastModif|tiki_short_datetime}
								{icon name="user"}{$info.user|userlink}
								{if $prefs.feature_wiki_history_ip ne 'n'}{tr _0=$info.ip}from %0{/tr}{/if}

								{if (isset($flaggedrev_approval) and $flaggedrev_approval) and $tiki_p_wiki_view_latest eq 'y'
									and $info.approved}<strong>({tr}approved{/tr})</strong>{/if}

								{if $info.comment}<div>{$info.comment|escape}</div>{/if}

								{if isset($translation_sources[$info.version]) and $translation_sources[$info.version]}
									{foreach item=source from=$translation_sources[$info.version]}
										<div>
											{tr}Updated from:{/tr} {self_link _script="tiki-index.php" page=$source.page|escape}{$source.page}{/self_link} at version {$source.version}
										</div>
									{/foreach}
								{/if}
								{if isset($translation_targets[$info.version]) and $translation_targets[$info.version]}
									{foreach item=target from=$translation_targets[$info.version]}
									<div>
										{tr}Used to update:{/tr} {self_link _script="tiki-index.php" page=$target.page|escape}{$target.page}{/self_link} to version {$target.version}
									</div>
									{/foreach}
								{/if}
							</td>
							{if $prefs.feature_contribution eq 'y'}
								<td>{section name=ix loop=$contributions}{if !$smarty.section.ix.first},{/if}{$contributions[ix].name|escape}{/section}</td>
							{/if}
							{if $prefs.feature_contribution eq 'y' and $prefs.feature_contributor_wiki eq 'y'}
								<td>
									{section name=ix loop=$contributors}{if !$smarty.section.ix.first},{/if}{$contributors[ix].login|username}{/section}
								</td>
							{/if}
							<td class="button_container">
								{if $current eq $info.version}
									<strong>{/if}{$info.version}<br>{tr}Current{/tr}{if $current eq $info.version}</strong>
								{/if}
							</td>
							<td class="button_container">
								{if $info.is_html}
									{icon name='html' iclass='tips' ititle='HTML'}
								{elseif $info.wysiwyg eq "y"}
									{icon name='wysiwyg' iclass='tips' ititle=":{tr}Wiki Wysiwyg{/tr}"}
								{/if}
							</td>
							<td class="button_container" style="white-space: nowrap">
								{self_link page=$page preview=$info.version _title=":{tr}View{/tr}" _class="tips" _icon_name="view"}v{/self_link}
							{if $tiki_p_wiki_view_source eq "y" and $prefs.feature_source eq "y"}
								{self_link page=$page source=$info.version _title=":{tr}Source{/tr}" _class="tips" _icon_name="code"}{/self_link}
							{/if}
							</td>
							{if $prefs.default_wiki_diff_style ne "old" and $history}
								<td class="button_container">
									<input type="radio" name="oldver" value="0" title="{tr}Compare{/tr}" {if isset($old.version)
										and $old.version == $info.version}checked="checked"{/if}>
								</td>
								<td class="button_container">
									<input type="radio" name="newver" value="0" title="{tr}Compare{/tr}" {if (isset($new.version)
										and $new.version == $info.version) or (!isset($smarty.request.diff_style)
										or !$smarty.request.diff_style)}checked="checked"{/if}>
								</td>
							{/if}
						{/if}
					</tr>

					{foreach name=hist item=element from=$history}
						<tr>
							{if $tiki_p_remove eq 'y'}
								<td class="button_container"><input type="checkbox" name="hist[{$element.version}]"></td>
							{/if}
							<td class="text-left">
								{$element.lastModif|tiki_short_datetime}
								{icon name="user"}{$element.user|userlink}
								{if $prefs.feature_wiki_history_ip ne 'n'}{tr _0=$element.ip}from %0{/tr}{/if}

								{if $element.comment}<span class="help-block">{$element.comment|escape}</span>{/if}

								{if (isset($flaggedrev_approval) and $flaggedrev_approval) and $tiki_p_wiki_view_latest eq 'y' and $element.approved}<strong>({tr}approved{/tr})</strong>{/if}

								{if isset($translation_sources[$element.version]) and $translation_sources[$element.version]}
									{foreach item=source from=$translation_sources[$element.version]}
									<div>
										{tr}Updated from:{/tr} {self_link _script="tiki-index.php" page=$source.page|escape}{$source.page}{/self_link} at version {$source.version}
									</div>
									{/foreach}
								{/if}
								{if isset($translation_targets[$element.version]) and $translation_targets[$element.version]}
									{foreach item=target from=$translation_targets[$element.version]}
									<div>
										{tr}Used to update:{/tr} {self_link _script="tiki-index.php" page=$target.page|escape}{$target.page}{/self_link} to version {$target.version}
									</div>
									{/foreach}
								{/if}
							</td>
							{if $prefs.feature_contribution eq 'y'}
								<td>
									{section name=ix loop=$element.contributions}{if !$smarty.section.ix.first}&nbsp;{/if}{$element.contributions[ix].name|escape}{/section}
								</td>
							{/if}
							{if $prefs.feature_contribution eq 'y' and $prefs.feature_contributor_wiki eq 'y'}
								<td>
									{section name=ix loop=$element.contributors}{if !$smarty.section.ix.first},{/if}{$element.contributors[ix].login|username}{/section}
								</td>
							{/if}
							<td class="button_container">
								{if $current eq $element.version}<strong>{/if}
								{if $show_all_versions eq "n" and not empty($element.session)}
									<em>{$element.session} - {$element.version}</em>
								{else}
									{$element.version}
								{/if}
								{if $current eq $element.version}</strong>{/if}
							</td>
							<td class="button_container">
								{if $element.is_html eq "1"}
									{icon name='html' iclass='tips' ititle='HTML'}
								{/if}
							</td>
							<td class="button_container" style="white-space: nowrap">
								{self_link page=$page preview=$element.version _title=":{tr}View{/tr}" _class="tips" _icon_name="view"}{/self_link}
								{if $tiki_p_wiki_view_source eq "y" and $prefs.feature_source eq "y"}
									{self_link page=$page source=$element.version _title=":{tr}Source{/tr}" _class="tips" _icon_name="code"}{/self_link}
								{/if}
								{if $prefs.default_wiki_diff_style eq "old"}
									&nbsp;{self_link page=$page diff2=$element.version diff_style="sideview" _title="{tr}Compare{/tr}"}c{/self_link}
									&nbsp;{self_link page=$page diff2=$element.version diff_style="unidiff" _title="{tr}Diff{/tr}"}d{/self_link}
								{/if}
								{if $tiki_p_rollback eq 'y' && $lock neq true}
									{self_link _script="tiki-rollback.php" page=$page version=$element.version _title=":{tr}Rollback{/tr}" _class="tips" _icon_name="back"}{/self_link}
								{/if}
							</td>
							{if $prefs.default_wiki_diff_style ne "old"}
							<td class="button_container">
								{if $show_all_versions eq 'n' and not empty($element.session)}
									<input type="radio" name="oldver" value="{$element.session}"
										title="{tr}Older Version{/tr}" {if (isset($old.version) and isset($element.session) and $old.version == $element.session)
										or ((!isset($smarty.request.diff_style) or !$smarty.request.diff_style)
										and $smarty.foreach.hist.first)}checked="checked"{/if}>
								{else}
									<input type="radio" name="oldver" value="{$element.version}"
										title="{tr}Older Version{/tr}" {if (isset($old.version) and isset($element.version) and $old.version == $element.version)
										or ((!isset($smarty.request.diff_style) or !$smarty.request.diff_style)
										and $smarty.foreach.hist.first)}checked="checked"{/if}>
								{/if}
							</td>
							<td class="button_container">
								{* if $smarty.foreach.hist.last &nbsp; *}
								<input type="radio" name="newver" value="{$element.version}" title="Select a newer version for comparison"
									{if isset($new.version) and $new.version == $element.version}checked="checked"{/if} >
							</td>
							{/if}
						</tr>
					{/foreach}
					{if $prefs.feature_multilingual eq 'y' and $tiki_p_edit eq 'y'}
						<tr>
							<td colspan="9">
								<div class="pull-left">
									<input type="checkbox" name="paginate" id="paginate"{if $paginate} checked="checked"{/if}>
									<label for="paginate">{tr}Enable pagination{/tr}</label>
									{if $paginate}
										<input type="text" name="history_pagesize" id="history_pagesize" value="{$history_pagesize}" size="5">
										<label for="history_pagesize">{tr}per page{/tr}</label>
									{/if}
								</div>
								<div class="form-inline pull-right">
									<div class="input-group input-group-sm">
										<span class="input-group-addon">
											{icon name="admin_i18n" class="tips" title=":{tr}Translation{/tr}"}
										</span>
										<select name="tra_lang" class="form-control">
											{section name=ix loop=$languages}
												<option value="{$languages[ix].value|escape}"{if $lang eq $languages[ix].value} selected="selected"{/if}>{$languages[ix].name}</option>
											{/section}
										</select>
										<div class="input-group-btn">
											<input type="submit" class="btn btn-primary btn-sm" name="update_translation" value="{tr}Update Translation{/tr}"/>
										</div>
									</div>
									{if $show_translation_history}
										<input type="hidden" name="show_translation_history" value="1">
										{button show_translation_history=0 _text="{tr}Hide translation history{/tr}" _auto_args="*" _class="btn btn-default btn-sm"}
									{else}
										{button show_translation_history=1 _text="{tr}Show translation history{/tr}" _auto_args="*" _class="btn btn-default btn-sm"}
									{/if}
								</div>
							</td>
						</tr>
					{/if}
					{if $paginate}
						<tr>
							<td colspan="9">
								<div class="text-center">
									{if isset($smarty.request.history_offset)}
										{pagination_links cant=$history_cant offset=$smarty.request.history_offset offset_arg="history_offset" step=$history_pagesize}{/pagination_links}
									{else}
										{pagination_links cant=$history_cant offset_arg="history_offset" step=$history_pagesize}{/pagination_links}
									{/if}
								</div>
							</td>
						</tr>
					{/if}
				</table>
			</div>
		</div>
	</form>
{/if}
{jq}
	$('input[name=compare], input[name=paginate]').click(function(){
		var values = [];
		var oldver= $('input[name=oldver]:checked').val();
		var newver= $('input[name=newver]:checked').val();
		if ($('#diff_style_all').is(':visible'))
			var diff_style = $('select[id=diff_style_all]').val();
		else
			var diff_style = $('select[id=diff_style_simple]').val();
		values.push("oldver="+oldver);
			if(newver!=0){values.push("newver="+newver);}
			if(diff_style!="sidediff"){values.push("diff_style="+diff_style);}
			document.getElementById('pagehistory').action += '&' + values.join('&');
			document.getElementById('pagehistory').submit();
	});
{/jq}
