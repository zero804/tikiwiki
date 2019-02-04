{* $Id$ *}

<div class="media">
	<div class="mr-4">
		<span class="fa-stack fa-lg" style="width: 100px;" title="{tr}Configuration Profiles Wizard{/tr}" >
			<i class="fas fa-cubes fa-stack-2x"></i>
			<i class="fas fa-flip-horizontal fa-magic fa-stack-1x ml-4 mt-4"></i>
		</span>
	</div>
	<div class="media-body">
		<h4 class="mt-0 mb-4">{tr}Check out some useful changes in the configuration for site administrators to ease debugging{/tr}.</h4>
		<h3>{tr}Profiles:{/tr}</h3>
			<div class="row">
				<div class="col-md-{*6 commented out until second column, below, is used. *}12">
					<div class="row">
						<div class="col-md-6">
							<img class="float-left" src="img/icons/large/profile_debug_mode48x48.png" alt="{tr}Debug Mode Enabled{/tr}" />
							<h4>{tr}Debug Mode Enabled{/tr}</h4>
							(<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Debug_Mode_Enabled&show_details_for=Debug_Mode_Enabled&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)<br/>
						</div>
						<div class="col-md-6">
							<h4>{tr}Debug Mode Disabled{/tr}</h4>
							(<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Debug_Mode_Disabled&show_details_for=Debug_Mode_Disabled&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
						</div>
					</div>
					{tr}Profile <i>Debug_Mode_Enabled</i> will help you detect potential errors and warnings which are hidden otherwise.{/tr}
					{tr}Once applied, you might like to apply the opposite profile: <i>Debug_Mode_Disabled</i>, if not changing the appropriate settings by hand.{/tr}
					<br/>
					<a href="https://dev.tiki.org/Recovery" target="tikihelp" class="tikihelp" title="{tr}Debug Mode Enabled{/tr} & {tr}Debug Mode Disabled{/tr}:
						{tr}More details{/tr}:
						<ul>
							<li>{tr}Enables/Disables debugging tools{/tr}</li>
							<li>{tr}Enables/Disables logging tools{/tr}</li>
							<li>{tr}Disables/Enables redirections to similar pages{/tr}</li>
							<li>{tr}Enables/Disables error and warning display to all users, not only admins{/tr} </li>
						</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
				</div>
				{* <div class="col-md-6">
					&nbsp;
				</div> *}
			</div>
	</div>
</div>
