<h1><a class="pagetitle" href="tiki-list_faqs.php">{tr}FAQs{/tr}</a>

{if $prefs.feature_help eq 'y'}
<a href="{$prefs.helpurl}FAQs" target="tikihelp" class="tikihelp" title="{tr}FAQs{/tr}">
<img src="img/icons/help.gif" border="0" height="16" width="16" alt='{tr}Help{/tr}' /></a>{/if}
{if $prefs.feature_view_tpl eq 'y'}
<a href="tiki-edit_templates.php?template=tiki-list_faqs.tpl" target="tikihelp" class="tikihelp" title="{tr}View tpl{/tr}: {tr}List FAQs Tpl{/tr}">
<img src="img/icons/info.gif" border="0" height="16" width="16" alt='{tr}Edit Tpl{/tr}' /></a>
{/if}
{if $tiki_p_admin eq 'y'}
<a href="tiki-admin.php?page=faqs" title="{tr}Admin Feature{/tr}">{icon _id='wrench' alt="{tr}Admin Feature{/tr}"}</a>
{/if}
</h1>

{if $tiki_p_admin_faqs eq 'y'}
{if $faqId > 0}
<h2>{tr}Edit this FAQ:{/tr} {$title}</h2>
<a href="tiki-list_faqs.php" class="linkbut">{tr}Create new FAQ{/tr}</a>
{else}
<h2>{tr}Create New FAQ:{/tr}</h2>
{/if}
<form action="tiki-list_faqs.php" method="post">
<input type="hidden" name="faqId" value="{$faqId|escape}" />
<table class="normal">
<tr><td class="formcolor">{tr}Title{/tr}:</td><td class="formcolor"><input type="text" name="title" value="{$title|escape}" /></td></tr>
<tr><td class="formcolor">{tr}Description{/tr}:</td><td class="formcolor"><textarea name="description" rows="4" cols="40">{$description|escape}</textarea></td></tr>
{include file=categorize.tpl}
<tr><td class="formcolor">{tr}Users can suggest questions{/tr}:</td><td class="formcolor"><input type="checkbox" name="canSuggest" {if $canSuggest eq 'y'}checked="checked"{/if} /></td></tr>
<tr><td  class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" name="save" value="{tr}Save{/tr}" /></td></tr>
</table>
</form>
{/if}
<h2>{tr}Available FAQs{/tr}</h2>
<div align="center">
{if $channels or ($find ne '')}
  {include file='find.tpl' _sort_mode='y'}
{/if}
<br />
<table class="normal">
<tr>
<th class="heading"><a class="tableheading" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'title_desc'}title_asc{else}title_desc{/if}">{tr}Title{/tr}</a></th>
<th class="heading"><a class="tableheading" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'description_desc'}description_asc{else}description_desc{/if}">{tr}Description{/tr}</a></th>
<!--<th class="heading"><a class="tableheading" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Created{/tr}</a></th>-->
<th style="text-align:right;" class="heading"><a class="tableheading" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}Visits{/tr}</a></th>
<th style="text-align:right;" class="heading"><a class="tableheading" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'questions_desc'}questions_asc{else}questions_desc{/if}">{tr}Questions{/tr}</a></th>
{if $tiki_p_admin_faqs eq 'y'}
<th class="heading">{tr}Action{/tr}</th>
{/if}
</tr>
{cycle values="odd,even" print=false}
{section name=user loop=$channels}
<tr>
<td class="{cycle advance=false}"><a class="tablename" href="tiki-view_faq.php?faqId={$channels[user].faqId}">{$channels[user].title}</a></td>
<td class="{cycle advance=false}">{$channels[user].description}</td>
<!--<td class="{cycle advance=false}">{$channels[user].created|tiki_short_datetime}</td>-->
<td style="text-align:right;" class="{cycle advance=false}">{$channels[user].hits}</td>
<td style="text-align:right;"  class="{cycle advance=false}">{$channels[user].questions} ({$channels[user].suggested})</td>
{if $tiki_p_admin_faqs eq 'y'}
<td  class="{cycle}">
   <a class="link" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;faqId={$channels[user].faqId}">{icon _id='page_edit'}</a>
   <a class="link" href="tiki-faq_questions.php?faqId={$channels[user].faqId}">{icon _id='help' alt='{tr}Questions{/tr}'}</a>
   &nbsp;&nbsp;<a class="link" href="tiki-list_faqs.php?offset={$offset}&amp;sort_mode={$sort_mode}&amp;remove={$channels[user].faqId}">{icon _id='cross' alt='{tr}Remove{/tr}'}</a>
</td>
{/if}
</tr>
{sectionelse}
<tr><td class="odd" colspan="{if $tiki_p_admin_faqs eq 'y'}5{else}4{/if}"><strong>{tr}No records found.{/tr}</strong><td></tr>
{/section}
</table>

{pagination_links cant=$cant step=$maxRecords offset=$offset}{/pagination_links}

</div>
