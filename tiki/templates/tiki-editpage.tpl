{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-editpage.tpl,v 1.27 2003-10-25 01:18:14 zaufi Exp $ *}

{popup_init src="lib/overlib.js"}

<!--Check to see if there is an editing conflict-->
{if $editpageconflict == 'y'}
<script language='Javascript' type='text/javascript'>
<!-- Hide Script
	alert('{tr}This page is being edited by{/tr} {$semUser}. {tr}Proceed at your own peril{/tr}.')
//End Hide Script-->
</script>
{/if}

<!--<a {popup sticky="true" trigger="onClick" caption="Special characters help" text="kj"}>foo</a><br />-->
{if $preview}
{include file="tiki-preview.tpl"}
{/if}
<h1>{tr}Edit{/tr}: {$page}{if $pageAlias ne ''}&nbsp({$pageAlias}){/if}</h1>
{if $page eq 'SandBox'}
<div class="wikitext">
{tr}The SandBox is a page where you can practice your editing skills, use the preview feature to preview the appeareance of the page, no versions are stored for this page.{/tr}
</div>
{/if}
<form  enctype="multipart/form-data" method="post" action="tiki-editpage.php" id='editpageform'>
<table class="normal">
<tr><td class="formcolor">{tr}Quicklinks{/tr}:</td><td class="formcolor">
{assign var=area_name value="editwiki"}
{include file=tiki-edit_help_tool.tpl}
</td></tr>

{include file=categorize.tpl}

{if $feature_wiki_templates eq 'y' and $tiki_p_use_content_templates eq 'y'}
<tr><td class="formcolor">{tr}Apply template{/tr}:</td><td class="formcolor">
<select name="templateId" onChange="javascript:document.getElementById('editpageform').submit();">
<option value="0">{tr}none{/tr}</option>
{section name=ix loop=$templates}
<option value="{$templates[ix].templateId|escape}">{tr}{$templates[ix].name}{/tr}</option>
{/section}
</select>
</td></tr>
{/if}
{if $feature_smileys eq 'y'}
<tr><td class="formcolor">{tr}Smileys{/tr}:</td><td class="formcolor">
<table>
     <tr>
          <td><a href="javascript:insertAt('editwiki','(:biggrin:)');"><img src="img/smiles/icon_biggrin.gif" alt="big grin" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:confused:)');"><img src="img/smiles/icon_confused.gif" alt="confused" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:cool:)');"><img src="img/smiles/icon_cool.gif" alt="cool" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:cry:)');"><img src="img/smiles/icon_cry.gif" alt="cry" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:eek:)');"><img src="img/smiles/icon_eek.gif" alt="eek" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:evil:)');"><img src="img/smiles/icon_evil.gif" alt="evil" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:exclaim:)');"><img src="img/smiles/icon_exclaim.gif" alt="exclaim" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:frown:)');"><img src="img/smiles/icon_frown.gif" alt="frown" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:idea:)');"><img src="img/smiles/icon_idea.gif" alt="idea" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:lol:)');"><img src="img/smiles/icon_lol.gif" alt="lol" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:mad:)');"><img src="img/smiles/icon_mad.gif" alt="mad" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:mrgreen:)');"><img src="img/smiles/icon_mrgreen.gif" alt="mr green" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:neutral:)');"><img src="img/smiles/icon_neutral.gif" alt="neutral" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:question:)');"><img src="img/smiles/icon_question.gif" alt="question" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:razz:)');"><img src="img/smiles/icon_razz.gif" alt="razz" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:redface:)');"><img src="img/smiles/icon_redface.gif" alt="redface" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:rolleyes:)');"><img src="img/smiles/icon_rolleyes.gif" alt="rolleyes" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:sad:)');"><img src="img/smiles/icon_sad.gif" alt="sad" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:smile:)');"><img src="img/smiles/icon_smile.gif" alt="smile" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:surprised:)');"><img src="img/smiles/icon_surprised.gif" alt="surprised" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:twisted:)');"><img src="img/smiles/icon_twisted.gif" alt="twisted" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:wink:)');"><img src="img/smiles/icon_wink.gif" alt="wink" border="0" /></a></td>
          <td><a href="javascript:insertAt('editwiki','(:arrow:)');"><img src="img/smiles/icon_arrow.gif" alt="arrow" border="0" /></a></td>
      </tr>    
      </table>
</td>
</tr>
{/if}
<!--<a class="link" href="javascript:insertAt('editwiki',"''text here''");">i</a>-->
{if $feature_wiki_description eq 'y'}
<tr><td class="formcolor">{tr}Description{/tr}:</td><td class="formcolor"><input size="80" class="wikitext" type="text" name="description" value="{$description|escape}" /></td>
{/if}
<tr><td class="formcolor">{tr}Edit{/tr}:</td><td class="formcolor">
<textarea id='editwiki' class="wikiedit" name="edit" rows="22" wrap="virtual" cols="80">{$pagedata|escape}</textarea>
</td>
{if $feature_wiki_footnotes eq 'y'}
{if $user}
<tr><td class="formcolor">{tr}Footnotes{/tr}:</td><td class="formcolor"><textarea name="footnote" rows="8" cols="80">{$footnote|escape}</textarea></td>
{/if}
{/if}

{if $page ne 'SandBox'}
<tr><td class="formcolor">{tr}Comment{/tr}:</td><td class="formcolor"><input size="80" class="wikitext" type="text" name="comment" value="{$commentdata|escape}" /></td>
{/if}
{if $wiki_feature_copyrights  eq 'y'}
<tr><td class="formcolor">{tr}Copyright{/tr}:</td><td class="formcolor">
<table border="0">
<tr><td class="formcolor">{tr}Title:{/tr}</td><td><input size="40" class="wikitext" type="text" name="copyrightTitle" value="{$copyrightTitle|escape}" /></td></tr>
<tr><td class="formcolor">{tr}Year:{/tr}</td><td><input size="4" class="wikitext" type="text" name="copyrightYear" value="{$copyrightYear|escape}" /></td></tr>
<tr><td class="formcolor">{tr}Authors:{/tr}</td><td><input size="40" class="wikitext" name="copyrightAuthors" type="text" value="{$copyrightAuthors|escape}" /></td></tr>
</table>
</td>
{/if}
{if $tiki_p_use_HTML eq 'y'}
<tr><td class="formcolor">{tr}Allow HTML{/tr}: </td><td class="formcolor"><input type="checkbox" name="allowhtml" {if $allowhtml eq 'y'}checked="checked"{/if}/></td>
{/if}
{if $wiki_spellcheck eq 'y'}
<tr><td class="formcolor">{tr}Spellcheck{/tr}: </td><td class="formcolor"><input type="checkbox" name="spellcheck" {if $spellcheck eq 'y'}checked="checked"{/if}/></td>
{/if}

<tr>
  <td class="formcolor">{tr}Import HTML{/tr}:</td>
  <td class="formcolor">
    <input class="wikitext" type="text" name="suck_url" value="{$suck_url|escape}" />&nbsp;
  </td>
</tr>
<tr>
  <td class="formcolor">&nbsp;</td>
  <td class="formcolor">
    <input type="submit" class="wikiaction" name="do_suck" value="{tr}Import{/tr}" />&nbsp;
    <input type="checkbox" name="parsehtml" {if $parsehtml eq 'y'}checked="checked"{/if}/>&nbsp;
    {tr}Try to convert HTML to wiki{/tr}
  </td>
</tr>
{if $tiki_p_admin_wiki eq 'y'}
<tr><td class="formcolor">{tr}Import page{/tr}:</td><td class="formcolor">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
<input name="userfile1" type="file" />
<a href="tiki-export_wiki_pages.php?page={$page|escape:"url"}&amp;all=1" class="link">{tr}export all versions{/tr}</a>
</td></tr>
{/if}
{if $feature_wiki_pictures eq 'y' and $tiki_p_upload_picture eq 'y'}
<tr><td class="formcolor">{tr}Upload picture{/tr}</td><td class="formcolor">
<input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
<input name="picfile1" type="file" />
</td></tr>
{/if}
{if $feature_wiki_icache eq 'y'}
<tr><td class="formcolor">{tr}Cache{/tr}</td><td class="formcolor">
    <select name="wiki_cache">
    <option value="0" {if $wiki_cache eq 0}selected="selected"{/if}>0 ({tr}no cache{/tr})</option>
    <option value="60" {if $wiki_cache eq 60}selected="selected"{/if}>1 {tr}minute{/tr}</option>
    <option value="300" {if $wiki_cache eq 300}selected="selected"{/if}>5 {tr}minutes{/tr}</option>
    <option value="600" {if $wiki_cache eq 600}selected="selected"{/if}>10 {tr}minute{/tr}</option>
    <option value="900" {if $wiki_cache eq 900}selected="selected"{/if}>15 {tr}minutes{/tr}</option>
    <option value="1800" {if $wiki_cache eq 1800}selected="selected"{/if}>30 {tr}minute{/tr}</option>
    <option value="3600" {if $wiki_cache eq 3600}selected="selected"{/if}>1 {tr}hour{/tr}</option>
    <option value="7200" {if $wiki_cache eq 7200}selected="selected"{/if}>2 {tr}hours{/tr}</option>
    </select> 
</td></tr>
{/if}

<input type="hidden" name="page" value="{$page|escape}" />
<tr><td class="formcolor">&nbsp;</td><td class="formcolor"><input type="submit" class="wikiaction" name="preview" value="{tr}preview{/tr}" /></td>

{if $wiki_feature_copyrights  eq 'y'}
<tr><td class="formcolor">{tr}License{/tr}:</td><td class="formcolor"><a href="tiki-index.php?page={$wikiLicensePage}">{tr}{$wikiLicensePage}{/tr}</a></td>
{if $wikiSubmitNotice neq ""}
<tr><td class="formcolor">{tr}Important{/tr}:</td><td class="formcolor"><b>{tr}{$wikiSubmitNotice}{/tr}</b></td>
{/if}
{/if}
<tr><td class="formcolor">&nbsp;</td><td class="formcolor">
{if $tiki_p_minor eq 'y'}
<input type="checkbox" name="isminor" />{tr}Minor{/tr}
{/if}
<input type="submit" class="wikiaction" name="save" value="{tr}save{/tr}" /> <a class="link" href="tiki-index.php?page={$page|escape:"url"}">{tr}cancel edit{/tr}</a></td>
</tr>
</table>
</form>
<br />
<!--<a href="javascript:replaceSome('editwiki','foo','bar');">foo2bar</a>-->
{include file=tiki-edit_help.tpl}
