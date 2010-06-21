{* $Id$ *}
{if !isset($show_heading) or $show_heading neq "n"}
	<div class="breadcrumbs"><a class="link" href="tiki-list_blogs.php">{tr}Blogs{/tr}</a> {$prefs.site_crumb_seper} {$title|escape}</div>
	{if strlen($heading) > 0 and $prefs.feature_blog_heading eq 'y'}
	  {eval var=$heading}
	{else}
	  {include file='blog-heading.tpl'}
	{/if}
	
	<div align="right" >
	<span class="blogactions">
			{if $tiki_p_blog_post eq "y"}
			{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y" or $public eq "y"}
			<a class="bloglink" href="tiki-blog_post.php?blogId={$blogId}">{icon _id='pencil_add' alt='{tr}Post{/tr}'}</a>
			{/if}
			{/if}
			{if $prefs.rss_blog eq "y"}
			<a class="bloglink" href="tiki-blog_rss.php?blogId={$blogId}">{icon _id='feed' alt='{tr}RSS feed{/tr}'}</a>
			{/if}
			{if ($user and $creator eq $user) or $tiki_p_blog_admin eq "y"}
			<a class="bloglink" href="tiki-edit_blog.php?blogId={$blogId}">{icon _id='page_edit' alt='{tr}Edit Blog{/tr}'}</a>
			{/if}
			
			{if $user and $prefs.feature_user_watches eq 'y'}
			{if $user_watching_blog eq 'n'}
			<a href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=add" class="icon">{icon _id='eye' alt='{tr}Monitor this Blog{/tr}'}</a>
			{else}
			<a href="tiki-view_blog.php?blogId={$blogId}&amp;watch_event=blog_post&amp;watch_object={$blogId}&amp;watch_action=remove" class="icon">{icon _id='no_eye' alt='{tr}Stop Monitoring this Blog{/tr}'}</a>
			{/if}
			{/if}
			{if $prefs.feature_group_watches eq 'y' and ( $tiki_p_admin_users eq 'y' or $tiki_p_admin eq 'y' )}
				<a href="tiki-object_watches.php?objectId={$blogId|escape:"url"}&amp;watch_event=blog_post&amp;objectType=blog&amp;objectName={$title|escape:"url"}&amp;objectHref={'tiki-view_blog.php?blogId='|cat:$blogId|escape:"url"}" class="icon">{icon _id='eye_group' alt='{tr}Group Monitor{/tr}'}</a>
			{/if}
	
	</span>
	
	{if $user and $prefs.feature_user_watches eq 'y'}
		{if $category_watched eq 'y'}
			{tr}Watched by categories{/tr}:
			{section name=i loop=$watching_categories}
				<a href="tiki-browse_categories.php?parentId={$watching_categories[i].categId}" 
	class="icon">{$watching_categories[i].name}</a>&nbsp;
			{/section}
		{/if}		
	{/if}
	</div>
	<br />
	
	{if $use_find eq 'y'}
		<div class="blogtools">
			<form action="tiki-view_blog.php" method="get">
			<input type="hidden" name="sort_mode" value="{$sort_mode|escape}" />
			<input type="hidden" name="blogId" value="{$blogId|escape}" />
			{tr}Find:{/tr} 
			<input type="text" name="find" value="{$find|escape}" /> 
			<input type="submit" name="search" value="{tr}Find{/tr}" />
			</form>
	
	       {* <!--
	          {tr}Sort posts by:{/tr}
	          <a class="bloglink" href="tiki-view_blog.php?blogId={$blogId}&amp;offset={$offset}&amp;sort_mode={if $sort_mode eq 'created_desc'}created_asc{else}created_desc{/if}">{tr}Date{/tr}</a>
	        -->	*}
		</div>
	{/if}
{/if}
{section name=ix loop=$listpages}
<div class="post{if !empty($container_class)} {$container_class}{/if}">
	<div class="clearfix postbody">
		<div class="author_actions clearfix">
			<div class="actions">
            {if ($ownsblog eq 'y') or ($user and $listpages[ix].user eq $user) or $tiki_p_blog_admin eq 'y'}
              <a class="blogt" href="tiki-blog_post.php?blogId={$listpages[ix].blogId}&amp;postId={$listpages[ix].postId}">{icon _id='page_edit'}</a> 
              &nbsp;
              <a class="blogt" href="tiki-view_blog.php?blogId={$blogId}&amp;remove={$listpages[ix].postId}">{icon _id='cross' alt='{tr}Remove{/tr}'}</a>
            {/if}

            {if $user and $prefs.feature_notepad eq 'y' and $tiki_p_notepad eq 'y'}
              <a title="{tr}Save to notepad{/tr}" href="tiki-view_blog.php?blogId={$blogId}&amp;savenotepad={$listpages[ix].postId}">{icon _id='disk'
							alt='{tr}Save to notepad{/tr}'}</a>
            {/if}
			</div>
			<div class="author_info">
			
				{if $use_author eq 'y' || $add_date eq 'y'}
				{tr}Published {/tr}
				{/if}
				
			    {if $use_author eq 'y'}
				{tr}by{/tr} {$listpages[ix].user|userlink} 
				{/if}
				
				{if $add_date eq 'y'}
				 {$listpages[ix].created|tiki_short_datetime}
				{/if}
				
				{if $show_avatar eq 'y'}
					{$listpages[ix].avatar}
				{/if}
				
				
			</div>
		</div>

		<a name="postId{$listpages[ix].postId}"></a> {* ?? *}

		<div class="clearfix postbody-title">
			{if $use_title eq 'y'}
			<div class="title"> {* because used in forums, but I don't know purpose *}
				<h2><a class="link" href="{$listpages[ix].postId|sefurl:blogpost}">{$listpages[ix].title|escape}</a></h2>
			</div>
			{/if}
	  
			{if $prefs.feature_freetags eq 'y' and $tiki_p_view_freetags eq 'y'}
				{if $listpages[ix].freetags.data|@count >0}
				<div class="freetaglist">{tr}Tags{/tr}:
					{foreach from=$listpages[ix].freetags.data item=taginfo}
						{capture name=tagurl}{if (strstr($taginfo.tag, ' '))}"{$taginfo.tag}"{else}{$taginfo.tag}{/if}{/capture}
						<a class="freetag" href="tiki-browse_freetags.php?tag={$smarty.capture.tagurl|escape:'url'}">{$taginfo.tag|escape}</a>
					{/foreach}
				</div>
				{/if}
			{/if}
	</div> <!-- posthead -->
	{*<div class="content">
	<div class="postbody-content">*}
		{$listpages[ix].parsed_data}
		{if $listpages[ix].pages > 1}
			<a class="link more" href="{$listpages[ix].postId|sefurl:blogpost}">
			{tr}More...{/tr} ({$listpages[ix].pages} {tr}pages{/tr})</a>
		{/if}
		
		{capture name='copyright_section'}
			{include file='show_copyright.tpl'}
		{/capture}
	
		{* When copyright section is not empty show it *}
		{if $smarty.capture.copyright_section neq ''}
			<p class="editdate">
				{$smarty.capture.copyright_section}
			</p>
		{/if}
		
		<div class="postfooter">
			<div class="status"> {* renamed to match forum footer layout *}
				<a href='tiki-print_blog_post.php?postId={$listpages[ix].postId}'>{icon _id='printer' alt='{tr}Print{/tr}'}</a>
				{if $prefs.feature_blog_sharethis eq "y"}
					{capture name=shared_title}{tr}Share This{/tr}{/capture}
					{capture name=shared_link_title}{tr}ShareThis via AIM, social bookmarking and networking sites, etc.{/tr}{/capture}
					{wiki}{literal}<script language="javascript" type="text/javascript">
						//Create your sharelet with desired properties and set button element to false
						var object{/literal}{$listpages[ix].postId}{literal} = SHARETHIS.addEntry({
							title:'{/literal}{$smarty.capture.shared_title|replace:'\'':'\\\''}{literal}',
							url:'{/literal}http://{$hostname}{$smarty.server.SERVER_NAME}{$smarty.server.SCRIPT_NAME|replace:'tiki-view_blog.php':'tiki-view_blog_post.php'}?postId={$listpages[ix].postId}{literal}'
						},
						{button:false});
						//Output your customized button
						document.write('<span id="share{/literal}{$listpages[ix].postId}{literal}"><a title="{/literal}{$smarty.capture.shared_link_title|replace:'\'':'\\\''}{literal}" href="javascript:void(0);"><img src="http://w.sharethis.com/images/share-icon-16x16.png?CXNID=1000014.0NXC" /></a></span>');
						//Tie customized button to ShareThis button functionality.
						var element{/literal}{$listpages[ix].postId}{literal} = document.getElementById("share{/literal}{$listpages[ix].postId}{literal}");
						object{/literal}{$listpages[ix].postId}{literal}.attachButton(element{/literal}{$listpages[ix].postId}{literal});
					</script>{/literal}{/wiki}
				{/if}
			</div>
			<div class="actions"> {* renamed to match forum footer layout *}
				<a class="link" href="{$listpages[ix].postId|sefurl:blogpost}">{tr}Permalink{/tr}</a>
				{if $allow_comments eq 'y' and $prefs.feature_blogposts_comments eq 'y'}
					| <a class="link" href="tiki-view_blog_post.php?find={$find|escape:url}&amp;blogId={$blogId}&amp;offset={$offset}&amp;sort_mode={$sort_mode}&amp;postId={$listpages[ix].postId}&amp;show_comments=1#comments">
					{$listpages[ix].comments}
					{if $listpages[ix].comments == 1}
						{tr}comment{/tr}
					{else}
						{tr}comments{/tr}</a>
					{/if}
				{/if}
			</div>
		</div>
	</div> <!-- postbody -->
</div> <!--blogpost -->
{/section}

{pagination_links cant=$cant step=$maxRecords offset=$offset}{/pagination_links}

