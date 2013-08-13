<?php
/* wppa-utils.php
* Package: wp-photo-album-plus
*
* Contains low-level utility routines
* Version 4.9.13
*
*/

function __a($txt, $dom = 'wppa_theme') {
	return __($txt, $dom);
}

// Bring album into cache
function wppa_cache_album($id) {
global $wpdb;
global $album;

	if ( ! is_numeric($id) || $id < '1' ) {
		wppa_dbg_msg('Invalid arg wppa_cache_album('.$id.')', 'red');
		return false;
	}
	if ( ! isset($album['id']) || $album['id'] != $id ) {
		$album = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".WPPA_ALBUMS."` WHERE `id` = %s", $id), 'ARRAY_A');
		wppa_dbg_q('Q90');
		if ( ! $album ) {
			wppa_dbg_msg('Album does not exist', 'red');
			return false;
		}
	}
	else {
		wppa_dbg_q('G90');
	}
	return true;
}

// Bring photo into cache
function wppa_cache_thumb($id) {
global $wpdb;
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) {
		wppa_dbg_msg('Invalid arg wppa_cache_thumb('.$id.')', 'red');
		return;
	}
	if ( ! isset($thumb['id']) || $thumb['id'] != $id ) {
		$thumb = $wpdb->get_row($wpdb->prepare("SELECT * FROM `".WPPA_PHOTOS."` WHERE `id` = %s", $id), 'ARRAY_A');
		wppa_dbg_q('Q91');
	}
	else {
		wppa_dbg_q('G91');
	}
}

// get url of thumb
function wppa_get_thumb_url($id) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_thumb_url('.$id.')', 'red');
	wppa_cache_thumb($id);
	return WPPA_UPLOAD_URL.'/thumbs/' . $thumb['id'] . '.' . $thumb['ext'];
}

// get path of thumb
function wppa_get_thumb_path($id) {
global $thumb;
	
	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_thumb_path('.$id.')', 'red');
	wppa_cache_thumb($id);
	return WPPA_UPLOAD_PATH.'/thumbs/'.$thumb['id'].'.'.$thumb['ext'];
}

// get url of a full sized image
function wppa_get_photo_url($id) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_photo_url('.$id.')', 'red');
	wppa_cache_thumb($id);
	return WPPA_UPLOAD_URL.'/'.$id.'.'.$thumb['ext'];
}

// get path of a full sized image
function wppa_get_photo_path($id) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_photo_path('.$id.')', 'red');
	wppa_cache_thumb($id);
	return WPPA_UPLOAD_PATH.'/'.$id.'.'.$thumb['ext'];
}

// get the name of a full sized image
function wppa_get_photo_name($id, $add_owner = false) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_photo_name('.$id.')', 'red');
	wppa_cache_thumb($id);
	$result = __(stripslashes($thumb['name']));
	if ( $add_owner ) {
		$user = get_user_by('login', $thumb['owner']);
		if ( $user ) {
			$result .= ' ('.$user->display_name.')';
		}
	}
	return $result;
}

// get the description of an image
function wppa_get_photo_desc($id, $do_shortcodes = false) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_photo_desc('.$id.')', 'red');
	wppa_cache_thumb($id);
	$desc = $thumb['description'];			// Raw data
	$desc = stripslashes($desc);			// Unescape
	$desc = __($desc);						// qTranslate 

	// To prevent recursive rendering of scripts or shortcodes:
	$desc = str_replace(array('%%wppa%%', '[wppa', '[/wppa]'), array('%-wppa-%', '{wppa', '{/wppa}'), $desc);

	if ( $do_shortcodes ) $desc = do_shortcode($desc);	// Do shortcodes if wanted
	else $desc = strip_shortcodes($desc);				// Remove shortcodes if not wanted

	$desc = wppa_html($desc);				// Enable html
	$desc = balanceTags($desc, true);		// Balance tags
	$desc = wppa_filter_iptc($desc, $id);	// Render IPTC tags
	$desc = wppa_filter_exif($desc, $id);	// Render EXIF tags
	
	return $desc;
}

// See if an album is in a separate tree
function wppa_is_separate($id) {

	if ( $id == '' ) return false;
	if ( ! is_numeric($id) ) {
		wppa_dbg_msg('Invalid arg wppa_is_separate('.$id.')', 'red');
		return false;
	}
	if ( $id == '-1' ) return true;
	if ( $id < '1' ) return false;
	$alb = wppa_get_parentalbumid($id);
	
	return wppa_is_separate($alb);
}

// Get the albums parent
function wppa_get_parentalbumid($id) {
global $album;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_parentalbumid('.$id.')', 'red');
	if ( ! wppa_cache_album($id) ) {
		wppa_dbg_msg('Album '.$id.' no longer exists, but is still set as a parent. Please correct this.', 'red');
		return '-9';	// Album does not exist
	}
	return $album['a_parent'];
}

// get album name
function wppa_get_album_name($id, $extended = false) {
global $wpdb;
global $album;

    $name = '';
	
	if ( $extended ) {
		if ( $id == '0' ) {
			$name = is_admin() ? __('--- none ---', 'wppa') : __a('--- none ---', 'wppa_theme');
			return $name;
		}
		if ( $id == '-1' ) {
			$name = is_admin() ? __('--- separate ---', 'wppa') : __a('--- separate ---', 'wppa_theme');
			return $name;
		}
		if ( $id == '-2' ) {
			$name = is_admin() ? __('--- all ---', 'wppa') : __a('--- all ---', 'wppa_theme');
			return $name;
		}
		if ( $id == '-9' ) {
			$name = is_admin() ? __('--- deleted ---', 'wppa') : __a('--- deleted ---', 'wppa_theme');
			return $name;
		}
		if ( $extended == 'raw' ) {
			$name = stripslashes($wpdb->get_var($wpdb->prepare("SELECT `name` FROM `".WPPA_ALBUMS."` WHERE `id` = %s", $id)));
			return $name;
		}
	}
	else {
		if ( $id == '-2' ) {
			$name = is_admin() ? __('All Albums', 'wppa') : __a('All Albums', 'wppa_theme');
			return $name;
		}
	}
	
	if ( ! is_numeric($id) || $id < '1' ) {
		wppa_dbg_msg('Invalid arg wppa_get_album_name('.$id.', '.$extended.')', 'red');
		return '';
	}
    else {
		if ( ! wppa_cache_album($id) ) $name = is_admin() ? __('--- deleted ---', 'wppa') : __a('--- deleted ---', 'wppa_theme');
		else $name = __(stripslashes($album['name']));
    }

	return $name;
}

// get album decription
function wppa_get_album_desc($id) {
global $album;
	
	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_album_desc('.$id.')', 'red');
	wppa_cache_album($id);
	$desc = $album['description'];			// Raw data
	$desc = stripslashes($desc);			// Unescape
	$desc = __($desc);						// qTranslate 
	$desc = wppa_html($desc);				// Enable html
	$desc = balanceTags($desc, true);		// Balance tags

	// To prevent recursive rendering of scripts or shortcodes:
	$desc = str_replace(array('%%wppa%%', '[wppa', '[/wppa]'), array('%-wppa-%', '{wppa', '{/wppa}'), $desc);
	return $desc;
}

// get a photos album id
function wppa_get_album_id_by_photo_id($id) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_album_id_by_photo_id('.$id.')', 'red');
	wppa_cache_thumb($id);
	return $thumb['album'];
}

function wppa_get_rating_count_by_id($id) {
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_rating_count_by_id('.$id.')', 'red');
	wppa_cache_thumb($id);
	return $thumb['rating_count'];
}

function wppa_get_rating_by_id($id, $opt = '') {
global $wpdb;
global $wppa_opt;
global $thumb;

	if ( ! is_numeric($id) || $id < '1' ) wppa_dbg_msg('Invalid arg wppa_get_rating_by_id('.$id.', '.$opt.')', 'red');
	wppa_cache_thumb($id);
	$rating = $thumb['mean_rating'];
	if ( $rating ) {
		$i = $wppa_opt['wppa_rating_prec'];
		$j = $i + '1';
		$val = sprintf('%'.$j.'.'.$i.'f', $rating);
		if ($opt == 'nolabel') $result = $val;
		else $result = sprintf(__a('Rating: %s', 'wppa_theme'), $val);
	}
	else $result = '';
	return $result;
}

function wppa_switch($key) {
global $wppa_opt;
	return $wppa_opt[$key] === true || $wppa_opt[$key] == 'yes';
}

function wppa_add_paths($albums) {
	if ( is_array($albums) ) foreach ( array_keys($albums) as $index ) {
		$tempid = $albums[$index]['id'];
		$albums[$index]['name'] = __(stripslashes($albums[$index]['name']));	// Translate name
		while ( $tempid > '0' ) {
			$tempid = wppa_get_parentalbumid($tempid);
			if ( $tempid > '0' ) {
				$albums[$index]['name'] = wppa_get_album_name($tempid).' > '.$albums[$index]['name'];
			}
			elseif ( $tempid == '-1' ) $albums[$index]['name'] = '-s- '.$albums[$index]['name'];
		}
	}
	return $albums;
}
	
function wppa_array_sort($array, $on, $order=SORT_ASC) {

    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}

function wppa_get_taglist() {
	$result = get_option('wppa_taglist', 'nil');
	if ( $result == 'nil' ) {
		$result = wppa_create_taglist();
	}
	return $result;
}

function wppa_clear_taglist() {
	if ( get_option('wppa_taglist', 'nil') != 'nil' ) {
		delete_option('wppa_taglist');
	}
}

function wppa_create_taglist() {
global $wpdb;
	$result = false;
	$total = '0';
	$photos = $wpdb->get_results("SELECT `id`, `tags` FROM `".WPPA_PHOTOS."` WHERE `status` <> 'pending' AND `tags` <> ''", ARRAY_A);
	if ( $photos ) foreach ( $photos as $photo ) {
		$tags = explode(',', $photo['tags']);
		if ( $tags ) foreach ( $tags as $tag ) {
			if ( $tag ) {
				if ( ! isset($result[$tag]) ) {	// A new tag
					$result[$tag]['tag'] = $tag;
					$result[$tag]['count'] = '1';
					$result[$tag]['ids'][] = $photo['id'];
				}
				else {							// An existing tag
					$result[$tag]['count']++;
					$result[$tag]['ids'][] = $photo['id'];
				}
			}
			$total++;
		}
	}
	if ( is_array($result) ) {
		foreach ( array_keys($result) as $key ) {
			$result[$key]['fraction'] = round($result[$key]['count'] * 100 / $total) / 100;
		}
		$result = wppa_array_sort($result, 'tag');
	}
	update_option('wppa_taglist', $result);
	return $result;
}

function wppa_update_option($option, $value) {
	update_option($option, $value);
	delete_option('wppa_cached_options');
	delete_option('wppa_cached_options_admin');
}

function wppa_album_exists($id) {
global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_ALBUMS."` WHERE `id` = %s", $id));
}

function wppa_photo_exists($id) {
global $wpdb;
	return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE `id` = %s", $id));
}

function wppa_dislike_add($photo) {
global $wppa_opt;

	$usr = wppa_get_user();
	$data = get_option('wppa_dislikes', false);
	
	if ( ! is_array($data) ) { 	// Empty
		$data[$photo][] = $usr;
		update_option('wppa_dislikes', $data);
		return;
	}
	else {
		if ( ! isset($data[$photo]) || ! in_array($usr, $data[$photo]) ) {
			$data[$photo][] = $usr;
			update_option('wppa_dislikes', $data);
			$count = count($data[$photo]);
			
			if ( $count % $wppa_opt['wppa_dislike_mail_every'] == '0' ) {	// Mail the admin
				$to        = get_bloginfo('admin_email');
				$subj 	   = __('Notification of inappropriate image', 'wppa');
				$cont['0'] = sprintf(__('Photo %s has been marked as inappropriate by %s different visitors.', 'wppa'), $photo, $count);
				$cont['1'] = '<a href="'.get_admin_url().'admin.php?page=wppa_admin_menu&tab=pmod&photo='.$photo.'" >'.__('Manage photo', 'wppa').'</a>';
				wppa_send_mail($to, $subj, $cont, $photo);
			}
		}
	}
}

function wppa_dislike_remove($photo) {

	$data = get_option('wppa_dislikes', false);
	if ( is_array($data) ) {
		if ( isset($data[$photo]) ) unset($data[$photo]);
		update_option('wppa_dislikes', $data);
	}
}

function wppa_dislike_get($photo) {
	
	$data = get_option('wppa_dislikes', false);
	if ( is_array($data) ) {
		if ( isset($data[$photo]) ) {
			return $data[$photo];
		}
	}
	return false;
}

function wppa_send_mail($to, $subj, $cont, $photo, $email = '') {

	$from			= 'From: noreply@'.substr(home_url(), strpos(home_url(), '.') + '1');
	$extraheaders 	= "\n" . 'MIME-Version: 1.0' . "\n" . 'Content-Transfer-Encoding: 8bit' . "\n" . 'Content-Type: text/html; charset="UTF-8"';
	$message 		= '
<html>
	<head>
		<title>'.$subj.'</title>
		<style>blockquote { color:#000077; background-color: #dddddd; border:1px solid black; padding: 6px; border-radius 4px;} </style>
	</head>
	<body>
		<h3>'.$subj.'</h3>
		<p><img src="'.wppa_get_thumb_url($photo).'" /></p>';
		if ( is_array($cont) ) {
			foreach ( $cont as $c ) if ( $c ) {
				$message .= '
		<p>'.$c.'</p>';
			}
		}
		else {
			$message .= '
		<p>'.$cont.'</p>';
		}
		if ( is_user_logged_in() ) {
			global $current_user;
			get_currentuserinfo();
			$e = $current_user->user_email;
			$eml = sprintf(__a('The visitors email address is: <a href="mailto:%s">%s</a>'), $e, $e);
			$message .= '
		<p>'.$eml.'</p>';
		}
		elseif ( $email ) {
			$e = $email;
			$eml = sprintf(__a('The visitor says his email address is: <a href="mailto:%s">%s</a>'), $e, $e);
			$message .= '
		<p>'.$eml.'</p>';
		}
		$message .= '
		<p><small>'.sprintf(__a('This message is automaticly generated at %s. It is useless to respond to it.'), '<a href="'.home_url().'" >'.home_url().'</a>').'</small></p>';
		$message .= '
	</body>
</html>';
				
	$iret = mail( $to , '['.str_replace('&#039;', '', get_bloginfo('name')).'] '.$subj , $message , $from . $extraheaders, '' );
	if ( ! $iret ) echo 'Mail sending Failed';
}

function wppa_get_imgalt($id) {
global $thumb;
global $wppa_opt;

	wppa_cache_thumb($id);
	switch ( $wppa_opt['wppa_alt_type'] ) {
		case 'fullname':
			$result = ' alt="'.esc_attr(wppa_get_photo_name($id)).'" ';
			break;
		case 'namenoext':
			$temp = wppa_get_photo_name($id);
			$ext = strrchr($temp, '.');
			if ( $ext ) {
				$temp = strstr($temp, $ext, true);
			}
			$result = ' alt="'.esc_attr($temp).'" ';
			break;
		case 'custom':
			$result = ' alt="'.esc_attr($thumb['alt']).'" ';
			break;
		default:
			$result = '';
			break;
	}
	return $result;
}

function wppa_get_slide_callback_url($callbackid) {
global $wppa;

	$url = wppa_get_permalink();
	if ( $wppa['start_album'] ) $url .= 'wppa-album='.$wppa['start_album'].'&amp;';
	else $url .= 'wppa-album=0&amp;';
	$url .= 'wppa-cover=0&amp;';
	$url .= 'wppa-slide&amp;';
	if ( $wppa['is_single'] ) $url .= 'wppa-single=1&amp;';
	if ( $wppa['in_widget'] ) $url .= 'wppa-woccur='.$wppa['widget_occur'].'&amp;';
	else $url .= 'wppa-occur='.$wppa['occur'].'&amp;';
	if ( $wppa['is_topten'] ) $url .= 'wppa-topten='.$wppa['topten_count'].'&amp;';
	if ( $wppa['is_lasten'] ) $url .= 'wppa-lasten='.$wppa['lasten_count'].'&amp;';
	if ( $wppa['is_comten'] ) $url .= 'wppa-comten='.$wppa['comten_count'].'&amp;';
	if ( $wppa['is_tag'] ) $url .= 'wppa-tag='.$wppa['is_tag'].'&amp;';
	$url .= 'wppa-photo=' . $callbackid;
		
	return $url;
}

function wppa_get_thumb_callback_url() {
global $wppa;

	$url = wppa_get_permalink();
	if ( $wppa['start_album'] ) $url .= 'wppa-album='.$wppa['start_album'].'&amp;';
	else $url .= 'wppa-album=0&amp;';
	$url .= 'wppa-cover=0&amp;';
	if ( $wppa['is_single'] ) $url .= 'wppa-single=1&amp;';
	if ( $wppa['in_widget'] ) $url .= 'wppa-woccur='.$wppa['widget_occur'].'&amp;';
	else $url .= 'wppa-occur='.$wppa['occur'].'&amp;';
	if ( $wppa['is_topten'] ) $url .= 'wppa-topten='.$wppa['topten_count'].'&amp;';
	if ( $wppa['is_lasten'] ) $url .= 'wppa-lasten='.$wppa['lasten_count'].'&amp;';
	if ( $wppa['is_comten'] ) $url .= 'wppa-comten='.$wppa['comten_count'].'&amp;';
	if ( $wppa['is_tag'] ) $url .= 'wppa-tag='.$wppa['is_tag'].'&amp;';

	$url = substr($url, 0, strlen($url) - 5);	// remove last '&amp;'
		
	return $url;
}

function wppa_treecount_a($alb) {
global $wpdb;

	$albums = $wpdb->get_results($wpdb->prepare('SELECT `id` FROM `'.WPPA_ALBUMS.'` WHERE `a_parent` = %s', $alb), ARRAY_A);
	$album_count = empty($albums) ? '0' : count($albums);
	$photo_count = $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM `'.WPPA_PHOTOS.'`  WHERE `album` = %s AND `status` <> "pending"', $alb));
	
	$result = array('albums' => $album_count, 'photos' => $photo_count);
	if ( empty($albums) ) {}
	else foreach ( $albums as $album ) {
		$subcount = wppa_treecount_a($album['id']);
		$result['albums'] += $subcount['albums'];
		$result['photos'] += $subcount['photos'];
	}
	return $result;
}