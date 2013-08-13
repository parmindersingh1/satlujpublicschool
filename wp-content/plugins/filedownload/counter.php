<?php
/*
Helper File for Plugin: Filedownload
Plugin URI: http://www.worldweb-innovation.de/
Description: Database functions
Author: Peter Gross
Author URI: http://www.worldweb-innovation.de/

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 * Tabellen erstellen wenn nicht vorhanden
 */
function filedownload_CreateTables() {
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	global $wpdb;
	
	// Counter-Tabelle existieren nicht - anlegen
	$sql ="CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."filedownload"." (
         id INT(10) NOT NULL AUTO_INCREMENT,
         filename VARCHAR(512) NOT NULL,
         count INT(10) NOT NULL,
         PRIMARY KEY (id)
        );";

  dbDelta($sql);
  //echo $sql; exit();	

}

function filedownload_Write($filename)
{
  global $wpdb;


	$query = 'SELECT * FROM '.$wpdb->prefix.'filedownload WHERE filename = \''.$filename.'\'';
	//print $query;
  $result=$wpdb->get_row($query, ARRAY_A);
  //print_r($result);exit();
  if (count($result) == 0) 
	{
    $wpdb->insert( $wpdb->prefix.'filedownload', array( 'filename' => $filename, 'count' => 1 ), array( '%s', '%d' ));    
  }
  else // iserip does not yet exist
  {
    $count = $result['count'] + 1;
    $wpdb->update( $wpdb->prefix.'filedownload', array( 'filename' => $filename, 'count' => $count ), array( 'filename' => $filename), array( '%s', '%d' ), array( '%s' ) );    
  }
}

function filedownload_Reset($filename)
{
  global $wpdb;

	$query = 'SELECT * FROM '.$wpdb->prefix.'filedownload WHERE filename = \''.$filename.'\'';
	//print $query;
  $result=$wpdb->get_row($query, ARRAY_A);
  //print_r($result);exit();
  if (count($result) == 0) 
	{
    $wpdb->insert( $wpdb->prefix.'filedownload', array( 'filename' => $filename, 'count' => 0 ), array( '%s', '%d' ));    
  }
  else // iserip does not yet exist
  {
    $wpdb->update( $wpdb->prefix.'filedownload', array( 'filename' => $filename, 'count' => 0 ), array( 'filename' => $filename), array( '%s', '%d' ), array( '%s' ) );    
  }
}

function filedownload_Delete($filename)
{
  global $wpdb;

	$query = 'DELETE FROM '.$wpdb->prefix.'filedownload WHERE filename = \''.$filename.'\'';
	//print $query;
	$result = $wpdb->query($query);
}

function filedownload_Read($filename)
{
  global $wpdb;


	$query = 'SELECT * FROM '.$wpdb->prefix.'filedownload WHERE filename = \''.$filename.'\'';
	//print $query;
  $result=$wpdb->get_row($query, ARRAY_A);
  //print_r($result);
  if (count($result) == 0) return "0";
  return strval($result[count]);
}

function filedownload_ReadAll()
{
  global $wpdb;


	$query = 'SELECT * FROM '.$wpdb->prefix.'filedownload';
	//print $query;
  $result=$wpdb->get_results($query, ARRAY_A);
  //print_r($result);exit();
  return $result;
}


?>
