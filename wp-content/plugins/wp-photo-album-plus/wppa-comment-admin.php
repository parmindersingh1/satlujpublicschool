<?php
/* wppa-commentadmin.php
* Package: wp-photo-album-plus
*
* manage all comments
* Version 4.9.13
*
*/

function _wppa_comment_admin() {
global $wpdb;
global $wppa;
global $wppa_opt;

	$continue = true;
	
	if (isset($_GET['tab'])) {
		if ($_GET['tab'] == 'edit') {
			$id = $_GET['edit_id'];
			$comment = $wpdb->get_row($wpdb->prepare( "SELECT * FROM ".WPPA_COMMENTS." WHERE id = %s LIMIT 1", $id ), ARRAY_A);
			if ($comment) {
			?>
			<div class="wrap">
				<?php $iconurl = WPPA_URL.'/images/comment.png'; ?>
				<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
					<br />
				</div>
				<h2><?php _e('Photo Albums -> Edit Comment', 'wppa'); ?></h2>
				<?php $action = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_manage_comments');
					if ( isset($_GET['wppa-page']) ) $action .= '&compage='.$_GET['wppa-page'];
					if ( isset($_GET['commentid']) ) $action .= '&commentid='.$_GET['commentid'];
				?>
				<form action="<?php echo $action ?>" method="post">
		
					<?php wp_nonce_field('$wppa_nonce', WPPA_NONCE); ?>
					<input type="hidden" name="edit_comment" value="<?php echo($comment['id']) ?>" />
					<table class="form-table albumtable">
						<tbody>
							<tr style="vertical-align:top" >
								<th>
									<?php $photo = $wpdb->get_row($wpdb->prepare( "SELECT * FROM ".WPPA_PHOTOS." WHERE id =  %s", $comment['photo']), "ARRAY_A" ) ?>
									<?php $url = WPPA_UPLOAD_URL.'/thumbs/'.$comment['photo'].'.'.$photo['ext'] ?>
									<img src="<?php echo($url) ?>" />
								</th>
								<td>
									<?php echo(wppa_qtrans($photo['name']).'<br/><br/>'.wppa_qtrans(stripslashes($photo['description']))) ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label ><?php _e('Photo:', 'wppa'); ?></label></th>
								<td><?php echo($comment['photo']) ?></td>								
							</tr>
							<tr>
								<th scope="row"><label ><?php _e('Album:', 'wppa'); ?></label></th>
								<td><?php 
									echo wppa_get_album_name($photo['album']);
									?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label ><?php _e('User:', 'wppa') ?></label></th>
								<td><input style="width:300px;" type="text" name="user" value="<?php echo($comment['user']) ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label ><?php _e('Email:', 'wppa') ?></label></th>
								<td><input style="width:300px;" type="text" name="email" value="<?php echo($comment['email']) ?>" /></td>
							</tr>
							<tr>
								<th scope="row"><label><?php _e('Comment:', 'wppa') ?></label></th>
								<td><textarea style="width:300px; height:150px;" name="comment"><?php echo(stripslashes($comment['comment'])) ?></textarea></td>
							</tr>
						</tbody>
					</table>
					<p>
						<input type="submit" class="button-primary" name="wppa_submit" value="<?php _e('Save Changes', 'wppa'); ?>" />
					</p>
				</form>			
			<?php
			}
			$continue = false;
		}
		if ($_GET['tab'] == 'delete') {
			$id = $_GET['delete_id'];
			$iret = $wpdb->query($wpdb->prepare( "DELETE FROM ".WPPA_COMMENTS." WHERE id = %s LIMIT 1", $id ) );
			if ($iret !== false) wppa_update_message('Comment deleted', 'wppa');
			else wppa_error_message('Error deleting comment', 'wppa');
			$continue = true;
		}
	}
	
	if ($continue) {

		// Update anything or do bulkaction
		if (isset($_POST['wppa_submit'])) {
			// Security check
			check_admin_referer( '$wppa_nonce', WPPA_NONCE );
			
			// Updates
			$iret = true;
			if (isset($_POST['wppa_comadmin_show'])) wppa_update_option('wppa_comadmin_show', $_POST['wppa_comadmin_show']);
			if (isset($_POST['wppa_comadmin_linkpage'])) wppa_update_option('wppa_comadmin_linkpage', $_POST['wppa_comadmin_linkpage']);
			if (isset($_POST['wppa_comadmin_order'])) wppa_update_option('wppa_comadmin_order', $_POST['wppa_comadmin_order']);
			if (isset($_POST['edit_comment'])) $iret = wppa_edit_comment($_POST['edit_comment']);
			
			// Bulk actions
			if (isset($_POST['bulkaction'])) switch ($_POST['bulkaction']) {
				case 'approveall':
					$query = "UPDATE " . WPPA_COMMENTS . " SET status = 'approved' WHERE status = 'pending'";
					if ( $wpdb->query($query) === false ) {
						wppa_error_message(__('Could not bulk update status', 'wppa'));
						$iret = false;
					}
					else $iret = true;
					break;
				case 'spamall':
					$query = "UPDATE " . WPPA_COMMENTS . " SET status = 'spam' WHERE status = 'pending'";
					if ( $wpdb->query($query) === false ) {
						wppa_error_message(__('Could not bulk update status', 'wppa'));
						$iret = false;
					}
					else $iret = true;
					break;
				case 'delspam':
					$query = "DELETE FROM " . WPPA_COMMENTS . " WHERE status = 'spam'";
					if ( $wpdb->query($query) === false ) {
						wppa_error_message(__('Could not bulk delete spam', 'wppa'));
						$iret = false;
					}
					break;
			}
			
			if ($iret) wppa_update_message(__('Changes Saved', 'wppa'));
			
			// Clear (super)cache
			wppa_clear_cache();
		} // Submit
		
		// Delete trash
		$query = "DELETE FROM " . WPPA_COMMENTS . " WHERE status = 'trash'";
		$wpdb->query($query);
		
		// Initialize normal diaplay
		$wppa_comadmin_linkpage = get_option('wppa_comadmin_linkpage', '0');
		$moderating = isset($_REQUEST['commentid']);
?>
		<div class="wrap">
			<?php $iconurl = WPPA_URL.'/images/comment.png'; ?>
			<div id="icon-album" class="icon32" style="background: transparent url(<?php echo($iconurl); ?>) no-repeat">
				<br />
			</div>
			<h2>
				<?php if ( $moderating ) _e('Photo Albums -> Moderate Comment', 'wppa');
					else _e('Photo Albums -> Comment admin', 'wppa'); 
				?>
			</h2>
			
			<?php if ($wppa_opt['wppa_show_comments'] == 'no') _e('<h3>The Comment system is not activated</h3><p>To activate: check Table II item 18 on the <b>Photo Albums -> Settings</b> screen and press <b>Save Changes</b>', 'wppa'); ?>
			
			<?php if ( ! $moderating ) { ?>
			<!-- Statistics -->
			<table>
				<tbody>
					<tr>
						<td><h3 style="margin:0; color:#777777;"><?php _e('Total:', 'wppa') ?></h3></td>
						<td><h3 style="margin:0;"><?php $count = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_COMMENTS."`" ); echo $count ?></h3></td>
					</tr>
					<tr>
						<td><h3 style="margin:0; color:green;"><?php _e('Approved:', 'wppa') ?></h3></td>
						<td><h3 style="margin:0;"><?php $count = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_COMMENTS."` WHERE `status` = 'approved'" ); echo $count ?></h3></td>
					</tr>
					<tr>
						<td><h3 style="margin:0; color:#e66f00;"><?php _e('Pending:', 'wppa') ?></h3></td>
						<td><h3 style="margin:0;"><?php $count = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_COMMENTS."` WHERE `status` = 'pending'" ); echo $count ?></h3></td>
					</tr>
					<tr>
						<td><h3 style="margin:0; color:red;"><?php _e('Spam:', 'wppa') ?></h3></td>
						<td><h3 style="margin:0;"><?php $count = $wpdb->get_var( "SELECT COUNT(*) FROM `".WPPA_COMMENTS."` WHERE `status` = 'spam'" ); echo $count ?></h3></td>
					</tr>
					<?php if ( $wppa_opt['wppa_spam_maxage'] != 'none' ) { ?>
					<tr>
						<td><h3 style="margin:0; color:red;"><?php _e('Auto deleted spam:', 'wppa') ?></h3></td>
						<td><h3 style="margin:0;"><?php echo get_option('wppa_spam_auto_delcount', '0') ?></h3></td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
			<!-- end statistics -->
			
			<!-- Settings -->
			<div style="border:1px solid #ccc; padding:4px; margin:4px 0" >
				<h3><?php _e('Settings', 'wppa') ?></h3>
				<form action="<?php echo wppa_dbg_url(get_admin_url().'admin.php?page=wppa_manage_comments') ?>" method="post">
					<p>
						<?php 
							wp_nonce_field('$wppa_nonce', WPPA_NONCE);
							_e('Linkpage:', 'wppa'); 
						?>
						<select name="wppa_comadmin_linkpage">
							<option value="0" <?php if ($wppa_comadmin_linkpage=='0') echo 'selected="selected"' ?> disabled="disabled" ><?php _e('--- Please select a page ---', 'wppa') ?></option>
							<?php
								$query = "SELECT `ID`, `post_title`, `post_content` FROM `" . $wpdb->posts . "` WHERE `post_type` = 'page' AND `post_status` = 'publish' ORDER BY `post_title` ASC";
								$pages = $wpdb->get_results ($query, ARRAY_A);
								if ($pages) {
									foreach ($pages as $page) {
										if ( stripos($page['post_content'], '%%wppa%%') !== false || stripos($page['post_content'], '[wppa') !== false ) {
											if ($wppa_comadmin_linkpage == $page['ID']) $sel = 'selected="selected"';
											else $sel = '';
											echo '<option value="'.$page['ID'].'" '.$sel.'>'.__($page['post_title']).'</option>';
										}
									}
								} 
							?>
						</select>
						<?php _e('You can see the photo and all its comments on the selected page by clicking on the thumbnail image', 'wppa'); ?>
					</p>
					<?php $comment_show = get_option('wppa_comadmin_show') ?>
					<p>
						<?php _e('Display status:', 'wppa') ?>
						<select name="wppa_comadmin_show">
							<option value="all" <?php if ($comment_show == 'all') echo('selected="selected"') ?>><?php _e('all', 'wppa') ?></option>
							<option value="pending" <?php if ($comment_show == 'pending') echo('selected="selected"') ?>><?php _e('pending', 'wppa') ?></option>
							<option value="approved" <?php if ($comment_show == 'approved') echo('selected="selected"') ?>><?php _e('approved', 'wppa') ?></option>
							<option value="spam" <?php if ($comment_show == 'spam') echo('selected="selected"') ?>><?php _e('spam', 'wppa') ?></option>
						</select>
						<?php $comment_order = get_option('wppa_comadmin_order', 'wppa') ?>
						<?php _e('Display order:', 'wppa') ?>
						<select name="wppa_comadmin_order">
							<option value="timestamp" <?php if ($comment_order == 'timestamp') echo('selected="selected"') ?>><?php _e('timestamp', 'wppa') ?></option>
							<option value="photo" <?php if ($comment_order == 'photo') echo('selected="selected"') ?>><?php _e('photo', 'wppa') ?></option>
						</select>
						<?php _e('Bulk action:', 'wppa') ?>
						<select name="bulkaction">
							<option value=""><?php  ?></option>
							<option value="approveall"><?php _e('Approve all pending', 'wppa') ?></option>
							<option value="spamall"><?php _e('Move all pending to spam', 'wppa') ?></option>
							<option value="delspam"><?php _e('Delete all spam', 'wppa') ?></option>
						</select>
						<input type="submit" class="button-primary" name="wppa_submit" value="<?php _e('Save Settings / Perform bulk action', 'wppa'); ?>" />
					</p>
				</form>
			</div>
			<!-- End Settings -->
			
			<?php 
			}
			if ( $moderating ) {
				$pagesize = '1';
				$where = " WHERE `id` = '".$_REQUEST['commentid']."'";
				$order = '';
				$curpage = '1';
				$limit = '';
			}
			else {
				$pagsize = $wppa_opt['wppa_comment_admin_pagesize']; 
				$where = ( $comment_show == 'all' ) ? '' : " WHERE `status` = '".$comment_show."'";
				$order = " ORDER BY `".$comment_order."`";
				if ( $comment_order == 'timestamp' ) $order .= " DESC";
				if (isset($_GET['wppa-page'])) {
					$curpage = $_GET['wppa-page'];
					$offset = ($_GET['wppa-page'] - 1) * $pagsize;
					$limit = " LIMIT ".$offset.",".$pagsize;
				}
				else {
					$limit = ' LIMIT 0,'.$pagsize;
					$curpage = '1';
				}
				if ( $pagsize == '0' ) $limit = ''; // Paginating is off

				$nitems = $wpdb->get_var( "SELECT COUNT(*) FROM ".WPPA_COMMENTS.$where );
				$link = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_manage_comments'); 
				wppa_admin_page_links($curpage, $pagsize, $nitems, $link);
			}
			?>
			<table class="widefat">
				<thead style="font-weight: bold" class="">
					<tr>
						<th scope="col"><?php _e('Photo', 'wppa') ?><br />
										<?php _e('(Album)', 'wppa') ?></th>
						<th scope="col"><?php _e('#', 'wppa') ?></th>
						<th scope="col"><?php _e('IP', 'wppa') ?></th>
						<th scope="col"><?php _e('User', 'wppa') ?></th>
						<th scope="col"><?php _e('Email', 'wppa') ?></th>
						<th scope="col"><?php _e('Time since', 'wppa') ?></th>
						<th scope="col"><?php _e('Comment', 'wppa') ?></th>
						<th scope="col" style="width: 130px;" ><?php _e('Status', 'wppa') ?></th>
						<th scope="col"><?php _e('Edit', 'wppa') ?></th>
						<th scope="col"><?php _e('Delete', 'wppa') ?></th>
					</tr>
				</thead>
				<tbody class="wppa_table_1">
					<?php 
					$comments = $wpdb->get_results( "SELECT * FROM `".WPPA_COMMENTS."`".$where.$order.$limit, ARRAY_A);
					if ($comments) {
						foreach ($comments as $com) { ?>
							<tr>
								<?php
								$photo = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".WPPA_PHOTOS." WHERE id = %s", $com['photo']), 'ARRAY_A');
								if ($photo) {
									$alb = $photo['album'];
									$pname = __($photo['name']);
									$albname = '('.wppa_get_album_name($alb).')';
								}
								else {
									$alb = '';
									$pname = '';
									$albname = '';
								}
								
								if ($wppa_comadmin_linkpage == '0') { ?>
									<td style="text-align:center">
										<img src="<?php echo(WPPA_UPLOAD_URL.'/thumbs/'.$com['photo'].'.'.$wpdb->get_var($wpdb->prepare( "SELECT ext FROM ".WPPA_PHOTOS." WHERE id = %s", $com['photo']))) ?>" style="max-height:64px;max-width:64px;" />
										<br />
										<?php echo $albname ?>
									</td><?php							
								} 
								else { 
									$url = get_page_link($wppa_comadmin_linkpage);
									if (strpos($url, '?')) $url .= '&';
									else $url .= '?';
									$url .= 'wppa-album='.$alb.'&wppa-photo='.$com['photo'].'&wppa-occur=1'; ?>
									<td style="text-align:center">
										<a href="<? echo $url ?>" target="_blank">
											<img title="<?php _e('Click to see the fullsize photo and all comments', 'wppa') ?>" src="<?php echo(WPPA_UPLOAD_URL.'/thumbs/'.$com['photo'].'.'.$wpdb->get_var($wpdb->prepare( "SELECT ext FROM ".WPPA_PHOTOS." WHERE id = %s", $com['photo']))) ?>" style="max-height:64px;max-width:64px;" />
										</a>
										<br />
										<?php echo $albname ?>
									</td><?php							
								} ?>
								<td><?php echo $com['photo'] ?></td>
								<td><?php echo $com['ip'] ?></td>
								<td><?php echo $com['user'] ?></td>
								<td><?php 
									if ( $com['email'] ) {
										$subject = str_replace(' ', '%20', sprintf(__('Reply to your comment on photo: %s on %s', 'wppa'), $pname, get_bloginfo('name')));
										echo '<a href="mailto:'.$com['email'].'?Subject='.$subject.'" title="'.__('Reply', 'wppa').'" >'.$com['email'].'</a>';
									}
									else {
										echo $com['email'];
									} ?>
								</td>
								<td><?php echo wppa_get_time_since($com['timestamp']) ?></td>
								<td><?php echo $com['comment'] ?></td>
								<td>
									<input type="hidden" id="photo-nonce-<?php echo $com['photo'] ?>" value="<?php echo wp_create_nonce('wppa_nonce_'.$com['photo']);  ?>" />
									<select name="status['<?php echo $com['id'] ?>']" onchange="jQuery('#wppa-comment-spin-<?php echo $com['id'] ?>').css('visibility', 'visible'); wppaAjaxUpdateCommentStatus(<?php echo $com['photo'] ?>, <?php echo $com['id'] ?>, this.value)">
										<option value="pending" 	<?php if($com['status'] == 'pending') 	echo 'selected="selected"' ?>><?php _e('Pending', 'wppa') ?></option>
										<option value="approved" 	<?php if($com['status'] == 'approved') 	echo 'selected="selected"' ?>><?php _e('Approved', 'wppa') ?></option>
										<option value="spam" 		<?php if($com['status'] == 'spam') 		echo 'selected="selected"' ?>><?php _e('Spam', 'wppa') ?></option>
									</select>
									<img id="wppa-comment-spin-<?php echo $com['id'] ?>" src="<?php echo wppa_get_imgdir().'wpspin.gif' ?>" style="visibility:hidden" />
								</td>
								<?php $url = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_manage_comments&tab=edit&edit_id='.$com['id']);
									if ( isset($_GET['wppa-page'])) $url .= '&compage='.$_GET['wppa-page']; 
									if ( isset($_GET['commentid']) ) $url .= '&commentid='.$_GET['commentid']; ?>
								<?php $delurl = wppa_dbg_url(get_admin_url().'admin.php?page=wppa_manage_comments&tab=delete&delete_id='.$com['id']) ?>
								<td style="color:green; cursor:pointer;" onclick="document.location='<?php echo($url) ?>'"><b><?php _e('Edit', 'wppa') ?></b></td>
								<td style="color:red; cursor:pointer;" onclick="if (confirm('<?php _e('Are you sure you want to delete this comment?', 'wppa') ?>')) document.location = '<?php echo($delurl) ?>';"><b><?php _e('Delete', 'wppa') ?></b></td>
							</tr>						
						<?php }					
					}					
					?>
				</tbody>
				<tfoot style="font-weight: bold" class="">
					<tr>
						<th scope="col"><?php _e('Photo', 'wppa') ?><br />
										<?php _e('(Album)', 'wppa') ?></th>
						<th scope="col"><?php _e('#', 'wppa') ?></th>
						<th scope="col"><?php _e('IP', 'wppa') ?></th>
						<th scope="col"><?php _e('User', 'wppa') ?></th>
						<th scope="col"><?php _e('Email', 'wppa') ?></th>
						<th scope="col"><?php _e('Time since', 'wppa') ?></th>
						<th scope="col"><?php _e('Comment', 'wppa') ?></th>
						<th scope="col"><?php _e('Status', 'wppa') ?></th>
						<th scope="col"><?php _e('Edit', 'wppa') ?></th>
						<th scope="col"><?php _e('Delete', 'wppa') ?></th>
					</tr>
				</tfoot>
			</table>
			<?php if ( ! $moderating ) wppa_admin_page_links($curpage, $pagsize, $nitems, $link) ?>
		</form>
	</div>
	<?php
	}
}

function wppa_edit_comment($id) {
global $wpdb;

	$record = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM ".WPPA_COMMENTS." WHERE id = %s LIMIT 0,1", $id ), ARRAY_A );
	if ($record) {
		if (isset($_POST['comment'])) $record['comment'] = $_POST['comment'];
		if (isset($_POST['email'])) $record['email'] = $_POST['email'];
		if (isset($_POST['user'])) $record['user'] = $_POST['user'];
		
		$iret = $wpdb->query($wpdb->prepare( "UPDATE `".WPPA_COMMENTS."` SET `comment` = %s, `email` = %s, `user` = %s WHERE `id` = %s LIMIT 1", $record['comment'], $record['email'], $record['user'], $id ) );
		if ($iret === false) {
			wppa_error_message(__('Unable to update comment. Err =', 'wppa').' 2.');
			return false;
		}
		return true;		
	}
	else {
		wppa_error_message(__('Unable to update comment. Err =', 'wppa').' 1.');
		return false;
	}
}
