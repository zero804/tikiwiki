<?php
/* by robferguson
 *
 * FOOTNOTE plugin. Inserts a superscripted number where the plugin is written starting with one and counting up as the additional footnotes are added.
 * 
 * Syntax:
 * 
 * {FOOTNOTE()/}
 */
function wikiplugin_footnote_help() {
	return tra("Inserts a superscripted footnote number next to text and takes in footnote as parameter").":<br />~np~{FOOTNOTE()}insert footnote here{FOOTNOTE}~/np~";
}

function wikiplugin_footnote_info() {
	return array(
		'name' => tra( 'Footnote' ),
		'documentation' => 'PluginFootnote',
		'description' => tra( 'Inserts a superscripted footnote number next to text and takes in footnote as parameter.' ),
		'prefs' => array('wikiplugin_footnote'),
		'body' => tra('The footnote'),
		'params' => array(
                    'sameas' => array(
                          'required' => false,
                           'name' => tra('Sameas'),
                           'description' => tra('Tag to existing footnote' )),
                    'checkDuplicate' => array(
                          'required' => false,
                           'name' => tra('CheckDuplicate'),
                           'description' => tra('check for duplcate footnotes'))
			   )
	);
}

function wikiplugin_footnote($data, $params) {
        if (empty($params)) {
		$GLOBALS["footnoteCount"]++;
		$footnoteCount = $GLOBALS["footnoteCount"];
		$GLOBALS["footnotesData"][] = trim($data);
        } else {
	        extract($params, EXTR_SKIP);
                if (!empty($sameas)) { 
        	   $footnoteCount = $sameas;
                } else {
                if (ucfirst($checkDuplicate) == "Y") {
	        foreach($GLOBALS["footnotesData"] as $key => $value){
	           if ( strcmp(trim($data), $value) == 0 )   {
                       $footnoteCount = $key + 1;
                       break;
                       }
                  }
	        }
	     }   // else for if (!empty($sameas
	}    // else for if (empty($params

	$html = '{SUP()}'
				. "<a id=\"ref_footnote$footnoteCount\" href=\"#footnote$footnoteCount\">$footnoteCount</a>"
				.	'{SUP}';

	return $html;
}
