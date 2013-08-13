<?php
/*
Plugin Name: Filedownload
Plugin URI: http://www.worldweb-innovation.de/
Description: This Plugin downloads a file by opening the browsers "file save dialog", also if the filetype normally would be opened in the browser or by any other application. You can specify you own mime type. You can watch and reset the download counters in the settings menu or show the counter in your post.
Version: 1.2
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
require_once('counter.php');

######################################################
# load language files
function filedownload_add_language_files() {
	load_plugin_textdomain('filedownload', 'wp-content/plugins/filedownload'  );
}
add_action('init', 'filedownload_add_language_files');

register_activation_hook( __FILE__, 'filedownload_CreateTables' );

function filedownload_shortcode_handle($atts, $content = null) 
{
  extract(shortcode_atts(array("file" => '', "type" => '', "style" => '' ), $atts));

  ($style=='')?$span="<span>":$span="<span style=\"$style\">";
  $referer = $_SERVER[REQUEST_URI];
  $plugin_dir = WP_PLUGIN_URL;

  if ($file=="") return __("Filedownload Error: parameter 'file' is empty!",'filedownload');
  if (substr($file, 0,7) == "http://") $path = $file;
  elseif (substr($file, 0,1) == "/")   $path = WP_CONTENT_URL . $file;
  else                                 $path = WP_CONTENT_URL . '/' . $file;

  if(($open = @fopen ($path, "r")) === false) return sprintf(__("Filedownload Error: file '%s' does not exist!",'filedownload'),$file); 
  fclose ($open);

  return "<a href=\"$plugin_dir/filedownload/download.php/?path=$path&type=$type&referer=$referer\">$span$content</span></a>";

}
add_shortcode('filedownload', 'filedownload_shortcode_handle');

function filedownload_shortcode_button_handle($atts, $content = null) 
{
  extract(shortcode_atts(array("file" => '', "type" => '', "style" => '' ), $atts));

  $referer = $_SERVER[REQUEST_URI];
  $plugin_dir = WP_PLUGIN_URL;

  if ($file=="") return __("Filedownload Error: parameter 'file' is empty!",'filedownload');
  if (substr($file, 0,7) == "http://") $path = $file;
  elseif (substr($file, 0,1) == "/")   $path = WP_CONTENT_URL . $file;
  else                                 $path = WP_CONTENT_URL . '/' . $file;

  if(($open = @fopen ($path, "r")) === false) return sprintf(__("Filedownload Error: file '%s' does not exist!",'filedownload'),$file); 
  fclose ($open);

  return "<form action=\"$plugin_dir/filedownload/download.php/?path=$path&type=$type&referer=$referer\" method=\"post\"><input style=\"$style\" type=\"submit\" value=\"$content\" /></form>";

}
add_shortcode('filedownload_button', 'filedownload_shortcode_button_handle');

function filedownload_shortcode_counter_handle($atts, $content = null) 
{
  extract(shortcode_atts(array("file" => '', "style" => '' ), $atts));

  ($style=='')?$span="<span>":$span="<span style=\"$style\">";

  if (substr($file, 0,7) == "http://") $filename = $file;
  elseif (substr($file, 0,1) == "/")   $filename = WP_CONTENT_URL . $file;
  else                                 $filename = WP_CONTENT_URL . '/' . $file;

  $count = filedownload_Read($filename);

  return "$span$content$count</span></a>";

}
add_shortcode('filedownload_counter', 'filedownload_shortcode_counter_handle');


######################################################
# Start the Admin Menu dialog
if ( is_admin() ) {	add_action('admin_menu', 'filedownload_menu'); }

######################################################
# End of main
######################################################

######################################################
# insert Menu 'filedownload' in settings
function filedownload_menu() {
	global $wp_version;

	if( function_exists('add_submenu_page') ) 
  {
		$menutitle = __('Filedownload','filedownload');
		$pagehook = add_submenu_page('options-general.php', $menutitle, $menutitle, 'manage_options', __FILE__, 'filedownload_options');
	}
}

######################################################
# Option Dialog function
######################################################
function filedownload_options() 
{
		if (!is_admin()) {
			print "Where do you come from?!";
			return false;
		}

// Debug only
//     $counters = filedownload_ReadAll();
//       print_r($counters);
//       print_r($counters->filename);
//       print_r($counters->counter);
//     return;
		
		# Process post
		if (isset($_POST['reset'])) 
    {
      //print_r($_POST);
      $selected = $_POST[SELECTED];
      //print_r($selected);
      if (count($selected) > 0) 
      {
   			print '<p style="color: #FF0000;">'.__('The following counters where set to null:','filedownload').'</p>';
        foreach ($selected as $filename)
        {
          filedownload_Reset($filename);
          print "filename = $filename<br />";
        }
      }
      else print '<p style="color: #FF0000;">'.__('Select the counters you want to reset!','filedownload').'</p>';
		}

		if (isset($_POST['delete'])) 
    {
      //print_r($_POST);
      $selected = $_POST[SELECTED];
      //print_r($selected);
      if (count($selected) > 0) 
      {
   			print '<p style="color: #FF0000;">'.__('The following counters where deleted:','filedownload').'</p>';
        foreach ($selected as $filename)
        {
          filedownload_Delete($filename);
          print "filename = $filename<br />";
        }
      }
      else print '<p style="color: #FF0000;">'.__('Select the counters you want to delete!','filedownload').'</p>';
		}
		
    print '<div class="wrap"><div id="icon-options-general" class="icon32"><br /></div><h2>'.__('Settings','filedownload').' &rsaquo; '.__('filedownload','filedownload').'</h2>';		
    print '<h4>'.__('Filedownload','filedownload').'</h4>';

		// Start Form
		print '<form action="" method="post" name="form_filedownload_option">';	
		
    printf('<table border="1" cellspacing="0" cellpadding="2" width="850px">');
    printf('<tbody>');
    printf('<tr style="background-color: #c0c0c0; text-align: center;" >');
    printf('</tr>');

    $counters = filedownload_ReadAll();
    //  print_r($counters);
    $i = 0;
    if (count($counters)>0)
      foreach ($counters as $counter) 
      {
        printf('<tr style="background-color: #c0c0c0; color: #E00000" >');
    		//if (get_option(PRIVAT_OPTION) == 'false')
    		  print '<td width="4000px" style="padding:6px;"><input type="checkbox" name="SELECTED[' . $i++ . ']" value="'.$counter[filename].'"> '. $counter[filename] .'</td>';
        //else
    		//  print '<td width="170px" style="padding:6px;"><input type="checkbox" name="' . $filename . '" value="true" checked> '. $filename .'</td>';
    
        print '<td width="30px" align="left" style="padding:5px;">'.__(' Count: ','filedownload').'</td>';
        print '<td align="right" style="padding:5px;">'. $counter[count] .'</td>';
        printf('</tr>');
      }
    printf('</tbody>');
    printf('</table>');

    print "<br />";
    printf('<table border="0" cellspacing="0" cellpadding="2" width="850px">');
    printf('<tbody>');
    printf('<tr>');

    //print "<input type='hidden' name='reset' value='true'>";
		print "<td><input type='submit' name='reset' class='button-primary' value='" . __('Reset Selected Counters','filedownload') . "'></td>";	

    //print "<input type='hidden' name='delete' value='true'>";
		print "<td><input type='submit' name='delete' class='button-primary' value='" . __('Delete Selected Counters','filedownload') . "'></td>";	 

	  print '<td style="text-align: right; color: #0000FF;"><i>'.__('Filedownload','filedownload').__(' is powered by ','filedownload').'<a href="http://blog-me.de" title="'.__('Create your free Wordpress Blog!', 'filedownload').'"><em>Blog-Me.de</em></a></i></td>';
    printf('</tr></tbody>');
    printf('</table>');
  
    print "<br /><br />";
    print '<h4>'.__("How to insert the download link, button or download counter in your post?",'filedownload').'</h4>';

    print __('Write the following text in your post for downloading a file in a link:','filedownload').'<br /><span style="font-size:80%"><strong>[filedownload file="http://www.domain...uploads/2011/06/MTB_Tour.gpx" type="application/xml" style="color:#0000EE;"]MTB_Tour.gpx[/filedownload]</strong></span>';
    print "<br /><br />";
    print __('Write the following text in your post for downloading a file with a button:','filedownload').'<br /><span style="font-size:80%"><strong>[filedownload_button file="http://www.domain...uploads/2011/06/MTB_Tour.gpx"]Download[/filedownload_button]</strong></span>';
    print "<br /><br />";
    print __('Write the following text in your post for showing the download counter:','filedownload').'<br /><span style="font-size:80%"><strong>[filedownload_counter file="http://www.domain...uploads/2011/06/MTB_Tour.gpx" style="color:#FF0000;"]Downloads:[/filedownload_counter]</strong></span>';
    print "<br /><br />";
    print __('where <strong>type</strong> and <strong>style</strong> are optional','filedownload');
    print "<br /><br /><hr><br />";

		print "</form>";	
    print '</div>';


}



?>
