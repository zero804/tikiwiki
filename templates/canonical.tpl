{if $prefs.feature_canonical_url eq 'y'}
	{if $mid eq 'tiki-show_page.tpl' or $mid eq 'tiki-index_p.tpl'}
		<link rel="canonical" href="{$base_url}{$page|sefurl}" />
	{elseif $mid eq 'tiki-view_tracker_item.tpl'}
		<link rel="canonical" href="{$base_url}tiki-view_tracker_item.php?itemId={$itemId}" />
	{elseif $mid eq 'tiki-view_forum_thread.tpl'}
		<link rel="canonical" href="{$base_url}tiki-view_forum_thread.php?comments_parentId={$comments_parentId}" />
	{elseif $mid eq 'tiki-view_blog_post.tpl'}
		<link rel="canonical" href="{$base_url}{$postId|sefurl:blogpost}" />
	{/if}
{/if}
