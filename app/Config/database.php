<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behaviour of Cake.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * Database configuration class.
 * You can specify multiple configurations for production, development and testing.
 *
 * datasource => The name of a supported datasource; valid options are as follows:
 *		Database/Mysql 		- MySQL 4 & 5,
 *		Database/Sqlite		- SQLite (PHP5 only),
 *		Database/Postgres	- PostgreSQL 7 and higher,
 *		Database/Sqlserver	- Microsoft SQL Server 2005 and higher
 *
 * You can add custom database datasources (or override existing datasources) by adding the
 * appropriate file to app/Model/Datasource/Database. Datasources should be named 'MyDatasource.php',
 *
 *
 * persistent => true / false
 * Determines whether or not the database should use a persistent connection
 *
 * host =>
 * the host you connect to the database. To add a socket or port number, use 'port' => #
 *
 * prefix =>
 * Uses the given prefix for all the tables in this database. This setting can be overridden
 * on a per-table basis with the Model::$tablePrefix property.
 *
 * schema =>
 * For Postgres/Sqlserver specifies which schema you would like to use the tables in. Postgres defaults to 'public'. For Sqlserver, it defaults to empty and use
 * the connected user's default schema (typically 'dbo').
 *
 * encoding =>
 * For MySQL, Postgres specifies the character encoding to use when connecting to the
 * database. Uses database default not specified.
 *
 * unix_socket =>
 * For MySQL to connect via socket specify the `unix_socket` parameter instead of `host` and `port`
 *
 * settings =>
 * Array of key/value pairs, on connection it executes SET statements for each pair
 * For MySQL : http://dev.mysql.com/doc/refman/5.6/en/set-statement.html
 * For Postgres : http://www.postgresql.org/docs/9.2/static/sql-set.html
 * For Sql Server : http://msdn.microsoft.com/en-us/library/ms190356.aspx
 */
class DATABASE_CONFIG {

	public $default = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'root',
		'password' => '',
		'database' => 'syspaweb_sms',
		'prefix' => '',
		//'encoding' => 'utf8',
		//
	);

	public $live = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'syspaweb_jose',
		'password' => 'joma1987',
		'database' => 'syspaweb_sms',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

	public $test = array(
		'datasource' => 'Database/Mysql',
		'persistent' => false,
		'host' => 'localhost',
		'login' => 'user',
		'password' => 'password',
		'database' => 'test_database_name',
		'prefix' => '',
		//'encoding' => 'utf8',
	);

	function __construct (){		
		//check to see if server name is set (thanks Frank)
		if(isset($_SERVER['SERVER_NAME'])){
			switch($_SERVER['SERVER_NAME']){				
				case 'www.sms.syspaweb.com':
				case 'http://sms.syspaweb.com':
				case 'sms.syspaweb.com':
					$this->default = $this->live;
					break;						
				case 'sms-local':
					$this->default = $this->default;
					break;
				default:
				case 'localhost':
					$this->default = $this->default;
					break;
			}
			//configure::write('debug',1);
			//pr('Database: '.$this->default['database']);
			//pr('host: '.$this->default['host']);
		}
	    else // we are likely baking, use our local db
	    {
	    	//echo php_uname();
			//echo PHP_OS;
			///////
			/* Some possible outputs:
			Linux localhost 2.4.21-0.13mdk #1 Fri Mar 14 15:08:06 EST 2003 i686
			Linux
			
			FreeBSD localhost 3.2-RELEASE #15: Mon Dec 17 08:46:02 GMT 2001
			FreeBSD
			
			Windows NT XN1 5.1 build 2600
			WINNT
			*/
				/* */   	
	    	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
       			$this->default = $this->development;
		   } else {
				//$this->default = $this->testing;
			   $this->default = $this->production;
		   }

			
			pr('Database: '.$this->default['database']);
			pr('host: '.$this->default['host']);
	    }
	}	
}

