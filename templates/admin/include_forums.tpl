{* $Id$ *}
<form method="post" action="tiki-admin.php?page=forums">
    <div class="row">
        <div class="form-group col-lg-12 clearfix">
			<a role="button" class="btn btn-default btn-sm" href="tiki-admin_forums.php" title="{tr}List{/tr}">
				{glyph name="list"} {tr}Forums{/tr}
			</a>
            <div class="pull-right">
                <input type="submit" class="btn btn-primary btn-sm" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>

	{tabset name="admin_forums"}
		{tab name="{tr}General Settings{/tr}"}
            <h2>{tr}General Settings{/tr}</h2>
		
			<fieldset class="table">
				<legend>{tr}Activate the feature{/tr}</legend>
				{preference name=feature_forums visible="always"}
			</fieldset>
			
			{preference name=home_forum}

			<fieldset>
				<legend>
					{tr}Features{/tr}{help url="Forum"}
				</legend>
				<input type="hidden" name="forumprefs" />
				{preference name=feature_forum_rankings}
				{preference name=feature_forum_parse}
				{preference name=feature_forum_topics_archiving}
				{preference name=feature_forum_quickjump}
				{preference name=feature_forum_replyempty}
				{preference name=forum_reply_notitle}
				{preference name=forum_comments_no_title_prefix}
				{preference name=forum_reply_forcetitle}
				{preference name=feature_forums_allow_thread_titles}
				{preference name=forum_inbound_mail_ignores_perms}
				{preference name=forum_inbound_mail_parse_html}
				{preference name=forum_match_regex}
			</fieldset>

			<fieldset>
				<legend>{tr}Threads{/tr}</legend>
				<input type="hidden" name="forumthreadprefs" />
				{preference name=forum_thread_defaults_by_forum}
				{preference name=forum_thread_user_settings}
				<div class="adminoptionboxchild" id="forum_thread_user_settings_childcontainer">
					{preference name=forum_thread_user_settings_keep}
					{preference name=forum_thread_user_settings_threshold}
				</div>
				{preference name=forum_comments_per_page}
				{preference name=forum_thread_style}
				{preference name=forum_thread_sort_mode}
			</fieldset>

			<fieldset>
				<legend>{tr}Searches{/tr}</legend>
				{preference name=feature_forums_name_search}
				{preference name=feature_forums_search}
				{preference name=feature_forum_content_search}
				<div class="adminoptionboxchild" id="feature_forum_content_search_childcontainer">
					{preference name=feature_forum_local_tiki_search}
					{preference name=feature_forum_local_search}
				</div>
			</fieldset>
		{/tab}

		{tab name="{tr}Forums Listing{/tr}"}
            <h2>{tr}Forums Listing{/tr}</h2>
			<input type="hidden" name="forumlistprefs" />
			{preference name=forums_ordering}
			{tr}Select which items to display when listing forums:{/tr}
			{preference name=forum_list_topics}
			{preference name=forum_list_posts}
			{preference name=forum_list_ppd}
			{preference name=forum_list_lastpost}
			{preference name=forum_list_visits}
			{preference name=forum_list_desc}
			<div class="adminoptionboxchild" id="forum_list_desc_childcontainer">
				{preference name=forum_list_description_len}
			</div>

			{preference name=forum_category_selector_in_list}
			<div class="adminoptionboxchild" id="forum_category_selector_in_list_childcontainer">
				{preference name=forum_available_categories}
			</div>
		{/tab}
	{/tabset}
    <br>{* I cheated. *}
    <div class="row">
        <div class="form-group col-lg-12 clearfix">
            <div class="text-center">
                <input type="submit" class="btn btn-primary btn-sm" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>
</form>
