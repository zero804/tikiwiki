<div id="thumbnails" style="float:left">

  {section name=changes loop=$files}

  {* Checkboxes *}
  {if $files[changes].isgal eq 1}
    {assign var=checkname value=$subgal_checkbox_name|default:'subgal'}
  {else}
    {assign var=checkname value=$file_checkbox_name|default:'file'}
  {/if}
  {if $gal_info.show_checked neq 'n' and $smarty.request.$checkname and in_array($files[changes].id,$smarty.request.$checkname)}
    {assign var=is_checked value='y'}
  {else}
    {assign var=is_checked value='n'}
  {/if}

  {* do not show subgals in browsing view *}
  {if $files[changes].isgal neq 1}

    {if ( $prefs.use_context_menu_icon eq 'y' or $prefs.use_context_menu_text eq 'y' ) and $gal_info.show_action neq 'n'}
      {capture name=over_actions}{strip}
      <div class='opaque'>
        <div class='box-title'>{tr}Actions{/tr}</div>
        <div class='box-data'>
          {include file=fgal_context_menu.tpl menu_icon=$prefs.use_context_menu_icon menu_text=$prefs.use_context_menu_text}
        </div>
      </div>
      {/strip}{/capture}
    {/if}
     
    {assign var=nb_over_infos value=0}
    {capture name=over_infos}{strip}
    <div class='opaque'>
      <div class='box-title'>{tr}Properties{/tr}</div>
      <div class='box-data'>
        <div>
        {foreach item=prop key=propname from=$fgal_listing_conf}
          {if isset($prop.key)}
            {assign var=propkey value=$item.key}
          {else}
            {assign var=propkey value="show_$propname"}
          {/if}
          {assign var=propval value=$files[changes].$propname}
  
          {* Format property values *}
          {if $propname eq 'created' or $propname eq 'lastmodif'}
            {assign var=propval value=$propval|tiki_long_date}
          {elseif $propname eq 'last_user' or $propname eq 'author' or $propname eq 'creator'}
            {assign var=propval value=$propval|username}
          {elseif $propname eq 'size'}
            {assign var=propval value=$propval|kbsize:true}
          {/if}
    
          {if isset($gal_info.$propkey) and $propval neq '' and $propname neq 'name' and ( $gal_info.$propkey eq 'a' or $gal_info.$propkey eq 'o' or ( $gal_info.$propkey eq 'y' and $show_details neq 'y' ) ) }
            <b>{$fgal_listing_conf.$propname.name}</b>: {$propval}<br />
            {assign var=nb_over_infos value=`$nb_over_infos+1`}
          {/if}
        {/foreach}
        </div>
      </div>
    </div>
    {/strip}{/capture}

    {if $nb_over_infos gt 0}
      {assign var=over_infos value=$smarty.capture.over_infos}
    {else}
      {assign var=over_infos value=''}
    {/if}

    {* build link *}
    {capture assign=link}{strip}
      {if $files[changes].isgal eq 1}
        href="tiki-list_file_gallery.php?galleryId={$files[changes].id}{if $filegals_manager neq ''}&amp;filegals_manager={$filegals_manager|escape}{/if}&amp;view=browse"
      {else}
        {if $filegals_manager neq ''}
          {assign var=seturl value="`$url_path`tiki-download_file.php?fileId=`$files[changes].id`&display"}

          {* Note: When using this code inside FCKeditor, SetMyUrl function is not defined and we use FCKeditor SetUrl native function *}
          href="javascript:if (typeof window.opener.SetMyUrl != 'undefined') window.opener.SetMyUrl('{$filegals_manager|escape}','{$seturl}'); else window.opener.SetUrl('{$seturl}'); checkClose();"

        {elseif $tiki_p_download_files eq 'y'}
          {if $gal_info.type eq 'podcast' or $gal_info.type eq 'vidcast'}
            href="{$prefs.fgal_podcast_dir}{$files[changes].path}"
          {else}
            href="tiki-download_file.php?fileId={$files[changes].id}&amp;{if $prefs.javascript_enabled eq 'y'}preview{else}display=y{/if}"
          {/if}
        {/if}
      {/if}
    {/strip}{/capture}

    {math equation="x + 6" x=$thumbnail_size assign=thumbnailcontener_size}

    <div id="{$checkname}_{$files[changes].id}" class="clearfix thumbnailcontener{if $is_checked eq 'y'} thumbnailcontenerchecked{/if}" style="float:left; width:{$thumbnailcontener_size}px">

      <div class="thumbnail" style="float:left; width:{$thumbnailcontener_size}px">
        <div class="thumbimagecontener" style="width:{$thumbnail_size}px;height:{$thumbnailcontener_size}px{if $show_infos neq 'y'};margin-bottom:4px{/if}">
          <div class="thumbimage">
            <div class="thumbimagesub" style="width:{$thumbnail_size}px;">{assign var=key_type value=$files[changes].type|truncate:9:'':true}
              <a {$link}{if $prefs.feature_shadowbox eq 'y' && $filegals_manager eq ''} rel="shadowbox[gallery];type={if $key_type eq 'image/png' or $key_type eq 'image/jpe' or $key_type eq 'image/gif'}img{else}iframe{/if}"{/if}{if $over_infos neq ''} {popup fullhtml="1" text=$over_infos|escape:"javascript"|escape:"html"}{else} title="{if $files[changes].name neq ''}{$files[changes].name|escape}{/if}{if $files[changes].description neq ''} ({$files[changes].description|escape}){/if}"{/if}>
                <img src="tiki-download_file.php?fileId={$files[changes].id}&amp;thumbnail" />
              </a>
            </div>
          </div>
        </div>

	{if $show_infos eq 'y'}
        <div class="thumbinfos">
        {foreach from=$fgal_listing_conf item=item key=propname}

          {if isset($item.key)}
            {assign var=key_name value=$item.key}
          {else}
            {assign var=key_name value="show_$propname"}
          {/if}
          {if isset($gal_info.$key_name) and ( $gal_info.$key_name eq 'y' or $gal_info.$key_name eq 'a' or $gal_info.$key_name eq 'i' or $propname eq 'name' ) }
            {assign var=propval value=$files[changes].$propname|escape}
        
            {* Format property values *}
            {if $propname eq 'id' or $propname eq 'name'}
              {if $propname eq 'name' and $propval eq '' and $gal_info.show_name eq 'n'}
                {* show the filename if only name should be displayed but is empty *}
                {assign var=propval value=$files[changes].filename}
                {assign var=propval value="<a class='fgalname namealias' $link>$propval</a>"}
              {else}
                {assign var=propval value="<a class='fgalname' $link>$propval</a>"}
              {/if}
            {elseif $propname eq 'created' or $propname eq 'lastmodif'}
              {assign var=propval value=$propval|tiki_short_date}
            {elseif $propname eq 'last_user' or $propname eq 'author' or $propname eq 'creator'}
              {assign var=propval value=$propval|userlink}
            {elseif $propname eq 'size'}
              {assign var=propval value=$propval|kbsize:true}
            {elseif $propname eq 'description' and $gal_info.max_desc gt 0}
              {assign var=propval value=$propval|truncate:$gal_info.max_desc:"...":false}
            {elseif $propname eq 'lockedby' and $propval neq ''}
              {assign var=propval value=$propval|userlink}
            {/if}
        
            {if $propname eq 'name'}
          <div class="thumbnamecontener">
            <div class="thumbname">
              <div class="thumbnamesub" style="width:{$thumbnail_size}px">
                {if $gal_info.show_name eq 'f' or ($gal_info.show_name eq 'a' and $files[changes].name eq '')}
                  <a class="fgalname" {$link} title="{$files[changes].filename}">{$files[changes].filename}</a>
                {else}
                  {$propval}
                {/if}
              </div>
            </div>
          </div>

          <div class="thumbinfosothers"{if $show_details eq 'n'} style="display:none"{/if}>

            {elseif $propval neq '' and $propname neq 'name' and $propname neq 'type'}

            <div class="thumbinfo{if $propname eq 'description'} thumbdescription{/if}">
              <span class="thumbinfoname">{$item.name}:</span>
              <span class="thumbinfoval"{if $propname neq 'description'} style="white-space: nowrap"{/if}>{$propval}</span>
            </div>

            {/if}
          {/if}
        {/foreach}
          </div> {* thumbinfosothers *}
        </div> {* thumbinfos *}
        {/if}
  
      </div> {* thumbnail *}

      <div class="thumbactions" style="float:right; width:{$thumbnail_size}px">

      {if $gal_info.show_checked neq 'n' and $tiki_p_admin_file_galleries eq 'y'}
        <label style="float:left"><input type="checkbox" onclick="flip_thumbnail_status('{$checkname}_{$files[changes].id}')" name="{$checkname}[]" value="{$files[changes].id|escape}" {if $is_checked eq 'y'}checked="checked"{/if} />{if isset($checkbox_label)}{$checkbox_label}{/if}</label>
      {/if}

      {if $gal_info.show_action neq 'n'}
        {if ( $prefs.use_context_menu_icon eq 'y' or $prefs.use_context_menu_text eq 'y' ) and $prefs.javascript_enabled eq 'y'}
          <a class="fgalname" title="{tr}Actions{/tr}" href="#" {popup trigger="onClick" sticky=1 mouseoff=1 fullhtml="1" text=$smarty.capture.over_actions|escape:"javascript"|escape:"html"}>{icon _id='wrench' alt='{tr}Actions{/tr}'}</a>
        {else}
          {include file=fgal_context_menu.tpl}
        {/if}
      {/if}

      </div> {* thumbactions *}
    </div> {* thumbnailcontener *}
  {/if}

  {sectionelse}
    <div>
      <b>{tr}No records found{/tr}</b>
    </div>
  {/section}

</div>
<br clear="all" />

{if $gal_info.show_checked neq 'n' and $tiki_p_admin_file_galleries eq 'y' and ( !isset($show_selectall) or $show_selectall eq 'y' )}
	{select_all checkbox_names='file[],subgal[]' label="{tr}Select All{/tr}"}
{/if}
