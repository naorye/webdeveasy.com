<?php
    global $post;
?>
<li>
    <div class="col-xs-2">
        <a href="<?php echo get_author_posts_url( $post->post_author ); ?>" class="profile-avatar">
            <?php echo et_get_avatar( $post->post_author, 65, array('class' => 'avatar img-responsive','alt' => '') ); ?>
        </a>
    </div>
    <div class="col-xs-10">
        <div class="blog-content">
            <span class="tag"><?php the_category( '-' ); ?></span><span class="cmt"><i class="fa fa-comments"></i><?php comments_number(); ?></span>
            <h2 class="title-blog"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h2>
            <?php
                if(is_single()){
                    the_content();
                } else {
                    the_excerpt();
            ?>
            <a href="<?php the_permalink(); ?>" class="read-more">
                <?php _e("READ MORE",ET_DOMAIN) ?><i class="fa fa-arrow-circle-o-right"></i>
            </a>
            <?php } ?>
        </div>
    </div>
</li>