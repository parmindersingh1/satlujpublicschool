<?php
/* wppa-album-admin-autosave.php
* Package: wp-photo-album-plus
*
* create, edit and delete albums
* version 4.9.16
*
*/

function _wppa_admin() {
	global $wpdb;
	global $q_config;
	global $wppa_opt;
	global $wppa_revno;
	
	if ( get_option('wppa_revision') != $wppa_revno ) wppa_check_database(true);
	
	echo '
<script type="text/javascript">
	/* <![CDATA[ */
	wppaAjaxUrl = "'.admin_url('admin-ajax.php').'";
	wppaUploadToThisAlbum = "'.__('Upload to this album', 'wppa').'";
	/* ]]> */
</script>
';

	// Delete trashed comments
	$query = "DELETE FROM " . WPPA_COMMENTS . " WHERE status='trash'";
	$wpdb->query($query);

	$sel = 'selected="selected"';

	// warn if the uploads directory is no writable
	if (!is_writable(WPPA_UPLOAD_PATH)) { 
		wppa_error_message(__('Warning:', 'wppa') . sprintf(__('The uploads directory does not exist or is not writable by the server. Please make sure that %s is writeable by the server.', 'wppa'), WPPA_UPLOAD_PATH));
	}

	// Fix orphan albums and deleted target pages
	$albs = $wpdb->get_results("SELECT * FROM `".WPPA_ALBUMS, ARRAY_A);
	if ( $albs ) {
		foreach ($albs as $alb) {
			if ( $alb['a_parent'] > '0' && wppa_get_parentalbumid($alb['a_parent']) == '-9' ) {	// Parent died?
				$wpdb->query("UPDATE `".WPPA_ALBUMS."` SET `a_parent` = '-1' WHERE `id` = '".$alb['id']."'");
			}
			if ( $alb['cover_linkpage'] > '0' ) {
				$iret = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".$wpdb->posts."` WHERE `ID` = %s AND `post_type` = 'page' AND `post_status` = 'publish'", $alb['cover_linkpage']));
				if ( ! $iret ) {	// Page gone?
					$wpdb->query("UPDATE `".WPPA_ALBUMS."` SET `cover_linkpage` = '0' WHERE `id` = '".$alb['id']."'");
				}
			}
		}
	}
	
	if (isset($_GET['tab'])) {		
		// album edit page
		if ($_GET['tab'] == 'edit'){
			if ($_GET['edit_id'] == 'new') {
				if ( ! wppa_can_create_album() ) wp_die('No rights to create an album');
				$id = wppa_nextkey(WPPA_ALBUMS);
				if (isset($_GET['parent_id'])) {
					$parent = $_GET['parent_id'];
					$name = wppa_get_album_name($parent).'-#'.$id;
					if ( ! current_user_can('administrator') ) {	// someone creating an album for someone else?
						$parentowner = $wpdb->get_var($wpdb->prepare("SELECT `owner` FROM `".WPPA_ALBUMS."` WHERE `id` = %s", $parent));
						if ( $parentowner !== wppa_get_user() ) wp_die('You are not allowed to create an album for someone else');
					}
				}
				else {
					$parent = '0';
					$name = __('New Album', 'wppa');
					if ( ! wppa_can_create_top_album() ) wp_die('No rights to create a top-level album');
				}				
				$uplim = $wppa_opt['wppa_upload_limit_count'].'/'.$wppa_opt['wppa_upload_limit_time'];
				$query = $wpdb->prepare("INSERT INTO `" . WPPA_ALBUMS . "` (`id`, `name`, `description`, `a_order`, `a_parent`, `p_order_by`, `main_photo`, `cover_linktype`, `cover_linkpage`, `owner`, `timestamp`, `upload_limit`, `alt_thumbsize`, `default_tags`, `cover_type`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '', '')", $id, $name, '', '0', $parent, '0', '0', 'content', '0', wppa_get_user(), time(), $uplim, '0');
				$iret = $wpdb->query($query);
				if ($iret === FALSE) {
					wppa_error_message(__('Could not create album.', 'wppa').'<br/>Query = '.$query);
					wp_die('Sorry, cannot continue');
				}
				else {
					$edit_id = $id;
					wppa_set_last_album($edit_id);
					wppa_update_message(__('Album #', 'wppa') . ' ' . $edit_id . ' ' . __('Added.', 'wppa'));
				}
			}
			else {
				$edit_id = $_GET['edit_id'];
			}
		
			$album_owner = $wpdb->get_var($wpdb->prepare("SELECT `owner` FROM ".WPPA_ALBUMS." WHERE `id` = %s", $edit_id));
			if ( ( $album_owner == '--- public ---' && ! current_user_can('administrator') ) || ! wppa_have_access($edit_id) ) {
				wp_die('You do not have the rights to edit this album');
			}
			
			if ( isset($_GET['applynewdesc']) ) {
				if ( ! wp_verify_nonce($_GET['wppa_nonce'], 'wppa_nonce') ) wp_die('You do not have the rights to do this');
				$iret = $wpdb->query($wpdb->prepare("UPDATE `".WPPA_PHOTOS."` SET `description` = %s WHERE `album` = %s", $wppa_opt['wppa_newphoto_description'], $edit_id));
				wppa_ok_message($iret.' descriptions updated.');
			}

			// Get the album information
			$albuminfo = $wpdb->get_row($wpdb->prepare('SELECT * FROM `'.WPPA_ALBUMS.'` WHERE `id` = %s', $edit_id), ARRAY_A); ?>	
			
			<div class="wrap">
				<h2><?php echo __('Edit Album Information', 'wppa').' <span style="color:blue">'.__('Auto Save', 'wppa').'</span>' ?></h2>
				<p class="description">
					<?php echo __('In this version of the album admin page, all modifications are instantly updated on the server.', 'wppa');
						  echo ' '.__('Edit fields are updated the moment you click anywhere outside the edit box.', 'wppa');
						  echo __('Selections are updated instantly, except for those that require a button push.', 'wppa');
						  echo __('The status fields keep you informed on the actions taken at the background.', 'wppa');
					?>
				</p>
				<p><?php _e('Album number:', 'wppa'); echo(' ' . $edit_id . '.'); ?></p>
					<input type="hidden" id="album-nonce-<?php echo $edit_id ?>" value="<?php echo wp_create_nonce('wppa_nonce_'.$edit_id);  ?>" />
					<table class="form-table albumtable">
						<tbody>
							<!-- Name -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:4px; padding-bottom:0;" scope="row">
									<label ><?php _e('Name:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:4px; padding-bottom:0;">
									<input type="text" style="width: 100%;" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'name', this)" value="<?php echo esc_attr(stripslashes($albuminfo['name'])) ?>" />
								</td>
								<td style="padding-top:4px; padding-bottom:0;">
									<span class="description"><?php _e('Type the name of the album. Do not leave this empty.', 'wppa'); ?></span>
								</td>
							</tr>
							<!-- Description -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<label ><?php _e('Description:', 'wppa'); ?></label>
								</th>
								<?php if ( get_option('wppa_use_wp_editor') == 'yes' ) { ?>
									<td style="padding-top:0; padding-bottom:0;" colspan="2" >
									
										<?php 
										$quicktags_settings = array( 'buttons' => 'strong,em,link,block,ins,ul,ol,li,code,close' );
										wp_editor(stripslashes($albuminfo['description']), 'wppaalbumdesc', array('wpautop' => false, 'media_buttons' => false, 'textarea_rows' => '6', 'tinymce' => false, 'quicktags' => $quicktags_settings ));
										?>
									
										<input type="button" class="button-secundary" value="<?php _e('Update Album description', 'wppa') ?>" onclick="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'description', document.getElementById('wppaalbumdesc') )" />
										<img id="wppa-album-spin" src="<?php echo wppa_get_imgdir().'wpspin.gif' ?>" style="visibility:hidden" />
										<br />
									</td>
								<?php }
								else { ?>
									<td style="padding-top:0; padding-bottom:0;">
										<textarea style="width: 100%; height: 80px;" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'description', this)" ><?php echo(stripslashes($albuminfo['description'])) ?></textarea>
									</td>
									<td style="padding-top:0; padding-bottom:0;">
										<span class="description"><?php _e('Enter / modify the description for this album.', 'wppa') ?></span>
									</td>
								<?php } ?>
							</tr>
							<!-- Owner -->
							<?php // if ( $wppa_opt['wppa_owner_only'] == 'yes' ) { 
							if ( current_user_can('administrator') ) {
							?>
								<tr style="vertical-align:top;" >
									<th style="padding-top:0; padding-bottom:0;" scope="row">
										<label ><?php _e('Owned by:', 'wppa'); ?></label>
									</th>
									<?php if ( $albuminfo['owner'] == '--- public ---' && !current_user_can('administrator') ) { ?>
										<td style="padding-top:0; padding-bottom:0;">
											<?php _e('--- public ---', 'wppa') ?>
										</td>
									<?php } else { ?>
										<td style="padding-top:0; padding-bottom:0;">
											<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'owner', this)" ><?php wppa_user_select($albuminfo['owner']); ?></select>
										</td>
										<td style="padding-top:0; padding-bottom:0;">
											<?php if (!current_user_can('administrator')) { ?>
												<span class="description" style="color:orange;" ><?php _e('WARNING If you change the owner, you will no longer be able to modify this album and upload or import photos to it!', 'wppa'); ?></span>
											<?php } ?>
										</td>
									<?php } ?>
								</tr>
							<?php } ?>
							<!-- Order # -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<label ><?php _e('Sort order #:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<input type="text" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'a_order', this)" value="<?php echo($albuminfo['a_order']) ?>" style="width: 50px;"/>
								</td>
								<td style="padding-top:0; padding-bottom:0;">
									<?php if ( $wppa_opt['wppa_list_albums_by'] != '1' && $albuminfo['a_order'] != '0' ) { ?>
										<span class="description" style="color:red">
										<?php _e('Album order # has only effect if you set the album sort order method to <b>Order #</b> in the Photo Albums -> Settings screen.', 'wppa') ?>
										</span>
									<?php } ?>
									<span class="description"><?php _e('If you want to sort the albums by order #, enter / modify the order number here.', 'wppa'); ?></span>
								</td>
							</tr>
							<!-- Parent -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<label ><?php _e('Parent album:', 'wppa'); ?> </label>
								</th>
								<td style="padding-top:0; padding-bottom:0; max-width:210px;">
									<?php if ( wppa_extended_access() ) { ?>
										<select id="wppa-parsel" style="max-width:100%;" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'a_parent', this)" ><?php echo wppa_album_select_a(array('checkaccess' => true, 'exclude' => $albuminfo['id'], 'selected' => $albuminfo['a_parent'], 'addnone' => true, 'addseparate' => true, 'disableancestors' => true, 'path' => wppa_switch('wppa_hier_albsel'))) ?></select>
									<?php } else { ?>
										<select id="wppa-parsel" style="max-width:100%;" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'a_parent', this)" ><?php echo wppa_album_select_a(array('checkaccess' => true, 'exclude' => $albuminfo['id'], 'selected' => $albuminfo['a_parent'], 'addselected' => true, 'disableancestors' => true, 'path' => wppa_switch('wppa_hier_albsel'))) ?></select>
									<?php } ?>
								</td>
								<td style="padding-top:0; padding-bottom:0;">
									<span class="description">
										<?php _e('If this is a sub album, select the album in which this album will appear.', 'wppa'); ?>
									</span>					
								</td>
							</tr>
							<!-- P-order-by -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<?php $order = $albuminfo['p_order_by']; ?>
									<label ><?php _e('Photo order:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'p_order_by', this)"><?php wppa_order_options($order, __('--- default ---', 'wppa'), __('Rating', 'wppa'), __('Timestamp', 'wppa')) ?></select>
								</td>
								<td style="padding-top:0; padding-bottom:0;">
									<span class="description">
										<?php _e('Specify the way the photos should be ordered in this album.', 'wppa'); ?><br />
										<?php if ( current_user_can('wppa_settings') ) _e('The default setting can be changed in the <b>Photo Albums -> Settings</b> page <b>Table IV-C1</b>.', 'wppa'); ?>
									</span>
								</td>
							</tr>
							<!-- Alternative thumbnail size? -->
							<?php if ( $wppa_opt['wppa_alt_is_restricted'] == 'no' || current_user_can('administrator') ) { ?>
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<label ><?php _e('Use alt thumbsize:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'alt_thumbsize', this)" >
										<option value="0" <?php if ( ! $albuminfo['alt_thumbsize'] ) echo 'selected="selected"' ?>><?php _e('no', 'wppa') ?></option>
										<option value="yes" <?php if ( $albuminfo['alt_thumbsize'] ) echo 'selected="selected"' ?>><?php _e('yes', 'wppa') ?></option>
									</select>
								</td>
								<td style="padding-top:6px; padding-bottom:0;">
									<span class="description">
										<?php _e('If set to <b>yes</b> The settings in <b>Table I-C1a,3a</b> and <b>4a</b> apply rather than <b>I-C1,3</b> and <b>4</b>.', 'wppa') ?>
									</span>
								</td>
							</tr>
							<?php } ?>
							<!-- Cover photo -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;">
									<label ><?php _e('Cover Photo:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<?php echo(wppa_main_photo($albuminfo['main_photo'])) ?>
								</td>
								<td style="padding-top:6px; padding-bottom:0;">
									<span class="description"><?php _e('Select the photo you want to appear on the cover of this album.', 'wppa'); ?></span>
								</td>
							</tr>
							<!-- Upload limit -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:4px;" scope="row">
									<label ><?php _e('Upload limit:', 'wppa') ?></label>
								</th>
								<td style="padding-top:0px; padding-bottom:4px;">
								<?php
									$lims = explode('/', $albuminfo['upload_limit']);
									if ( current_user_can('administrator') ) { ?>
										<input type="text" id="upload_limit_count" value="<?php echo($lims[0]) ?>" style="width: 50px" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'upload_limit_count', this)" />
										<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'upload_limit_time', this)" >
											<option value="0" <?php if ($lims[1] == '0') echo 'selected="selected"' ?>><?php _e('for ever', 'wppa') ?></option>
											<option value="3600" <?php if ($lims[1] == '3600') echo 'selected="selected"' ?>><?php _e('per hour', 'wppa') ?></option>
											<option value="86400" <?php if ($lims[1] == '86400') echo 'selected="selected"' ?>><?php _e('per day', 'wppa') ?></option>
											<option value="604800" <?php if ($lims[1] == '604800') echo 'selected="selected"' ?>><?php _e('per week', 'wppa') ?></option>
											<option value="2592000" <?php if ($lims[1] == '2592000') echo 'selected="selected"' ?>><?php _e('per month', 'wppa') ?></option>
											<option value="31536000" <?php if ($lims[1] == '31536000') echo 'selected="selected"' ?>><?php _e('per year', 'wppa') ?></option>
										</select>
										</td>
										<td style="padding-top:6px; padding-bottom:4px;">
										<span class="description"><?php _e('Set the upload limit (0 means unlimited) and the upload limit period.', 'wppa'); ?></span>
										<?php
									}
									else {
										
										if ( $lims[0] == '0' ) _e('Unlimited', 'wppa');
										else {
											echo $lims[0].' ';
											switch ($lims[1]) {
												case '3600': _e('per hour', 'wppa'); break;
												case '86400': _e('per day', 'wppa'); break;
												case '604800': _e('per week', 'wppa'); break;
												case '2592000': _e('per month', 'wppa'); break;
												case '31536000': _e('per year', 'wppa'); break;
											}
										}
									}
								?>
								</td>
							</tr>
							
							<!-- Default tags -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;" scope="row">
									<label ><?php _e('Default tags:', 'wppa') ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<input type="text" id="default_tags" value="<?php echo $albuminfo['default_tags'] ?>" style="width: 100%" onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'default_tags', this)" />
								</td>
								<td style="padding-top:6px; padding-bottom:4px;">
									<span class="description"><?php _e('Enter the tags that you want to be assigned to new photos in this album.', 'wppa') ?></span>
								</td>
							</tr>
							
							<!-- Apply default tags -->
							<?php $onc1 = 'if (confirm(\''.__('Are you sure you want to set the default tags to all photos in this album?', 'wppa').'\')) wppaAjaxUpdateAlbum('.$edit_id.', \'set_deftags\', 0 ); '; ?>
							<?php $onc2 = 'if (confirm(\''.__('Are you sure you want to add the default tags to all photos in this album?', 'wppa').'\')) wppaAjaxUpdateAlbum('.$edit_id.', \'add_deftags\', 0 ); '; ?>
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;" scope="row">
									<input type="button" class="button-secundary" style="color:blue; width:100%;" onclick="<?php echo $onc1 ?>" value="Apply default tags" />
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<input type="button" class="button-secundary" style="color:blue; width:100%;" onclick="<?php echo $onc2 ?>" value="Add default tags" />
								</td>
							</tr>

							<!-- Link type -->
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;" scope="row">
									<label ><?php _e('Link type:', 'wppa') ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<?php $linktype = $albuminfo['cover_linktype']; ?>
									<?php /* if ( !$linktype ) $linktype = 'content'; /* Default */ ?>	
									<?php /* if ( $albuminfo['cover_linkpage'] == '-1' ) $linktype = 'none'; /* for backward compatibility */ ?>
									<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'cover_linktype', this)" >
										<option value="content" <?php if ( $linktype == 'content' ) echo ($sel) ?>><?php _e('the sub-albums and thumbnails', 'wppa') ?></option>
										<option value="slide" <?php if ( $linktype == 'slide' ) echo ($sel) ?>><?php _e('the album photos as slideshow', 'wppa') ?></option>
										<option value="none" <?php if ( $linktype == 'none' ) echo($sel) ?>><?php _e('no link at all', 'wppa') ?></option>
									</select>
								</td>
							</tr>
							
							<!-- Link page -->
							<?php if ( $wppa_opt['wppa_link_is_restricted'] == 'no' || current_user_can('administrator') ) { ?>
							<tr style="vertical-align:top;" >
								<th style="padding-top:0; padding-bottom:0;" scope="row">
									<label ><?php _e('Link to:', 'wppa'); ?></label>
								</th>
								<td style="padding-top:0; padding-bottom:0;">
									<?php $query = 'SELECT `ID`, `post_title` FROM `'.$wpdb->posts.'` WHERE `post_type` = \'page\' AND `post_status` = \'publish\' ORDER BY `post_title` ASC';
									$pages = $wpdb->get_results($query, ARRAY_A);
									if (empty($pages)) {
										_e('There are no pages (yet) to link to.', 'wppa');
									} else {
										$linkpage = $albuminfo['cover_linkpage'];
										if (!is_numeric($linkpage)) $linkpage = '0'; ?>
										<select onchange="wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'cover_linkpage', this)" >
											<option value="0" <?php if ($linkpage == '0') echo($sel); ?>><?php _e('--- the same page or post ---', 'wppa'); ?></option>
											<?php foreach ($pages as $page) { ?>
												<option value="<?php echo($page['ID']); ?>" <?php if ($linkpage == $page['ID']) echo($sel); ?>><?php _e($page['post_title']); ?></option>
											<?php } ?>
										</select>
								</td>
								<td style="padding-top:0; padding-bottom:0;">
										<span class="description">
											<?php _e('If you want, you can link the title to a WP page in stead of the album\'s content. If so, select the page the title links to.', 'wppa'); ?>
										</span>
									<?php }	?>
								</td>
							</tr>
							<?php } ?>

							<!-- Reset Ratings -->
							<?php if ( $wppa_opt['wppa_rating_on'] == 'yes' ) { ?>
								<tr style="vertical-align:top;" >
									<th style="padding-top:0; padding-bottom:0;" scope="row">
										<input type="button" class="button-secundary" style="color:blue; width:100%" onclick="if (confirm('<?php _e('Are you sure you want to clear the ratings in this album?', 'wppa') ?>')) wppaAjaxUpdateAlbum(<?php echo $edit_id ?>, 'clear_ratings', 0 ) " value="<?php _e('Reset ratings', 'wppa') ?>" /> 
									</th>
								</tr>
							<?php } ?>
							
							<!-- Goto Upload -->
							<?php if ( current_user_can('wppa_upload') ) { 
								$a = wppa_allow_uploads($albuminfo['id']);
								if ( $a ) {
									$full = 'none';
									$notfull = '';
								}
								else {
									$full = '';
									$notfull = 'none';
								} 
								$onc = 'document.location = \''.wppa_dbg_url(get_admin_url()).'/admin.php?page=wppa_upload_photos&wppa-set-album='.$albuminfo['id'].'\'';
								$oncfull = 'alert(\''.__('Change the upload limit or remove photos to enable new uploads.', 'wppa').'\')';
								?>
								<tr style="vertical-align:top;" >
									<th style="padding-top:0; padding-bottom:0;" scope="row">
										<input id="notfull" type="button" class="button-secundary" style="color:blue; width:100%; display:<?php echo $notfull ?>" onclick="<?php echo $onc ?>" value="<?php _e('Upload to this album', 'wppa'); if ( $a > '0') echo ' '.sprintf(__('(max %d)', 'wppa'), $a) ?>" /> 
										<input id="full" type="button" class="button-secundary" style="color:red; width:100%; display:<?php echo $full ?>" onclick="<?php echo $oncfull ?>" value="<?php _e('Album is full', 'wppa') ?>" /> 
									</th>
								</tr>
							<?php } ?>
							
							<!-- Apply New photo desc -->
							<?php if ( wppa_switch('wppa_apply_newphoto_desc') ) { 
							$onc = 'if ( confirm(\'Are you sure you want to set the description of all photos to \n\n'.esc_js($wppa_opt['wppa_newphoto_description']).'\')) document.location=\''.wppa_ea_url($albuminfo['id'], 'edit').'&applynewdesc\'';
							?>
								<tr style="vertical-align:top;" >
									<th style="padding-top:0; padding-bottom:0;" scope="row">
										<input type="button" class="button-secundary" style="color:blue; width:100%;" onclick="<?php echo $onc ?>" value="<?php _e('Apply new photo desc', 'wppa') ?>" />
									</th>
								</tr>
							<?php } ?>
							<!-- Status -->
							<tr style="vertical-align:bottom;" >
								<th style="padding-top:0; padding-bottom:2px;" scope="row" >
									<label ><?php _e('Status', 'wppa') ?></label>
								</th>
								<td id="albumstatus-<?php echo $edit_id ?>" style="padding-left:10px;padding-top:0; padding-bottom:2px;">
									<?php echo sprintf(__('Album %s is not modified yet', 'wppa'), $edit_id) ?>
								</td>
							</tr>
						</tbody>
					</table>
							
				<h2><?php _e('Manage Photos', 'wppa'); ?></h2>
				<?php wppa_album_photos($edit_id) ?>
			</div>
<?php 	} 

		// Comment moderate
		else if ($_GET['tab'] == 'cmod') {
			$photo = $_GET['photo'];
			$alb = wppa_get_album_id_by_photo_id($photo);
			if ( current_user_can('wppa_comments') && wppa_have_access($alb) ) { ?>
				<div class="wrap">
					<h2><?php _e('Moderate comment', 'wppa') ?></h2>
				<?php //	<input type="hidden" id="album-nonce-<?php echo $edit_id ?><?//" value="<?php echo wp_create_nonce('wppa_nonce_'.$edit_id);  ?><?//" /> ?>
					<?php wppa_album_photos('', $photo) ?>
				</div>				
<?php		}
			else {
				wp_die('You do not have the rights to do this');
			}
		}
		
		// Photo moderate
		elseif ( $_GET['tab'] == 'pmod' || $_GET['tab'] == 'pedit' ) {
			$photo = $_GET['photo'];
			$alb = wppa_get_album_id_by_photo_id($photo);
			if ( current_user_can('wppa_admin') && wppa_have_access($alb) ) { ?>
				<div class="wrap">
					<h2><?php 	if ( $_GET['tab'] == 'pmod' ) _e('Moderate photo', 'wppa');
								else _e('Edit photo', 'wppa'); ?>
					</h2>
					<?php wppa_album_photos('', $photo) ?>
				</div>				
<?php		}
			else {
				wp_die('You do not have the rights to do this');
			}
		}

		// album delete confirm page
		else if ($_GET['tab'] == 'del') { 

			$album_owner = $wpdb->get_var($wpdb->prepare("SELECT `owner` FROM ".WPPA_ALBUMS." WHERE `id` = %s", $_GET['edit_id']));
			if ( ( $album_owner == '--- public ---' && ! current_user_can('administrator') ) || ! wppa_have_access($_GET['edit_id']) ) {
				wp_die('You do not have the rights to delete this album');
			}
?>			
			<div class="wrap">
				<?php $iconurl = WPPA_URL.'/images/albumdel32.png'; ?>
				<div id="icon-albumdel" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
					<br />
				</div>

				<h2><?php _e('Delete Album', 'wppa'); ?></h2>
				
				<p><?php _e('Album:', 'wppa'); ?> <b><?php echo wppa_get_album_name($_GET['edit_id']); ?>.</b></p>
				<p><?php _e('Are you sure you want to delete this album?', 'wppa'); ?><br />
					<?php _e('Press Delete to continue, and Cancel to go back.', 'wppa'); ?>
				</p>
				<form name="wppa-del-form" action="<?php echo(wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu')) ?>" method="post">
					<?php wp_nonce_field('$wppa_nonce', WPPA_NONCE) ?>
					<p>
						<?php _e('What would you like to do with photos currently in the album?', 'wppa'); ?><br />
						<input type="radio" name="wppa-del-photos" value="delete" checked="checked" /> <?php _e('Delete', 'wppa'); ?><br />
						<input type="radio" name="wppa-del-photos" value="move" /> <?php _e('Move to:', 'wppa'); ?> 
						<select name="wppa-move-album">
							<?php echo wppa_album_select_a(array('checkaccess' => true, 'path' => wppa_switch('wppa_hier_albsel'), 'selected' => '0', 'exclude' => $_GET['edit_id'], 'addpleaseselect' => true)) ?>
						</select>
					</p>
				
					<input type="hidden" name="wppa-del-id" value="<?php echo($_GET['edit_id']) ?>" />
					<input type="button" class="button-primary" value="<?php _e('Cancel', 'wppa'); ?>" onclick="parent.history.back()" />
					<input type="submit" class="button-primary" style="color: red" name="wppa-del-confirm" value="<?php _e('Delete', 'wppa'); ?>" />
				</form>
			</div>
<?php	
		}
	} 
	else {	//  'tab' not set. default, album manage page.
		
		// if add form has been submitted
		if (isset($_POST['wppa-na-submit'])) {
			check_admin_referer( '$wppa_nonce', WPPA_NONCE );

			wppa_add_album();
		}
		
		// if album deleted
		if (isset($_POST['wppa-del-confirm'])) {
			check_admin_referer( '$wppa_nonce', WPPA_NONCE );
			
			$album_owner = $wpdb->get_var($wpdb->prepare("SELECT `owner` FROM ".WPPA_ALBUMS." WHERE `id` = %s", $_POST['wppa-del-id']));
			if ( ( $album_owner == '--- public ---' && ! current_user_can('administrator') ) || ! wppa_have_access($_POST['wppa-del-id']) ) {
				wp_die('You do not have the rights to delete this album');
			}

			if ($_POST['wppa-del-photos'] == 'move') {
				$move = $_POST['wppa-move-album'];
				if ( wppa_have_access($move) ) {
					wppa_del_album($_POST['wppa-del-id'], $move);
				}
				else {
					wppa_error_message(__('Unable to move photos. Album not deleted.', 'wppa'));
				}
			} else {
				wppa_del_album($_POST['wppa-del-id'], '');
			}
		}
		
		if ( wppa_extended_access() ) {
			if ( isset($_GET['switchto']) ) update_option('wppa_album_table_'.wppa_get_user(), $_GET['switchto']);
			$style = get_option('wppa_album_table_'.wppa_get_user(), 'flat');
		}
		else $style = 'flat';
		
		// The Manage Album page 
?>	
		<div class="wrap">
			<?php $iconurl = WPPA_URL.'/images/album32.png'; ?>
			<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
				<br />
			</div>

			<h2><?php _e('Manage Albums', 'wppa'); ?></h2>
			<br />
			<?php 
			// The Create new album button
			if ( wppa_can_create_top_album() ) {
				$url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id=new');
				$vfy = __('Are you sure you want to create a new album?', 'wppa');
				echo '<input type="button" class="button-primary" onclick="if (confirm(\''.$vfy.'\')) document.location=\''.$url.'\';" value="'.__('Create New Empty Album', 'wppa').'" />';
			}
			// The switch to button(s)
			if ( wppa_extended_access() ) {
				if ( $style == 'flat' ) { ?>
					<input type="button" class="button-secundary" onclick="document.location='<?php echo wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;switchto=collapsable') ?>'" value="<?php _e('Switch to Collapsable table', 'wppa'); ?>" />		
				<?php } 
				if ( $style == 'collapsable' ) { ?>
					<input type="button" class="button-secundary" onclick="document.location='<?php echo wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;switchto=flat') ?>'" value="<?php _e('Switch to Flat table', 'wppa'); ?>" />		
				<?php } 
			} ?>
			
			<br />
			<?php // The table of existing albums 
				if ( $style == 'flat' ) wppa_admin_albums_flat();
				else wppa_admin_albums_collapsable(); 
			?>
			<br />
		</div>
<?php	
	}
}

// The albums table flat
function wppa_admin_albums_flat() {
	global $wpdb;
	
	// Read the albums
	$albums = $wpdb->get_results( "SELECT * FROM `" . WPPA_ALBUMS . "` ORDER BY `id`", ARRAY_A );

	// Find the ordering method
	$reverse = false;
	if ( isset($_GET['order_by']) ) $order = $_GET['order_by']; else $order = '';
	if ( ! $order ) {
		$order = get_option('wppa_album_order_'.wppa_get_user(), 'id');
		$reverse = (get_option('wppa_album_order_'.wppa_get_user().'_reverse') == 'yes');
	}
	else {
		$old_order = get_option('wppa_album_order_'.wppa_get_user(), 'id');
		$reverse = (get_option('wppa_album_order_'.wppa_get_user().'_reverse') == 'yes');
		if ( $old_order == $order ) {
			$reverse = ! $reverse;
		}
		else $reverse = false;
		update_option('wppa_album_order_'.wppa_get_user(), $order);
		if ( $reverse ) update_option('wppa_album_order_'.wppa_get_user().'_reverse', 'yes');
		else update_option('wppa_album_order_'.wppa_get_user().'_reverse', 'no');
	}
	
	if ( ! empty($albums) ) {

		// Setup the sequence array
		$seq = false;
		$num = false;
		foreach( $albums as $album ) {
			switch ( $order ) {
				case 'name':
					$seq[] = strtolower(wppa_qtrans(stripslashes($album['name'])));
					break;
				case 'description':
					$seq[] = strtolower(wppa_qtrans(stripslashes($album['description'])));
					break;
				case 'owner':
					$seq[] = strtolower($album['owner']);
					break;
				case 'a_order':
					$seq[] = $album['a_order'];
					$num = true;
					break;
				case 'a_parent':
					$seq[] = strtolower(wppa_get_album_name($album['a_parent']), 'extended');
					break;
				default:
					$seq[] = $album['id'];
					$num = true;
					break;
			}
		}
		
		// Sort the seq array
		if ( $num ) asort($seq, SORT_NUMERIC);
		else asort($seq, SORT_REGULAR);

		// Reverse ?
		if ( $reverse ) {
			$t = $seq;
			$c = count($t);
			$tmp = array_keys($t);
			$seq = false;
			for ( $i = $c-1; $i >=0; $i-- ) {
				$seq[$tmp[$i]] = '0';
			}
		}

		$downimg = '<img src="'.wppa_get_imgdir().'down.png" alt="down" style=" height:12px; position:relative; top:2px; " />';
		$upimg   = '<img src="'.wppa_get_imgdir().'up.png" alt="up" style=" height:12px; position:relative; top:2px; " />';
?>	
<!--	<div class="table_wrapper">	-->
		<table class="widefat" style="margin-top:12px;" >
			<thead>
			<tr>
				<?php $url = get_admin_url().'admin.php?page=wppa_admin_menu&amp;order_by='; ?>
				<th scope="col" style="min-width: 50px;" >
					<a href="<?php echo wppa_dbg_url($url.'id') ?>">
						<?php _e('ID', 'wppa');
							if ($order == 'id') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>					
					</a>
				</th>
				<th scope="col" style="min-width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'name') ?>">
						<?php _e('Name', 'wppa'); 
							if ($order == 'name') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'description') ?>">
						<?php _e('Description', 'wppa'); 
							if ($order == 'description') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php if (current_user_can('administrator')) { ?>
				<th scope="col" style="min-width: 100px;">
					<a href="<?php echo wppa_dbg_url($url.'owner') ?>">
						<?php _e('Owner', 'wppa'); 
							if ($order == 'owner') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php } ?>
                <th scope="col" style="min-width: 100px;" >
					<a href="<?php echo wppa_dbg_url($url.'a_order') ?>">
						<?php _e('Order', 'wppa'); 
							if ($order == 'a_order') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
                <th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'a_parent') ?>">
						<?php _e('Parent', 'wppa'); 
							if ($order == 'a_parent') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col" title="<?php _e('Albums/Photos/Photos that need Moderation', 'wppa') ?>" >
					<?php _e('A/P/PM', 'wppa'); ?>
				</th>
				<th scope="col"><?php _e('Edit', 'wppa'); ?></th>
				<th scope="col"><?php _e('Delete', 'wppa'); ?></th>	
				<?php if ( wppa_can_create_album() ) echo '<th scope="col">'.__('Create', 'wppa').'</th>'; ?>
			</tr>
			</thead>
			<tbody>
			<?php $alt = ' class="alternate" '; ?>
		
			<?php
//				foreach ($albums as $album) if(wppa_have_access($album)) { 
				$idx = '0';
				foreach (array_keys($seq) as $s) {
					$album = $albums[$s];
					if (wppa_have_access($album)) {
						$pendcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s AND status=%s", $album['id'], 'pending')); 
						?>
						<tr <?php echo($alt); if ($pendcount) echo 'style="background-color:#ffdddd"' ?>>
							<td><?php echo($album['id']) ?></td>
							<td><?php echo(esc_attr(wppa_qtrans(stripslashes($album['name'])))) ?></td>
							<td><small><?php echo(esc_attr(wppa_qtrans(stripslashes($album['description'])))) ?></small></td>
							<?php if (current_user_can('administrator')) { ?>
								<td><?php echo($album['owner']); ?></td>
							<?php } ?>
							<td><?php echo($album['a_order']) ?></td>
							<td><?php echo wppa_get_album_name($album['a_parent'], 'extended') ?></td>
							<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id='.$album['id']); ?>
							<?php $na = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_ALBUMS."` WHERE a_parent=%s", $album['id'])); ?>
							<?php $np = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s", $album['id'])); ?>
							<?php $nm = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s AND status=%s", $album['id'], 'pending')); ?>
							<td><?php echo $na.'/'.$np; if ($nm) echo '/<span style="font-weight:bold; color:red">'.$nm.'</span>'; ?></td>
							<?php if ( $album['owner'] != '--- public ---' || current_user_can('administrator') ) { ?>
								<?php $url = wppa_ea_url($album['id']) ?>
								<td><a href="<?php echo($url) ?>" class="wppaedit"><?php _e('Edit', 'wppa'); ?></a></td>
								<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=del&amp;id='.$album['id']); ?>
								
								<?php $url = wppa_ea_url($album['id'], 'del') ?>
								<td><a href="<?php echo($url) ?>" class="wppadelete"><?php _e('Delete', 'wppa'); ?></a></td>
								<?php if ( wppa_can_create_album() ) {
									$url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id=new&amp;parent_id='.$album['id']);
									$onc = 'if (confirm(\''.__('Are you sure you want to create a subalbum?', 'wppa').'\')) document.location=\''.$url.'\';';
									echo '<td><a onclick="'.$onc.'" class="wppacreate">'.__('Create', 'wppa').'</a></td>'; 
								}
							}
							else { ?>
							<td></td><td></td><?php if ( wppa_can_create_album() ) echo '<td></td' ?>
							<?php } ?>
						</tr>		
						<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
					}
					$idx++;
				}
			
?>	
			</tbody>
			<tfoot>
			<tr>
				<?php $url = get_admin_url().'admin.php?page=wppa_admin_menu&amp;order_by='; ?>
				<th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'id') ?>">
						<?php _e('ID', 'wppa');
							if ($order == 'id') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>					
					</a>
				</th>
				<th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'name') ?>">
						<?php _e('Name', 'wppa'); 
							if ($order == 'name') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'description') ?>">
						<?php _e('Description', 'wppa'); 
							if ($order == 'description') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php if (current_user_can('administrator')) { ?>
				<th scope="col" style="width: 100px;">
					<a href="<?php echo wppa_dbg_url($url.'owner') ?>">
						<?php _e('Owner', 'wppa'); 
							if ($order == 'owner') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php } ?>
                <th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'a_order') ?>">
						<?php _e('Order', 'wppa'); 
							if ($order == 'a_order') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
                <th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'a_parent') ?>">
						<?php _e('Parent', 'wppa'); 
							if ($order == 'a_parent') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col" title="<?php _e('Albums/Photos/Photos that need Moderation', 'wppa') ?>" >
					<?php _e('A/P/PM', 'wppa'); ?>
				</th>
				<th scope="col"><?php _e('Edit', 'wppa'); ?></th>
				<th scope="col"><?php _e('Delete', 'wppa'); ?></th>	
				<?php if ( wppa_can_create_album() ) echo '<th scope="col">'.__('Create', 'wppa').'</th>'; ?>
			</tr>
			</tfoot>
		
		</table>
<!--	</div> -->
<?php
	$albcount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_ALBUMS."`" );
	$photocount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_PHOTOS."`" );
	$pendingcount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE `status` = 'pending'" );
	
	echo sprintf(__('There are <strong>%d</strong> albums and <strong>%d</strong> photos in the system.', 'wppa'), $albcount, $photocount);
	if ( $pendingcount ) echo ' '.sprintf(__('<strong>%d</strong> photos are pending moderation.', 'wppa'), $pendingcount);
	
	$lastalbum = $wpdb->get_row( "SELECT `id`, `name` FROM `".WPPA_ALBUMS."` ORDER BY `timestamp` DESC LIMIT 1", ARRAY_A );
	if ( $lastalbum ) echo '<br />'.sprintf(__('The most recently added album is <strong>%s</strong> (%d).', 'wppa'), __(stripslashes($lastalbum['name'])), $lastalbum['id']);
	$lastphoto = $wpdb->get_row( "SELECT `id`, `name` FROM `".WPPA_PHOTOS."` ORDER BY `timestamp` DESC LIMIT 1", ARRAY_A );
	if ( $lastphoto ) echo '<br />'.sprintf(__('The most recently added photo is <strong>%s</strong> (%d).', 'wppa'), __(stripslashes($lastphoto['name'])), $lastphoto['id']);
?>
<?php	
	} else { 
?>
	<p><?php _e('No albums yet.', 'wppa'); ?></p>
<?php
	}
}

// The albums table collapsable
function wppa_admin_albums_collapsable() {
	global $wpdb;
	
	// Read the albums
	$albums = $wpdb->get_results( "SELECT * FROM `" . WPPA_ALBUMS . "` ORDER BY `id`", ARRAY_A);

	// Find the ordering method
	$reverse = false;
	if ( isset($_GET['order_by']) ) $order = $_GET['order_by']; else $order = '';
	if ( ! $order ) {
		$order = get_option('wppa_album_order_'.wppa_get_user(), 'id');
		$reverse = (get_option('wppa_album_order_'.wppa_get_user().'_reverse') == 'yes');
	}
	else {
		$old_order = get_option('wppa_album_order_'.wppa_get_user(), 'id');
		$reverse = (get_option('wppa_album_order_'.wppa_get_user().'_reverse') == 'yes');
		if ( $old_order == $order ) {
			$reverse = ! $reverse;
		}
		else $reverse = false;
		update_option('wppa_album_order_'.wppa_get_user(), $order);
		if ( $reverse ) update_option('wppa_album_order_'.wppa_get_user().'_reverse', 'yes');
		else update_option('wppa_album_order_'.wppa_get_user().'_reverse', 'no');
	}
	
	if ( ! empty($albums) ) {

		// Setup the sequence array
		$seq = false;
		$num = false;
		foreach( $albums as $album ) {
			switch ( $order ) {
				case 'name':
					$seq[] = strtolower(wppa_qtrans(stripslashes($album['name'])));
					break;
				case 'description':
					$seq[] = strtolower(wppa_qtrans(stripslashes($album['description'])));
					break;
				case 'owner':
					$seq[] = strtolower($album['owner']);
					break;
				case 'a_order':
					$seq[] = $album['a_order'];
					$num = true;
					break;
				case 'a_parent':
					$seq[] = strtolower(wppa_get_album_name($album['a_parent']), 'extended');
					break;
				default:
					$seq[] = $album['id'];
					$num = true;
					break;
			}
		}
		
		// Sort the seq array
		if ( $num ) asort($seq, SORT_NUMERIC);
		else asort($seq, SORT_REGULAR);

		// Reverse ?
		if ( $reverse ) {
			$t = $seq;
			$c = count($t);
			$tmp = array_keys($t);
			$seq = false;
			for ( $i = $c-1; $i >=0; $i-- ) {
				$seq[$tmp[$i]] = '0';
			}
		}

		$downimg = '<img src="'.wppa_get_imgdir().'down.png" alt="down" style=" height:12px; position:relative; top:2px; " />';
		$upimg   = '<img src="'.wppa_get_imgdir().'up.png" alt="up" style=" height:12px; position:relative; top:2px; " />';
?>	
<!--	<div class="table_wrapper">	-->
		<table class="widefat" style="margin-top:12px;" >
			<thead>
			<tr>
				<th style="min-width:20px;" >
					<img src="<?php echo wppa_get_imgdir().'backarrow.gif' ?>" style="height:16px;" title="<?php _e('Collapse subalbums', 'wppa') ?>" />
					<img src="<?php echo wppa_get_imgdir().'arrow.gif' ?>" style="height:16px;" title="<?php _e('Expand subalbums', 'wppa') ?>" />
				</th>
				<?php $url = get_admin_url().'admin.php?page=wppa_admin_menu&amp;order_by='; ?>
				<th scope="col" colspan="6" style="min-width: 50px;" >
					<a href="<?php echo wppa_dbg_url($url.'id') ?>">
						<?php _e('ID', 'wppa');
							if ($order == 'id') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>					
					</a>
				</th>
				
				<th scope="col" style="min-width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'name') ?>">
						<?php _e('Name', 'wppa'); 
							if ($order == 'name') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'description') ?>">
						<?php _e('Description', 'wppa'); 
							if ($order == 'description') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php if (current_user_can('administrator')) { ?>
				<th scope="col" style="min-width: 100px;">
					<a href="<?php echo wppa_dbg_url($url.'owner') ?>">
						<?php _e('Owner', 'wppa'); 
							if ($order == 'owner') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php } ?>
                <th scope="col" style="min-width: 100px;" >
					<a href="<?php echo wppa_dbg_url($url.'a_order') ?>">
						<?php _e('Order', 'wppa'); 
							if ($order == 'a_order') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
                <th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'a_parent') ?>">
						<?php _e('Parent', 'wppa'); 
							if ($order == 'a_parent') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col" title="<?php _e('Albums/Photos/Photos that need Moderation', 'wppa') ?>" >
					<?php _e('A/P/PM', 'wppa'); ?>
				</th>
				<th scope="col"><?php _e('Edit', 'wppa'); ?></th>
				<th scope="col"><?php _e('Delete', 'wppa'); ?></th>	
				<?php if ( wppa_can_create_album() ) echo '<th scope="col">'.__('Create', 'wppa').'</th>'; ?>
			</tr>
			</thead>
			<tbody>
		
			<?php wppa_do_albumlist('0', '0', $albums, $seq); ?>
			<?php if ( $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_ALBUMS."` WHERE `a_parent` = '-1'" ) > 0 ) { ?>
				<tr>
					<td colspan="12" ><em><?php _e('The following albums are ---separate--- and do not show up in the generic album display', 'wppa'); ?></em></td>
				</tr>
				<?php wppa_do_albumlist('-1', '0', $albums, $seq); ?>
			<?php } ?>
			</tbody>
			<tfoot>
			<tr>
				<th>
					<img src="<?php echo wppa_get_imgdir().'backarrow.gif' ?>" style="height:16px;" />
					<img src="<?php echo wppa_get_imgdir().'arrow.gif' ?>" style="height:16px;" />
				</th>
				<?php $url = get_admin_url().'admin.php?page=wppa_admin_menu&amp;order_by='; ?>
				<th scope="col" colspan="6" >
					<a href="<?php echo wppa_dbg_url($url.'id') ?>">
						<?php _e('ID', 'wppa');
							if ($order == 'id') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>					
					</a>
				</th>
				
				<th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'name') ?>">
						<?php _e('Name', 'wppa'); 
							if ($order == 'name') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'description') ?>">
						<?php _e('Description', 'wppa'); 
							if ($order == 'description') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php if (current_user_can('administrator')) { ?>
				<th scope="col" style="width: 100px;">
					<a href="<?php echo wppa_dbg_url($url.'owner') ?>">
						<?php _e('Owner', 'wppa'); 
							if ($order == 'owner') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<?php } ?>
                <th scope="col">
					<a href="<?php echo wppa_dbg_url($url.'a_order') ?>">
						<?php _e('Order', 'wppa'); 
							if ($order == 'a_order') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
                <th scope="col" style="width: 120px;">
					<a href="<?php echo wppa_dbg_url($url.'a_parent') ?>">
						<?php _e('Parent', 'wppa'); 
							if ($order == 'a_parent') {
								if ( $reverse ) echo $upimg;
								else echo $downimg;
							}
						?>
					</a>
				</th>
				<th scope="col" title="<?php _e('Albums/Photos/Photos that need Moderation', 'wppa') ?>" >
					<?php _e('A/P/PM', 'wppa'); ?>
				</th>
				<th scope="col"><?php _e('Edit', 'wppa'); ?></th>
				<th scope="col"><?php _e('Delete', 'wppa'); ?></th>	
				<?php if ( wppa_can_create_album() ) echo '<th scope="col">'.__('Create', 'wppa').'</th>'; ?>
			</tr>
			</tfoot>
		
		</table>
		
		<script type="text/javascript" >
			function checkArrows() {
				elms = jQuery('.alb-arrow-off');
				for(i=0;i<elms.length;i++) {
					elm = elms[i];
					if ( elm.parentNode.parentNode.style.display == 'none' ) elm.style.display = 'none';
				}
				elms = jQuery('.alb-arrow-on');
				for(i=0;i<elms.length;i++) {
					elm = elms[i];
					if ( elm.parentNode.parentNode.style.display == 'none' ) elm.style.display = '';
				}
			}
		</script>
<!--	</div> -->
<?php
	$albcount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_ALBUMS."`" );
	$photocount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_PHOTOS."`" );
	$pendingcount = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE `status` = 'pending'" );

	echo sprintf(__('There are <strong>%d</strong> albums and <strong>%d</strong> photos in the system.', 'wppa'), $albcount, $photocount);
	if ( $pendingcount ) echo ' '.sprintf(__('<strong>%d</strong> photos are pending moderation.', 'wppa'), $pendingcount);
	
	$lastalbum = $wpdb->get_row( "SELECT `id`, `name` FROM `".WPPA_ALBUMS."` ORDER BY `timestamp` DESC LIMIT 1", ARRAY_A );
	if ( $lastalbum ) echo '<br />'.sprintf(__('The most recently added album is <strong>%s</strong> (%d).', 'wppa'), __(stripslashes($lastalbum['name'])), $lastalbum['id']);
	$lastphoto = $wpdb->get_row( "SELECT `id`, `name` FROM `".WPPA_PHOTOS."` ORDER BY `timestamp` DESC LIMIT 1", ARRAY_A );
	if ( $lastphoto ) echo '<br />'.sprintf(__('The most recently added photo is <strong>%s</strong> (%d).', 'wppa'), __(stripslashes($lastphoto['name'])), $lastphoto['id']);
?>
<?php	
	} else { 
?>
	<p><?php _e('No albums yet.', 'wppa'); ?></p>
<?php
	}
}

function wppa_do_albumlist($parent, $nestinglevel, $albums, $seq) {
global $wpdb;

	$alt = true;

		foreach (array_keys($seq) as $s) {			// Obey the global sequence
			$album = $albums[$s];
			if ( $album['a_parent'] == $parent ) {
				if (wppa_have_access($album)) {
					$pendcount = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s AND status=%s", $album['id'], 'pending')); 
					$haschildren = wppa_have_accessable_children($album); 
					{
						$class = '';
						if ( $parent != '0' && $parent != '-1' ) {
							$class .= 'wppa-alb-on-'.$parent.' ';
							$par = $parent;
							while ( $par != '0' && $par != '-1' ) {
								$class .= 'wppa-alb-off-'.$par.' ';
								$par = wppa_get_parentalbumid($par);
							}
						}
						if ( $alt ) $class .= ' alternate';
						$style = '';
						if ( $pendcount ) $style .= 'background-color:#ffdddd; ';
					//	if ( $haschildren ) $style .= 'font-weight:bold; '; 
						if ( $parent != '0' && $parent != '-1' ) $style .= 'display:none; ';
						$onclickon = 'jQuery(\'.wppa-alb-on-'.$album['id'].'\').css(\'display\',\'\'); jQuery(\'#alb-arrow-on-'.$album['id'].'\').css(\'display\',\'none\'); jQuery(\'#alb-arrow-off-'.$album['id'].'\').css(\'display\',\'\');';
						$onclickoff = 'jQuery(\'.wppa-alb-off-'.$album['id'].'\').css(\'display\',\'none\'); jQuery(\'#alb-arrow-on-'.$album['id'].'\').css(\'display\',\'\'); jQuery(\'#alb-arrow-off-'.$album['id'].'\').css(\'display\',\'none\'); checkArrows();';
						$indent = $nestinglevel;
						if ( $indent > '5' ) $indent = 5;
						?>

						<tr class="<?php echo $class ?>" style="<?php echo $style ?>" >
							<?php
							$i = 0;
							while ( $i < $indent ) {
								echo '<td style="padding:2px;" ></td>';
								$i++;
							}
							?>
							<td style="padding:2px; text-align:center;" ><?php if ( $haschildren ) { ?>
								<img id="alb-arrow-off-<?php echo $album['id'] ?>" class="alb-arrow-off" style="height:16px; display:none;" src="<?php echo wppa_get_imgdir().'backarrow.gif' ?>" onclick="<?php echo $onclickoff ?>" title="<?php _e('Collapse subalbums', 'wppa') ?>" />
								<img id="alb-arrow-on-<?php echo $album['id'] ?>" class="alb-arrow-on" style="height:16px;" src="<?php echo wppa_get_imgdir().'arrow.gif' ?>" onclick="<?php echo $onclickon ?>" title="<?php _e('Expand subalbums', 'wppa') ?>" />
							<?php } ?></td>
							<td style="padding:2px;" ><?php echo($album['id']); ?></td>
							<?php 
							$i = $indent;
							while ( $i < 5 ) {
								echo '<td style="padding:2px;" ></td>';
								$i++;
							}
							?>
							<td><?php echo(esc_attr(wppa_qtrans(stripslashes($album['name'])))) ?></td>
							<td><small><?php echo(esc_attr(wppa_qtrans(stripslashes($album['description'])))) ?></small></td>
							<?php if (current_user_can('administrator')) { ?>
								<td><?php echo($album['owner']); ?></td>
							<?php } ?>
							<td><?php echo($album['a_order']) ?></td>
							<td><?php echo wppa_get_album_name($album['a_parent'], 'extended') ?></td>
							<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id='.$album['id']); ?>
							<?php $na = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_ALBUMS."` WHERE a_parent=%s", $album['id'])); ?>
							<?php $np = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s", $album['id'])); ?>
							<?php $nm = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_PHOTOS."` WHERE album=%s AND status=%s", $album['id'], 'pending')); ?>
							<td><?php echo $na.'/'.$np; if ($nm) echo '/<span style="font-weight:bold; color:red">'.$nm.'</span>'; ?></td>
							<?php if ( $album['owner'] != '--- public ---' || current_user_can('administrator') ) { ?>
								<?php $url = wppa_ea_url($album['id']) ?>
								<td><a href="<?php echo($url) ?>" class="wppaedit"><?php _e('Edit', 'wppa'); ?></a></td>
								<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=del&amp;id='.$album['id']); ?>
								
								<?php $url = wppa_ea_url($album['id'], 'del') ?>
								<td><a href="<?php echo($url) ?>" class="wppadelete"><?php _e('Delete', 'wppa'); ?></a></td>
								<?php if ( wppa_can_create_album() ) {
									$url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab=edit&amp;edit_id=new&amp;parent_id='.$album['id']);
									$onc = 'if (confirm(\''.__('Are you sure you want to create a subalbum?', 'wppa').'\')) document.location=\''.$url.'\';';
									echo '<td><a onclick="'.$onc.'" class="wppacreate">'.__('Create', 'wppa').'</a></td>'; 
								}
							}
							else { ?>
							<td></td><td></td><?php if ( wppa_can_create_album() ) echo '<td></td' ?>
							<?php } ?>
						</tr>		
						<?php if ($alt == '') { $alt = ' class="alternate" '; } else { $alt = '';}
						if ( $haschildren ) wppa_do_albumlist($album['id'], $nestinglevel+'1', $albums, $seq);
					}
				}
			}
		}
	
}		

function wppa_have_accessable_children($alb) {
global $wpdb;

	$albums = $wpdb->get_results( "SELECT * FROM `" . WPPA_ALBUMS . "` WHERE `a_parent` = " . $alb['id'], ARRAY_A );

	if ( ! $albums || ! count($albums) ) return false;
	foreach ( $albums as $album ) {
		if ( wppa_have_access($album) ) return true;
	}
	return false;
}

// delete an album 
function wppa_del_album($id, $move = '') {
	global $wpdb;

	if ( $move && !wppa_have_access($move) ) {
		wppa_error_message(__('Unable to move photos to album %s. Album not deleted.', 'wppa'));
		return false;
	}
	
	$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_ALBUMS . '` WHERE `id` = %s LIMIT 1', $id));

	if (empty($move)) { // will delete all the album's photos
		$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `' . WPPA_PHOTOS . '` WHERE `album` = %s', $id), ARRAY_A);

		if (is_array($photos)) {
			foreach ($photos as $photo) {
				// remove the photos and thumbs
				$file = ABSPATH . 'wp-content/uploads/wppa/' . $photo['id'] . '.' . $photo['ext'];
				if (file_exists($file)) {
					unlink($file);
				}
				/* else: silence */
				$file = ABSPATH . 'wp-content/uploads/wppa/thumbs/' . $photo['id'] . '.' . $photo['ext'];
				if (file_exists($file)) {
					unlink($file);
				}
				/* else: silence */
				// remove the photo's ratings
				$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_RATING . '` WHERE `photo` = %s', $photo['id']));
				// remove the photo's comments
				$wpdb->query($wpdb->prepare('DELETE FROM `' . WPPA_COMMENTS . '` WHERE `photo` = %s', $photo['id']));
			} 
		}
		
		// remove the database entries
		$wpdb->query($wpdb->prepare('DELETE FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s', $id));
	} else {
		$wpdb->query($wpdb->prepare('UPDATE `'.WPPA_PHOTOS.'` SET `album` = %s WHERE `album` = %s', $move, $id));
	}
	
	wppa_update_message(__('Album Deleted.', 'wppa'));
}

// select main photo
function wppa_main_photo($cur = '') {
	global $wpdb;
	
    $a_id = $_GET['edit_id'];
	$photos = $wpdb->get_results($wpdb->prepare('SELECT * FROM `'.WPPA_PHOTOS.'` WHERE `album` = %s '.wppa_get_photo_order($a_id), $a_id), ARRAY_A);
	
	$output = '';
	if (!empty($photos)) {
		$output .= '<select name="wppa-main" onchange="wppaAjaxUpdateAlbum('.$a_id.', \'main_photo\', this)" >';
		$output .= '<option value="0">'.__('--- random ---', 'wppa').'</option>';

		foreach($photos as $photo) {
			if ($cur == $photo['id']) { 
				$selected = 'selected="selected"'; 
			} 
			else { 
				$selected = ''; 
			}
			$name = __(stripslashes($photo['name']));
			if ( strlen($name) > 45 ) $name = substr($name, 0, 45).'...';
			$output .= '<option value="'.$photo['id'].'" '.$selected.'>'.$name.'</option>';
		}
		
		$output .= '</select>';
	} else {
		$output = '<p>'.__('No photos yet', 'wppa').'</p>';
	}
	return $output;
}

function wppa_ea_url($edit_id, $tab = 'edit') {

	$nonce = wp_create_nonce('wppa_nonce');
//	$referrer = $_SERVER["REQUEST_URI"];
	return wppa_dbg_url(get_admin_url().'admin.php?page=wppa_admin_menu&amp;tab='.$tab.'&amp;edit_id='.$edit_id.'&amp;wppa_nonce='.$nonce);
}

function wppa_extended_access() {
global $wppa_opt;

	if ( current_user_can('administrator') ) return true;
	if ( $wppa_opt['wppa_owner_only'] == 'no' ) return true;
	return false;
}

function wppa_can_create_album() {
global $wppa_opt;
global $wpdb;

	if ( wppa_extended_access() ) return true;
	if ( $wppa_opt['wppa_max_albums'] == '0' ) return true;	// 0 = unlimited
	$user = wppa_get_user();
	$albs = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM `".WPPA_ALBUMS."` WHERE `owner` = %s", $user));
	if ( $albs < $wppa_opt['wppa_max_albums'] ) return true;
	return false;
}

function wppa_can_create_top_album() {
global $wppa_opt;

	if ( current_user_can('administrator') ) return true;
	if ( ! wppa_can_create_album() ) return false;
	if ( $wppa_opt['wppa_grant_an_album'] == 'yes' && $wppa_opt['wppa_grant_parent'] != '0' ) return false;
	return true;
}