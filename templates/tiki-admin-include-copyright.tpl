{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-admin-include-copyright.tpl,v 1.4 2007-10-12 07:55:49 nyloth Exp $ *}

<div class="rbox" name="tip">
<div class="rbox-title" name="tip">{tr}Tip{/tr}</div>  
<div class="rbox-data" name="tip">{tr}Copyright allows to determine a copyright for all the objects of tikiwiki{/tr}.</div>
</div>
<br />

  <div class="cbox">
    <div class="cbox-title">
    {tr}Copyright Management{/tr}
    </div>
    <div class="cbox-data">
    <form action="tiki-admin.php?page=copyright" method="post">
    <table class="admin">
    <tr><td class="form">{tr}License Page{/tr}: </td><td><input type="text" name="wikiLicensePage" value="{$prefs.wikiLicensePage|escape}" /></td></tr>
    <tr><td class="form">{tr}Submit Notice{/tr}: </td><td><input type="text" name="wikiSubmitNotice" value="{$prefs.wikiSubmitNotice|escape}" /></td></tr>
   <tr><td class="form">{tr}Enable Feature for Wiki{/tr}:</td><td><input type="checkbox" name="wiki_feature_copyrights" {if $prefs.wiki_feature_copyrights eq 'y'}checked="checked"{/if}/></td></tr>
  <tr><td class="form">{tr}Enable Feature for Articles{/tr}:</td><td><input type="checkbox" name="articles_feature_copyrights" {if $prefs.articles_feature_copyrights eq 'y'}checked="checked"{/if}/></td></tr>
  <tr><td class="form">{tr}Enable Feature for Blogues{/tr}:</td><td><input type="checkbox" name="blogues_feature_copyrights" {if $prefs.blogues_feature_copyrights eq 'y'}checked="checked"{/if}/></td></tr>
  <tr><td class="form">{tr}Enable Feature for Faqs{/tr}:</td><td><input type="checkbox" name="faqs_feature_copyrights" {if $prefs.faqs_feature_copyrights eq 'y'}checked="checked"{/if}/></td></tr>
  
    <tr><td colspan="2" class="button"><input type="submit" name="setcopyright" value="{tr}Change preferences{/tr}" /></td></tr>    


     
</table>
    </form>
    </div>
  </div>

