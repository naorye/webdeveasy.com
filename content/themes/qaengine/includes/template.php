<?php
/**
*
* TEMPLATE SELECT CATEGORIES (REDIRECT)
* @param 
* @author ThaiNT
* @since 1.0
*
**/
function qa_template_share($id){
	$url 	= get_permalink( $id );
	$title  = get_the_title( $id );
	return '<ul class="socials-share"><li><a href="https://www.facebook.com/sharer/sharer.php?u='.$url.'&t='.$title.'" target="_blank" class="btn-fb"><i class="fa fa-facebook"></i></a></li><li><a target="_blank" href="http://twitter.com/share?text='.$title.'&url='.$url.'" class="btn-tw"><i class="fa fa-twitter"></i></a></li><li class="ggplus"><a target="_blank"  href="https://plus.google.com/share?url='.$url.'" class="btn-gg"><i class="fa fa-google-plus"></i></a></li></ul>';
}
/**
*
* TEMPLATE SELECT CATEGORIES (REDIRECT)
* @param 
* @author ThaiNT
* @since 1.0
*
**/
function qa_template_paginations($query,$paged){
	global $wp_rewrite;
    echo paginate_links( array(
        'base'      => str_replace('99999', '%#%', esc_url(get_pagenum_link( 99999 ))),
        'format'    => $wp_rewrite->using_permalinks() ? 'page/%#%' : '?paged=%#%',
        'current'   => max(1, $paged),
        'mid_size'  => 1,
        'total'     => $query->max_num_pages,
        'prev_text' => '<',
        'next_text' => '>',
        'type'      => 'list'
    ) );
}
/**
*
* TEMPLATE SELECT CATEGORIES (REDIRECT)
* @param 
* @author ThaiNT
* @since 1.0
*
**/
function qa_option_categories_redirect($current = false, $args = array()){
	$current = get_query_var( 'term' );
	$args = wp_parse_args( $args, array(
		'hide_empty' => 0,
	));
		$terms = get_terms( 'question_category', $args );
		foreach ($terms as $term) {
	?>
	<option <?php echo $current == $term->slug ? 'selected' : ''; ?> value="<?php echo get_term_link($term, 'question_category' ); ?>">
		<?php
			if($term->parent) echo '--';
			echo $term->name;
		?>
	</option>
	<?php
		}
}
/**
*
* TEMPLATE FILTER QUESTIONS LIST
* @param 
* @author ThaiNT
* @since 1.0
*
**/
function qa_template_filter_questions(){
	if(is_post_type_archive( 'question' )){
		$current = get_post_type_archive_link( 'question' );
	} elseif (is_page_template( 'page-questions.php' ) || is_page_template( 'page-pending.php' )) {
		$current = et_get_page_link('questions');
	} elseif ( is_tax() ) {
		global $term;
		$taxonomy = is_tax('question_category') ? 'question_category' : 'qa_tag';
		$current = get_term_link( $term, $taxonomy );
	} elseif(is_front_page()) {
		$current = home_url();
	} else {
		$current = '';
	}

	if(isset($_GET['sort']) && $_GET['sort'] == 'vote')
		$args = array('sort' => 'vote');
	elseif(isset($_GET['sort']) && $_GET['sort'] == 'unanswer')
		$args = array('sort' => 'unanswer');
	else
		$args = array();

	$posts_per_page = apply_filters( 'qa_filter_numbers_array', array(20,15,10,5) );
	$opt_pp = (int)get_option( 'posts_per_page' );

	if(!in_array($opt_pp, $posts_per_page))
		$posts_per_page[] = $opt_pp;

	rsort($posts_per_page);
?>
		<div class="row q-filter-waypoints collapse" id="q_filter_waypoints">
			<div class="col-md-2 col-xs-2">
				<button type="button" data-toggle="modal" class="action ask-question">
	                <i class="fa fa-plus"></i> <?php _e("ASK A QUESTION", ET_DOMAIN) ?>
	            </button>				
			</div>
			<div class="col-md-8 col-sm-10 col-xs-10">
				<div class="row">
					<div class="col-md-2 col-xs-2">
						<?php
							$keyword = get_query_var( 'keyword' );
							if( isset($keyword) && $keyword != "" ){
						?>
						<span class="q-f-title"><?php _e("Search Questions", ET_DOMAIN) ?></span>
						<?php } else { ?>
						<span class="q-f-title">
							<?php _e("All Questions", ET_DOMAIN) ?>
						</span>
						<?php } ?>
					</div><!-- END TITLE -->
					<div class="col-md-4 col-xs-4">
						<ul class="q-f-sort">
		                    <li>
		                        <a class="<?php echo !isset($_GET['sort']) ? 'active' : ''; ?>" href="<?php echo $current ?>"><?php _e("Latest",ET_DOMAIN) ?></a>
		                    </li>
		                    <li>
		                        <a class="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'vote' ? 'active' : ''; ?>" href="<?php echo add_query_arg(array('sort' => 'vote')); ?>"><?php _e("Votes",ET_DOMAIN) ?></a>
		                    </li>
		                    <li>
		                        <a class="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'unanswer' ? 'active' : ''; ?>" href="<?php echo add_query_arg(array('sort' => 'unanswer')); ?>"><?php _e("Unanswered",ET_DOMAIN) ?></a>
		                    </li>
		                </ul><!-- END FILTER -->						
					</div>
					<div class="col-md-6 col-xs-6">
						<div class="select-categories-wrapper">
		                    <div class="select-categories">
		                        <select class="select-grey-bg" id="move_to_category">
		                            <option><?php _e("Filter by category",ET_DOMAIN) ?></option>
                            		<?php qa_option_categories_redirect() ?>
			                    </select>
		                    </div>
		                </div><!-- END SELECT CATEGORIES -->
                        <div class="number-of-questions-wrapper">
                            <div class="number-of-questions">
                                <select id="filter-numbers" class="select-grey-bg">
                                    <?php
                                        $current_pp = isset($_GET['numbers']) && $_GET['numbers'] ? $_GET['numbers'] : $opt_pp;
                                        foreach ($posts_per_page as $key => $value) {
                                            $args['numbers'] = $value;
                                    ?>
                                    <option <?php if( $current_pp == $value ) echo 'selected'; ?> value="<?php echo add_query_arg($args, $current); ?>"><?php echo $value ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><!-- END POSTS PER PAGE -->
					</div>										
				</div>
			</div>			
		</div>
        <div class="row question-filter" id="question_filter">
            <div class="col-md-6 col-xs-6 sort-questions">
                <ul>
                    <li>
                        <a class="<?php echo !isset($_GET['sort']) && !is_page_template( 'page-pending.php' ) ? 'active' : ''; ?>" href="<?php echo $current ?>"><?php _e("Latest",ET_DOMAIN) ?></a>
                    </li>
                    <li>
                        <a class="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'vote' ? 'active' : ''; ?>" href="<?php echo add_query_arg(array('sort' => 'vote'), $current); ?>"><?php _e("Votes",ET_DOMAIN) ?></a>
                    </li>
                    <li>
                        <a class="<?php echo isset($_GET['sort']) && $_GET['sort'] == 'unanswer' ? 'active' : ''; ?>" href="<?php echo add_query_arg(array('sort' => 'unanswer'), $current); ?>"><?php _e("Unanswered",ET_DOMAIN) ?></a>
                    </li>
                    <?php 
                    	global $current_user;
                    	if( current_user_can( 'manage_options' ) && et_count_posts("pending") > 0 ){
                    ?>
                    <li>
                        <a class="<?php echo is_page_template( 'page-pending.php' ) ? 'active' : ''; ?>" href="<?php echo et_get_page_link('pending'); ?>"><?php _e("Pending",ET_DOMAIN) ?></a>
                    </li>                    
                    <?php } ?>
                </ul>
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="number-of-questions-wrapper">
                	<span class="number-of-questions-text"><?php _e("Questions Per Page: ", ET_DOMAIN ); ?></span>
                 	<div class="number-of-questions">
                        <select id="filter-numbers" class="select-grey-bg">
							<?php
								foreach ($posts_per_page as $key => $value) {
									$args['numbers'] = $value;
							?>
                            <option <?php if( $current_pp == $value ) echo 'selected'; ?> value="<?php echo add_query_arg($args, $current); ?>"><?php echo $value ?></option>
							<?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div><!-- END QUESTIONS-FILTER -->
<?php
}

/**
*
* TEMPLATE COMMENT FOR SINGLE POST
* @param array $comments , $args , int $depth
* @author ThaiNT
* @since 1.0
*
**/
function qa_comment_post_template($comment, $args, $depth){
	$GLOBALS['comment'] = $comment;
?>
	<li class="et-comment" id="comment-<?php echo $comment->comment_ID ?>">
		<div class="et-comment-left">
			<div class="et-comment-thumbnail">
				<?php echo et_get_avatar($comment->user_id); ?>
			</div>
		</div>
		<div class="et-comment-right">
			<div class="et-comment-header">
				<a href="<?php comment_author_url() ?>"><strong class="et-comment-author"><?php comment_author() ?></strong></a>
				<span class="et-comment-time icon" data-icon="t"><?php comment_date() ?></span>
			</div>
			<div class="et-comment-content">
				<?php comment_text() ?>
				<p class="et-comment-reply"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></p>
			</div>
		</div>
		<div class="clearfix"></div>
<?php	
}

/**
*
* TEMPLATE TAG
* @param array $comments 
* @author ThaiNT
* @since 1.0
*
**/
function qa_tag_template(){
	?>
	<script type="text/template" id="tag_item">
	
		<input type="hidden" name="tags[]" value="<%= name %>" />
		<%= name %> <a href="javascript:void(0)" class="delete"><i class="fa fa-times"></i></a>
		
	</script>
	<?php
}
/**
*
* JS TEMPLATE COMMENT
* @param array $comments 
* @author ThaiNT
* @since 1.0
*
**/
function qa_comment_template(){
	// get template-js/item-comment.php
	get_template_part( 'template-js/item', 'comment' );
}

/**
*
* JS TEMPLATE ANSWER
* @param array $comments 
* @author ThaiNT
* @since 1.0
*
**/
function qa_answer_template(){
	// get template-js/item-answer.php
	get_template_part( 'template-js/item', 'answer' );
}
/**
*
* TEMPLATE print selec categories
* @param array $comments 
* @author ThaiNT
* @since 1.0
*
**/
function qa_select_categories($args = array()){
		$args = wp_parse_args( $args, array(
				'hide_empty' => 0,
			));
	?>
	<div class="select-categories">
		<select id="question_category" name="question_category" class="categories-select">
			<option value=""><?php _e("Select Category",ET_DOMAIN) ?></option>
			<?php
				$terms = get_terms( 'question_category', $args );
				foreach ($terms as $term) {
			?>
			<option value="<?php echo $term->slug ?>">
				<?php 
					if($term->parent) echo '--';
					echo $term->name;
				?>
			</option>
			<?php
				}
			?>		
		</select>
	</div>
	<?php
}
/**
*
* TEMPLATE LOOP FOR COMMENTS
* @param array $comments 
* @author ThaiNT
* @since 1.0
*
**/
function qa_comments_loop($child){
	global $qa_comment;
	$qa_comment = QA_Comments::convert($child);
	if(et_load_mobile())
		get_template_part( 'mobile/template/item' , 'comment' );
	else
		get_template_part( 'template/item' , 'comment' );

}
/**
*
* TEMPLATE LOOP FOR ANSWERS
* @param array $answers 
* @author ThaiNT
* @since 1.0
*
**/
function qa_answers_loop(){

	global $post,$wp_rewrite,$current_user , $qa_question;
	$question_ID = $post->ID;
	$answersData = array();
	$commentsData = array();
	$question = QA_Questions::convert(get_post($question_ID));

	$qa_question =	$question;

	$paged = get_query_var( 'page' ) ? get_query_var( 'page' ) : 1 ;

	$reply_args = array(
			'post_type' 	=> 'answer',
			'post_status'	=> 'publish',
			'post_parent'	=> $post->ID,
			'paged'			=> $paged,
		);

	if( isset($_GET['sort']) && $_GET['sort'] == "oldest" ){
		$reply_args['order'] = 'ASC';
	} else {
		add_filter("posts_join"		, array("QA_Front", "_post_vote_join") );
		add_filter("posts_orderby"	, array("QA_Front", "_post_vote_orderby") );
	}
	$replyQuery = new WP_Query($reply_args);
?>
	<!-- ANSWERS LOOP -->
	<div id="answers_main_list">
		<?php
		if($replyQuery->have_posts()){
			while($replyQuery->have_posts()){ $replyQuery->the_post();
				global $post, $qa_answer, $qa_answer_comments;
				$qa_answer =  QA_Answers::convert($post);
				$answersData[] = $qa_answer;
				$qa_answer_comments   = get_comments( array( 
			                        'post_id'       => $qa_answer->ID,
			                        'parent'        => 0,
			                        'status'        => 'approve',
			                        'post_status'   => 'publish',
			                        'order'         => 'ASC',
			                        'type'			=> 'answer'
			                    ) );
				$commentsData = array_merge($commentsData, $qa_answer_comments);

			?>
			<div class="row question-main-content question-item answer-item" id="<?php echo $qa_answer->ID ?>">
			    <?php get_template_part( 'template/item', 'answer' ); ?>
			</div><!-- END REPLY-ITEM -->
			<?php
			}
		}
		wp_reset_query();
		?> 
	</div>      
	<!-- ANSWERS LOOP -->
	<div class="row paginations <?php echo $replyQuery->max_num_pages > 1 ? '' : 'collapse'; ?>">
	    <div class="col-md-12">
	        <?php
	            echo paginate_links( array(
	                'base'      => get_permalink($question_ID) . '%#%',
	                'format'    => $wp_rewrite->using_permalinks() ? 'page/%#%' : '?paged=%#%',
	                'current'   => max(1, $paged),
	                'total'     => $replyQuery->max_num_pages,
	                'mid_size'  => 1,
	                'prev_text' => '<',
	                'next_text' => '>',
	                'type'      => 'list'
	            ) );
	        ?>        
	    </div>
	</div><!-- END PAGINATIONS -->
	<script type="text/javascript">
		<?php
	        $parent_comments    = get_comments( array( 
	            'post_id'       => $question_ID,
	            'parent'        => 0,
	            'status'        => 'approve',
	            'post_status'   => 'publish',
	            'order'         => 'ASC',
	            'type'			=> 'question'
	        ) );
	        $commentsData = !empty($commentsData) ? $commentsData : array();
		?>
		var answersData  = <?php echo json_encode( $answersData, JSON_HEX_QUOT ) ?>;
		var commentsData = <?php echo json_encode( array_merge( $parent_comments, $commentsData ), JSON_HEX_QUOT ) ?>;
	</script>
<?php
}

/**
*
* COUNT POST IN TAGS
* @param int $tag_id
* @author ThaiNT
* @since 1.0
*
**/
function qa_count_post_in_tags($tag){
	$today   = getdate();
	$today_query = new WP_Query( 'post_type=question&qa_tag='.$tag.'&year=' . $today["year"] . '&monthnum=' . $today["mon"] . '&day=' . $today["mday"] );	

	$week  = date('W');
	$year  = date('Y');
	$month = date('m');
	$week_query   = new WP_Query( 'post_type=question&qa_tag='.$tag.'&year=' . $year . '&w=' . $week );
	$month_query  = new WP_Query( 'post_type=question&qa_tag='.$tag.'&year=' . $year . '&monthnum=' . $month );

	return sprintf(__('%s today, %s this week, %s this month.',ET_DOMAIN),
						$today_query->found_posts,
						$week_query->found_posts,
						$month_query->found_posts
		);
}
/**
*
* MODAL UPLOAD IMAGES TINYMCE
* @param null
* @author ThaiNT
* @since 1.0
*
**/
function qa_upload_images_modal(){
	?>
	<div class="modal fade modal-submit-questions" id="upload_images" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
					<h4 class="modal-title modal-title-sign-in" id="myModalLabel"><?php _e("Insert Images", ET_DOMAIN) ?></h4>
				</div>
				<div class="modal-body">
					<div class="upload-location" id="images_upload_container">
						<span class="title"><?php _e( 'Upload an image', ET_DOMAIN ) ?></span>
						<div class="input-file">
							<?php
								$disabled = !is_user_logged_in() ? 'disabled="disabled" style="opacity:0.7;"' : '' ;
								$file_text = is_user_logged_in() ? __("No file chosen.",ET_DOMAIN) : __("Please log in to use this function.",ET_DOMAIN);
							?>                     
							<input type="button" <?php echo $disabled ?> value="<?php _e("Browse",ET_DOMAIN);?>" class="bg-button-file button" id="images_upload_browse_button">                        
							<span class="filename"><?php echo $file_text; ?></span>
							<span class="et_ajaxnonce" id="<?php echo wp_create_nonce( 'et_upload_images' ); ?>"></span> 
						</div>
					</div> 
	                <div class="upload-url">
	                    <span class="title"><?php _e( 'Add an image via URL', ET_DOMAIN ) ?></span>
	                    <div class="input-url">
	                      	<input type="text" placeholder="https://www.images.jpg" id="external_link" class="form-control">
		                    <div class="button-event">
		                  		<button type="button" id="insert" class="btn"><?php _e( 'Insert', ET_DOMAIN ) ?></button>
		                    	<a href="javascript:void(0)" class="btn-cancel collapse" data-dismiss="modal"><?php _e( 'Cancel', ET_DOMAIN ) ?></a>
		                    </div>
	                    </div>                  
	                </div>					 
				</div>
			</div>
		</div>
	</div>
	<?php
}
/**
*
* MODAL LOGIN / REGISTER
* @param null
* @author ThaiNT
* @since 1.0
*
**/
function qa_reset_password_modal(){
	get_template_part( 'template/modal', 'forgotpass' );
}
/**
*
* MODAL LOGIN / REGISTER
* @param null
* @author ThaiNT
* @since 1.0
*
**/
function qa_login_register_modal(){
	get_template_part('template/modal' , 'authentication' );
}
/**
*
* MODAL EDIT PROFILE
* @param null
* @author ThaiNT
* @since 1.0
*
**/
function qa_edit_profile_modal(){
	get_template_part( 'template/modal', 'profile' );
}
/**
*
* MODAL INSERT QUESTION
* @action 2
* @author ThaiNT
* @since 1.0
*
**/
function qa_insert_question_modal(){
	get_template_part( 'template/modal', 'question' );
}
/**
 *
 * COUNT ANSWERS OF QUESTION
 * @param  int $question_id
 * @author ThaiNT
 * @since v1.0
 *
 **/
function et_count_answer($id){
	$childs = get_children( array(
 			'post_type' 	=> 'answer',
 			'post_parent'	=> $id,
		) );
	return count($childs);
}
/**
 *
 * CONVERT POST_DATE INTO  HUMAN TIME
 * @param  int $timestamp
 * @author ToanNM
 * @since v1.0
 *
 **/
function et_the_time( $from ){	
	// 
	if ( time() - $from > (7 * 24 * 60 * 60) ){
		return sprintf( __('on %s', ET_DOMAIN), date_i18n( get_option('date_format'), $from, true ) );
	} else {
		return et_human_time_diff( $from ) .' '.__('ago',ET_DOMAIN);
	}
}

function et_number_based($zero, $single, $plural, $num){
	if ( (int)$num <= 0 ){
		return $zero;
	} else if ( (int)$num == 1 ){
		return $single;
	} else if ( (int)$num > 1 ){
		return $plural;
	}
}

function et_selected( $selected, $current, $echo = true){
	if ( $selected == $current ){
		$return = 'selected="selected"';
	} else {
		$return = '';
	}

	if ( $echo ) echo $return;
	
	return $return;
}

/**
 * Determines the difference between two timestamps.
 *
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "2 days".
 *
 * @since 1.5.0
 *
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string Human readable time difference.
 */
function et_human_time_diff( $from, $to = '' ) {
	if ( empty( $to ) )
		$to = current_time('timestamp');

	$diff = (int) abs( $to - $from );

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 )
			$mins = 1;
		/* translators: min=minute */
		$since = sprintf( et_number_based( __('%s min', ET_DOMAIN), __('%s min', ET_DOMAIN) , __('%s mins', ET_DOMAIN), $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 )
			$hours = 1;
		$since = sprintf( et_number_based( __('%s hour', ET_DOMAIN), __('%s hour', ET_DOMAIN), __('%s hours', ET_DOMAIN), $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 )
			$days = 1;
		$since = sprintf( et_number_based( __('%s day', ET_DOMAIN), __('%s day', ET_DOMAIN), __('%s days', ET_DOMAIN), $days ), $days );
	} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 )
			$weeks = 1;
		$since = sprintf( et_number_based( __('%s week', ET_DOMAIN), __('%s week', ET_DOMAIN), __('%s weeks', ET_DOMAIN), $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
		if ( $months <= 1 )
			$months = 1;
		$since = sprintf( et_number_based( __('%s month', ET_DOMAIN), __('%s month', ET_DOMAIN), __('%s months', ET_DOMAIN), $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 )
			$years = 1;
		$since = sprintf( et_number_based( __('%s year', ET_DOMAIN), __('%s year', ET_DOMAIN), __('%s years', ET_DOMAIN), $years ), $years );
	}

	return $since;
}

/**
 * Get elapsed time string
 * @param int $timestamp
 *
 */
function time_elapsed_string($ptime){
	$etime = time() - $ptime;

	if ($etime < 1){
		return '0 seconds';
	}

	$a = array( 12 * 30 * 24 * 60 * 60  =>  __('year', ET_DOMAIN),
				30 * 24 * 60 * 60       =>  __('month', ET_DOMAIN),
				24 * 60 * 60            =>  __('day', ET_DOMAIN),
				60 * 60                 =>  __('hour', ET_DOMAIN),
				60                      =>  __('minute', ET_DOMAIN),
				1                       =>  __('second', ET_DOMAIN)
				);

	if ( $etime > (7 * 24 * 60 * 60) ){
		return sprintf(' on %s at %s', date_i18n( get_option('date_format'), $ptime ), date_i18n( get_option( 'time_format' ) ) );
	}

	foreach ($a as $secs => $str)
	{
		$d = $etime / $secs;
		if ($d >= 1)
		{
			$r = round($d);
			return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
		}
	}
}

/**
 *
 * Get the login/register page link. If the login/register page doesn't exist, it will create a new page.
 * @param int $page_type: login or register
 * @return $link
 * @author James
 * @version 1.0
 * @copyright enginethemes.com team
 * @package white panda
 *
 **/
function et_get_page_link( $pages , $params = array() , $create = true ){
	//'page_template'
	

	$page_args	=	array(
			'post_title'        => '',
			'post_content'  => __( 'Please fill out the form below ' , ET_DOMAIN ),
			'post_type'         => 'page',
			'post_status'       => 'publish'
	) ;

	if(is_array($pages)) {
		$page_type	=	$pages['page_type'];
		$page_args	=	wp_parse_args( $pages, $page_args);
	} else {
		$page_type	=	$pages;
		$page_args['post_title'] = $page_type;
	}

	$link	=	apply_filters( 'et_pre_filter_get_page_link' , '' , $page_type ); 
	if( $link ) {
		$return = add_query_arg( $params , $link );
		return $return ;
	}

	// find post template
	$pages = get_pages( array( 'meta_key' => '_wp_page_template' ,'meta_value' => 'page-'.$page_type.'.php', 'numberposts' => 1 ) );
	if ( empty($pages) || !is_array($pages) ){
		if(! $create ) return false; 
		$id = wp_insert_post($page_args);
		
		if ( $id ){
			update_post_meta( $id , '_wp_page_template' , 'page-'.$page_type.'.php' );
		}
	}
	else {
		$page = array_shift( $pages );
		$id = $page->ID;
	}
	
	$return = get_permalink( $id );
	/**
	 * update transient page link 
	*/
	//set_transient( 'page-'.$page_type.'.php', $return , 3600*24*30 );
	update_option( 'page-'.$page_type.'.php', $return );
	
	if ( !empty( $params ) && is_array( $params ) ){
		$return = add_query_arg( $params , $return );
	}
	
	return apply_filters('et_get_page_link', $return, $page_type, $params);
}

/**
 * Return ForumEngine search link
 * @param string $query
 * @return string $link
 */
function qa_search_link($query){
	global $wp_rewrite;

	if ( $wp_rewrite->using_permalinks() ){
		$search_slug = apply_filters( 'search_question_slug', 'search-questions' );
		return home_url( '/' . $search_slug . '/' . urlencode( $query ) );
	} else {
		return add_query_arg( array(
			'keyword' 		=> urlencode( $query )
		), home_url() );
	}
}

function qa_comment_form ( $post, $type = 'question' ) {
	global $current_user;
	/**
     * check privileges
    */
    $privi  =   qa_get_privileges();
    $comment_prover     =   '';

    if( !qa_user_can('add_comment') && isset( $privi->add_comment ) ) {
        $content          = sprintf(__("You must have %d points to add comment.", ET_DOMAIN), $privi->add_comment )   ;
        $comment_prover =   'data-container="body" data-toggle="popover" data-content="'. $content .'"';
    }
?>
	<a <?php echo $comment_prover; ?> class="add-comment" data-id="<?php echo $post->ID ?>" href="javascript:void(0)"><?php _e("Add Comment",ET_DOMAIN) ?></a>
    <div class="clearfix"></div>
    <form class="child-reply" method="POST">
        <input type="hidden" name="qa_nonce"        value="<?php echo wp_create_nonce( 'insert_comment' );?>" />
        <input type="hidden" name="comment_post_ID" value="<?php echo $post->ID ?>" />
        <input type="hidden" name="comment_type"    value="<?php echo $type ?>" />
        <input type="hidden" name="user_id"         value="<?php echo $current_user->ID ?>" />
        <div id="editor_wrap_<?php echo $post->ID ?>" class="child-answer-wrap collapse">
            <div class="wp-editor-container">
                <textarea name="post_content" id="insert_answer_<?php echo $post->ID ?>"></textarea>
            </div>
            <div class="row submit-wrapper">
                <div class="col-md-3 col-xs-3">
                    <button id="submit_reply" class="btn-submit">
                        <?php _e("Add comment",ET_DOMAIN) ?>
                    </button>
                </div>
                <div class="col-md-9 col-xs-9">
                    <a href="javascript:void(0)" class="hide-comment"><?php _e("Cancel",ET_DOMAIN) ?></a>
                </div>
            </div>
        </div>
    </form><!-- END SUBMIT FORM COMMENT -->  

<?php

}

/**
 * echo tos text in form comment, post answer
 * @author Dakachi
*/
function qa_tos () {
	printf(__('By posting your answer, you agree to the <a target="_blank" href="%s">privacy policy</a> and <a target="_blank" href="%s">terms of service.</a>', ET_DOMAIN), et_get_page_link('term'), et_get_page_link('term'));
}

/**
*
* MODAL Report
* @param null
* @author tambh
* @since 1.0
*
**/
function qa_report_modal(){
	get_template_part('template/modal' , 'report' );
}
?>