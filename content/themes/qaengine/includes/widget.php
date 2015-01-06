<?php
class QA_Hot_Questions_Widget extends WP_Widget
{
	
	function __construct() {
		$widget_ops = array('classname' => 'widget', 'description' => __( 'Drag this widget to any sidebars to display a list of hot questions.',ET_DOMAIN) );
		$control_ops = array('width' => 250, 'height' => 100);
		parent::__construct('question_hot_widget', __('QA Latest Questions / Hot Question',ET_DOMAIN) , $widget_ops ,$control_ops );
	}

	function update ( $new_instance, $old_instance ) {
		if($new_instance['normal_question'] != $old_instance['normal_question'] || $new_instance['number'] != $old_instance['number']){
			delete_transient( 'hot_questions_query' );
			delete_transient( 'latest_questions_query' );
		}
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('HOT QUESTIONS',ET_DOMAIN) , 'number' => '8', 'date' => '', 'normal_question' => 0) );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of questions to display:', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('normal_question'); ?>"><?php _e('Latest questions (sort by date)', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('normal_question'); ?>" name="<?php echo $this->get_field_name('normal_question'); ?>" value="1" type="checkbox" <?php checked( $instance['normal_question'], 1 ); ?> value="<?php echo esc_attr( $instance['normal_question'] ); ?>" />
		</p>		
		<p>
			<label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Date range:', ET_DOMAIN) ?></label>
			<select id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>">
				<option <?php selected( $instance['date'], "all" ); ?> value="all"><?php _e('All days', ET_DOMAIN) ?></option>
				<option <?php selected( $instance['date'], "last7days" ); ?> value="last7days"><?php _e('Last 7 days', ET_DOMAIN) ?></option>
				<option <?php selected( $instance['date'], "last30days" ); ?> value="last30days"><?php _e('Last 30 days', ET_DOMAIN) ?></option>
			</select>
		</p>				
	<?php
	}

	function widget( $args, $instance ) {

		global $wpdb;
		if(!isset($instance['normal_question'])){

			if(get_transient( 'hot_questions_query' ) === false){
				$hour = 12;
				$today = strtotime("$hour:00:00");		
				$last7days = strtotime('-7 day', $today);
				$last30days = strtotime('-30 day', $today);

				if($instance['date'] == "last7days"){
					$custom = "AND post_date >= '".date("Y-m-d H:i:s", $last7days)."' AND post_date <= '".date("Y-m-d H:i:s", $today)."' ";
				} elseif ($instance['date'] == "last30days") {
					$custom = "AND post_date >= '".date("Y-m-d H:i:s", $last30days)."' AND post_date <= '".date("Y-m-d H:i:s", $today)."' ";
				} else {
					$custom = "";
				}

				$query ="
					SELECT * FROM $wpdb->posts as post
					WHERE post_status = 'publish' 
						AND post_type = 'question' 
					";
				$query .= $custom;	
				$query .="	ORDER BY comment_count DESC,post_date DESC 
					LIMIT ".$instance['number']." 
					";
				$questions = $wpdb->get_results($query);
				set_transient( 'hot_questions_query', $questions, apply_filters( 'qa_time_expired_transient', 24*60*60 ));						
			} else {
				$questions = get_transient( 'hot_questions_query' );
			}

		} else {

			if(get_transient( 'latest_questions_query' ) === false){
				$query ="
					SELECT * FROM $wpdb->posts as post
					WHERE post_status = 'publish' 
						  AND post_type = 'question' 					
					ORDER BY post_date DESC 
					LIMIT ".$instance['number']."
					";

			$questions = $wpdb->get_results($query);
			set_transient( 'latest_questions_query', $questions, apply_filters( 'qa_time_expired_transient', 24*60*60 ) );	

			} else {
				$questions = get_transient( 'latest_questions_query' );
			}				
		}
		// delete_transient( 'latest_questions_query' );
		// delete_transient( 'hot_questions_query' );
	?>
    <div class="widget widget-hot-questions">
        <h3><?php echo esc_attr($instance['title']) ?></h3>
        <ul>
			<?php
				foreach ($questions as $question) {
			?>
            <li>
                <a href="<?php echo get_permalink( $question->ID );?>">
                    <span class="topic-avatar">
                    	<?php echo et_get_avatar($question->post_author, 30) ?>
                    </span>
                    <span class="topic-title"><?php echo $question->post_title ?></span>
                </a>
            </li>
            <?php } ?>                                     
        </ul>
    </div><!-- END widget-related-tags -->  	
	<?php
	}
}//End Class Hot Questions

class QA_Statistic_Widget extends WP_Widget
{
	
	function __construct() {
		$widget_ops = array('classname' => 'widget', 'description' => __( 'Drag this widget to sidebar to display the statistic of website.',ET_DOMAIN) );
		$control_ops = array('width' => 250, 'height' => 100);
		parent::__construct('qa_statistic_widget', __('QA Statistics',ET_DOMAIN) , $widget_ops ,$control_ops );
	}

	function update ( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('STATISTICS WIDGET',ET_DOMAIN)) );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>				
	<?php
	}

	function widget( $args, $instance ) {
		$questions = wp_count_posts('question');
		$result    = count_users();
	?>
    <div class="widget widget-statistic">
    	<p class="questions-count">
    		<?php _e("Questions",ET_DOMAIN) ?><br>
    		<span><?php echo  $questions->publish; ?></span>
    	</p>
    	<p class="members-count">
    		<?php _e("Members",ET_DOMAIN) ?><br>
    		<span><?php echo $result['total_users']; ?></span>        		
    	</p>
    </div><!-- END widget-statistic -->
	<?php
	}
}
class QA_Tags_Widget extends WP_Widget
{
	
	function __construct() {
		$widget_ops = array('classname' => 'widget', 'description' => __( 'Drag this widget to sidebar to display the list of tags.',ET_DOMAIN) );
		$control_ops = array('width' => 250, 'height' => 100);
		parent::__construct('qa_tags_widget', __('QA Tags',ET_DOMAIN) , $widget_ops ,$control_ops );
	}

	function update ( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __('Tags Widget',ET_DOMAIN) , 'number' => '8') );
	?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of tag to display:', ET_DOMAIN) ?> </label>
			<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>				
	<?php
	}

	function widget( $args, $instance ) {
		$tags = get_terms( 'qa_tag', array(
			'hide_empty' => 0 , 
			'orderby' 	 => 'count',
			'order'		 => 'DESC',
			'number'	 => $instance['number']
			));
	?>
    <div class="widget widget-related-tags">
        <h3><?php echo esc_attr($instance['title']) ?></h3>
        <ul>
        	<?php
        		foreach ($tags as $tag) {
        	?>
            <li>
            	<a class="q-tag" href="<?php echo get_term_link( $tag, 'qa_tag' ); ?>"><?php echo $tag->name ?></a> x <?php echo $tag->count ?>
            </li>
            <?php } ?>
        </ul>
        <a href="<?php echo et_get_page_link('tags') ?>"><?php _e("See more tags") ?></a>
    </div><!-- END widget-related-tags -->    
	<?php
	}
}

/**
 * QA_Recent_Activity widget class
 *
 * @since 1.0
 */

class QA_Recent_Activity extends WP_Widget
{
	
	function __construct() {
		$widget_ops = array('classname' => 'widget', 'description' => __( 'Drag this widget to sidebar to display the list of user\'s activities.',ET_DOMAIN) );
		$control_ops = array('width' => 250, 'height' => 100);
		parent::__construct('qa_recent_activity', __('QA Recent Activities',ET_DOMAIN) , $widget_ops ,$control_ops );
	}

	function update ( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' , 'number' => '8') );
		
		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', ET_DOMAIN) ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of activities to display:', ET_DOMAIN) ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
			</p>				
		<?php
		
	}

	function widget( $args, $instance ) {
		global $user_ID;
		$param = array();

		if( !$user_ID ) return;
		
		if(isset($instance['number']) && $instance['number']) {
			$param['showposts']	=	$instance['number'];
		}
		
		?>
		<div class="widget widget-recent-activity">
			<?php if(esc_attr($instance['title']) != "" ){ ?>
				<h3><?php echo esc_attr($instance['title']) ?></h3>
			<?php }
			if(!get_transient( 'qa_changelog_'.$user_ID )) {
				ob_start();
				$content	=	qa_list_changelog($param);
				$content	=	ob_get_clean();
				set_transient( 'qa_changelog_'.$user_ID , $content, 7*24*3600 );
			} else {
				$content	=	get_transient( 'qa_changelog_'.$user_ID );
			}

			echo $content;
		?>
		</div><!-- END widget-recent-activities -->    

		<?php
	}
}