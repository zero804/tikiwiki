{* $Id$ *}

{if $prefs.feature_forums eq 'y'}
{if !isset($tpl_module_title)}
{if $nonums eq 'y'}
{eval var="{tr}Top `$module_rows` Forum Posters{/tr}" assign="tpl_module_title"}
{else}
{eval var="{tr}Top Forum Posters{/tr}" assign="tpl_module_title"}
{/if}
{/if}
{tikimodule title=$tpl_module_title name="top_forum_posters" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox}
{if $nonums != 'y'}<ol>{else}<ul>{/if}
{section name=ix loop=$modTopForumPosters}
<li>
	<div class="module" style="float:left; width:50px">{$modTopForumPosters[ix].name|avatarize}</div>
	<div class="module" style="float:left">{$modTopForumPosters[ix].name}</div>
	<div class="module" style="float:left;width:20px">{$modTopForumPosters[ix].posts}</div>
</li>
{/section}
{if $nonums != 'y'}</ol>{else}</ul>{/if}
{/tikimodule}
{/if}
