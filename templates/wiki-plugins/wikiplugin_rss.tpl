{if $title}
	<div class="rsstitle">
		<a target="_blank" href="{$title.url|escape}">{$title.title|escape}</a>
	</div>
{/if}
<ul class="rsslist">
	{foreach from=$items item=item}
            {if $item.icon and $showicon}
            <div style="list-style:square inside url({$item.icon})">
            {/if}
		<li class="rssitem">
                    <a target="_blank" href="{$item.url|escape}">{$item.title|escape}</a>

                    {if $item.author and $showauthor and $item.pubDate and $showdate}
				&nbsp;&nbsp;&nbsp;({$item.author|escape}, <span class="rssdate">{$item.pubDate|escape}</span>)
                    {elseif $item.author and $showauthor}
				&nbsp;&nbsp;&nbsp;({$item.author|escape})
                    {elseif $item.pubDate and $showdate}
				&nbsp;&nbsp;&nbsp;(<span class="rssdate">{$item.pubDate|escape}</span>)
                    {/if}
			
                    {if $item.description && $showdesc}
				<div class="rssdescription">
					{$item.description|escape}
				</div>
                    {/if}
		</li>
            {if $item.icon and $showicon}
            </div>
            {/if}
	{/foreach}
</ul>
