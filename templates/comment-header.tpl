{* $Id$ *}
<div class="postbody-title">

	{if $thread_style != 'commentStyle_headers' and $comment.threadId > 0
		and $forum_mode neq 'y' || ( $forum_mode eq 'y' and $forumId > 0 and $comments_parentId > 0 )
	}
	<div class="actions">
		{if	$forum_mode neq 'y' && (
				$tiki_p_edit_comments eq 'y'
				|| $comment.userName == $user
			)
			|| $forum_mode eq 'y' && (
				$tiki_p_admin_forum eq 'y'
				|| ( $comment.userName == $user && $tiki_p_forum_edit_own_posts eq 'y' )
			)
		}
		<a title="{tr}Edit{/tr}"
			{if $first eq 'y'}
			class="admlink" href="tiki-view_forum.php?comments_offset={$smarty.request.topics_offset}{$thread_sort_mode_param}&amp;comments_threshold={$smarty.request.topics_threshold}{$comments_find_param}&amp;comments_threadId={$comment.threadId}&amp;openpost=1&amp;forumId={$forum_info.forumId}{$comments_per_page_param}"
			{else}
			class="link" href="{$comments_complete_father}comments_threadId={$comment.threadId}&amp;comments_threshold={$comments_threshold}&amp;comments_offset={$comments_offset}&amp;thread_sort_mode={$thread_sort_mode}&amp;comments_per_page={$comments_per_page}&amp;comments_parentId={$comments_parentId}&amp;thread_style={$thread_style}&amp;edit_reply=1#form"
			{/if}
		>{icon _id='page_edit'}</a>
		{/if}

		{if
			( $forum_mode neq 'y' and $tiki_p_remove_comments eq 'y' )
			|| ( $forum_mode eq 'y' and $tiki_p_admin_forum eq 'y' )
		}
		<a title="{tr}Delete{/tr}"
			{if $first eq 'y'}
			class="admlink" href="tiki-view_forum.php?comments_offset={$smarty.request.topics_offset}{$thread_sort_mode_param}&amp;comments_threshold={$smarty.request.topics_threshold}{$comments_find_param}&amp;comments_remove=1&amp;comments_threadId={$comment.threadId}&amp;forumId={$forum_info.forumId}{$comments_per_page_param}"
			{else}
			class="link" href="{$comments_complete_father}comments_threshold={$comments_threshold}&amp;comments_threadId={$comment.threadId}&amp;comments_remove=1&amp;comments_offset={$comments_offset}&amp;thread_sort_mode={$thread_sort_mode}&amp;comments_per_page={$comments_per_page}&amp;comments_parentId={$comments_parentId}&amp;thread_style={$thread_style}"
			{/if}
		>{icon _id='cross' alt='{tr}Delete{/tr}'}</a>
		{/if}
					
		{if $tiki_p_forums_report eq 'y' and $forum_mode eq 'y'}
			{self_link report=$comment.threadId _icon='delete' _alt='{tr}Report this post{/tr}' _title='{tr}Report this post{/tr}'}{/self_link}
		{/if}
					
	  	{if $user and $prefs.feature_notepad eq 'y' and $tiki_p_notepad eq 'y' and $forumId}
			{self_link savenotepad=$comment.threadId _icon='disk' _alt='{tr}Save to notepad{/tr}' _title='{tr}Save to notepad{/tr}'}{/self_link}
		{/if}
	
		{if $user and $prefs.feature_user_watches eq 'y' and $display eq ''}
		{if $forum_mode eq 'y'}
		{if $user_watching_topic eq 'n'}
			{self_link watch_event='forum_post_thread' watch_object=$comments_parentId watch_action='add' _icon='eye' _alt='{tr}Monitor this Topic{/tr}' _title='{tr}Monitor this Topic{/tr}'}{/self_link}
		{else}
			{self_link watch_event='forum_post_thread' watch_object=$comments_parentId watch_action='remove' _icon='no_eye' _alt='{tr}Stop Monitoring this Topic{/tr}' _title='{tr}Stop Monitoring this Topic{/tr}'}{/self_link}
		{/if}
		{/if}
		<br />
		{if $category_watched eq 'y'}
			{tr}Watched by categories{/tr}:
			{section name=i loop=$watching_categories}
				<a href="tiki-browse_categories?parentId={$watching_categories[i].categId}">{$watching_categories[i].name}</a>&nbsp;
			{/section}
		{/if}	
		{/if}
	</div>
	{/if}

	{if $first neq 'y'}
	<div class="checkbox">
		{if $tiki_p_admin_forum eq 'y' and $forum_mode eq 'y' and $comment.threadId > 0}
		<input type="checkbox" name="forumthread[]" value="{$comment.threadId|escape}" {if $smarty.request.forumthread and in_array($comment.threadId,$smarty.request.forumthread)}checked="checked"{/if} />
		{/if}
	</div>
	{/if}

	{if $comment.title neq ''}
	<div class="title">
	{if $first eq 'y'}
		<h2>{$comment.title}</h2>
	{else}
		{if $comments_reply_threadId == $comment.threadId}
		{icon _id='flag_blue'}<span class="highlight">
		{/if}
		<a class="link" href="{$comments_complete_father}comments_parentId={$comment.threadId}&amp;comments_per_page=1&amp;thread_style={$thread_style}">{$comment.title}</a>
		{if $comments_reply_threadId == $comment.threadId}
		</span>
		{/if}
	{/if}

	</div>
	{/if}

	{if $thread_style eq 'commentStyle_headers'}
		{include file="comment-footer.tpl"  comment=$comments_coms[rep]}
	{/if}
</div>
