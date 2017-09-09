<div>&nbsp;</div>

{tabset}

	{tab name="{tr}Tiki Connect{/tr}"}
		<br>
		<fieldset>
			<legend><strong>{tr}Join the community!{/tr}</strong></legend>
			<p>{tr}Tiki Wiki CMS Groupware is Free and Open Source Software (FOSS). It is a community-driven project which exists and improves thanks to the participation of people just like YOU.{/tr}</p>
			<p>{button href="https://tiki.org/Join" _text="{tr}Join the Community{/tr}"}</p>
		</fieldset>
		<fieldset>
			<legend><strong>{tr}Tiki news{/tr}</strong></legend>
			<p>
				{tr}Embedded RSS feed from info.tiki.org TODO, meanwhile{/tr}
				{button href="https://tiki.org/tiki-articles_rss.php?ver=2" _text="Add RSS"}
			</p>
			<p>
				{tr}Tiki Newsletter{/tr} {button _text="{tr}Subscribe{/tr}" href="https://tiki.org/tiki-admin_newsletter_subscriptions.php?nlId=6&info=1"}
			</p>
		</fieldset>
		<fieldset>
			<legend><strong>{tr}Promote your site{/tr}</strong></legend>
			<p>
				{tr}Add your site to the Tiki directory.{/tr}
				{button href="tiki-register_site.php" _text="{tr}Submit your site{/tr}"}
			</p>
		</fieldset>
		<fieldset>
			<legend><strong>{tr}Spread the word{/tr}</strong></legend>
			<p>
				{tr}Add the "Powered by" module to your site. {/tr}
				{button href="tiki-admin_modules.php?textFilter=powered&cookietab=3" _text="{tr}Manage Modules{/tr}"}
			</p>
		</fieldset>
		<fieldset>
			<legend><strong>{tr}Help improve Tiki{/tr}</strong></legend>
			<p>
				{tr}Submit a feature request or bug report.{/tr} {button _text="{tr}Visit Tiki development site{/tr}" href="https://dev.tiki.org/Make+a+wish"}
			</p>
		</fieldset>
		<fieldset>
			<legend><strong>{tr}Make a financial contribution to the Tiki Association{/tr}</strong></legend>
			<p>
				<a href="https://tiki.org/Donation">
					<img src="img/tiki/Tiki_Contribute_Button.png" alt="{tr}Contribute to Tiki{/tr}">
				</a>
			</p>
		</fieldset>
	{/tab}

	{tab name="{tr}Settings{/tr}"}
		<br>
		<form class="admin form-horizontal" id="connect" name="connect" action="tiki-admin.php?page=connect" method="post">
			{include file='access/include_ticket.tpl'}
			{include file='admin/include_apply_top.tpl'}
			<fieldset>
				<legend>{tr}Tiki connect{/tr}{help url="Connect"}</legend>
				{remarksbox type="info" title="{tr}New Feature{/tr}" icon="bricks"}
					<p><em>{tr}Please note: Experimental - work in progress{/tr}</em></p>
					<p>{tr}Tiki Connect is a way to let the Tiki project know how it is being used, and which parts people like or would like fixing (or explaining).{/tr}<br>
						{tr}Once enabled, when you click the '<strong>Send Info</strong>' button below you will be connected with <em>mother.tiki.org</em>, which is where the data will be collected.{/tr}
					</p>
					<p>{tr}You can also send feedback about Tiki by checking the 'Feedback' checkbox(once Tiki Connect is enabled, next to the 'Preference Filters' bar above).{/tr}
						{tr}Icons will appear next to all the preferences where you can 'like', request a 'fix' or ask 'what is this for?'{/tr}<br>
						{tr}Your votes are sent when you connect with mother.tiki.org (currently only by clicking the '<strong>Send Info</strong>' button){/tr}
				{/remarksbox}
				{preference name="connect_feature"}
				<div class="adminoptionboxchild" id="connect_feature_childcontainer">
					<div class="form-group">
						<div class="col-sm-offset-4 col-sm-8">
							{button _script="#" class="btn btn-default timeout" _text="{tr}Send Info{/tr}" _title="{tr}Send the data{/tr}" _id="connect_send_btn"}
							{button _script="#" class="btn btn-default timeout" _text="{tr}Preview info{/tr}" _title="{tr}See what is going to be sent{/tr}" _id="connect_list_btn"}
							{if empty($prefs.connect_site_title)}
								{button _text="{tr}Fill form{/tr}" class="btn btn-default timeout" _title="{tr}Fill this form in based on other preferences{/tr}" _id="connect_defaults_btn" _script="#"}
							{/if}
						</div>
					</div>
					{preference name="connect_send_info"}
					<div class="adminoptionboxchild" id="connect_send_info_childcontainer">
						{preference name="connect_site_title"}
						{preference name="connect_site_email"}
						{preference name="connect_site_url"}
						{preference name="connect_site_keywords"}
						{preference name="connect_site_location"}
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-8">
								{$headerlib->add_map()}
								<div class="map-container" style="height:250px;width:400px;" data-geo-center="{defaultmapcenter}"
									data-target-field="connect_site_location"{if $prefs.connect_server_mode eq "y"}
									data-icon-name="tiki" data-icon-src="img/tiki/tikiicon.png"
									data-icon-size="[16,16]" data-icon-offset="[-8,-16]" data-marker-filter=".geolocated.connection"{/if}>
								</div>
							</div>
						</div>
					</div>
					{preference name="connect_send_anonymous_info"}

					<div class="adminoptionboxchild"{if $prefs.connect_server_mode neq "y"} style="display:none;"{/if}>
						<strong>{tr}Advanced settings{/tr}</strong> {tr}Exposed to assist testing and development{/tr}
						{preference name="connect_frequency"}
						{preference name="connect_server"}
						{preference name="connect_last_post"}
						{preference name="connect_server_mode"}
						{preference name="connect_guid"}
					</div>
				</div>

			</fieldset>
			{include file='admin/include_apply_bottom.tpl'}
		</form>
	{/tab}

	{if $prefs.connect_server_mode eq "y"}
		{tab name="{tr}Connections Received{/tr}"}
			<br>
			<form class="admin form-horizontal" name="cserver_form" action="tiki-admin.php?page=connect" method="post">
				{include file='access/include_ticket.tpl'}
				<input name="cserver_search" type="text" value="{$cserver_search_text}">
				<input name="cserver" type="submit" class="btn btn-default timeout" value="{tr}Search{/tr}">
				{button cserver="rebuild" _auto_args="cserver,page" _text="{tr}Rebuild Index{/tr}" _title="{tr}Rebuild received connections index{/tr}"}
				{if !empty($connect_stats)}
					<span>{tr _0=$connect_stats.received _1=$connect_stats.guids}<strong>Server stats:</strong> %0 reports received from %1 Tikis{/tr}</span>
				{/if}
			</form>

			<div class="table-responsive">
				<table class="table">
					<tr>
						<th>{tr}Created{/tr}</th>
						<th>{tr}Title{/tr}</th>
						<th>{tr}Language{/tr}</th>
						<th>{tr}Keywords{/tr}</th>
					</tr>

					{section name=connection loop=$connect_recent}
						<tr>
							<td>
								{$connect_recent[connection].created}
							</td>
							<td class="text">
								<a class="{$connect_recent[connection].class}" {$connect_recent[connection].metadata} href="{$connect_recent[connection].url}">{$connect_recent[connection].title|escape}</a>
							</td>
							<td>
								{$connect_recent[connection].language}
							</td>
							<td>
								{$connect_recent[connection].keywords}
							</td>
						</tr>
					{sectionelse}
						{norecords _colspan=4}
					{/section}
				</table>
			</div>

		{/tab}
	{/if}

	{tab name="{tr}Jitsi{/tr}"}
		<br>
		<form class="admin form-horizontal" id="connect" name="connect" action="tiki-admin.php?page=connect" method="post">
			{include file='access/include_ticket.tpl'}
			{include file='admin/include_apply_top.tpl'}
			<fieldset>
				<legend>{tr}Jitsi{/tr}{help url="Jitsi"}</legend>

				<div class="form-group">
					<label class="control-label col-sm-4" for="jitsi-url">
						{tr}Provision URL{/tr}
					</label>
					<div class="col-sm-8">
						<input id="jitsi-url" readonly type="text" value="{$jitsi_url|escape}" class="form-control">
					</div>
				</div>
				{preference name=suite_jitsi_provision}
				{preference name=suite_jitsi_configuration}
			</fieldset>
			{include file='admin/include_apply_bottom.tpl'}
		</form>
	{/tab}

{/tabset}
