<?php

/* Template name: Login
 * Description: Pagina de Login. */
 ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo get_bloginfo( 'name' ); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<?php //echo get_template_part('parts/header_css'); ?>
	<?php //echo get_template_part('parts/header_js'); ?>
	<?php wp_head();?>
</head>
<!-- ADD THE CLASS sidedar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body class="hold-transition login-page">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- =============================================== -->
<!-- section -->
    <section class="aa_loginForm">
        <?php 
            global $user_login;
            // In case of a login error.
            if ( isset( $_GET['login'] ) && $_GET['login'] == 'failed' ) : ?>
    	            <div class="aa_error">
    		            <p><?php _e( 'FAILED: Try again!', 'AA' ); ?></p>
    	            </div>
            <?php 
                endif;
            // If user is already logged in.
            if ( is_user_logged_in() ) : ?>

                <div class="aa_logout"> 
                    
                    <?php 
                        _e( 'Hello', 'AA' ); 
                        echo $user_login; 
                    ?>
                    
                    </br>
                    
                    <?php _e( 'You are already logged in.', 'AA' ); ?>

                </div>

                <a id="wp-submit" href="<?php echo wp_logout_url(); ?>" title="Logout">
                    <?php _e( 'Logout', 'AA' ); ?>
                </a>

            <?php 
                // If user is not logged in.
                else: 
                
                    // Login form arguments.
                    $args = array(
                        'echo'           => true,
                        'redirect'       => home_url( '/wp-admin/' ), 
                        'form_id'        => 'loginform',
                        'label_username' => __( 'Username' ),
                        'label_password' => __( 'Password' ),
                        'label_remember' => __( 'Remember Me' ),
                        'label_log_in'   => __( 'Log In' ),
                        'id_username'    => 'user_login',
                        'id_password'    => 'user_pass',
                        'id_remember'    => 'rememberme',
                        'id_submit'      => 'wp-submit',
                        'remember'       => true,
                        'value_username' => NULL,
                        'value_remember' => true
                    ); 
                    
                    // Calling the login form.
                    wp_login_form( $args );
                endif;
        ?> 

	</section>
	<!-- /section -->

<!-- /.login-box -->

<?php get_footer(); ?>

