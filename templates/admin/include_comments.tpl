{* $Id: include_comments.tpl 44059 2012-11-22 16:13:24Z lphuberdeau $ *}

<form action="tiki-admin.php?page=comments" method="post">
	<input type="hidden" name="ticket" value="{$ticket|escape}">
    <div class="row">
        <div class="form-group col-lg-12">
			<a href="tiki-list_comments.php" class="btn btn-default btn-sm" title="{tr}List{/tr}">{glyph name="list"} {tr}Comments{/tr}</a>
            <div class="pull-right">
                <input type="submit" class="btn btn-primary btn-sm" name="commentssetprefs" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>

	{tabset name="admin_wiki"}
		{tab name="{tr}General Preferences{/tr}"}
            <h2>{tr}General Preferences{/tr}</h2>

			<fieldset>
				<legend>{tr}Site-wide features{/tr}</legend>

				<div class="admin featurelist">
					{preference name=feature_comments_moderation}
					{preference name=feature_comments_locking}
					{preference name=feature_comments_post_as_anonymous}
					{preference name=comments_vote}
					{preference name=comments_archive}
					{preference name=comments_allow_correction}

					{preference name=comments_akismet_filter}

					<div class="adminoptionboxchild" id="comments_akismet_filter_childcontainer">
						{preference name=comments_akismet_apikey}
						{preference name=comments_akismet_check_users}
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>{tr}Display options{/tr}</legend>

				<div class="admin featurelist">
					{preference name=comments_notitle}
					{preference name=section_comments_parse}
					{preference name=comments_field_email}
					{preference name=comments_field_website}
					{preference name=default_rows_textarea_comment}
				</div>
			</fieldset>

			<fieldset>
				<legend>{tr}Permissions{/tr}</legend>
					{permission_link mode=button textFilter=comment showDisabled=y}
			</fieldset>

			<fieldset>
				<legend>{tr}Inline comments{/tr}</legend>
				{preference name=feature_inline_comments}

				{tr}The feature below must be activated for this feature to work.{/tr}
				{preference name=feature_wiki_paragraph_formatting}
			</fieldset>

			<fieldset>
				<legend>{tr}Using comments in various features{/tr}</legend>

				<div class="table">
					{preference name=feature_article_comments}
					{preference name=feature_wiki_comments}
					<div class="adminoptionboxchild" id="feature_wiki_comments_childcontainer">
						{preference name=wiki_comments_displayed_default}
						{preference name=wiki_comments_form_displayed_default}
						{preference name=wiki_comments_per_page}
						{preference name=wiki_comments_default_ordering}
						{preference name=wiki_comments_allow_per_page}
						{preference name=wiki_watch_comments}
					</div>
					{preference name=feature_blogposts_comments}
					{preference name=feature_file_galleries_comments}
					<div class="adminoptionboxchild" id="feature_file_galleries_comments_childcontainer">
						{preference name='file_galleries_comments_per_page'}
						{preference name='file_galleries_comments_default_ordering'}
					</div>
					{preference name=feature_poll_comments}
					{preference name=feature_faq_comments}
					{preference name=wikiplugin_trackercomments}
				</div>
			</fieldset>

		{/tab}
{/tabset}

    <br>{* I cheated. *}
    <div class="row">
        <div class="form-group col-lg-12">
            <div class="text-center">
                <input type="submit" class="btn btn-primary btn-sm" name="commentssetprefs" title="{tr}Apply Changes{/tr}" value="{tr}Apply{/tr}">
            </div>
        </div>
    </div>
</form>
