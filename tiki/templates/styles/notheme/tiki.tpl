{* $Header: /cvsroot/tikiwiki/tiki/templates/styles/notheme/tiki.tpl,v 1.3 2003-08-14 00:48:27 zaufi Exp $ *}
{* Index we display a wiki page here *}

{include file="header.tpl"}
<div id="tiki-main">
  {if $feature_top_bar eq 'y'}
  <div id="tiki-top">
    {include file="tiki-top_bar.tpl"}
  </div><!-- top -->
  {/if}

  <div id="tiki-mid">

    {* Display left modules if available *}

    {if $feature_left_column eq 'y' and count($left_modules) gt 0}
      <div id="leftcolumn">
        {section name=homeix loop=$left_modules}
          {$left_modules[homeix].data}
        {/section}
      </div><!-- left -->
    {/if}

    {* Calculate size of center div *}

    {php} $add_style=''; {/php}
    {if $feature_left_column eq 'y' and count($left_modules) gt 0}
	{php} $add_style.='margin-left: 190px;'; {/php}
    {/if}
    {if $feature_right_column eq 'y' and count($right_modules) gt 0}
	{php} $add_style.='margin-right: 190px;'; {/php}
    {/if}
    {php}
    global $style;
	$add_style = strlen($add_style) && !strstr($style, "II") ? 'style="'.$add_style.'"' : ''; 
	global $smarty;
	$smarty->assign('add_style', $add_style);
    {/php}

    {* Display center column *}

    <div id="tiki-center" {$add_style}>
      {include file=$mid}
      {if $show_page_bar eq 'y'}
        {include file="tiki-page_bar.tpl"}
      {/if}
      {if $feature_bot_bar eq 'y' and strstr($style, "II") ne ''}
        <div id="tiki-bot">
          {include file="tiki-bot_bar.tpl"}
        </div><!-- bottom -->
      {/if}
    </div><!-- center -->

    {* Display right modules if available *}

    {if $feature_right_column eq 'y' and count($right_modules) gt 0}
      <div id="rightcolumn">
        {section name=homeix loop=$right_modules}
          {$right_modules[homeix].data}
        {/section}
      </div><!-- right -->
    {/if}

  </div><!-- div id="tiki-mid" -->

  {if $feature_bot_bar eq 'y' and strstr($style, "II") eq ''}
    <div id="tiki-bot" {$add_style}>
      {include file="tiki-bot_bar.tpl"}
    </div><!-- bottom -->
  {/if}


</div><!-- tiki-main -->

{include file="footer.tpl"}
