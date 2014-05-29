{* $Id: upgrade_others.tpl 51428 2014-05-21 09:30:17Z xavidp $ *}

<div class="adminWizardIconleft"><img src="img/icons/large/wizard_upgrade48x48.png" alt="{tr}Upgrade Wizard{/tr}" title="{tr}Upgrade Wizard{/tr}"/></div>
{tr}Here you can see listed other features and settings that were not included in the previous sections{/tr}.
<br/><br/>
<div class="adminWizardContent">

    <fieldset>
        <legend>{tr}Ratings in Forums{/tr}</legend>
        <div class="adminWizardIconright"><img src="img/icons/large/rating48x48.png" alt="{tr}Ratings{/tr}" title="{tr}Ratings{/tr}"/></div>
        <ul>
            <li>{tr}New option per forum: "User information display > <strong>Topic Rating</strong>" by each user{/tr}
                <a href="http://doc.tiki.org/Rating" target="tikihelp" class="tikihelp" title="{tr}Topic Rating by each user:{/tr}
                {tr}Since Tiki12.2, there is a new forum setting to allow the optional display of the Rating by each user to that forum thread topic in each reply{/tr}.
                <br/><br/>
                {tr}This setting is useful to ease the task to reach consensus on deliberations (in forum threads) by identifying in a more clear way the position (topic rating) of each person on that topic at each moment on the discussion{/tr}.
                <br/><br/>
                {tr}Click to read more{/tr}
	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            </li>
        </ul>
    </fieldset>

    <fieldset>
        <legend>{tr}Sysadmin Tasks{/tr}</legend>
        <div class="adminWizardIconright"><img src="img/icons/large/xfce4-appfinder48x48.png" alt="{tr}Search{/tr}" title="{tr}Search{/tr}"/></div>
        <b>{tr}Search Index{/tr}</b>:
        <ul>
            <li>{tr}You can rebuild the unified search index (feature '<b>Advanced Search</b>') by visiting example.com/tiki-admin.php?page=search&rebuild=now or through setting a <b>cron job</b>{/tr}
                <a href="http://doc.tiki.org/Cron+Job+to+Rebuild+Search+Index" target="tikihelp" class="tikihelp" title="{tr}Cron Job to Rebuild Search Index:{/tr}
                {tr}Starting in Tiki9, if you had a large site you should set up a Cron job to regularly rebuild the search index.{/tr}
                <br/><br/>
                {tr}Starting in Tiki11, the syntax to rebuild the search index changed{/tr}.
                {tr}Example{/tr}
                <pre>0 0 * * * cd /path_to_tiki;php console.php index[{tr}colon{/tr}]rebuild</pre>
                {tr}Click to read more{/tr}
	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            </li>
        </ul>
        <div class="adminWizardIconright"><img src="img/icons/large/console.png" alt="{tr}Console{/tr}" title="{tr}Console{/tr}"/></div>
        <b>{tr}Console{/tr}</b>:
        <ul>
            <li>{tr}Starting in Tiki11, <b>console.php</b> script exists to help you administer your Tiki or <em>MultiTiki</em> instance via the command line{/tr}.
                <a href="http://doc.tiki.org/Console" target="tikihelp" class="tikihelp" title="{tr}Console (console.php script):{/tr}
                {tr}All the other command line scripts from before Tiki11 (ex. php installer/shell.php) will continue to work in Tiki12, but may not work anymore since Tiki13 so you will need to switch to use console.php script instead{/tr}.
                <br/><br/>
                {tr}Example. Database update{/tr}
                <pre>php console.php database[{tr}colon{/tr}]update</pre>
                {tr}Or{/tr}
                <pre>php console.php d[{tr}colon{/tr}]u</pre>
                <br/><br/>
                {tr}In case of Tikis with domains <code>site1.example.com</code> & <code>site2.example.com</code>, in a <em>MultiTiki</em> setup, append an argument like <code> --site=sitename.example.com</code>{/tr}
                <pre>php console.php d[{tr}colon{/tr}]u --site=site1.example.com</pre>
                <pre>php console.php d[{tr}colon{/tr}]u --site=site2.example.com</pre>
                <br/><br/>
                {tr}Click to read more{/tr}
    	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            </li>
        </ul>
        <div class="adminWizardIconright"><img src="img/icons/large/mail_queue48x48.png" alt="{tr}Mail Queue{/tr}" title="{tr}Mail Queue{/tr}"/></div>
        <b>{tr}Mail Queue{/tr}</b> & <b>{tr}Daily Reports for User Watches{/tr}</b>:
        <ul>
            <li>{tr}Starting in Tiki12.2, console.php also handles the feature <strong>Mail Queue</strong>, which has been also fixed, and it is a very useful feature in Tikis with heavy load of notification email sending{/tr}.
                <a href="http://doc.tiki.org/Mail+Queue" target="tikihelp" class="tikihelp" title="{tr}Mail Queue:{/tr}
                {tr}When Tiki has many notification emails to send upon new changes in your site (e.g. a new calendar event), the site may seem unresponsive for some seconds until the whole mail delivery is finished{/tr}.
                {tr}The more users or groups subscribed to receive notification emails for changes in that object, the longer that unresponsive time just after the user has clicked the submit button{/tr}.
                <br/><br/>
                {tr}You can prevent that unresponsive time by means of setting Tiki to store notification emails in an email queue (see <strong>Admin home > General > General Preferences > Mail > Mail Sender > STMP</strong>, and <strong>Mail Delivery > Queue</strong>), and request the server to process the email sending based on a cron job{/tr}.
                <br/><br/>
                {tr}Example. Send the Mail Queue{/tr}
                <pre>php console.php mail-queue[{tr}colon{/tr}]send</pre>
                {tr}Or{/tr}
                <pre>php console.php m[{tr}colon{/tr}]s</pre>
                <br/><br/>
                {tr}Click to read more{/tr}
	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
            <li>{tr}And since Tiki12.3, console.php also handles <strong>Daily Reports for User Watches</strong>, which finally allows this feature to be used in '<em>MultiTiki</em>' setups{/tr}.
                <a href="http://doc.tiki.org/Daily+Reports" target="tikihelp" class="tikihelp" title="{tr}Daily Reports for User Watches:{/tr}
                {tr}This feature which allows users to choose to received notification emails grouped in a periodic digest with the frequency they choose (daily, weekly, ...){/tr}.
                <br/><br/>
                {tr}Example. Send the Daily Report{/tr}
                <pre>php console.php daily-report[{tr}colon{/tr}]send --site==site1.example.com</pre>
                <pre>php console.php daily-report[{tr}colon{/tr}]send --site==site2.example.com</pre>
                {tr}Or{/tr}
                <pre>php console.php d[{tr}colon{/tr}]s --site==site1.example.com</pre>
                <pre>php console.php d[{tr}colon{/tr}]s --site==site2.example.com</pre>
                <br/><br/>
                {tr}Click to read more{/tr}
	    	">
                    <img src="img/icons/help.png" alt="" width="16" height="16" class="icon" />
                </a>
        </ul>
    </fieldset>

    <fieldset>
        <legend>{tr}Other Features{/tr}</legend>
        <div class="admin clearfix featurelist">
            {preference name=conditions_enabled}
            <div class="adminoptionboxchild" id="conditions_enabled_childcontainer">
                {preference name=conditions_page_name}
                {preference name=conditions_minimum_age}
            </div>
            {preference name=feature_docs}
            {preference name=feature_jcapture}
            <div class="adminoptionboxchild" id="feature_jcapture_childcontainer">
                {preference name=fgal_for_jcapture}
            </div>
            {preference name=feature_draw}
            <div class="adminoptionboxchild" id="feature_draw_childcontainer">
                {preference name=feature_draw_hide_buttons}
                {preference name=feature_draw_separate_base_image}
                <div class="adminoptionboxchild" id="feature_draw_separate_base_image_childcontainer">
                    {preference name=feature_draw_in_userfiles}
                </div>
            </div>
        </div>
    </fieldset>

</div>
