{* $Id$ *}

<div class="media">
	<div class="media-left">
		<span class="fa-stack fa-lg margin-right-18em" alt="{tr}Configuration Profiles Wizard{/tr}" title="{tr}Configuration Profiles Wizard{/tr}" >
			<i class="fa fa-cubes fa-stack-2x"></i>
			<i class="fa fa-rotate-270 fa-magic fa-stack-2x margin-left-9em"></i>
		</span>
	</div>
	<div class="media-body">
		<h4 class="media-heading margin-bottom-md">{tr}Check out these more advanced configurations that demonstrate some other Tiki Features that you might be interested in for your site{/tr}.</h4>
		{remarksbox type="warning" title="{tr}Warning{/tr}"}
			<a target="tikihelp" class="tikihelp" style="float:right" title="{tr}Demo Profiles:{/tr}
				{tr}They are initially intended for testing environments, so that, after you have played with the feature, you don't have to deal with removing the created objects, nor with restoring the potentially changed settings in your site{/tr}.
				<br/><br/>
				{tr}Once you know what they do, you can also apply them in your production site, in order to have working templates of the underlying features, that you can further adapt to your site later on{/tr}."
				>
			{icon name="help"}
			</a>
		{tr}They are not to be initially applied in production environments since they cannot be easily reverted and changes and new objects in your site are created for real{/tr}
		{/remarksbox}
		<h3>{tr}Profiles:{/tr}</h3>
			<div class="row">
				<div class="col-md-6">
					<h4>{tr}Shopping Cart{/tr}</h4>
					(<a href="tiki-admin.php?profile=Shopping_Cart&show_details_for=Shopping_Cart&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
					<br>
					{tr}This profile provides a Shopping Cart and the corresponding basic payment system.{/tr}
					{tr}It currently uses the PayPal shopping cart rather than the built in Tiki{/tr}.
					<br/>
					<a href="https://doc.tiki.org/Shopping+Cart" target="tikihelp" class="tikihelp" title="{tr}Shopping Cart{/tr}:
						{tr}It creates:{/tr}
						<ul>
							<li>{tr}A tracker for products including price, weight, image and stock quantity{/tr}</li>
							<li>{tr}Some sample items which are open, pending and closed items, with different permissions to view or edit them for different groups of users{/tr}</li>
							<li>{tr}A small category subtree to classify products{/tr}</li>
							<li>{tr}Wiki pages to display the available products list, one product details page and a search form{/tr}</li>
						</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://tiki.org/display544" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
							<img src="img/profiles/profile_thumb_shopping_cart.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="small text-center">
								{tr}Click to expand{/tr}
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4>{tr}Easy GeoBlog{/tr}</h4>
					(<a href="tiki-admin.php?profile=Easy+GeoBlog&show_details_for=Easy+GeoBlog&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
					<br>
					{tr}This profile demonstrates the geolocation of Blog posts, in conjunction with other associated features{/tr}
					<br/>
					<a href="https://doc.tiki.org/Geolocation" target="tikihelp" class="tikihelp" title="{tr}Easy GeoBlog{/tr}:
						{tr}More details{/tr}:
						<ul>
							<li>{tr}Single map with all geolocated blog posts{/tr}</li>
							<li>{tr}Different home page once the user logs in{/tr}</li>
							<li>{tr}Random header image from files included in a file gallery{/tr}</li>
							<li>{tr}WYSIWYG Editor (compatible mode with wiki syntax){/tr}</li>
							<li>{tr}Wiki, Search, Menu & Tags{/tr}</li>
							<li>{tr}Comments moderation & Banning (for anonymous comments to your site){/tr}</li>
						</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://tiki.org/display512" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
								<img src="img/profiles/profile_thumb_easy_geoblog.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="small text-center">
								{tr}Click to expand{/tr}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
							<h4>{tr}Profile Conditional_Display_in_Forms{/tr}</h4>
							(<a href="tiki-admin.php?profile=Conditional_Display_in_Forms_14&show_details_for=Conditional_Display_in_Forms_14&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
							<br>
							{tr}This profile demonstrates the setup to conditionally hide or display some fields in a form{/tr}.
					<br/>
					<a href="https://doc.tiki.org/PluginJQ" target="tikihelp" class="tikihelp" title="{tr}Conditional_Display_in_Forms:{/tr}
							{tr}Main features used{/tr}:
							<ul>
								<li>{tr}Trackers{/tr}</li>
								<li>{tr}Plugin JQ (jQuery){/tr}</li>
								<li>{tr}Plugin TrackerIf{/tr}</li>
								<br/>
							</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://tiki.org/display988" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
								<img src="img/profiles/profile_thumb_conditional_display_in_forms.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="small text-center">
								{tr}Click to expand{/tr}
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<h4>{tr}Profile Execute_on_list{/tr}</h4>
					(<a href="tiki-admin.php?profile=Execute+on+list&show_details_for=Execute+on+list&categories%5B%5D={$tikiMajorVersion}.x&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a>)
					<br>
					{tr}This profile demonstrates how to perform actions on several items from a list using Plugin ListExecute{/tr}.
					<br/>
					<a href="https://doc.tiki.org/PluginListExecute" target="tikihelp" class="tikihelp" title="{tr}Execute on list:{/tr}
							{tr}Main features used{/tr}:
							<ul>
								<li>{tr}Trackers{/tr}</li>
								<li>{tr}Plugin ListExecute{/tr}</li>
								<br/>
							</ul>
						{tr}Click to read more{/tr}"
					>
						{icon name="help"}
					</a>
					<div class="row">
						<div class="col-md-8 col-md-offset-2">
							<a href="http://tiki.org/display1228" class="thumbnail internal" data-box="box" title="{tr}Click to expand{/tr}">
								<img src="img/profiles/profile_thumb_execute_on_list.png" alt="Click to expand" class="regImage pluginImg" title="{tr}Click to expand{/tr}" />
							</a>
							<div class="small text-center">
								{tr}Click to expand{/tr}
							</div>
						</div>
					</div>
				</div>
			</div>
	</div>
</div>
