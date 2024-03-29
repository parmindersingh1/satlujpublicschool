<?php
/* wppa-ajax.php
*
* Functions used in ajax requests
* version 4.9.13
*
*/
add_action('wp_ajax_wppa', 'wppa_ajax_callback');
add_action('wp_ajax_nopriv_wppa', 'wppa_ajax_callback');

function wppa_ajax_callback() {
global $wpdb;
global $wppa_opt;
global $wppa;

	$wppa['ajax']  = true;
	$wppa['error'] = '0';
	$wppa['out']   = '';

	// ALTHOUGH IF WE ARE HERE AS FRONT END VISITOR, is_admin() is true. 
	// So, $wppa_opt switches are 'yes' or 'no' and not true or false.
	
	$wppa_action = $_REQUEST['wppa-action'];
	
	switch ($wppa_action) {
		case 'makeorigname':
			$photo = $_REQUEST['photo-id'];
			$from = $_REQUEST['from'];
			if ( $from == 'fsname' ) {
				$type = $wppa_opt['wppa_art_monkey_link'];
			}
			elseif ( $from == 'popup' ) {
				$type = $wppa_opt['wppa_art_monkey_popup_link'];
			}
			else {
				echo '||7||'.__('Unknown source of request', 'wppa');
				exit;
			}
			$data = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".WPPA_PHOTOS."` WHERE `id` = %s", $photo), ARRAY_A);
			if ($data) {	// The photo is supposed to exist
				// Make the name
				$name = __($data['name']);
				$name = sanitize_file_name($name);
				$dotpos = strrpos($name, '.');
				if ( $dotpos !== false ) $name = substr($name, '0', $dotpos);
				if ( strlen($name) == '0' ) {
					echo '||1||'.__('Empty filename', 'wppa');
					exit;
				}
				// Make the filenames
				$source = WPPA_UPLOAD_PATH.'/'.$photo.'.'.$data['ext'];
				$dest = WPPA_UPLOAD_PATH.'/temp/'.$name.'.'.$data['ext'];
				$zipfile = WPPA_UPLOAD_PATH.'/temp/'.$name.'.zip';
				$tempdir = WPPA_UPLOAD_PATH.'/temp';
				if ( ! is_dir($tempdir) ) @ mkdir($tempdir);
				if ( ! is_dir($tempdir) ) {
					echo '||2||'.__('Unable to create tempdir', 'wppa');
					exit;
				}
				// Remove obsolete files
				// To prevent filling up diskspace, divide lifetime by 2 and repeat removing obsolete files until count <= 10
				$filecount = 100;
				$lifetime = 3600;
				while ( $filecount > 10 ) {
					$files = glob(WPPA_UPLOAD_PATH.'/temp/*');
					$filecount = 0;
					if ( $files ) {	
						$timnow = time();
						$expired = $timnow - $lifetime;
						foreach ( $files as $file ) {
							$modified = filemtime($file);
							if ( $modified < $expired ) unlink($file);
							else $filecount++;
						}
					}
					$lifetime /= 2;
				}
				// Make the files
				if ( $type == 'file' ) {
					copy($source, $dest);
					$ext = $data['ext'];
				}
				elseif ( $type == 'zip' ) {
					if ( ! class_exists('ZipArchive') ) {
						echo '||8||'.__('Unable to create zip archive', 'wppa');
						exit;
					}
					$ext = 'zip';
					$wppa_zip = new ZipArchive;
					$wppa_zip->open($zipfile, 1);
					$wppa_zip->addFile($source, basename($dest));
					$wppa_zip->close();						
				}
				else {
					echo '||6||'.__('Unknown type', 'wppa');
					exit;
				}
				
				$desturl = WPPA_UPLOAD_URL.'/temp/'.$name.'.'.$ext;
				echo '||0||'.$desturl;	// No error: return url
				exit;
			}
			else {
				echo '||9||'.__('The photo does no longer exist', 'wppa');
				exit;
			}
			exit;
			break;
			
		case 'tinymcedialog':
			$result = wppa_make_tinymce_dialog();
			echo $result;
			exit;
			break;
			
		case 'rate':
			// Get commandline args
			$photo  = $_REQUEST['wppa-rating-id'];
			$rating = $_REQUEST['wppa-rating'];
			$occur  = $_REQUEST['wppa-occur'];
			$index  = $_REQUEST['wppa-index'];
			$nonce  = $_REQUEST['wppa-nonce'];
			
			// Make errortext
			$errtxt = __('An error occurred while processing you rating request.', 'wppa');
			$errtxt .= "\n".__('You may refresh the page and try again.', 'wppa');
			$wartxt = __('Althoug an error occurred while processing your rating, your vote has been registered.', 'wppa');
			$wartxt .= "\n".__('However, this may not be reflected in the current pageview', 'wppa');
			
			// Check on validity
			if ( ! wp_verify_nonce($nonce, 'wppa-check') ) {
				echo '0||100||'.$errtxt;
				exit;																// Nonce check failed
			}
			if ( $wppa_opt['wppa_rating_max'] == '5' && ! in_array($rating, array('-1', '1', '2', '3', '4', '5')) ) {
				echo '0||101||'.$errtxt.':'.$rating;
				exit;																// Value out of range
			}
			elseif ( $wppa_opt['wppa_rating_max'] == '10' && ! in_array($rating, array('-1', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10')) ) {
				echo '0||106||'.$errtxt.':'.$rating;
				exit;																// Value out of range
			}
			
			// In case value = -1 this is a dislike vote
			if ( $rating == '-1' ) {
				wppa_dislike_add($photo);
				echo $occur.'||'.$photo;
				exit;
			}
			
			// Get other data
			$user     = wppa_get_user();
			$mylast   = $wpdb->get_var($wpdb->prepare( 'SELECT * FROM `'.WPPA_RATING.'` WHERE `photo` = %s AND `user` = %s ORDER BY `id` DESC LIMIT 1', $photo, $user ) ); 
			$myavgrat = '0';														// Init
			
			// Case 0: Illegal second vote
			if ( $mylast && $wppa_opt['wppa_rating_change'] == 'no' && $wppa_opt['wppa_rating_multi'] == 'no' ) {
				echo '0||109||'.__('Illegal attempt to enter a second vote.', 'wppa');
				exit;
			}
			// Case 1: This is my first vote for this photo
			if ( ! $mylast ) {
				$key = wppa_nextkey(WPPA_RATING);
				$iret = $wpdb->query($wpdb->prepare('INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (%s, %s, %s, %s)', $key, $photo, $rating, $user));
				if ( $iret === false ) {
					echo '0||102||'.$errtxt;
					exit;															// Fail on storing vote
				}
				else {
					//SUCCESSFUL RATING, ADD POINTS
					if( function_exists('cp_alterPoints') && is_user_logged_in() ) {
						cp_alterPoints(cp_currentUser(), $wppa_opt['wppa_cp_points_rating']);
					}
				}
				$myavgrat = $rating;
			}
			// Case 2: I will change my previously given vote
			elseif ( $wppa_opt['wppa_rating_change'] == 'yes' ) {					// Votechanging is allowed
				$query = $wpdb->prepare( 'UPDATE `'.WPPA_RATING.'` SET `value` = %s WHERE `photo` = %s AND `user` = %s LIMIT 1', $rating, $photo, $user );
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||103||'.$errtxt;
					exit;															// Fail on update
				}
				$myavgrat = $rating;
			}
			// Case 3: Add another vote from me
			elseif ( $wppa_opt['wppa_rating_multi'] == 'yes' ) {					// Rating multi is allowed
				$key = wppa_nextkey(WPPA_RATING);
				$query = $wpdb->prepare( 'INSERT INTO `'.WPPA_RATING. '` (`id`, `photo`, `value`, `user`) VALUES (%s, %s, %s, %s)', $key, $photo, $rating, $user );
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||104||'.$errtxt;
					exit;															// Fail on storing vote
				}
				// Compute my avg rating
				$query = $wpdb->prepare( 'SELECT * FROM `'.WPPA_RATING.'`  WHERE `photo` = %s AND `user` = %s', $photo, $user );
				$myrats = $wpdb->get_results($query, ARRAY_A);
				if ( ! $myrats) {
					echo '0||105||'.$wartxt;
					exit;															// Fail on retrieve
				}
				$sum = 0;
				$cnt = 0;
				foreach ($myrats as $rt) {
					$sum += $rt['value'];
					$cnt ++;
				}
				if ($cnt > 0) $myavgrat = $sum/$cnt; else $myavgrat = '0';
			}
			else { 																	// Should never get here....
				echo '0||110||'.__('Unexpected error', 'wppa');
				exit;
			}

			// Find Old avgrat
			$oldavgrat = $wpdb->get_var($wpdb->prepare('SELECT `mean_rating` FROM '.WPPA_PHOTOS.' WHERE `id` = %s', $photo));
			if ($oldavgrat === false) {
				echo '0||108||'.$wartxt;
				exit;																// Fail on read old avgrat
			}
			// Compute new allavgrat
			$ratings = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM '.WPPA_RATING.' WHERE `photo` = %s', $photo), ARRAY_A);
			if ($ratings) {
				$sum = 0;
				$cnt = 0;
				foreach ($ratings as $rt) {
					$sum += $rt['value'];
					$cnt ++;
				}
				if ($cnt > 0) $allavgrat = $sum/$cnt; else $allavgrat = '0';
				if ($allavgrat == '10') $allavgrat = '9.99999';	// For sort order reasons text field
			}
			else $allavgrat = '0';

			// Store it in the photo info if it has been changed
			if ( $oldavgrat != $allavgrat ) {
				$query = $wpdb->prepare('UPDATE `'.WPPA_PHOTOS. '` SET `mean_rating` = %s WHERE `id` = %s', $allavgrat, $photo);
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||106||'.$wartxt;
					exit;																// Fail on save
				}
			}
			
			// Compute rating_count
			$ratcount = $wpdb->get_var( $wpdb->prepare( 'SELECT COUNT(*) FROM `'.WPPA_RATING.'`  WHERE `photo` = %s', $photo));
			if ( $ratcount !== false ) {
				$query = $wpdb->prepare('UPDATE `'.WPPA_PHOTOS. '` SET `rating_count` = %s WHERE `id` = %s', $ratcount, $photo);
				$iret = $wpdb->query($query);
				if ( $iret === false ) {
					echo '0||107||'.$wartxt;
					exit;																// Fail on save
				}
			}

			// Success!
			wppa_clear_cache();
			echo $occur.'||'.$photo.'||'.$index.'||'.$myavgrat.'||'.$allavgrat;
			break;
		
		case 'render':	
			// Correct the fact that this is a non-admin operation
			require_once 'wppa-non-admin.php';
			wppa_load_theme();
			foreach(array_keys($wppa_opt) as $s) {
				if ( $wppa_opt[$s] == 'no' ) $wppa_opt[$s] = false;
			}
			$wppa['ajax'] = true;
			// Do the dirty stuff
			echo wppa_albums();
			break;
			
		case 'delete-photo':
			$photo = $_REQUEST['photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '||0||'.__('You do not have the rights to delete a photo', 'wppa').$nonce;
				exit;																// Nonce check failed
			}
			// Get file extension
			$ext = $wpdb->get_var($wpdb->prepare('SELECT `ext` FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s', $photo));
			// Get album
			$album = $wpdb->get_var($wpdb->prepare('SELECT `album` FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s', $photo));
			// Delete fullsize image
			$file = ABSPATH.'wp-content/uploads/wppa/'.$photo.'.'.$ext;
			if (file_exists($file)) unlink($file);
			// Delete thumbnail image
			$file = ABSPATH.'wp-content/uploads/wppa/thumbs/'.$photo.'.'.$ext;
			if (file_exists($file)) unlink($file);
			// Delete db entries
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `id` = %s LIMIT 1', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_COMMENTS.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_IPTC.'` WHERE `photo` = %s', $photo));
			$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_EXIF.'` WHERE `photo` = %s', $photo));
			// Delete dislikes
			wppa_dislike_remove($photo);
			
			echo '||1||<span style="color:red" >'.sprintf(__('Photo %s has been deleted', 'wppa'), $photo).'</span>';
			wppa_clear_cache();
			echo '||';
			$a = wppa_allow_uploads($album);
			if ( ! $a ) echo 'full';
			else echo 'notfull||'.$a;
			break;

		case 'update-album':
			$album = $_REQUEST['album-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			$item  = $_REQUEST['item'];
			$value = $_REQUEST['value'];
			$value  = wppa_decode($value);
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$album) ) {
				echo '||0||'.__('You do not have the rights to update album information', 'wppa').$nonce;
				exit;																// Nonce check failed
			}

			switch ($item) {
				case 'clear_ratings':
					$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $album), ARRAY_A);
					if ($photos) foreach ($photos as $photo) {
						$iret1 = $wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_RATING.'` WHERE `photo` = %s', $photo['id']));
						$iret2 = $wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `mean_rating` = %s WHERE `id` = %s', '', $photo['id']));
					}
					if ($photos && $iret1 !== false && $iret2 !== false) {
						echo '||97||'.__('<b>Ratings cleared</b>', 'wppa').'||'.__('No ratings for this photo.', 'wppa');
					}
					elseif ($photos) {
						echo '||1||'.__('An error occurred while clearing ratings', 'wppa');
					}
					else {
						echo '||97||'.__('<b>No photos in this album</b>', 'wppa').'||'.__('No ratings for this photo.', 'wppa');
					}
					exit;
					break;
				case 'set_deftags':
					$photos = $wpdb->get_results($wpdb->prepare('SELECT COUNT(*) FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $album), ARRAY_A);
					$deftag = $wpdb->get_var($wpdb->prepare('SELECT `default_tags` FROM `'.WPPA_ALBUMS.'` WHERE `id` = %s', $album));
					$iret = $wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `tags` = %s WHERE `album` = %s', $deftag, $album));
					if ( $photos && $iret !== false ) {
						echo '||97||'.__('<b>Tags set to defaults</b> (reload)', 'wppa');
					}
					elseif ($photos) {
						echo '||1||'.__('An error occurred while setting tags', 'wppa');
					}
					else {
						echo '||97||'.__('<b>No photos in this album</b>', 'wppa');
					}
					wppa_clear_taglist();
					exit;
					break;
				case 'add_deftags':
					$photos = $wpdb->get_results($wpdb->prepare('SELECT `id`, `tags` FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $album), ARRAY_A);
					$deftag = $wpdb->get_var($wpdb->prepare('SELECT `default_tags` FROM `'.WPPA_ALBUMS.'` WHERE `id` = %s', $album));
					$iret = true;
					if ( $photos ) foreach ( $photos as $photo ) {
						if ( $iret ) {
							$tags = wppa_sanitize_tags($photo['tags'].','.$deftag);
							$iret = $wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `tags` = %s WHERE `id` = %s', $tags, $photo['id']));
						}					
					}
					if ( $photos && $iret !== false ) {
						echo '||97||'.__('<b>Tags added width defaults</b> (reload)', 'wppa');
					}
					elseif ($photos) {
						echo '||1||'.__('An error occurred while adding tags', 'wppa');
					}
					else {
						echo '||97||'.__('<b>No photos in this album</b>', 'wppa');
					}
					wppa_clear_taglist();
					exit;
					break;
				case 'name':
					$value = strip_tags($value);
					$itemname = __('Name', 'wppa');
					break;
				case 'description':
					$itemname = __('Description', 'wppa');
					if ( $wppa_opt['wppa_check_balance'] == 'yes' ) {
						$value = str_replace(array('<br/>','<br>'), '<br />', $value);
						if ( balanceTags( $value, true ) != $value ) {
							echo '||3||'.__('Unbalanced tags in album description!', 'wppa');
							exit;
						}
					}
					break;
				case 'a_order':
					$itemname = __('Album order #', 'wppa');
					break;
				case 'main_photo':
					$itemname = __('Cover photo', 'wppa');
					break;
				case 'a_parent':
					$itemname = __('Parent album', 'wppa');
					break;
				case 'p_order_by':
					$itemname = __('Photo order', 'wppa');
					break;
				case 'alt_thumbsize':
					$itemname = __('Use Alt thumbsize', 'wppa');
					break;
				case 'cover_linktype':
					$itemname = __('Link type', 'wppa');
					break;
				case 'cover_linkpage':
					$itemname = __('Link to', 'wppa');
					break;
				case 'owner':
					$itemname = __('Owner', 'wppa');
					break;
				case 'upload_limit_count':
					wppa_ajax_check_range($value, false, '0', false, __('Upload limit count', 'wppa'));
					if ( $wppa['error'] ) exit;
					$oldval = $wpdb->get_var($wpdb->prepare('SELECT `upload_limit` FROM '.WPPA_ALBUMS.' WHERE `id` = %s', $album));
					$temp = explode('/', $oldval);
					$value = $value.'/'.$temp[1];
					$item = 'upload_limit';
					$itemname = __('Upload limit count', 'wppa');
					break;
				case 'upload_limit_time':
					$oldval = $wpdb->get_var($wpdb->prepare('SELECT `upload_limit` FROM '.WPPA_ALBUMS.' WHERE `id` = %s', $album));
					$temp = explode('/', $oldval);
					$value = $temp[0].'/'.$value;
					$item = 'upload_limit';
					$itemname = __('Upload limit time', 'wppa');
					break;
				case 'default_tags':
					$value = wppa_sanitize_tags($value);
					$itemname = __('Default tags', 'wppa');
					break;
				default:
					$itemname = $item;
			}
			
			$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_ALBUMS.' SET `'.$item.'` = %s WHERE `id` = %s', $value, $album));
			if ($iret !== false ) {
				echo '||0||'.sprintf(__('<b>%s</b> of album %s updated', 'wppa'), $itemname, $album);
				if ( $item == 'upload_limit' ) {
					echo '||';
					$a = wppa_allow_uploads($album);
					if ( ! $a ) echo 'full';
					else echo 'notfull||'.$a;
				}
			}
			else {
				echo '||2||'.sprintf(__('An error occurred while trying to update <b>%s</b> of album %s', 'wppa'), $itemname, $album);
				echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
			}
			wppa_clear_cache();
			exit;
			break;
		
		case 'update-comment-status':
			$photo = $_REQUEST['wppa-photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			$comid = $_REQUEST['wppa-comment-id'];
			$comstat = $_REQUEST['wppa-comment-status'];
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '||0||'.__('You do not have the rights to update comment status', 'wppa').$nonce;
				exit;																// Nonce check failed
			}

			$iret = $wpdb->query($wpdb->prepare('UPDATE `'.WPPA_COMMENTS.'` SET `status` = %s WHERE `id` = %s', $comstat, $comid));
			
			if ( $iret !== false ) {
				echo '||0||'.sprintf(__('Status of comment #%s updated', 'wppa'), $comid);
			}
			else {
				echo '||1||'.sprintf(__('Error updating status comment #%s', 'wppa'), $comid);
			}
			exit;
			break;
			
		case 'watermark-photo':
			$photo = $_REQUEST['photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
		
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '||1||'.__('You do not have the rights to change photos', 'wppa');
				exit;																// Nonce check failed
			}
			
			$ext = $wpdb->get_var($wpdb->prepare("SELECT `ext` FROM `".WPPA_PHOTOS."` WHERE `id` = %s", $photo));
			
			if ( wppa_add_watermark(WPPA_UPLOAD_PATH.'/'.$photo.'.'.$ext) ) {
				echo '||0||'.__('Watermark applied', 'wppa');
				exit;
			}
			else {
				echo '||1||'.__('An error occured while trying to apply a watermark', 'wppa');
				exit;
			}

		case 'update-photo':
			$photo = $_REQUEST['photo-id'];
			$nonce = $_REQUEST['wppa-nonce'];
			$item  = $_REQUEST['item'];
			$value = $_REQUEST['value'];
			$value  = wppa_decode($value);
			
			// Check validity
			if ( ! wp_verify_nonce($nonce, 'wppa_nonce_'.$photo) ) {
				echo '||0||'.__('You do not have the rights to update photo information', 'wppa');
				exit;																// Nonce check failed
			}
			
			switch ($item) {
				case 'rotright':
				case 'rotleft':
					$angle = $item == 'rotleft' ? '90' : '270';
					$wppa['error'] = wppa_rotate($photo, $angle);
					$leftorright = $item == 'rotleft' ? __('left', 'wppa') : __('right', 'wppa');
					if ( ! $wppa['error'] ) {
						echo '||0||'.sprintf(__('Photo %s rotated %s', 'wppa'), $photo, $leftorright);
					}
					else {
						echo '||'.$wppa['error'].'||'.sprintf(__('An error occurred while trying to rotate photo %s', 'wppa'), $photo);
					}
					exit;
					break;
					
				case 'moveto':
					$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_PHOTOS.' SET `album` = %s WHERE `id` = %s', $value, $photo));
					if ($iret !== false ) {
						echo '||99||'.sprintf(__('Photo %s has been moved to album %s (%s)', 'wppa'), $photo, wppa_get_album_name($value), $value);
					}
					else {
						echo '||3||'.sprintf(__('An error occurred while trying to move photo %s', 'wppa'), $photo);
					}
					exit;
					break;
					
				case 'copyto':
					$wppa['error'] = wppa_copy_photo($photo, $value);
					if ( ! $wppa['error'] ) {
						echo '||0||'.sprintf(__('Photo %s copied to album %s (%s)', 'wppa'), $photo, wppa_get_album_name($value), $value);
					}
					else {
						echo '||4||'.sprintf(__('An error occurred while trying to copy photo %s', 'wppa'), $photo);
						echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
					}
					break;
					
				case 'name':
				case 'description':
				case 'p_order':
				case 'owner':
				case 'linkurl':
				case 'linktitle':
				case 'linktarget':
				case 'tags':
				case 'status':
				case 'alt':
					switch ($item) {
						case 'name':
							$value = strip_tags($value);
							$itemname = __('Name', 'wppa');
							break;
						case 'description':
							$itemname = __('Description', 'wppa');
							if ( $wppa_opt['wppa_check_balance'] == 'yes' ) {
								$value = str_replace(array('<br/>','<br>'), '<br />', $value);
								if ( balanceTags( $value, true ) != $value ) {
									echo '||3||'.__('Unbalanced tags in photo description!', 'wppa');
									exit;
								}
							}
							break;
						case 'p_order':
							$itemname = __('Photo order #', 'wppa');
							break;
						case 'owner':
							$itemname = __('Owner', 'wppa');
							break;
						case 'linkurl':
							$itemname = __('Link url', 'wppa');
							break;
						case 'linktitle':
							$itemname = __('Link title', 'wppa');
							break;
						case 'linktarget':
							$itemname = __('Link target', 'wppa');
							break;
						case 'tags':
							// Sanitize tags
							$value = wppa_sanitize_tags($value);
							wppa_clear_taglist();
							$itemname = __('Photo Tags', 'wppa');
							break;
						case 'status':
							$itemname = __('Status', 'wppa');
							break;
						case 'alt':
							$itemname = __('HTML Alt', 'wppa');
							$value = strip_tags(stripslashes($value));
							break;
						default:
							$itemname = $item;
					}
					$iret = $wpdb->query($wpdb->prepare('UPDATE '.WPPA_PHOTOS.' SET `'.$item.'` = %s WHERE `id` = %s', $value, $photo));
					if ($iret !== false ) {
						echo '||0||'.sprintf(__('<b>%s</b> of photo %s updated', 'wppa'), $itemname, $photo);
					}
					else {
						echo '||2||'.sprintf(__('An error occurred while trying to update <b>%s</b> of photo %s', 'wppa'), $itemname, $photo);
						echo '<br>'.__('Press CTRL+F5 and try again.', 'wppa');
					}
					exit;
					break;

					
				default:
					echo '||98||This update action is not implemented yet('.$item.')';
					exit;
			}
			wppa_clear_cache();
			break;
			
		// The wppa-settings page calls ajax with $wppa_action == 'update-option';
		case 'update-option':
			// Verify that we are legally here
			$nonce  = $_REQUEST['wppa-nonce'];
			if ( ! wp_verify_nonce($nonce, 'wppa-nonce') ) {
				echo '||1||'.__('You do not have the rights to update settings', 'wppa');
				exit;																// Nonce check failed
			}
			
			// Initialize
			$old_minisize = wppa_get_minisize();		// Remember for later, maybe we do something that requires regen
			$option = $_REQUEST['wppa-option'];			// The option to be processed
			$value  = isset($_REQUEST['value']) ? wppa_decode($_REQUEST['value']) : '';	// The new value, may also contain & # and +
			$value  = stripslashes($value);
			$alert  = '';			// Init the return string data
			$wppa['error']  = '0';	//
			$title  = '';			//
			
			$option = wppa_decode($option);
			// Dispatch on option
			if ( substr($option, 0, 16) == 'wppa_iptc_label_' ) {
				$tag = substr($option, 16);
				$q = $wpdb->prepare("UPDATE `".WPPA_IPTC."` SET `description`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				echo $output;
				exit;
			}
			elseif ( substr($option, 0, 17) == 'wppa_iptc_status_' ) {
				$tag = substr($option, 17);
				$q = $wpdb->prepare("UPDATE `".WPPA_IPTC."` SET `status`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				echo $output;			
				exit;
			}
			elseif ( substr($option, 0, 16) == 'wppa_exif_label_' ) {
				$tag = substr($option, 16);
				$q = $wpdb->prepare("UPDATE `".WPPA_EXIF."` SET `description`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				echo $output;
				exit;
			}
			elseif ( substr($option, 0, 17) == 'wppa_exif_status_' ) {
				$tag = substr($option, 17);
				$q = $wpdb->prepare("UPDATE `".WPPA_EXIF."` SET `status`=%s WHERE `tag`=%s AND `photo`='0'", $value, $tag);
				$bret = $wpdb->query($q);
				// Produce the response text
				if ($bret) {
					$output = '||0||'.$tag.' updated to '.$value.'||';
				}
				else {
					$output = '||1||Failed to update '.$tag.'||';
				}
				echo $output;			
				exit;
			}
			elseif ( substr($option, 0, 5) == 'caps-' ) {	// Is capability setting
				global $wp_roles;
				//$R = new WP_Roles;
				$setting = explode('-', $option);
				if ( $value == 'yes' ) {
					$wp_roles->add_cap($setting[2], $setting[1]);
					echo '||0||'.__('Capability granted', 'wppa').'||';
					exit;
				}
				elseif ( $value == 'no' ) {
					$wp_roles->remove_cap($setting[2], $setting[1]);
					echo '||0||'.__('Capability withdrawn', 'wppa').'||';
					exit;
				}
				else {
					echo '||1||Invalid value: '.$value.'||';
					exit;
				}
			}
			else switch ($option) {
					
				case 'wppa_colwidth': //	 ??	  fixed   low	high	title
					wppa_ajax_check_range($value, 'auto', '100', false, __('Column width.', 'wppa'));
					break;
				case 'wppa_fullsize':
					wppa_ajax_check_range($value, false, '100', false, __('Full size.', 'wppa'));
					break;
				case 'wppa_maxheight':
					wppa_ajax_check_range($value, false, '100', false, __('Max height.', 'wppa'));
					break;
				case 'wppa_thumbsize':
					wppa_ajax_check_range($value, false, '50', false, __('Thumbnail size.', 'wppa'));
					break;
				case 'wppa_tf_width':
					wppa_ajax_check_range($value, false, '50', false, __('Thumbnail frame width', 'wppa'));
					break;
				case 'wppa_tf_height':
					wppa_ajax_check_range($value, false, '50',false,  __('Thumbnail frame height', 'wppa'));
					break;
				case 'wppa_tn_margin':
					wppa_ajax_check_range($value, false, '0', false, __('Thumbnail Spacing', 'wppa'));
					break;
				case 'wppa_min_thumbs':
					wppa_ajax_check_range($value, false, '0', false, __('Photocount treshold.', 'wppa'));
					break;
				case 'wppa_thumb_page_size':
					wppa_ajax_check_range($value, false, '0', false, __('Thumb page size.', 'wppa'));
					break;
				case 'wppa_smallsize':
					wppa_ajax_check_range($value, false, '50', false, __('Cover photo size.', 'wppa'));
					break;
				case 'wppa_album_page_size':
					wppa_ajax_check_range($value, false, '0', false, __('Album page size.', 'wppa'));
					break;
				case 'wppa_topten_count':
					wppa_ajax_check_range($value, false, '2', false, __('Number of TopTen photos', 'wppa'), '40');
					break;
				case 'wppa_topten_size':
					wppa_ajax_check_range($value, false, '32', false, __('Widget image thumbnail size', 'wppa'), wppa_get_minisize());
					break;
				case 'wppa_max_cover_width':
					wppa_ajax_check_range($value, false, '150', false, __('Max Cover width', 'wppa'));
					break;
				case 'wppa_text_frame_height':
					wppa_ajax_check_range($value, false, '0', false, __('Minimal Cover text frame height', 'wppa'));
					break;
				case 'wppa_bwidth':
					wppa_ajax_check_range($value, '', '0', false, __('Border width', 'wppa'));
					break;
				case 'wppa_bradius':
					wppa_ajax_check_range($value, '', '0', false, __('Border radius', 'wppa'));
					break;
				case 'wppa_box_spacing':
					wppa_ajax_check_range($value, '', '-20', '100', __('Box spacing', 'wppa'));
					break;
				case 'wppa_popupsize':				
					$floor = $wppa_opt['wppa_thumbsize'];
					$temp  = $wppa_opt['wppa_smallsize'];
					if ($temp > $floor) $floor = $temp;
					wppa_ajax_check_range($value, false, $floor, $wppa_opt['wppa_fullsize'], __('Popup size', 'wppa'));
					break;
				case 'wppa_fullimage_border_width':
					wppa_ajax_check_range($value, '', '0', false, __('Fullsize border width', 'wppa'));
					break;
				case 'wppa_lightbox_bordersize':
					wppa_ajax_check_range($value, false, '0', false, __('Lightbox Bordersize', 'wppa'));
					break;
				case 'wppa_comment_count':
					wppa_ajax_check_range($value, false, '2', '40', __('Number of Comment widget entries', 'wppa'));
					break;
				case 'wppa_comment_size':
					wppa_ajax_check_range($value, false, '32', wppa_get_minisize(), __('Comment Widget image thumbnail size', 'wppa'), wppa_get_minisize());
					break;
				case 'wppa_rerate':
					if ( wppa_recalculate_ratings() ) $title = __('Ratings recalculated', 'wppa');
					break;
				case 'wppa_thumb_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Opacity.', 'wppa'));
					break;
				case 'wppa_cover_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Opacity.', 'wppa'));
					break;
				case 'wppa_star_opacity':
					wppa_ajax_check_range($value, false, '0', '50', __('Opacity.', 'wppa'));
					break;
				case 'wppa_filter_priority':
					wppa_ajax_check_range($value, false, '10', false, __('Filter priority', 'wppa'));
					break;
				case 'wppa_gravatar_size':
					wppa_ajax_check_range($value, false, '10', '256', __('Avatar size', 'wppa'));
					break;
				case 'wppa_watermark_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Watermark opacity', 'wppa'));
					break;
				case 'wppa_ovl_txt_lines':
					wppa_ajax_check_range($value, 'auto', '0', '24', __('Number of text lines', 'wppa'));
					break;
				case 'wppa_ovl_opacity':
					wppa_ajax_check_range($value, false, '0', '100', __('Overlay opacity', 'wppa'));
					break;
				case 'wppa_upload_limit_count':
					wppa_ajax_check_range($value, false, '0', false, __('Upload limit', 'wppa'));
					break;
				case 'wppa_dislike_mail_every':
					wppa_ajax_check_range($value, false, '0', false, __('Notify inappropriate', 'wppa'));
					break;
				case 'wppa_cp_points_comment':
				case 'wppa_cp_points_rating':
				case 'wppa_cp_points_upload':
					wppa_ajax_check_range($value, false, '0', false, __('Cube Points points', 'wppa'));
					break;
				case 'wppa_rating_clear':
					$iret1 = $wpdb->query( 'TRUNCATE TABLE '.WPPA_RATING );
					$iret2 = $wpdb->query( 'UPDATE '.WPPA_PHOTOS.' SET mean_rating="0", rating_count="0" WHERE id > -1' );
					if ($iret1 !== false && $iret2 !== false) {
						delete_option('wppa_'.WPPA_RATING.'_lastkey');
						$title = __('Ratings cleared', 'wppa');
					}
					else {
						$title = __('Could not clear ratings', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;

				case 'wppa_iptc_clear':
					$iret = $wpdb->query( 'TRUNCATE TABLE '.WPPA_IPTC );
					if ($iret !== false) {
						delete_option('wppa_'.WPPA_IPTC.'_lastkey');
						$title = __('IPTC data cleared', 'wppa');
						$alert = __('Refresh this page to clear table X', 'wppa');
					}
					else {
						$title = __('Could not clear IPTC data', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;

				case 'wppa_exif_clear':
					$iret = $wpdb->query( 'TRUNCATE TABLE '.WPPA_EXIF );
					if ($iret !== false) {
						delete_option('wppa_'.WPPA_EXIF.'_lastkey');
						$title = __('EXIF data cleared', 'wppa');
						$alert = __('Refresh this page to clear table XI', 'wppa');
					}
					else {
						$title = __('Could not clear EXIF data', 'wppa');
						$alert = $title;
						$wppa['error'] = '1';
					}
					break;
					
				case 'wppa_recup':
					$result = wppa_recuperate_iptc_exif();
					echo '||0||'.__('Recuperation performed', 'wppa').'||'.$result;
					exit;
					break;

				case 'wppa_regen':
				case 'wppa_thumb_aspect':
					if ( get_option('wppa_lastthumb', '-2') == '-2' ) {
						wppa_update_option('wppa_lastthumb', '-1');	// Trigger regen if not doing already
						$old_minisize--;
					}				
					break;

				case 'wppa_rating_max':
					if ( $value == '5' && $wppa_opt['wppa_rating_max'] == '10' ) {
						$rats = $wpdb->get_results( 'SELECT `id`, `value` FROM `'.WPPA_RATING.'`', ARRAY_A );
						if ( $rats ) {
							foreach ( $rats as $rat ) {
								$wpdb->query($wpdb->prepare('UPDATE `'.WPPA_RATING.'` SET `value` = %s WHERE `id` = %s', $rat['value']/2, $rat['id']));
							}
						}
					}
					if ( $value == '10' && $wppa_opt['wppa_rating_max'] == '5' ) {
						$rats = $wpdb->get_results( 'SELECT `id`, `value` FROM `'.WPPA_RATING.'`', ARRAY_A );
						if ( $rats ) {
							foreach ( $rats as $rat ) {
								$wpdb->query($wpdb->prepare('UPDATE `'.WPPA_RATING.'` SET `value` = %s WHERE `id` = %s', $rat['value']*2, $rat['id']));
							}
						}
					}
					wppa_recalculate_ratings();
					wppa_update_option($option, $value);
					$wppa['error'] = '0';
					$alert = '';
					break;
					
				case 'wppa_newphoto_description':
					if ( $wppa_opt['wppa_check_balance'] == 'yes' && balanceTags( $value, true ) != $value ) {
						$alert = __('Unbalanced tags in photo description!', 'wppa');
						$wppa['error'] = '1';
					}
					else {
						wppa_update_option($option, $value);
						$wppa['error'] = '0';
						$alert = '';
					}
					break;
				
				default:
					// Do the update only
					wppa_update_option($option, $value);
					$wppa['error'] = '0';
					$alert = '';
			}
			if ( $wppa['error'] ) {
				if ( ! $title ) $title = sprintf(__('Failed to set %s to %s', 'wppa'), $option, $value);
				if ( ! $alert ) $alert .= $wppa['out'];
			}
			else {
				wppa_update_option($option, $value);
				if ( ! $title ) $title = sprintf(__('Setting %s updated to %s', 'wppa'), $option, $value);
			}
			
			// Did we do something that will require regen?
			$new_minisize = wppa_get_minisize();
			if ( $old_minisize != $new_minisize ) {
				wppa_update_option('wppa_lastthumb', '-1');	// Trigger regen
				$alert .= __('You just changed a setting that requires the regeneration of thumbnails.', 'wppa');
				$alert .= ' '.__('This process will start as soon as you refresh or re-enter the settings page.', 'wppa');
			}
			
			// Produce the response text
			$output = '||'.$wppa['error'].'||'.esc_attr($title).'||'.esc_js($alert);
			
			echo $output;
			wppa_clear_cache();
			exit;
			break;	// End update-option
			
		default:	// Unimplemented $wppa-action
		die('-1');
	}
	exit;
}

function wppa_decode($string) {
	$arr = explode('||HASH||', $string);
	$result = implode('#', $arr);
	$arr = explode('||AMP||', $result);
	$result = implode('&', $arr);
	$arr = explode('||PLUS||', $result);
	$result = implode('+', $arr);
	
	return $result;
}

function wppa_ajax_check_range($value, $fixed, $low, $high, $title) {
global $wppa;
	if ( $fixed !== false && $fixed == $value ) return;						// User enetred special value correctly
	if ( !is_numeric($value) ) $wppa['error'] = true;						// Must be numeric if not specaial value
	if ( $low !== false && $value < $low ) $wppa ['error'] = true;			// Must be >= given min value
	if ( $high !== false && $value > $high ) $wppa ['error'] = true;		// Must be <= given max value
	
	if ( !$wppa ['error'] ) return;		// Still no error, ok
	
	// Compose error message
	if ($low !== false && $high === false) {	// Only Minimum given
		$wppa['out'] .= __('Please supply a numeric value greater than or equal to', 'wppa') . ' ' . $low . ' ' . __('for', 'wppa') . ' ' . $title;
		if ( $fixed !== false ) {
			if ( $fixed ) $wppa['out'] .= '. ' . __('You may also enter:', 'wppa') . ' ' . $fixed;
			else $wppa['out'] .= '. ' . __('You may also leave/set this blank', 'wppa');
		}
	}
	else {	// Also Maximum given
		$wppa['out'] .= __('Please supply a numeric value greater than or equal to', 'wppa') . ' ' . $low . ' ' . __('and less than or equal to', 'wppa') . ' ' . $high . ' ' . __('for', 'wppa') . ' ' . $title;
		if ( $fixed !== false ) {
			if ( $fixed ) $wppa['out'] .= '. ' . __('You may also enter:', 'wppa') . ' ' . $fixed;
			else $wppa['out'] .= '. ' . __('You may also leave/set this blank', 'wppa');
		}
	}
}

function wppa_sanitize_tags($value) {
	$value = strip_tags($value);
	$value = str_replace(' ', '', $value);
	$value = str_replace(';', ',', $value);
	$value = str_replace('"', '', $value);
	$value = str_replace('\'', '', $value);
	$value = str_replace('\\', '', $value);
	$value = stripslashes($value);
	$temp = explode(',', $value);
	if ( is_array($temp) ) {
		asort($temp);
		$value = '';
		$first = true;
		$previdx = '';
		foreach ( array_keys($temp) as $idx ) {
			$temp[$idx] = strtoupper(substr($temp[$idx], 0, 1)).strtolower(substr($temp[$idx], 1));
			if ( $temp[$idx] ) {
				if ( $first ) {
					$first = false;
					$value .= $temp[$idx];
					$previdx = $idx;
				}
				elseif ( $temp[$idx] !=  $temp[$previdx] ) {	// Skip duplicates
					$value .= ','.$temp[$idx];
					$previdx = $idx;
				}
			}									
		}
	}
	return $value;
}
