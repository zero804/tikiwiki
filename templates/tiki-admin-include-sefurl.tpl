{* $Id: tiki-user_information.tpl 16912 2009-02-25 03:38:43Z luciash $ *}
<form class="admin" method="post" action="tiki-admin.php?page=sefurl">
	<div class="heading input_submit_container" style="text-align: right;">
		<input type="submit" name="save" value="{tr}Change preferences{/tr}" />
	</div>
	<fieldset class="admin">
		{if $needtowarn}
		{remarksbox type="warning" title="{tr}Warning{/tr}"}	
		{tr}SEFURL will not work unless Tiki specific directives are deployed to the .htaccess file. You will need to execute htaccess.sh or manually configure the .htaccess file.{/tr}	
		{/remarksbox}
		{/if}
		<legend>{tr}Settings{/tr}</legend>
		{preference name=feature_sefurl}
		{preference name=feature_sefurl_filter}

		<div style="padding:0.5em;clear:both">
			<label for="feature_sefurl_paths">
				{tr}List of Url Parameters that should go in the path{/tr}
			</label>
			{strip}
				{capture name=paths}
					{foreach name=loop from=$prefs.feature_sefurl_paths item=path}
						{$path}
						{if !$smarty.foreach.loop.last}/{/if}
					{/foreach}
				{/capture}
			{/strip}
			<input type="text" id="feature_sefurl_paths" name="feature_sefurl_paths" value="{$smarty.capture.paths|escape}" />
		</div>

		{preference name=feature_sefurl_title_article}
		{preference name=feature_sefurl_title_blog}

	</fieldset>
	<div class="heading input_submit_container" style="text-align: center;padding:1em;">
		<input type="submit" name="save" value="{tr}Change preferences{/tr}" />
	</div>
</form>
