	<?php
		if(is_singular( 'question' )){
			qa_mobile_answer_template();
			qa_mobile_comment_template();
		}
		qa_tag_template();
		echo ae_get_option('google_analytics');
	?>    
    <?php wp_footer(); ?>
	</body>
</html>