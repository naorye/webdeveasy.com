<?php
/**
 * Template: BLOG LISTING AUTHOR
 * version 1.0
 * @author: ThaiNT
 **/
	et_get_mobile_header();
global $wp_query, $wp_rewrite, $current_user;

$user = get_user_by( 'id', get_query_var( 'author' ) );
$user = QA_Member::convert($user);    
?>
<!-- CONTAINER -->
<div class="wrapper-mobile">
	<!-- TOP BAR -->
	<section class="profile-user-wrapper">
    	<div class="container">
            <div class="row">
                <div class="col-md-3 col-xs-3 padding-right-0">
                    <a href="javascript:void(0)" class="profile-avatar">
                        <?php echo et_get_avatar( $user->ID, 65); ?>
                    </a>
                </div>
                <div class="col-md-9 col-xs-9">
                    <div class="profile-wrapper">
                    	<span class="user-name-profile"><?php echo $user->display_name;  ?></span>
                    	<span class="address-profile">
                            <?php if( $user->user_location ) { 
                                echo '<i class="fa fa-map-marker"></i>' .$user->user_location  ;
                            }else { 
                                echo '<i class="fa fa-globe"></i>' . __("Earth", ET_DOMAIN)  ;
                            } ?>
                        </span>
                        <span class="email-profile"><i class="fa fa-envelope"></i> <?php echo $user->show_email == "on" ? $user->user_email : __('Email is hidden.', ET_DOMAIN); ?></span>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-8 col-xs-8 padding-right-0">
                	<div class="list-bag-profile-wrapper">
                    	<!-- <span class="badges-profile">Professor</span> -->
                        <?php qa_user_badge($user->ID) ?>
                        <span class="point-profile"><span><?php echo qa_get_user_point($user->ID) ? qa_get_user_point($user->ID) : 0 ?><i class="fa fa-star"></i></span><?php _e("points", ET_DOMAIN) ?></span>
                    </div>
                </div>
                <div class="col-md-4 col-xs-4 padding-left-0">
                	 <div class="list-bag-profile-wrapper text-right">
                         <span class="question-profile"><?php echo et_count_user_posts($user->ID) ?><i class="fa fa-question-circle"></i></span>
                         <span class="answers-profile"><?php echo et_count_user_posts($user->ID, "answer") ?><i class="fa fa-comments"></i></span>
                     </div>
                </div>
            </div>
        </div>
    </section>
    <!-- TOP BAR / END -->
    
    <!-- MIDDLE BAR -->
    <section class="middle-bar bg-white">
    	<div class="container">
            <div class="row">
            	<div class="col-md-12">
                	<ul class="menu-middle-bar">
                        <li class="<?php if(!isset($_GET['type'])) echo 'active'; ?>" >
                            <a href="<?php echo get_author_posts_url($user->ID); ?>"><?php _e('Questions',ET_DOMAIN) ?></a>
                        </li>
                        <li class="<?php if(isset($_GET['type']) && $_GET['type'] == "answer") echo 'active'; ?>" >
                            <a href="<?php echo add_query_arg(array('type'=>'answer')); ?>"><?php _e('Answers',ET_DOMAIN) ?></a>
                        </li>
                        <li  class="<?php if(isset($_GET['type']) && $_GET['type'] == "post") echo 'active'; ?>">
                            <a href="<?php echo add_query_arg(array('type'=>'post')); ?>"><?php _e('Posts',ET_DOMAIN) ?></a>
                        </li>                        
                    </ul>
                </div>
    		</div>
        </div>
        <div class="form-search-wrapper">
        	<form id="form-search" class="collapse">
            	<a href="javascript:void(0)" class="clear-text-search"><i class="fa fa-times-circle"></i></a>
                <a href="javascript:void(0)" class="close-form-search">Cancel</a>
            	<input type="text" name="" id="" placeholder="Enter keyword" class="form-input-search">
            </form>
        </div>
    </section>
    <!-- MIDDLE BAR / END -->
    
    <!-- LIST QUESTION -->
    <section class="list-question-wrapper">
    	<div class="container">
            <div class="row">
            	<div class="col-md-12">
                	<ul class="list-question <?php if(isset($_GET['type']) && $_GET['type'] == "post") echo 'list-posts'; ?>">
                    <?php
                        $paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

                        $type = isset($_GET['type']) ? $_GET['type'] : 'question';

                        $query = QA_Questions::get_questions(array(
                                'post_type' => $type,
                                'paged'     => $paged,
                                'author'    => $user->ID
                            ));

                        if($query->have_posts()){
                            while($query->have_posts()){
                                $query->the_post();
                                get_template_part( 'mobile/template/'.$type, 'loop' );
                            }
                        }  
                        wp_reset_query();
                        
                    ?>                    
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- LIST QUESTION / END -->
    <section class="list-pagination-wrapper">
        <?php 
            qa_template_paginations($query, $paged);
        ?>
    </section>
    <!-- PAGINATIONS QUESTION / END -->
</div>
<!-- CONTAINER / END -->
<?php
	et_get_mobile_footer();
?>