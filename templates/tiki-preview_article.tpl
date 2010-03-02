<h2>{tr}Preview{/tr}: {$page}</h2>

<div class="article">
	<div class="articletitle">
		<h2>{$title}</h2>
		<span class="titleb">{tr}By:{/tr} {$authorName} {tr}on:{/tr} {$publishDate|tiki_short_datetime} ({$reads} {tr}Reads{/tr})</span>
	</div>

	{if $type eq 'Review'}
		<div class="articleheading">
			{tr}Rating{/tr}: 
			{repeat count=$rating}
				<img src="img/icons/blue.gif" alt=''/>
			{/repeat}
			{if $rating > $entrating}
				<img src="img/icons/bluehalf.gif" alt=''/>
			{/if}
			({$rating}/10)
		</div>
	{/if}

	<div class="articleheading">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top">
					{if $useImage eq 'y'}
						{if $hasImage eq 'y'}
							{if $imageIsChanged eq 'y'}
								<img alt="{tr}Article image{/tr}" src="article_image.php?image_type=preview&amp;id={$previewId}" {if $image_x > 0}width="{$image_x}"{/if}{if $image_y > 0 }height="{$image_y}"{/if} />
							{else}
								{if $subId}
									<img alt="{tr}Article image{/tr}" src="article_image.php?image_type=submission&amp;id={$subId}" />
								{else}
									<img alt="{tr}Article image{/tr}" src="article_image.php?image_type=article&amp;id={$articleId}" />
								{/if}
							{/if}
						{else}
							<img alt="{tr}Topic image{/tr}" src="article_image.php?image_type=topic&amp;id={$topicId}" />
						{/if}
					{else}
						<img alt="{tr}Topic image{/tr}" src="article_image.php?image_type=topic&amp;id={$topicId}" />
					{/if}
				</td>
				<td valign="top">
					<span class="articleheading">{$parsed_heading}</span>
				</td>
			</tr>
		</table>
	</div>

	<div style="padding:5px;" class="articletrailer">
		({$size} {tr}bytes{/tr})
	</div>

	<div class="articlebody">
		{$parsed_body}
	</div>
</div>
