{* $Header: /cvsroot/tikiwiki/tiki/templates/modules/mod-top_image_galleries.tpl,v 1.13 2007-10-14 17:51:02 mose Exp $ *}

{if $prefs.feature_galleries eq 'y'}
{if !isset($tpl_module_title)}
{if $nonums eq 'y'}
{eval var="{tr}Top `$module_rows` galleries{/tr}" assign="tpl_module_title"}
{else}
{eval var="{tr}Top galleries{/tr}" assign="tpl_module_title"}
{/if}
{/if}

{tikimodule title="{tr}Top galleries{/tr}" name="top_image_galleries" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox}
<table  border="0" cellpadding="0" cellspacing="0">
{section name=ix loop=$modTopGalleries}
<tr>{if $nonums != 'y'}<td class="module" valign="top">{$smarty.section.ix.index_next})</td>{/if}
<td class="module"><a class="linkmodule" href="tiki-browse_gallery.php?galleryId={$modTopGalleries[ix].galleryId}">{$modTopGalleries[ix].name}</a></td></tr>
{/section}
</table>
{/tikimodule}
{/if}
