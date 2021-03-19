<?php
/*
bbps-user-ranking
contains functions relating to the user ranking
this file will grow as we extend our forum user options
to include things like upload badges for your rankings etc!
*/

//update the user post count meta everytime the user creates a new post
function bbps_increament_post_count(){
	global $current_user;
	
	$post_type = get_post_type();
	//bail unless we are creating topics or replies
	if ( $post_type == 'topic' || $post_type == 'reply' ){
	
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		$user_rank = get_user_meta($user_id, '_bbps_rank_info');
		
		 //if this is their first post
		if ( empty($user_rank[0]) ){
			bbps_create_user_ranking_meta($user_id);
			//$meta_value = 1;
		}else {
			$meta_value = $post_count + 1;
			//update_user_meta( $user_id, '_bbps_rank_info', $meta_value, $post_count );
		}
		bbps_check_ranking($user_id);
		
	}
	return;
		
}
add_action('save_post','bbps_increament_post_count');

function bbps_check_ranking($user_id){
	$user_rank = get_user_meta( $user_id, '_bbps_rank_info' );
	

	$post_count = $user_rank[0]['post_count'];
	$current_rank = $user_rank[0]['current_ranking'];
	$next_rank = $user_rank[0]['count_next_ranking'];
	$post_count = $post_count + 1;
	$rankings = get_option('_bbps_reply_count');
	
		foreach ($rankings as $rank){
			
			//if post count == the end value then this title no longer applies so remove it
			//we subtract one here to allow for the between number eg between 1 - 4 we still
			//want to dispaly the title if the post count is 4
			
			
				if($post_count - 1 == $rank['end'])
					$current_rank ="";
			
			if ($post_count == $rank['start'])
				$current_rank = $rank['title'];	
		}
		
		$meta = array(	'post_count' => $post_count,
						'current_ranking' => $current_rank,);
					
		update_user_meta( $user_id, '_bbps_rank_info', $meta );
}

//creates the user meta if the user has no rating.
function bbps_create_user_ranking_meta($user_id){

$meta = array(
		'post_count' => '0', //this will be one because the prev post count was 0 and we are in the save post hook
		'current_ranking' => '',
		);
		
	update_user_meta( $user_id, '_bbps_rank_info', $meta);
}

function bbps_display_user_title(){
	
	$user_id = bbp_get_reply_author_id();
	$user_rank = get_user_meta( $user_id, '_bbps_rank_info' );

	if($user_rank[0]['current_ranking'])
		echo '<div id ="bbps-user-title">'.$user_rank[0]['current_ranking'] . '</div>';
		
}

function bbps_display_user_post_count(){
if (get_option('_bbps_enable_post_count')== 1){
	$user_id = bbp_get_reply_author_id();
	$user_rank = get_user_meta( $user_id, '_bbps_rank_info' );
	if($user_rank[0]['post_count'])
		echo '<div id ="bbps-post-count"> Post count: '.$user_rank[0]['post_count'] . '</div>';
}
}
//this action hook was manually added in to the loop-replies.php template
//just below the author ip stuff (but out of the is super admin check :) ) do_action('bbp-author-area'); 
//you will need to put this into your template to display the user ranking info.

add_action('bbp_theme_after_reply_author_details', 'bbps_display_user_title');
add_action('bbp_theme_after_reply_author_details', 'bbps_display_user_post_count');
?>