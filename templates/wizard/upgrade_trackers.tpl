{* $Id: upgrade_trackers.tpl 51355 2014-05-17 08:07:20Z xavidp $ *}

<div class="adminWizardIconleft"><img src="img/icons/large/wizard_upgrade48x48.png" alt="{tr}Upgrade Wizard{/tr}" title="{tr}Upgrade Wizard{/tr}"/></div><div class="adminWizardIconright"><img src="img/icons/large/gnome-settings-font48x48.png" alt="{tr}Trackers{/tr}" title="{tr}Trackers{/tr}"/></div>
{tr}Here you can set up a few options and features related to Trackers which were added across several versions of Tiki{/tr}.
{tr}You might have not specially noticed when they appeared but you should know about these enhancements since you might be interested in using some of them in your site{/tr}.
<br/><br/>
<div class="adminWizardContent">
    <fieldset>
        <legend>{tr}Tracker settings{/tr}</legend>
        <div class="admin clearfix featurelist">
            {preference name=tracker_clone_item}
            {preference name=tracker_change_field_type}
            {preference name=tracker_show_comments_below}
            {preference name=tracker_refresh_itemlink_detail}
            {preference name=feature_reports}
            {preference name=ajax_inline_edit}
            <div class="adminoptionboxchild" id="ajax_inline_edit_childcontainer">
                {preference name=ajax_inline_edit_trackerlist}
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>{tr}Tracker Field Types{/tr}</legend>
        <div class="admin clearfix featurelist">
            {preference name=trackerfield_rating}
            <ul>
                <li>
                    {tr}Former ways to manage ratings in trackers are <b>deprecated</b>{/tr}
                    <a href="http://doc.tiki.org/Ratings" target="tikihelp" class="tikihelp" title="{tr}Deprecated Rating Systems in Trackers:{/tr}
                    <ul>
                        <li>{tr}Tracker field types{/tr} > {tr}Stars (deprecated){/tr}</li>
                        <li>{tr}Tracker field types{/tr} > {tr}Stars (system - deprecated){/tr}</li>
                        <li>{tr}edit Tracker{/tr} > {tr}Features{/tr} > {tr}Allow ratings (deprecated, use rating field){/tr}</li>
                    </ul>">
                        <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                    </a>
                </li>
            </ul>
            {preference name=trackerfield_kaltura}
        </div>
    </fieldset>

    <fieldset>
        <legend>{tr}Other options to be set elsewhere{/tr}</legend>
        <ul>
            <li>{tr}Add a <em>User and Registration tracker</em>{/tr}
                <a href="http://doc.tiki.org/User+Tracker" target="tikihelp" class="tikihelp" title="{tr}User and Registration tracker: You can use trackers to collect additional information for users during registration or even later once they are registered users.{/tr}
    		{tr}Some uses of this type of tracker could be{/tr}
	    	<ul>
		        <li>{tr}To collect user information (such as mailing address or phone number){/tr}</li>
		        <li>{tr}To require the user to acknowledge a user agreement{/tr}</li>
		        <li>{tr}To prevent spammer registration, by asking new users to provide a reason why they want to join (the prompt should tell the user that his answer should indicate that he or she clearly understands what the site is about).{/tr}</li>
	    	</ul>
		    {tr}The profile will enable the feature 'Trackers' for you and a few other settings required. Once the profile is applied, you will be provided with instructions about further steps that you need to perform manually.{/tr}">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a> :
                <a href="tiki-admin.php?profile=User_Trackers&show_details_for=User_Trackers&repository=http%3a%2f%2fprofiles.tiki.org%2fprofiles&page=profiles&preloadlist=y&list=List#step2" target="_blank">{tr}apply profile now{/tr}</a> ({tr}new window{/tr})</em>
            </li>
            <li>{tr}Display last comment author and date{/tr}
                <a href="http://doc.tiki.org/Trackers" target="tikihelp" class="tikihelp" title="{tr}Display last comment author and date:{/tr}
                {tr}In lists of tracker items, through the interface of the tracker feature as well as through list in tracker related plugins{/tr}.
                <br/><br/>
                {tr}You can set it in{/tr} <em>'{tr}tracker edition{/tr} > {tr}Features{/tr} > {tr}Allow comments{/tr} > {tr}Display last comment author and date{/tr}'</em>.
		    ">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            </li>
            <li>{tr}User can see his own items{/tr}
                <a href="http://doc.tiki.org/Trackers" target="tikihelp" class="tikihelp" title="{tr}User can see his own items:{/tr}
                {tr}The tracker needs a user field with the auto-assign activated{/tr}. {tr}No extra permission is needed at the tracker permissions level to allow a user to see just his own items through Plugin TrackerList with the param view=user{/tr}.
                <br/><br/>
                {tr}You can set it in{/tr} <em>'{tr}tracker edition{/tr} > {tr}Permissions{/tr} > {tr}User can see his own items{/tr}'</em>.
	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            </li>
        </ul>
    </fieldset>
</div>
