<div class="modal fade modal-submit-questions" id="login_register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
 	<div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
	        <h4 class="modal-title modal-title-sign-in" id="myModalLabel">Sign In</h4>
	      </div>
	      <div class="modal-body">
	        <form id="signin_form" class="form_modal_style">
	        	<label><?php _e("Username or Email", ET_DOMAIN) ?></label>
	        	<input type="text" class="email_user"  name="username" id="username" />
	            <label><?php _e("Password", ET_DOMAIN) ?></label>
	        	<input type="password" class="password_user" id="password" name="password">
	            <a href="javascript:void(0)" class="link_forgot_pass"><?php _e("Forgot password", ET_DOMAIN) ?></a>
	            <div class="clearfix"></div>
	            <input type="submit" name="submit" value="<?php _e("Sign in", ET_DOMAIN) ?>" class="btn-submit">
	            <a href="javascript:void(0)" class="link_sign_up"><?php _e("Sign up", ET_DOMAIN) ?></a>
	            <ul class="social-icon clearfix"> 
				<!-- google plus login -->
				<?php if(ae_get_option('gplus_login', false)){?>
							<li class="gp"><a id="signinButton" href="#" class="sc-icon color-google" ><i class="fa fa-google-plus-square"></i></a></li>
				<?php } ?>
				<!-- twitter plus login -->
				<?php if(ae_get_option('twitter_login', false)){?>
					<li class="tw"><a href="<?php echo add_query_arg('action', 'twitterauth', home_url()) ?>" class="sc-icon color-twitter" ><i class="fa fa-twitter-square"></i></a></li>
				<?php } ?>
				<!-- facebook plus login -->
				<?php if(ae_get_option('facebook_login', false)){?>
					<li class="fb"><a href="#" id="facebook_auth_btn" class="sc-icon color-facebook" ><i class="fa fa-facebook-square"></i></a></li>
				<?php } ?>

			</ul>    
	        </form>
	        <form id="signup_form" class="form_modal_style">
	        	<label><?php _e("Username", ET_DOMAIN) ?></label>
	        	<input type="text" class="name_user" name="username" id="username" />
	            <label><?php _e("Email", ET_DOMAIN) ?></label>
	        	<input type="text" class="email_user" name="email" id="email" />
	            <label><?php _e("Password", ET_DOMAIN) ?></label>
	        	<input type="password" class="password_user_signup" id="password1" name="password" />
	            <label><?php _e("Retype Password", ET_DOMAIN) ?></label>
	        	<input type="password" class="repeat_password_user_signup" id="re_password" name="re_password" />
	        	<?php do_action( 'qa_after_signup_form' ); ?>
	            <div class="clearfix"></div>
	            <input type="submit" name="submit" value="<?php _e("Sign up", ET_DOMAIN) ?>" class="btn-submit">
	            <a href="javascript:void(0)" class="link_sign_in"><?php _e("Sign in", ET_DOMAIN) ?></a>
	            <div class="clearfix"></div>
	            <p class="policy-sign-up"><?php _e('By clicking "Sign up" you indicate that you have read and agree to the <a target="_blank" href="'.et_get_page_link('term').'">privacy policy</a> and <a target="_blank" href="'.et_get_page_link('term').'">terms of service.</a>', ET_DOMAIN) ?></p>
	        </form>

	        <form id="forgotpass_form" class="form_modal_style collapse">
	        	<label><?php _e("Enter your email here", ET_DOMAIN) ?></label>
	        	<input type="text" class="name_user" name="email" id="email" />
	        	<input type="submit" name="submit" value="<?php _e("Send", ET_DOMAIN) ?>" class="btn-submit">
	        </form>	 
	      </div>
	    </div>
  </div>
</div>