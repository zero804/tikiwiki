{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/simple/modules/mod-search_wiki_page.tpl,v 1.3 2007-07-20 17:33:59 jyhem Exp $ *}

{tikimodule title="{tr}Search Wiki PageName{/tr}" name="search_wiki_page" flip=$module_params.flip decorations=$module_params.decorations}
  <form class="forms" method="post" action="tiki-listpages.php">
    <input name="find" size="14" type="text" accesskey="s" value="{$find}"/>
    <input type="hidden" name="exact_match" value="On"/>
    <button type="submit" class="wikiaction" name="search">{tr}Go{/tr}</button> 
  </form>
{/tikimodule}
