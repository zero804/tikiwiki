{* based on /cvsroot/tikiwiki/tiki/templates/modules/mod-last_file_galleries.tpl,v 1.13 2007/10/14 17:51:01 mose *}

{if $prefs.feature_file_galleries eq 'y'}
{if !isset($tpl_module_title)}
{if $nonums eq 'y'}
{eval var="{tr}Last `$module_rows` modified file galleries{/tr}" assign="tpl_module_title"}
{else}
{eval var="{tr}Last modified file galleries{/tr}" assign="tpl_module_title"}
{/if}
{/if}
{tikimodule title=$tpl_module_title name="last_file_galleries" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox}
{if $nonums != 'y'}<ol>{else}<ul>{/if}
   {section name=ix loop=$modLastFileGalleries}
      <li>
        <a class="linkmodule" href="tiki-list_file_gallery.php?galleryId={$modLastFileGalleries[ix].galleryId}">
            {$modLastFileGalleries[ix].name}
          </a>
        </li>
    {/section}
{if $nonums != 'y'}</ol>{else}</ul>{/if}
{/tikimodule}
{/if}
