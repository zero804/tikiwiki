<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

require_once 'lib/core/lib/TikiDb/ErrorHandler.php';

abstract class TikiDb
{
	private static $instance;

	private $errorHandler;
	private $errorMessage;
	private $serverType;

	protected $savedQuery;

	private $tablePrefix;
	private $usersTablePrefix;

	public static function get() // {{{
	{
		return self::$instance;
	} // }}}

	public static function set( TikiDb $instance ) // {{{
	{
		return self::$instance = $instance;
	} // }}}

	function startTimer() // {{{
	{
		list($micro, $sec) = explode(' ', microtime());
		return $micro + $sec;
	} // }}}

	function stopTimer($starttime) // {{{
	{
		global $elapsed_in_db;
		list($micro, $sec) = explode(' ', microtime());
		$now=$micro + $sec;
		$elapsed_in_db+=$now - $starttime;
	} // }}}

	abstract function qstr( $str );

	abstract function query( $query = null, $values = null, $numrows = -1, $offset = -1, $reporterrors = true );

	function lastInsertId() // {{{
	{
		return $this->getOne( 'SELECT LAST_INSERT_ID()' );
	} // }}}

	function queryError( $query, &$error, $values = null, $numrows = -1, $offset = -1 ) // {{{
	{
		$this->errorMessage = '';
		$result = $this->query( $query, $values, $numrows, $offset, false );
		$error = $this->errorMessage;

		return $result;
	} // }}}

	function getOne( $query, $values = null, $reporterrors = true, $offset = 0 ) // {{{
	{
		$result = $this->query( $query, $values, 1, $offset, $reporterrors );

		if ( $result ) {
			$res = $result->fetchRow();

			if ( empty( $res ) ) {
				return $res;
			}
		
			return reset( $res );
		}

		return false;
	} // }}}

	function fetchAll( $query = null, $values = null, $numrows = -1, $offset = -1, $reporterrors = true ) // {{{
	{
		$result = $this->query( $query, $values, $numrows, $offset, $reporterrors );

		$rows = array();
		while( $row = $result->fetchRow() ) {
			$rows[] = $row;
		}

		return $rows;
	} // }}}

	function fetchMap( $query = null, $values = null, $numrows = -1, $offset = -1, $reporterrors = true ) // {{{
	{
		$result = $this->fetchAll( $query, $values, $numrows, $offset, $reporterrors );

		$map = array();

		foreach( $result as $row ) {
			$key = array_shift( $row );
			$value = array_shift( $row );

			$map[ $key ] = $value;
		}

		return $map;
	} // }}}

	function setErrorHandler( TikiDb_ErrorHandler $handler ) // {{{
	{
		$this->errorHandler = $handler;
	} // }}}

	function setTablePrefix( $prefix ) // {{{
	{
		$this->tablePrefix = $prefix;
	} // }}}

	function setUsersTablePrefix( $prefix ) // {{{
	{
		$this->usersTablePrefix = $prefix;
	} // }}}

	function getServerType() // {{{
	{
		return $this->serverType;
	} // }}}

	function setServerType( $type ) // {{{
	{
		$this->serverType = $type;
	} // }}}

	function getErrorMessage() // {{{
	{
		return $this->errorMessage;
	} // }}}

	protected function setErrorMessage( $message ) // {{{
	{
		$this->errorMessage = $message;
	} // }}}

	protected function handleQueryError( $query, $values, $result ) // {{{
	{
		if( $this->errorHandler )
			$this->errorHandler->handle( $this, $query, $values, $result );
		else {
			require_once 'TikiDb/Exception.php';
			throw new TikiDb_Exception( $this->getErrorMessage() );
		}
	} // }}}

	protected function convertQueryTablePrefixes( &$query ) // {{{
	{
		$db_table_prefix = $this->tablePrefix;
		$common_users_table_prefix = $this->usersTablePrefix;

		if ( !is_null($db_table_prefix) && !empty($db_table_prefix) ) {

			if( !is_null($common_users_table_prefix) && !empty($common_users_table_prefix) ) {
				$query = str_replace("`users_", "`".$common_users_table_prefix."users_", $query);
			} else {
				$query = str_replace("`users_", "`".$db_table_prefix."users_", $query);
			}

			$query = str_replace("`tiki_", "`".$db_table_prefix."tiki_", $query);
			$query = str_replace("`messu_", "`".$db_table_prefix."messu_", $query);
			$query = str_replace("`sessions", "`".$db_table_prefix."sessions", $query);
		}
	} // }}}

	function convertSortMode( $sort_mode ) // {{{
	{
		if ( !$sort_mode ) {
			return '';
		}
		// parse $sort_mode for evil stuff
		$sort_mode = str_replace('pref:','',$sort_mode);
		$sort_mode = preg_replace('/[^A-Za-z_,.]/', '', $sort_mode);

		if ($sort_mode == 'random') {
			return "RAND()";
		}

		$sorts=explode(',', $sort_mode);
		foreach($sorts as $k => $sort) {

			// force ending to either _asc or _desc unless it's "random"
			$sep = strrpos($sort, '_');
			$dir = substr($sort, $sep);
			if (($dir !== '_asc') && ($dir !== '_desc')) {
				if ( $sep != (strlen($sort) - 1) ) {
					$sort .= '_';
				}
				$sort .= 'asc';
			}

			$sort = preg_replace('/_asc$/', '` asc', $sort);
			$sort = preg_replace('/_desc$/', '` desc', $sort);
			$sort = '`' . $sort;
			$sort = str_replace('.', '`.`', $sort);
			$sorts[$k]=$sort;
		}

		$sort_mode=implode(',', $sorts);
		return $sort_mode;
	} // }}}
	
	function getQuery() // {{{
	{
		return $this->savedQuery;
	} // }}}

	function setQuery( $sql ) // {{{
	{
		$this->savedQuery = $sql;
	} // }}}

	function ifNull( $field, $ifNull ) // {{{
	{
		return " COALESCE($field, $ifNull) ";
	} // }}}

	function in( $field, $values, &$bindvars ) // {{{
	{
		$parts = explode('.', $field);
		foreach($parts as &$part)
			$part = '`' . $part . '`';
		$field = implode('.', $parts);
		$bindvars = array_merge( $bindvars, $values );

		if( count( $values ) > 0 ) {
			$values = rtrim( str_repeat( '?,', count( $values ) ), ',' );
			return " $field IN( $values ) ";
		} else {
			return " 0 ";
		}
	} // }}}

	function parentObjects(&$objects, $table, $childKey, $parentKey) // {{{
	{
		$query = "select `$childKey`, `$parentKey` from `$table` where `$childKey` in (".implode(',',array_fill(0, count($objects),'?')).')';
		foreach ($objects as $object) {
			$bindvars[] = $object['itemId'];
		}
		$result = $this->query($query, $bindvars);
		while ($res = $result->fetchRow()) {
			$ret[$res[$childKey]] = $res[$parentKey];
		}
		foreach ($objects as $i=>$object) {
			$objects[$i][$parentKey] = $ret[$object['itemId']];
		}
	} // }}}

	function concat() // {{{
	{
		$arr = func_get_args();

		// suggestion by andrew005@mnogo.ru
		$s = implode(',',$arr);
		if (strlen($s) > 0) return "CONCAT($s)";
		else return '';
	} // }}}

	function getCharsetVariables() // {{{
	{
		$return = array();

		foreach ( array( 'character_set%', 'collation%' ) as $varName ) {
			$result = $this->query( "show variables like '$varName'" );
			while ( $res = $result->fetchRow() ) {
				$return[ $res['Variable_name'] ] = $res['Value'];
			}
		}

		return $return;
	} // }}}

	function getDefaultConfigCharsets() { // {{{
		$return = false;

		global $api_tiki;
		if ( $api_tiki == 'pdo' ) {
			global $local_php;
			if ( ! empty( $local_php ) && file_exists( $local_php ) ) {
				include( $local_php );

				$db_hoststring = "host=$host_tiki";
				if ( $db_tiki == 'mysqli' ) {
					$db_tiki = 'mysql';
					if ( isset( $socket_tiki ) ) {
						$db_hoststring = "unix_socket=$socket_tiki";
					}
				}

				if ( $db_tiki == 'mysql' ) {
					$return = array();

					// Create another PDO connection, to use the "PDO::MYSQL_ATTR_READ_DEFAULT_GROUP" attribute, which allows to get MySQL default config from my.cnf file
					$tmpPdo = new PDO("$db_tiki:$db_hoststring;dbname=$dbs_tiki", $user_tiki, $pass_tiki, array(PDO::MYSQL_ATTR_READ_DEFAULT_GROUP => true) );
			                if ( $result = $tmpPdo->query( "show variables like 'character_set_%'" ) ) {
						while ( $res = $result->fetch() ) {
							$return[ $res['Variable_name'] ] = $res['Value'];
						}
			                }
				}
			}
		}

		return $return;
	} // }}}

	function detectContentCharset( &$errorMsg, $dbCharsetVariables = null, $dbDefaultConfigCharsets = null, $previousDbApi = null ) { // {{{

		if ( $dbCharsetVariables === null ) {
			$dbCharsetVariables = $this->getCharsetVariables();
		}
		if ( empty( $previousDbApi ) ) {
			$previousDbApi = $api_tiki;
		}

		$dbCharset = $dbCharsetVariables['character_set_database'];
		$utf8DbCharset = ( substr( strtoupper($dbCharset), 0, 3 ) == 'UTF' );

		$dbConnectionCharset = $dbCharsetVariables['character_set_connection'];
		$utf8ConnectionCharset = ( substr( strtoupper($dbConnectionCharset), 0, 3 ) == 'UTF' );

		if ( $previousDbApi == 'adodb' && $api_tiki != 'adodb' ) {
			// We are updating Tiki from AdoDB to PDO abstraction layer...
			//  ... this is why we have to check AdoDB Connection Charset instead

			if ( $dbDefaultConfigCharsets === null ) {
				$dbDefaultConfigCharsets = $this->getDefaultConfigCharsets();
			}
			$utf8ConnectionCharset = ( substr( strtoupper($dbDefaultConfigCharsets['character_set_connection']), 0, 3 ) == 'UTF' );
		}

		if ( $utf8ConnectionCharset && $utf8DbCharset ) {
			// FULL UTF-8 installation (UTF-8 DB + UTF-8 connection)
			return 'utf8';
		} elseif ( $dbConnectionCharset == $dbCharset ) {
			// DB is not in UTF-8, but MySQL will try to convert on-the-fly if possible
			return $dbCharset;
		} else {
			// either DB is in UTF-8, but data is wrongly reencoded
			// or connection is in UTF-8, but DB is in another charset
			return false;
		}

		return false;
	} // }}}

	function detectBestClientCharset( $dbCharsetVariables = null, $dbDefaultConfigCharsets = null, $previousDbApi = null ) { // {{{
		global $api_tiki;

		if ( empty( $dbCharsetVariables ) ) {
			$dbCharsetVariables = $this->getCharsetVariables();
		}
		if ( empty( $previousDbApi ) ) {
			$previousDbApi = $api_tiki;
		}

		$dbClientCharset = $dbCharsetVariables['character_set_client'];
		$adodbUtf8ClientCharset = $utf8ClientCharset = ( substr( strtoupper($dbClientCharset), 0, 3 ) == 'UTF' );

		if ( $previousDbApi == 'adodb' && $api_tiki != 'adodb' ) {
			if ( $dbDefaultConfigCharsets === null ) {
				$dbDefaultConfigCharsets = $this->getDefaultConfigCharsets();
			}
			$adodbUtf8ClientCharset = ( substr( strtoupper($dbDefaultConfigCharsets['character_set_client']), 0, 3 ) == 'UTF' );
		}

		if ( $utf8ClientCharset ) {
			// The current connection is using UTF-8 for ClientCharset
			if ( $api_tiki == 'pdo' ) {
				// The current DB abstraction layer is PDO
				if ( $previousDbApi == 'pdo' ) {
					// The data in DB has been stored using PDO
					return 'utf8';
				} else {
					// The data in DB has been stored using AdoDB
					// (we are most probably in the upgrade process from 4.x to 5.x)
					if ( $adodbUtf8ClientCharset ) {
						// AdoDB was using an UTF-8 Client Charset
						return 'utf8';
					} else {
						// AdoDB was using a wrong Client Charset
						return $dbDefaultConfigCharsets['character_set_client'];
					}
				}
			} else {
				// The current DB abstraction layer is AdoDB
				return 'utf8';
			}
		} else {
			// The current connection is using a wrong Client Charset
			if ( $api_tiki == 'pdo' ) {
				// The current DB abstraction layer is PDO
				if ( $previousDbApi == 'pdo' ) {
					// The data in DB has been stored using PDO
					return $dbClientCharset;
				} else {
					// The data in DB has been stored using AdoDB
					// (we are most probably in the upgrade process from 4.x to 5.x)
					if ( $adodbUtf8ClientCharset ) {
						// AdoDB was using an UTF-8 Client Charset
						return 'utf8';
					} else {
						// AdoDB was using a wrong Client Charset
						return $dbDefaultConfigCharsets['character_set_client'];
					}
				}
			} else {
				// The current DB abstraction layer is AdoDB
				// => ClientCharset can't be forced unless a data reencoding is done
				return $dbClientCharset;
			}
		}

		return false;
	} // }}}
}
