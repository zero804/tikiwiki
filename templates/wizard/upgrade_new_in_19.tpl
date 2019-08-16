{* $Id$ *}

<div class="media">
		<div class="mr-4">
			<span class="float-left fa-stack fa-lg margin-right-18em" alt="{tr}Upgrade Wizard{/tr}" title="Upgrade Wizard">
			<i class="fas fa-arrow-circle-up fa-stack-2x"></i>
			<i class="fas fa-flip-horizontal fa-magic fa-stack-1x ml-4 mt-4"></i>
			</span>
		</div>
	<br/><br/><br/>
	<div class="media-body">
		{tr}Main new and improved features and settings in Tiki 19.{/tr}
		<a href="https://doc.tiki.org/Tiki19" target="tikihelp" class="tikihelp text-info" title="{tr}Tiki19:{/tr}
			{tr}It is a Standard Term Support (STS) version.{/tr}
			{tr}It will be supported until Tiki 20.1 is released.{/tr}
			{tr}Some internal libraries and optional external packages have been upgraded or replaced by more updated ones.{/tr}
			<br/><br/>
			{tr}Click to read more{/tr}
		">
			{icon name="help" size=1}
		</a>
		<fieldset class="table clearfix featurelist">
			<legend>{tr}New Features{/tr}</legend>
			<div class="adminoption form-group row">
				<ul><li>{tr}Federation{/tr}: <a class="adminoption" href="tiki-admin_sync.php">{tr}Synchronize Dev{/tr}</a>
					<a href="https://doc.tiki.org/Sync%20Dev-Prod%20Servers" target="tikihelp" class="tikihelp text-info" title="{tr}Tiki19:{/tr}
						{tr}Use this tool if you have at least two different Tiki instances serving as development, staging or production instances. You can compare differences between Tiki configuration, wiki pages and their contents as well as tracker and field configurations. Especially useful when changes from a development server needs to be applied to production one. This tool will only show differences between instances, you will still have to manually apply the changes to the production one.{/tr}
						<br/><br/>
						{tr}Click to read more{/tr}
					">
					{icon name="help" size=1}
				</a></li></ul>
			</div>
			<div class="adminoption form-group row">
			</div>
		</fieldset>
		<fieldset>
			<legend> {tr}Settings for Media Alchemyst{/tr}{help url="Media-Alchemyst"}</legend>
			{preference name=alchemy_ffmpeg_path}
			{preference name=alchemy_ffprobe_path}
			{preference name=alchemy_unoconv_path}
			{preference name=alchemy_gs_path}
			{preference name=alchemy_imagine_driver}
		</fieldset>
		<fieldset class="table clearfix featurelist">
			<legend>{tr}New Wiki Plugins{/tr}</legend>
			{preference name=wikiplugin_diagram}
			{preference name=wikiplugin_ganttchart}
			{preference name=wikiplugin_layout}
			{preference name=wikiplugin_slideshowslide}
			{preference name=wikiplugin_swiper}
			{preference name=wikiplugin_xmpp}
		</fieldset>
		<fieldset class="table clearfix featurelist">
			<legend>{tr}Improved Plugins{/tr}</legend>
			{preference name=wikiplugin_button}
			{preference name=wikiplugin_img}
			{preference name=wikiplugin_pivottable}
			{preference name=wikiplugin_img}
			{preference name=wikiplugin_slideshow}
			{preference name=wikiplugin_together}
			{preference name=wikiplugin_trackercalendar}
		</fieldset>
		<fieldset class="table clearfix featurelist">
			<legend>{tr}Other Extended Features{/tr}</legend>
			<div class="adminoption form-group row">
				<label class="col-sm-3 col-form-label"><b>{tr}Scheduler{/tr}</b>:</label>
				<div class="col-sm-offset-1 col-sm-11">
					{tr}New actions can be performed.{/tr}
					<a href="https://doc.tiki.org/Tiki19#Scheduler">{tr}More Information{/tr}...</a><br/><br/>
				</div>
			</div>
			{* {preference name=foo} *}
			<div class="adminoption form-group row">
				<label class="col-sm-3 col-form-label"><b>{tr}Trackers{/tr}</b>:</label>
				<ul>
					<li>{tr}Added a preview button in tracker field files to enable pdf view in new window without download document{/tr}</li>
					<li>{tr}'Save and comment' can be enabled per tracker and permits to add a comment when saving an edit for a tracker item.{/tr}</li>
					<li>{tr}Create tracker from File{/tr}.</li>
				</ul>
				<a href="https://doc.tiki.org/Tiki19#Trackers">{tr}More Information{/tr}...</a><br/><br/>
			</div>
			{preference name=wikiplugin_list_convert_trackerlist}
			<label class="col-sm-3 col-form-label"><b>{tr}H5P{/tr}</b>:</label>
			{preference name='h5p_enabled'}
			<div class="adminoptionboxchild" id="h5p_enabled_childcontainer">
				{preference name='h5p_filegal_id'}
				{preference name='h5p_whitelist'}
				{preference name='h5p_dev_mode'}
				{preference name='h5p_track_user'}
				{preference name='h5p_save_content_state'}
				<div class="adminoptionboxchild" id="h5p_save_content_state_childcontainer">
					{preference name='h5p_save_content_frequency'}
				</div>
				{preference name='h5p_export'}
				{preference name='h5p_hub_is_enabled'}
				{preference name='h5p_site_key'}
				{preference name='h5p_h5p_site_uuid'}
				{preference name='h5p_content_type_cache_updated_at'}
				{preference name='h5p_check_h5p_requirements'}
				{preference name='h5p_send_usage_statistics'}
				{preference name='h5p_has_request_user_consent'}
				{preference name='h5p_enable_lrs_content_types'}

				{remarksbox type="info" title="{tr}H5P Info{/tr}"}
				{tr}Service URL to purge unused libraries can be found here. Can be used in a cron task{/tr}<br>
					<a href="{service controller='h5p' action='cron' token=$prefs.h5p_cron_token}" class="btn btn-link">
						{service controller='h5p' action='cron' token=$prefs.h5p_cron_token}
					</a>
				{/remarksbox}
			</div>
		</fieldset>
		<i>{tr}And many more improvements{/tr}.
			{tr}See the full list of changes.{/tr}</i>
		<a href="https://doc.tiki.org/Tiki19" target="tikihelp" class="tikihelp" title="{tr}Tiki19:{/tr}
			{tr}Click to read more{/tr}
		">
			{icon name="help" size=1}
		</a>
	</div>
</div>
