<?php
/*
bbps - support functions
Contains all the functions that generate and update the topic status.
*/


add_action( 'bbp_template_before_single_topic', 'bbps_add_support_forum_features' );

function bbps_get_update_capabilities() {

	global $current_user;
	$current_user = wp_get_current_user();
	$user_id      = $current_user->ID;

	$topic_author_id = bbp_get_topic_author_id();
	$permissions     = get_option( '_bbps_status_permissions' );
	$can_edit        = '';
	// check the users permission this is easy
	if ( $permissions['admin'] == 1 && current_user_can( 'administrator' ) || $permissions['mod'] == 1 && current_user_can( 'bbp_moderator' ) || $permissions['user'] == 1 && current_user_can( 'bbp_moderator' ) ) {
		$can_edit = true;
	}
	// now check the current user against the topic creator are they they same person and can they cahnge the status?
	if ( $user_id == $topic_author_id && $permissions['user'] == 1 ) {
		$can_edit = true;
	}

	return $can_edit;
}


function bbps_add_support_forum_features() {
	// only display all this stuff if the support forum option has been selected.
	if ( bbps_is_support_forum( bbp_get_forum_id() ) ) {
		$can_edit = bbps_get_update_capabilities();
		$topic_id = bbp_get_topic_id();
		$status   = bbps_get_topic_status( $topic_id );
		?> <div id="bbps_support_forum_options"> 
		<?php
		// get out the option to tell us who is allowed to view and update the drop down list.
		if ( $can_edit == true ) {
			?>
			<p class="topic_stat">This topic is: &nbsp; </p> 
			<?php
			bbps_generate_staus_options( $topic_id, $status );
		} else {
			?>
			This topic is: 
			<?php
			echo $status;
		}
		?>
		 </div> 
		<?php
	}
}

function bbps_get_topic_status( $topic_id ) {
	$default = get_option( '_bbps_default_status' );
	$status  = get_post_meta( $topic_id, '_bbps_topic_status', true );
	// to do not hard code these if we let the users add their own satus
	if ( $status ) {
		$switch = $status;
	} else {
		$switch = $default;
	}

	switch ( $switch ) {
		case 1:
			return 'not resolved';
			break;
		case 2:
			return 'resolved';
			break;
		case 3:
			return 'not a support question';
			break;
	}
}

// generates a drop down list with the support forum topic status only for admin tho.
function bbps_generate_staus_options( $topic_id ) {

	$dropdown_options = get_option( '_bbps_used_status' );
	$status           = get_post_meta( $topic_id, '_bbps_topic_status', true );
	$default          = get_option( '_bbps_default_status' );

	// only use the default value as selected if the topic doesnt ahve a status set
	if ( $status ) {
		$value = $status;
	} else {
		$value = $default;
	}

	?>
	<form id="bbps-topic-status" name="bbps_support" action="" method="post">
		<select name="bbps_support_option" id="bbps_support_options"> 
			<?php
			// we only want to display the options the user has selected. the long term goal is to let users add their own forum statuses
			// this is broken says its selected but is showing the wrong  option WHYYYYY
			if ( $dropdown_options['res'] == 1 ) {
				?>
			 <option value="1" <?php selected( $value, 1 ); ?> >not resolved</option> 
				<?php
			}
			if ( $dropdown_options['notres'] == 1 ) {

				?>
			 <option value="2" <?php selected( $value, 2 ); ?> >resolved</option> 
				<?php
			}
			if ( $dropdown_options['notsup'] == 1 ) {

				?>
			 <option value="3" <?php selected( $value, 3 ); ?> >not a support question</option> <?php } ?>
		</select>
		<input type="submit" value="Update" name="bbps_support_submit" />
		<input type="hidden" value="bbps_update_status" name="bbps_action"/>
		<input type="hidden" value="<?php echo $topic_id; ?>" name="bbps_topic_id" />
	</form> 
	<?php
}

function bbps_update_status() {
	$topic_id = $_POST['bbps_topic_id'];
	$status   = $_POST['bbps_support_option'];
	update_post_meta( $topic_id, '_bbps_topic_status', $status );
}

// need to find a hook or think of the best way to do this
if ( isset( $_POST['bbps_support_submit'] ) ) {
	bbps_update_status( $_POST );
}

// if the topic is resolved add that class to it!
// we are now only going to make the word resolved green if you want the whole topic to be green then please uncomment this function
/*
function bbps_check_resolved( $class = '' ) {
	$topic_id = get_the_ID();
	$status = get_post_meta( $topic_id, '_bbps_topic_status', true );

	if ( $status == 2 )
		$class[] = 'resolved';

	return $class;
}

add_filter('post_class', 'bbps_check_resolved');
*/

/*
	function bbps_modify_title - will add the topic
	status resolved to any resolved topics this will
	be coloured green

	@uses get_the_title() - to get the post title
	@uses bbp_get_topic_id() - to get the topic ID
*/
/*
function bbps_modify_title($title, $topic_id = 0){
	$topic_id = bbp_get_topic_id( $topic_id );
	$title = "";

	//2 is the resolved status ID
	if (get_post_meta( $topic_id, '_bbps_topic_status', true ) == 2 && get_post_type() == 'topic')
		$title = '<span class="resolved"> [Resolved] </span>';

	$title .= get_the_title( $topic_id );
	return $title;

	}

add_filter('bbp_get_topic_title', 'bbps_modify_title', 10, 2);
*/

function bbps_modify_title( $title, $topic_id = 0 ) {
	$topic_id = bbp_get_topic_id( $topic_id );
	$title    = '';

	// 2 is the resolved status ID
	if ( get_post_meta( $topic_id, '_bbps_topic_status', true ) == 2 ) {
		echo '<span class="resolved"> [Resolved] </span>';
	}

}

add_action( 'bbp_theme_before_topic_title', 'bbps_modify_title' );








?>
