{* $Id$ *}<!DOCTYPE html>
<html id="print" lang="{if !empty($pageLang)}{$pageLang}{else}{$prefs.language}{/if}">
	<head>
{include file='header.tpl'}
	</head>
	<body{html_body_attributes}>

		<div id="tiki-clean">
			<header class="articletitle">
				<h2>{$title|escape}</h2>
				<span class="titleb">{tr}Author:{/tr} {$authorName|escape} {$publishDate|tiki_short_datetime:'Published At:'} ({$reads} {tr}Reads{/tr})</span>
			</header>
	
			<div class="articleheading">
{if $useImage eq 'y'}
	{if $hasImage eq 'y'}
				<img alt="{tr}Article image{/tr}" src="article_image.php?image_type=article&amp;id={$articleId}"{if $image_x lt 0} width="{$image_x}"{/if}{if $image_y gt 0} height="{$image_y}"{/if}>
	{/if}
{elseif $topicId ne 0}
				<img alt="{tr}Topic image{/tr}" src="article_image.php?image_type=topic&amp;id={$topicId}">
{/if}
				<div class="articleheadingtext">{$parsed_heading}</div>
			</div>

{if $show_size eq 'y'}
			<div class="articletrailer">
				({$size} {tr}bytes{/tr})
			</div>
{/if}
	
			<div class="articlebody">
{if $tiki_p_read_article eq 'y'}
				{$parsed_body}
{else}
				<div class="error simplebox">
					{tr}You do not have permission to read complete articles.{/tr}
				</div>
{/if}
			</div>
		</div>

{include file='footer.tpl'}
	</body>
</html>
