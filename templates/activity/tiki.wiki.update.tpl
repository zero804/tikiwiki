{activityframe activity=$activity heading="{tr _0=$activity.user|userlink}%0 modified a page{/tr}"}
	<p>{object_link type=$activity.type id=$activity.object}</p>
	<small>{tr}View changes:{/tr} <a href="tiki-pagehistory.php?page=Registered+HomePage&oldver={$activity.old_version|escape}">{tr}history{/tr}</small>
	{if is_array($activity.aggregate)}
	<small>{$activity.aggregate.user|userlink}</small>
	{/if}
{/activityframe}
