<div class="actions">
	{if ! $parentId && $allow_lock}
		{self_link controller=comment action=lock type=$type objectId=$objectId _icon=lock _class="confirm-prompt" _confirm="{tr}Do you really want to lock comments?{/tr}"}{tr}Lock{/tr}{/self_link}
	{/if}
	{if ! $parentId && $allow_unlock}
		{self_link controller=comment action=unlock type=$type objectId=$objectId _icon=lock_break _class="confirm-prompt" _confirm="{tr}Do you really want to unlock comments?{/tr}"}{tr}Unlock{/tr}{/self_link}
	{/if}
</div>
{if ! $parentId}
	<h3>{tr}Comments{/tr}</h3>
{/if}

{if $cant gt 0}
	<ol>
		{foreach from=$comments item=comment}
			<li class="comment {if $comment.archived eq 'y'}archived{/if} {if ! $parentId && $prefs.feature_wiki_paragraph_formatting eq 'y'}inline{/if}" data-comment-thread-id="{$comment.threadId|escape}">
				<article>
					<div class="actions">
						{if $allow_remove}
							{self_link controller=comment action=remove threadId=$comment.threadId _icon=cross _class="confirm-prompt" _confirm="{tr}Are you sure you want to remove this comment?{/tr}"}{tr}Remove{/tr}{/self_link}
						{/if}
						{if $allow_archive}
							{if $comment.archived eq 'y'}
								{self_link controller=comment action=archive do=unarchive threadId=$comment.threadId _icon=ofolder _class="confirm-prompt" _confirm="{tr}Are you sure you want to unarchive this comment?{/tr}"}{tr}Unarchive{/tr}{/self_link}
							{else}
								{self_link controller=comment action=archive do=archive threadId=$comment.threadId _icon=folder _class="confirm-prompt" _confirm="{tr}Are you sure you want to archive this comment?{/tr}"}{tr}Archive{/tr}{/self_link}
							{/if}
						{/if}
						{if $allow_moderate and $comment.approved neq 'y'}
							{self_link controller=comment action=moderate do=approve threadId=$comment.threadId _icon=comment_approve _class="confirm-prompt" _confirm="{tr}Are you sure you want to approve this comment?{/tr}"}{tr}Approve{/tr}{/self_link}
							{self_link controller=comment action=moderate do=reject threadId=$comment.threadId _icon=comment_reject _class="confirm-prompt" _confirm="{tr}Are you sure you want to reject this comment?{/tr}"}{tr}Reject{/tr}{/self_link}
						{/if}
					</div>
					{if $prefs.comments_notitle eq 'y'}
						<div class="title notitle clearfix">
							<span class="avatar">{$comment.userName|avatarize}</span>
							<h4>{tr _0=$comment.userName|userlink}By %0{/tr}</h4>
							<div class="date">{tr _0=$comment.commentDate|tiki_short_datetime}On %0{/tr}</div>
						</div>
						{else}
						<div class="title clearfix">
							<h4 class="title">{$comment.title}</h4>
							<span class="avatar">{$comment.userName|avatarize}</span>
							<div class="author_info">{tr _0=$comment.userName|userlink}Comment posted by %0{/tr}</div>
							<div class="date">{tr _0=$comment.commentDate|tiki_short_datetime}On %0{/tr}</div>
						</div>
					{/if}
					<div class="body">
						{$comment.parsed}
					</div>

					<div class="buttons comment-form">
						<table>
							<tr>
								{if $allow_post && $comment.locked neq 'y'}
								<td>
									<div class="button">{self_link controller=comment action=post type=$type objectId=$objectId parentId=$comment.threadId}{tr}Reply{/tr}{/self_link}</div>
								</td>
								{/if}
								<td>
								{if $comment.can_edit}
									<div class="button">{self_link controller=comment action=edit threadId=$comment.threadId}{tr}Edit{/tr}{/self_link}</div>
								{/if}
								</td>
								{if $comment.userName ne $user and $comment.approved eq 'y' and $prefs.wiki_comments_simple_ratings eq 'y' and ($tiki_p_vote_comments eq 'y' or $tiki_p_admin_comments eq 'y' )}
								<td>
									<form class="commentRatingForm" method="post" action="" style="float: right;">
										{rating type="comment" id=$comment.threadId}
										<input type="hidden" name="id" value="{$comment.threadId}" />
										<input type="hidden" name="type" value="comment" />
									</form>
									{jq}
										var crf = $('form.commentRatingForm').submit(function() {
											var vals = $(this).serialize();
											$.modal(tr('Loading...'));
											$.post($.service('rating', 'vote'), vals, function() {
												$.modal();
												$.notify(tr('Thanks for rating!'));
											});
											return false;
										});
									{/jq}
								</td>
								{/if}
								{if $prefs.wiki_comments_simple_ratings eq 'y' && ($tiki_p_ratings_view_results eq 'y' or $tiki_p_admin eq 'y')}
									{rating_result type="comment" id=$comment.threadId}
								{/if}
							</tr>
						</table>
					</div>
					{if $comment.replies_info.numReplies gt 0}
						{include file='comment/list.tpl' comments=$comment.replies_info.replies cant=$comment.replies_info.numReplies parentId=$comment.threadId}
					{/if}
				</article>
			</li>
		{/foreach}
	</ol>
{else}
	{remarksbox type=info title="{tr}No comments{/tr}"}
		{tr}There are no comments at this time.{/tr}
	{/remarksbox}
{/if}

{if ! $parentId && $allow_post}
	<div class="button buttons comment-form {if $prefs.wiki_comments_form_displayed_default eq 'y'}autoshow{/if}">{self_link controller=comment action=post type=$type objectId=$objectId}{tr}Post new comment{/tr}{/self_link}</div>
{/if}

{if ! $parentId && $prefs.feature_wiki_paragraph_formatting eq 'y'}
	<a id="note-editor-comment" href="#" style="display:none;">{tr}Add Comment{/tr}</a>
{/if}

<script type="text/javascript">
var ajax_url = '{$base_url}';
var objectId = '{$objectId|escape:'javascript'}';
</script>
