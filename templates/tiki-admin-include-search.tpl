{* $Id$ *}

{if $prefs.feature_search_stats eq 'y'}
	{remarksbox type="tip" title="{tr}Tip{/tr}"}
		{tr}Search stats{/tr} {tr}can be seen on page{/tr} <a class='rbox-link' target='tikihelp' href='tiki-search_stats.php'>{tr}Search stats{/tr}</a> {tr}in Admin menu{/tr}
	{/remarksbox}
{/if}


<form action="tiki-admin.php?page=search" method="post">
	<input type="hidden" name="searchprefs" />
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>

	{tabset name=admin_search}
		{tab name="{tr}General Settings{/tr}"}
			<fieldset>
				<legend>
					{tr}Search type{/tr}{help url="Search"}
				</legend>
				{preference name=feature_search_fulltext}
				{preference name=feature_search}

			<div class="adminoptionboxchild" id="feature_search_childcontainer">				
					{tr}Specify the Tiki search settings{/tr}:
					
					{preference name=search_refresh_index_mode}
					{preference name=search_refresh_rate}
					{preference name=search_min_wordlength}
					{preference name=search_max_syllwords}
					{preference name=search_syll_age}
					{preference name=search_lru_purge_rate}
					{preference name=search_lru_length}

					<em>{tr}The Tiki search indexes must be refreshed if you turn the Tiki search on{/tr}:</em>
					{if $refresh_index_all_now neq 'y'}
						<br />
						<a href="tiki-admin.php?page=search&amp;refresh_index_all_now=y" class="button" title="{tr}Refresh all search index now{/tr}">{tr}Refresh all search index now{/tr}</a>
					{/if}
					{if $refresh_index_now neq 'y'}
						<br />
						<a href="tiki-admin.php?page=search&amp;refresh_index_now=y" class="button" title="{tr}Refresh wiki search index now{/tr}">{tr}Refresh wiki search index now{/tr}</a>
					{/if}
					{if $refresh_tracker_index_now neq 'y' and $prefs.trk_with_mirror_tables neq 'y'}
						<br />
						<a href="tiki-admin.php?page=search&amp;refresh_tracker_index_now=y" class="button" title="{tr}Refresh trackers search index now{/tr}">{tr}Refresh tracker search index now{/tr}</a>
					{/if}
					{if $refresh_files_index_now neq 'y' and $prefs.trk_with_mirror_tables neq 'y'}
						<br />
						<a href="tiki-admin.php?page=search&amp;refresh_files_index_now=y" class="button" title="{tr}Refresh files search index now{/tr}">{tr}Refresh files search index now{/tr}</a>
					{/if}
				</div>
			</fieldset>
				

			<fieldset>
				<legend>{tr}Features{/tr}</legend>
				{preference name=feature_sitesearch}
				{preference name=feature_referer_highlight}
				{preference name=search_parsed_snippet}
				{preference name=feature_search_stats}
			</fieldset>

			<fieldset>
				<legend>{tr}Permissions{/tr}</legend>
					{icon _id=information} {tr}Enabling these options will improve performance, but may show forbidden results{/tr}.

					{preference name=feature_search_show_forbidden_obj}
					{preference name=feature_search_show_forbidden_cat}
			</fieldset>
		{/tab}

		{tab name="{tr}Search Results{/tr}"}
			{tr}Select the items to display on the search results page{/tr}:
			{preference name=feature_search_show_object_filter}
			{preference name=feature_search_show_search_box}
			{preference name=search_default_where}
			{tr}Select the information to display for each result{/tr}:
			{preference name=feature_search_show_visit_count}
			{preference name=feature_search_show_pertinence}
			{preference name=feature_search_show_object_type}
			{preference name=feature_search_show_last_modification}
		{/tab}
	{/tabset}
	<div class="heading input_submit_container" style="text-align: right">
		<input type="submit" value="{tr}Change preferences{/tr}" />
	</div>
</form>
