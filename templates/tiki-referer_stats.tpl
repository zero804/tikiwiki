{title help="Referer+Stats"}{tr}Referer stats{/tr}{/title}

<div class="navbar">
	{button href="tiki-referer_stats.php?clear=1" _text="{tr}Clear Stats{/tr}"}
</div>

{include file='find.tpl' _sort_mode='y'}

<table class="normal">
  <tr>
  <th>
    <a href="tiki-referer_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'referer_desc'}referer_asc{else}referer_desc{/if}">{tr}Word{/tr}</a>
  </th>
  <th>
    <a href="tiki-referer_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'hits_desc'}hits_asc{else}hits_desc{/if}">{tr}Hits{/tr}</a>
  </th>
  <th>
    <a href="tiki-referer_stats.php?offset={$offset}&amp;sort_mode={if $sort_mode eq 'last_desc'}last_asc{else}last_desc{/if}">{tr}Last{/tr}</a>
  </th>
  </tr>
  {cycle values="odd,even" print=false}
  {section name=user loop=$channels}
    <tr>
      <td class="{cycle advance=false}">{$channels[user].referer}</td>
      <td class="{cycle advance=false}">{$channels[user].hits}</td>
      <td class="{cycle}">{$channels[user].last|tiki_short_datetime}</td>
    </tr>
  {/section}
</table>

{pagination_links cant=$cant_pages step=$prefs.maxRecords offset=$offset }{/pagination_links}
