<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_pluginmanager_help() {
	return tra("Displays a list of plugins available in this wiki.").":<br />~np~{PLUGINMANAGER(info=>version|description|arguments)}{PLUGINMANAGER}~/np~";
}
/**
* Include the library {@link PluginsLib}
*/
require_once "lib/wiki/pluginslib.php";
/**
* Plugin Manager
* Displays a list of plugins available in this wiki.
*
* Params:
* <ul>
* <li>info (allows multiple columns, joined by '|') : version,description,arguments
*           . By default, selected all.
* </ul>
*
* @package Tikiwiki
* @subpackage TikiPlugins
* @author Claudio Bustos
* @version $Revision: 1.11 $
*/
class WikiPluginPluginManager extends PluginsLib
{
    var $expanded_params = array('info');
    function getDefaultArguments() {
        return array(
					'info' => 'description|parameters|paraminfo', 
					'plugin' => '', 
					'singletitle' => 'table',
        			'titletag' => 'h3',
        			'start' => '',
        			'limit' => '',
        		);
    }
    function getName() {
        return "PluginManager";
    }
    function getVersion() {
        return preg_replace("/[Revision: $]/", '',
            "\$Revision: 1.11 $");
    }
    function getDescription() {
        return wikiplugin_pluginmanager_help();
    }
    function run($data, $params) {
        global $wikilib, $helpurl, $tikilib;
        if (!is_dir(PLUGINS_DIR)) {
            return $this->error("No plugins directory defined");
        }
        if (empty($helpurl)) {
        	$helpurl = 'http://doc.tikiwiki.org/';
        }
        
        $params = $this->getParams($params);
        extract($params,EXTR_SKIP);
              
        $aPrincipalField = array('field' => 'plugin', 'name' => 'Plugin');
        
        $aPlugins = array();
        if (!empty($plugin)) {
	        if (strpos($plugin, '|') !== false) {
	        	$userlist = explode('|',$plugin);
	        	foreach ($userlist as $useritem) {
	        		$aPlugins[] = 'wikiplugin_' . $useritem . '.php';
	        	}
        	} elseif (strpos($plugin, '-') !== false) {
        		$userrange = explode('-',$plugin);
        		$aPlugins = $wikilib->list_plugins();
        		$begin = array_search('wikiplugin_' . $userrange[0] . '.php', $aPlugins);
        		$end = array_search('wikiplugin_' . $userrange[1] . '.php', $aPlugins);
        		$beginerror = '';
        		$enderror = '';
        		if ($begin === false || $end === false) {
        			if ($begin === false) {
        				$beginerror = $userrange[0];
        			} 
        			if ($end === false) {
        				$enderror = $userrange[1];
        				!empty($beginerror) ? $and = ' and ' : $and = '';
        			}
        			return tra('^Plugin Manager error: ') . $beginerror . $and . $enderror . tra(' not found^');
        		} elseif ($end > $begin) {
        			$aPlugins = array_slice($aPlugins, $begin, $end-$begin+1);
        		} else {
        			$aPlugins = array_slice($aPlugins, $end, $begin-$end+1);
        		}     		
        	} elseif (!empty($limit)) { 
        		$aPlugins = $wikilib->list_plugins();
        		$begin = array_search('wikiplugin_' . $plugin . '.php', $aPlugins); 
        		if ($begin === false) {
        			return tra('^Plugin manager error: ') . $begin . tra(' not found^');
        		} else {
        			$aPlugins = array_slice($aPlugins, $begin, $limit);
        		}
        	} else {
        		$aPlugins[] = 'wikiplugin_' . $plugin . '.php';
        	}
        } else {
        	$aPlugins = $wikilib->list_plugins();
        	if (!empty($start) || !empty($limit)) {
        		if (!empty($start) && !empty($limit)) {
        			$aPlugins = array_slice($aPlugins, $start-1, $limit);
        		} elseif (!empty($start)) {
        			$aPlugins = array_slice($aPlugins, $start-1);
        		} else {			
        			$aPlugins = array_slice($aPlugins, 0, $limit);
        		}
        	}
        }
        
        $aData=array();
        if ($singletitle == 'table' || count($aPlugins) > 1) {
        	foreach($aPlugins as $sPluginFile) {
	        	global $sPlugin, $numparams;
	        	$infoPlugin = get_plugin_info($sPluginFile);
	    		if (in_array('description',$info)) {
					if (isset($infoPlugin['description'])) {
						if ($numparams > 1) {
		    				$aData[$sPlugin]['description']['onekey'] = $infoPlugin['description'];
						} else {
							$aData[$sPlugin]['description'] = $infoPlugin['description'];
						}
					} else {
						$aData[$sPlugin]['description'] = ' --- ';
					}
	    		}
		   		if (in_array('parameters',$info)) {
	    			if ($numparams > 0) {
		    			if ($aPrincipalField['field'] == 'plugin' && !in_array('options',$info) && $numparams > 1) {
		    				$aData[$sPlugin][$aPrincipalField['field']]['rowspan'] = $numparams;
		    				if (in_array('description',$info)) {
		    					$aData[$sPlugin]['description']['rowspan'] = $numparams;
		    				}
		    			}
		    			foreach($infoPlugin['params'] as $paramname => $param) {
		    				if (isset($infoPlugin['params'][$paramname]['description'])) {
		    					$paramblock = '~np~' . $infoPlugin['params'][$paramname]['description'] . '~/np~';
		    				}
		    				if (isset($param['options']) && is_array($param['options'])) {
		    					$paramblock .= '<br /><br /><em>Options:</em> ';
		    					$i = 0;
								foreach($param['options'] as $oplist => $opitem) {
									if (!empty($opitem['value'])) {
										$paramblock .= $opitem['value'];
									} else {
										$paramblock .=  $opitem['text'];
									}
									$paramblock .= ' | ';
									$i++;
								}
								$paramblock = substr($paramblock, 0, -3);
							}
		    				if (isset($infoPlugin['params'][$paramname]['required']) && $infoPlugin['params'][$paramname]['required'] == true) {
		    					$aData[$sPlugin]['parameters']['<b>' . $paramname . '</b>'] = $paramblock;
		    				} else {
		    					$aData[$sPlugin]['parameters'][$paramname] = $paramblock;
		    				}
		    			}
					} else {
						$aData[$sPlugin]['parameters']['<em>no parameters</em>'] = '<em>n/a</em>';
					}
				} 
				$aData[$sPlugin]['plugin']['plugin'] = '[' . $helpurl . 'Plugin' . ucfirst($sPlugin) . '|' . ucfirst($sPlugin) . ']';
	        } // Plugins Loop
        	return PluginsLibUtil::createTable($aData, $info, $aPrincipalField);
        } else {
        	//Replicates a documentation table for parameters for a single plugin
        	//Don't use plugin lib table to avoid making custom modifications
			global $sPlugin, $numparams;
	        $infoPlugin = get_plugin_info($aPlugins[0]);
        	if ($singletitle == 'top') {
        		$title = '<' . $titletag . '>['. $helpurl . 'Plugin' . ucfirst($sPlugin) 
        			. '|' . ucfirst($sPlugin) . ']</' . $titletag . '>';
        		$title .= $infoPlugin['description'] . '<br />';
        		if (isset($infoPlugin['introduced'])) {
        			$title .= '<em>Introduced in Tiki version ' . $infoPlugin['introduced'] . '</em><br />';
        		}
        		$title .= '<br />';
        	} else {
        		$title = '';
        	}
        	$headbegin = "\n\t\t" . '<td class="heading">';
        	$cellbegin = "\n\t\t" . '<td>';
        	$header =  "\n\t" . '<tr class="heading">' . $headbegin . 'Parameters</td>';
        	if (isset($numparams) && $numparams > 0) {
        		$header .= $headbegin . 'Accepted Values</td>';
 		       	$header .= $headbegin . 'Description</td>';
        		$rowCounter = 1;
        		foreach ($infoPlugin['params'] as $paramname => $paraminfo) {
        			$class = ($rowCounter%2) ? 'odd' : 'even';
        			$rows .= "\n\t" . '<tr class="' . $class . '">' . $cellbegin;
        			//Parameters column
        			if (isset($paraminfo['required']) && $paraminfo['required'] == true) {
        				$rows .= '<b>' . $paramname . '</b>';
        			} else {
        				$rows .= $paramname;
        			}
        			$rows .= '</td>';
        			$rows .= $cellbegin;
        			//Accepted Values column
        			if (isset($paraminfo['accepted'])) {
        				$rows .= $paraminfo['accepted'] . '</td>';
        			} elseif (isset($paraminfo['options'])) {
        				$optcounter = 1;
        				$numoptions = count($paraminfo['options']);
						foreach($paraminfo['options'] as $oplist => $opitem) {
							if (isset($opitem['value'])) {
								$rows .= $opitem['value'];
							} else {
								$rows .=  $opitem['text'];
							}
							if ($optcounter < $numoptions) {	
								if ($numoptions > 10) {
									$rows .= ' | ';
								} else {
									$rows .= '<br />';
								}
							}
							$optcounter++;
						}
						$rows .= '</td>';
        			} elseif (isset($paraminfo['filter'])) {
        				if ($paraminfo['filter'] == 'striptags') {
        					$rows .= 'any string except for HTML and PHP tags';
        				} else {
        					$rows .= $paraminfo['filter'];
        				}
        				$rows .= '</td>';
        			} else {
        				$rows .= '</td>';
        			}
        			//Description column
        			$rows .= $cellbegin . $paraminfo['description'] . '</td>';
        			//Default column
        			if (isset($paraminfo['default'])) {
        				if ($rowCounter == 1) {
        					$header .= $headbegin . 'Default</td>';
        				}
						$rows .= $cellbegin . $paraminfo['default'] . '</td>';
        			}
        		    if (isset($paraminfo['since'])) {
        				if ($rowCounter == 1) {
        					$header .= $headbegin . 'Since</td>';
        				}
						$rows .= $cellbegin . $paraminfo['since'] . '</td>';
        			}
 		       		$rows .= "\n\t" . '</tr>';
 		       		$rowCounter++;
        		}
        	} else {
        		$rows .= "\n\t" . '<tr class="odd">' . $cellbegin . '<em>no parameters</em></td>';
        	}
        	$header .= "\n\t" . '</tr>';
        	$sOutput = $title . '<em>Required parameters are in</em> <b>bold</b><br /><table class="normal">' . $header . $rows . '</table>' . "\n";
        	return $sOutput;
        }
    }
    function processDescription($sDescription) {
        $sDescription=str_replace(",",", ",$sDescription);
        $sDescription=str_replace("|","| ",$sDescription);
        $sDescription=strip_tags(wordwrap($sDescription,35));
        return $sDescription;
    }
}

function wikiplugin_pluginmanager_info() {
    return array(
    	'name' => tra('Plugin Manager'),
    	'documentation' => 'PluginManager',
    	'description' => tra("Displays a list of plugins available in this wiki."),
    	'prefs' => array( 'wikiplugin_pluginmanager' ),
    	'introduced' => 3,
    	'params' => array(
    		'info' => array(
    			'required' => false,
    			'name' => tra('Information'),
    			'description' => tra('Determines what information is shown. Values separated with | . Ignored when singletitle is set to top or none.'),
   				'filter' => 'striptags',
    			'accepted' => 'One or more of: description | parameters | paraminfo',
    			'default' => 'description | parameters | paraminfo ',
    			'since' => '',    
    		),
			'plugin' => array(
    			'required' => false,
    			'name' => tra('Plugin'),
    			'description' => tra('Name of a plugin (e.g., backlinks), or list separated by |, or range separated by "-". Single plugin can be used with limit parameter.'),
    			'filter' => 'striptags',
    			'default' => 'none',
    			'since' => '',    				
    		),
    		'singletitle' => array(
    			'required' => false,
    			'name' => tra('Single Title'),
    			'description' => tra('Set placement of plugin name and description when displaying information for only one plugin'),
    			'filter' => 'alpha', 
    			'default' => 'table',
    			'since' => '5.1', 
    			'options' => array(
					array('text' => tra('Top'), 'value' => 'top'), 
					array('text' => tra('Table'), 'value' => 'table'), 
					array('text' => tra('None'), 'value' => 'none'), 
				),  				
    		),
    		'titletag' => array(
    			'required' => false,
    			'name' => tra('Title heading size'),
    			'description' => tra('Sets the heading size for the title, e.g., h2.'),
    			'filter' => 'striptags',
    			'default' => 'h3',
    			'since' => '5.1',    				
    		),
    		'start' => array(
    			'required' => false,
    			'name' => tra('Start'),
    			'description' => tra('Start with this plugin record number (must be an integer 1 or greater).'),
    			'filter' => 'digits',
    			'default' => '',
    			'since' => '5.1',    				
    		),
    		'limit' => array(
    			'required' => false,
    			'name' => tra('Limit'),
    			'description' => tra('Number of plugins to show. Can be used either with start or plugin as the starting point. Must be an integer 1 or greater.'),
    			'filter' => 'digits',
    			'default' => '',
    			'since' => '5.1',    				
    		),
    	),
    );
}

function get_plugin_info($sPluginFile) {
	global $wikilib, $helpurl, $tikilib;
	require_once 'lib/wiki/pluginslib.php';
	preg_match("/wikiplugin_(.*)\.php/i", $sPluginFile, $match);
	global $sPlugin, $numparams;
	$sPlugin= $match[1];
	include_once(PLUGINS_DIR.'/'.$sPluginFile);
	$infoPlugin = $tikilib->plugin_info($sPlugin);
	$numparams = isset($infoPlugin['params']) ? count($infoPlugin['params']) : 0;
	return $infoPlugin;
}

function wikiplugin_pluginmanager($data, $params) {
    $plugin = new WikiPluginPluginManager();
    return $plugin->run($data, $params);
}
