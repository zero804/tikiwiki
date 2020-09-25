{extends "layout_view.tpl"}

{block name="title"}
	{title}{$title}{/title}
{/block}

{block name="navigation"}
	<div class="t_navbar mb-4">
		{permission name=admin_machine_learning}
			<a class="btn btn-link" href="{service controller=ml action=create}">{icon name=create} {tr}New{/tr}</a>
		{/permission}
		<a class="btn btn-link" href="{service controller=ml action=list}">{icon name=list} {tr}Manage{/tr}</a>
	</div>
{/block}

{block name="content"}
	<p>{$model.description|escape|nl2br}</p>
	<p>{tr}Use this model by entering a sample information in the form below and execute a query against the trained model. This will produce results based on the chosen estimator and show you the most relevant matches.{/tr}</p>
	<form class="use-ml" method="post" action="{service controller=ml action=use mlmId=$model.mlmId}">
		{trackerfields trackerId=$trackerId fields=$fields}
		<div class="submit">
			<input
				type="submit"
				class="btn btn-primary"
				value="{tr}Submit{/tr}"
			>
		</div>
	</form>
	{if $results}
		<br/>
		{foreach from=$results key=$itemId item=row}
			<p>
				{object_link type=trackeritem id=$itemId}:
				{foreach $row.fields as $field}
					{trackeroutput field=$field}
				{/foreach}
			</p>
		{/foreach}
	{/if}
{/block}
