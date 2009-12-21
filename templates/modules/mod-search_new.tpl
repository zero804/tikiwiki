{* $Id$ *}

{if $prefs.feature_search eq 'y' and $tiki_p_search eq 'y'}
{if !isset($tpl_module_title)}{assign var=tpl_module_title value="{tr}Search{/tr}"}{/if}
{tikimodule error=$module_params.error title=$tpl_module_title name="search_new" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
    <form class="forms" method="get" action="tiki-searchindex.php">
    <input id="fuser" name="highlight" size="14" type="text" accesskey="s" />

 	{if $prefs.feature_search_show_object_filter eq 'y'}

	{tr}in:{/tr}<br />
    <select name="where">
    <option value="pages">{tr}Entire Site{/tr}</option>
    {if $prefs.feature_wiki eq 'y'}
    <option value="wikis">{tr}Wiki Pages{/tr}</option>
    {/if}
    {if $prefs.feature_directory eq 'y'}
    <option value="directory">{tr}Directory{/tr}</option>
    {/if}
    {if $prefs.feature_galleries eq 'y'}
    <option value="galleries">{tr}Image Gals{/tr}</option>
    <option value="images">{tr}Images{/tr}</option>
    {/if}
    {if $prefs.feature_file_galleries eq 'y'}
    <option value="files">{tr}Files{/tr}</option>
    {/if}
    {if $prefs.feature_articles eq 'y'}
    <option value="articles">{tr}Articles{/tr}</option>
    {/if}
    {if $prefs.feature_forums eq 'y'}
    <option value="forums">{tr}Forums{/tr}</option>
    {/if}
    {if $prefs.feature_blogs eq 'y'}
    <option value="blogs">{tr}Blogs{/tr}</option>
    <option value="posts">{tr}Blog Posts{/tr}</option>
    {/if}
    {if $prefs.feature_faqs eq 'y'}
    <option value="faqs">{tr}FAQs{/tr}</option>
    {/if}
    {if $prefs.feature_trackers eq 'y'}
    <option value="trackers">{tr}Tracker{/tr}</option>
    {/if}
    </select>
	
	{elseif !empty($prefs.search_default_where)}
		<input type="hidden" name="where" value="{$prefs.search_default_where|escape}" />
    {/if}
	
    <input type="submit" class="wikiaction" name="search" value="{tr}Go{/tr}"/> 
    </form>
{/tikimodule}
{/if}
