{tikimodule error=$module_params.error title=$tpl_module_title name="most_commented" flip=$module_params.flip decorations=$module_params.decorations nobox=$module_params.nobox notitle=$module_params.notitle}
	{if $nonums != 'y'}<ol>{else}<ul>{/if}
	{section name=ix loop=$modMostCommented}
		{if $modContentType eq 'article'}
			<li><a href="tiki-read_article.php?articleId={$modMostCommented[ix].articleId}">{$modMostCommented[ix].title}</a></li>
		{/if}
		
		{if $modContentType eq 'blog'}
			<li><a href="tiki-view_blog_post.php?postId={$modMostCommented[ix].postId}">{$modMostCommented[ix].title}</a></li>
		{/if}
		
		{if $modContentType eq 'wiki'}
			<li><a href="tiki-index.php?page={$modMostCommented[ix].pageName}">{$modMostCommented[ix].pageName}</a></li>
		{/if}
		
	{/section}
	{if $nonums != 'y'}</ol>{else}</ul>{/if}
{/tikimodule}