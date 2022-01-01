<?php
/**
 * Template part for displaying the Google Signin button
 *
 * @package vrchecke
 */

namespace VRCHECKE\VRCHECKE;

?>


<div id="gSignInWrapper">
	<!-- <span class="label">Sign in with:</span> -->
	<a href="<?php echo $args['login_url']; ?>">
		<div id="customBtn" class="customGPlusSignIn">
			<span class="icon"></span>&nbsp;&nbsp;<span class="buttonText">Google</span>
		</div>
	</a>
</div>
