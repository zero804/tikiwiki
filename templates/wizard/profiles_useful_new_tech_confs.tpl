{* $Id$ *}

<div class="media">
	<div class="mr-4">
		<span class="fa-stack fa-lg" style="width: 100px;" title="{tr}Configuration Profiles Wizard{/tr}" >
			<i class="fas fa-cubes fa-stack-2x"></i>
			<i class="fas fa-flip-horizontal fa-magic fa-stack-1x ml-4 mt-4"></i>
		</span>
	</div>
	<div class="media-body">
		<h4 class="mt-0 mb-4">{tr}Check out this set of useful configurations that involve using some new technology for your site{/tr}. </h4>
		<h3>{tr}Profiles:{/tr}</h3>
			<div class="row">
				<div class="col-md-6">
					<h4>{tr}Write Together{/tr}</h4>
					(<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Together_15&show_details_for=Together_15&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
					<br/>
					{tr}This profile adds a simple wiki page on a side module (using the Module menupage), showing the button to start co-writing with TogetherJS.{/tr}
					<br/>
					<a href="https://doc.tiki.org/PluginTogether" target="tikihelp" class="tikihelp" title="{tr}Write Together{/tr}:
						{tr}More details{/tr}:
						<ul>
							<li>{tr}Allows cowriting documents in real time{/tr}</li>
							<li>{tr}Allows voice communication in real time while editing{/tr}</li>
							<li>{tr}Uses the TogetherJS Mozilla widget{/tr}</li>
						</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://doc.tiki.org/display842" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
								<img src="img/profiles/profile_thumb_write_together.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="text-center">
								<div class="small">{tr}Click to expand{/tr}</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4>{tr}Post-it Sticky Note{/tr}</h4>
					(<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Post-it_Sticky_Note_15&show_details_for=Post-it_Sticky_Note_15&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
					<br>
					{tr}This profile allows to display a sticky note (a "post-it") in your tiki site{/tr}.
					<br/>
					<a href="http://doc.tiki.org/Custom+Code+HowTo+-+Post-It+Notes" target="tikihelp" class="tikihelp" title="{tr}Post-it Sticky Note{/tr}:
						{tr}More details{/tr}:
						<ul>
							<li>{tr}You can move it to another location{/tr}</li>
							<li>{tr}You can customize the contents and which groups of users will see it (by default, only to Admins){/tr}</li>
							<li>{tr}It will be shown for each user of that group until manually closed.{/tr}</li>
						</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://tiki.org/display515" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
								<img src="img/profiles/profile_thumb_post_it_sticky_note.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="text-center small">
								{tr}Click to expand{/tr}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<!--	<b>{tr}Profile X{/tr}</b> (<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Profile_X&show_details_for=Profile_X&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)<br/>
					<br>
					{tr}This profile allows to {/tr}
					<ul>
						<li>{tr}...{/tr}</li>
						<li>{tr}...{/tr}</li>
						<li>{tr}...{/tr}</li>
						<br/><em>{tr}See also{/tr} <a href="https://doc.tiki.org/Feature_X" target="_blank">{tr}Feature_X in doc.tiki.org{/tr}</a></em>
					</ul>
					-->
				</div>
				<div class="col-md-6">
					<!--	<b>{tr}Profile X{/tr}</b> (<a href="tiki-admin.php?ticket={ticket mode=get}&profile=Profile_X&show_details_for=Profile_X&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)<br/>
					<br>
					{tr}This profile allows to {/tr}
					<ul>
						<li>{tr}...{/tr}</li>
						<li>{tr}...{/tr}</li>
						<li>{tr}...{/tr}</li>
						<br/><em>{tr}See also{/tr} <a href="https://doc.tiki.org/Feature_X" target="_blank">{tr}Feature_X in doc.tiki.org{/tr}</a></em>
					</ul>
					-->
				</div>
			</div>
	</div>
</div>
