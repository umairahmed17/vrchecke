<?php
/**
 * Template part for displaying a post
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

vrchecke()->print_styles( 'vrchecke-login' );
$login = ( isset( $_GET['login'] ) ) ? $_GET['login'] : 0;
if ( is_user_logged_in() ) : ?>
<p>You are already logged in.</p>
	<?php
endif;
if ( ! is_user_logged_in() ) :
	?>



<div class="error-div" style="<?php echo ( $login ) ? 'display:flex;' : ''; ?>">
	<?php
	if ( $login === 'failed' ) {
		echo '<p class="login-msg">ERROR:Invalid username and/or password.</p>';
	} elseif ( $login === 'empty-username' ) {
		echo '<p class="login-msg">ERROR:Username and/or Password is empty.</p>';
	} elseif ( $login === 'empty-username' ) {
		echo '<p class="login-msg">ERROR:Username and/or Password is empty.</p>';
	} elseif ( $login === 'invalid' ) {
		echo '<p class="login-msg">ERROR:Invalid username and/or password.</p>';
	} elseif ( $login === 'false' ) {
		echo '<p class="login-msg">ERROR:You are logged out.</p>';
	}
	?>
	<!-- <a href="javascript:void(0)" class="error-hide">Hide</a> -->
</div>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-login' ); ?>>


	<div class="login-container">

		<div class="login-btn-container">
			<h1 class="login-title"><?php the_title(); ?></h1>
			<?php echo do_shortcode( '[google-login]' ); ?>
		</div>
		<div class="divider-container">
			<hr class="divider">
			<p class="divider__content">OR</p>
			<hr class="divider">
		</div>
		<div class="login__form">
			<?php wp_login_form(); ?>
			<a class="form__forget-password" href="<?php echo esc_url( wp_lostpassword_url() ); ?>">Forget your
				password</a>
		</div>
	</div>
	<div class="info-container">

	</div>
	<?php
	if ( is_search() ) {
		get_template_part( 'template-parts/content/entry_summary', get_post_type() );
	} else {
		get_template_part( 'template-parts/content/entry_content', get_post_type() );
	}
	get_template_part( 'template-parts/content/entry_footer', get_post_type() );
	?>
</article>

<?php endif; ?>

<script type="text/javascript">
(function($) {
	$(document).ready(() => {
		$('#user_login', '#loginform').attr('placeholder', 'Username');
		$('#user_pass', '#loginform').attr('placeholder', 'Password');
	});
}(jQuery))
</script>
