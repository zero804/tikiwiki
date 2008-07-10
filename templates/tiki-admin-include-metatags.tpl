<div class="cbox">
<div class="cbox-title">
  {tr}{$crumbs[$crumb]->description}{/tr}
  {help crumb=$crumbs[$crumb]}
</div>
<div class="cbox-data">
        <form action="tiki-admin.php?page=metatags" method="post">
        <table class="admin">
        <tr><td class="form"><b>{tr}Item{/tr}</b></td>
            <td class="form"><b>{tr}Value{/tr}</b></td>
        </tr>
        <tr><td class="form">{tr}Meta Keywords{/tr}:</td><td><input type="text" name="metatag_keywords" value="{$prefs.metatag_keywords}" size="50" /> <br />
        {tr}Insert freetags in keyword list{/tr} <input type="checkbox" name="prefs_metatag_freetags" {if $prefs.metatag_freetags eq 'y'}checked="checked"{/if}<br />
        {tr}Use the thread title in Forum pages instead{/tr} <input type="checkbox" name="prefs_metatag_threadtitle" {if $prefs.metatag_threadtitle eq 'y'}checked="checked"{/if}<br />
        {tr}Use the image title in Image gallery pages instead{/tr} <input type="checkbox" name="prefs_metatag_imagetitle" {if $prefs.metatag_imagetitle eq 'y'}checked="checked"{/if}
        </td></tr>
        <tr><td class="form">{tr}Meta Description{/tr}:</td><td><input type="text" name="metatag_description" value="{$prefs.metatag_description}" size="50" /></td></tr>
        <tr><td class="form">{tr}Meta Author{/tr}:</td><td><input type="text" name="metatag_author" value="{$prefs.metatag_author}" size="50" /></td></tr>
        <tr><td class="heading" colspan="3" align="center">{tr}Geourl{/tr}<a target="_blank" href="http://www.geourl.org/">{icon _id='help'}</a></td></tr>
        <tr><td class="form">{tr}geo.position{/tr}:</td><td><input type="text" name="metatag_geoposition" value="{$prefs.metatag_geoposition}" size="50" /></td></tr>
        <tr><td class="form">{tr}geo.region{/tr}:</td><td><input type="text" name="metatag_georegion" value="{$prefs.metatag_georegion}" size="50" /></td></tr>
        <tr><td class="form">{tr}geo.placename{/tr}:</td><td><input type="text" name="metatag_geoplacename" value="{$prefs.metatag_geoplacename}" size="50" /></td></tr>
        <tr><td class="heading" colspan="3" align="center">{tr}Robots{/tr}</td></tr>
        <tr><td class="form">{tr}meta robots{/tr}:</td><td><input type="text" name="adm_metatag_robots" value="{$adm_metatag_robots}" size="50" /></td></tr>
        <tr><td class="form">{tr}revisit after{/tr}:</td><td><input type="text" name="metatag_revisitafter" value="{$prefs.metatag_revisitafter}" size="50" /></td></tr>
        <tr><td class="form" colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3" class="button"><input type="submit" name="metatags" value="{tr}Change settings{/tr}" /></td></tr>
        </table>
        </form>
</div>
</div>
