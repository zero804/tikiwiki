<div class="wiki-edithelp" width="100%" id='edithelpzone' >
<p>
<a class="wiki">{tr}TextFormattingRules{/tr}</a>
</p>
<table width=100%>
<tr><td width=20%><strong>{tr}Emphasis{/tr}:</strong></td><td> '<strong></strong>' {tr}for{/tr} <em>{tr}italics{/tr}</em>, _<em></em>_ {tr}for{/tr} <strong>{tr}bold{/tr}</strong>, '<strong></strong>'_<em></em>_ {tr}for{/tr} <em><strong>{tr}both{/tr}</strong></em></td></tr>
<tr><td><strong>{tr}Lists{/tr}:</strong></td><td> * {tr}for bullet lists{/tr}, # {tr}for numbered lists{/tr}, ;{tr}term{/tr}:{tr}definition{/tr} {tr}for definiton lists{/tr}</td></tr>
<tr><td><strong>{tr}Wiki References{/tr}:</strong></td><td> {tr}JoinCapitalizedWords or use{/tr} (({tr}page{/tr})) {tr}or{/tr} (({tr}page|desc{/tr})) {tr}for wiki references{/tr} )){tr}SomeName{/tr}(( {tr}prevents referencing{/tr}</td></tr>
{if $feature_drawings eq 'y'}
<tr><td><strong>{tr}Drawings{/tr}:</strong></td><td> "{literal}{{/literal}draw name=foo} {tr}creates the editable drawing foo{/tr}</td></tr>
{/if}
<tr><td><strong>{tr}External links{/tr}:</strong></td><td> {tr}use square brackets for an{/tr} {tr}external link{/tr}: [URL] {tr}or{/tr} [URL|{tr}link_description{/tr}] {tr}or{/tr} [URL|{tr}description{/tr}|nocache].</td></tr>
<tr><td><strong>{tr}Multi-page pages{/tr}:</strong></td><td>{tr}use ...page... to separate pages{/tr}</td></tr>
<tr><td><strong>{tr}Misc{/tr}:</strong></td><td> "!", "!!", "!!!" {tr}make_headings{/tr}, "-<em></em>-<em></em>-<em></em>-" {tr}makes a horizontal rule{/tr} "==={tr}text{/tr}===" {tr}underlines text{/tr}</td></tr>
<tr><td><strong>{tr}Title bar{/tr}:</strong></td><td> "-={tr}title{/tr}=-" {tr}creates a title bar{/tr}.</td></tr>
<tr><td><strong>{tr}Images{/tr}:</strong></td><td> "{literal}{{/literal}img src=http://example.com/foo.jpg width=200 height=100 align=center link=http://www.yahoo.com desc=foo}" {tr}displays an image{/tr} {tr}height width desc link and align are optional{/tr}</td></tr>
<tr><td><strong>{tr}Non cacheable images{/tr}:</strong></td><td> "{literal}{{/literal}img src=http://example.com/foo.jpg?nocache=1 width=200 height=100 align=center link=http://www.yahoo.com desc=foo}" {tr}displays an image{/tr} {tr}height width desc link and align are optional{/tr}</td></tr>
<tr><td><strong>{tr}Tables{/tr}:</strong></td><td> "||row1-col1|row1-col2|row1-col3||row2-col1|row2-col2col3||" {tr}creates a table{/tr}</td></tr>
<tr><td><strong>{tr}RSS feeds{/tr}:</strong></td><td> "{literal}{{/literal}rss id=n max=m{literal}}{/literal}" {tr}displays rss feed with id=n maximum=m items{/tr}</td></tr>
<tr><td><strong>{tr}Simple box{/tr}:</strong></td><td> "^{tr}Box content{/tr}^" {tr}Creates a box with the data{/tr}</td></tr>
<tr><td><strong>{tr}Dynamic content{/tr}:</strong></td><td> "{literal}{{/literal}content id=n}" {tr}Will be replaced by the actual value of the dynamic content block with id=n{/tr}</td></tr>
<tr><td><strong>{tr}Colored text{/tr}:</strong></td><td> "~~#FFEE33:{tr}some text{/tr}~~" {tr}Will display using the indicated HTML color{/tr}</td></tr>
<tr><td><strong>{tr}Center{/tr}:</strong></td><td> "::{tr}some text{/tr}::" {tr}Will display the text centered{/tr}</td></tr>
<tr><td><strong>{tr}Non parsed sections{/tr}:</strong></td><td> "~np~ {tr}data{/tr} ~/np~" {tr}Prevents parsing data{/tr}</td></tr>
</table>
{if count($plugins) ne 0}
<p>
<a class="wiki">{tr}PluginsHelp{/tr}</a>
</p>
<table width=100%>
{section name=i loop=$plugins}
 <tr>
  <td width=20%><code>{$plugins[i].name}</code></td>
  <td>{if $plugins[i].help eq ''}{tr}No description available{/tr}{else}{$plugins[i].help}{/if}</td>
 </tr>
{/section}
</table>
{/if}
</div>
