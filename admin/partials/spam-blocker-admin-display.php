<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       awais300@gmail.com
 * @since      2.0.0
 *
 * @package    Spam_Blocker
 * @subpackage Spam_Blocker/admin/partials
 */

?>

<div class="wrap">
	<div id="awp-spam-blocker" class="awp-spam-blocker container">
		<section class="header">
			<?php if ( ! empty( $message ) ) : ?>
				<div class="notice notice-success is-dismissible spam-blocker-message">
					<p><?php echo esc_html( $message ); ?></p>
				</div>
			<?php endif; ?>
		</section>

		<section class="content">
			<form enctype="application/x-www-form-urlencoded" method="post" id="honeypot-form" name="honeypot-form">
				<h1>Spam Blocker Settings</h1>
				<br/>

				<input type="checkbox" name="honeypot-comments" id="honeypot-comments"
				<?php
				if ( ( isset( $post['honeypot-comments'] ) && 'on' === $post['honeypot-comments'] ) || true === $honeypot_options['honeypot-comments'] ) :
					?>
				 checked="checked"<?php endif; ?>> <?php echo esc_html__( 'Enable Spam Protection for WordPress Comments', 'spam-blocker' ) ?> <br/>

				<input type="checkbox" name="honeypot-login" id="honeypot-login"
				<?php
				if ( ( isset( $post['honeypot-login'] ) && 'on' === $post['honeypot-login'] ) || true === $honeypot_options['honeypot-login'] ) :
					?>
					checked="checked"<?php endif; ?>> <?php echo esc_html__( 'Enable Spam Protection on WordPress Login Page', 'spam-blocker' ) ?><br/>

				<input type="checkbox" name="honeypot-register" id="honeypot-register"
				<?php
				if ( ( isset( $post['honeypot-register'] ) && 'on' === $post['honeypot-register'] ) || true === $honeypot_options['honeypot-register'] ) :
					?>
					checked="checked"<?php endif; ?>> <?php echo esc_html__( 'Enable Spam Protection on WordPress Registration Page', 'spam-blocker' ) ?><br/>

				<?php if (class_exists('UM_Functions')): ?>
				<input type="checkbox" name="honeypot-um-register" id="honeypot-um-register"
					<?php if ( ( isset( $post['honeypot-um-register'] ) && 'on' === $post['honeypot-um-register'] ) || true === $honeypot_options['honeypot-um-register'] ) :
						?>
						checked="checked"<?php endif; ?>> <?php echo esc_html__( 'Enable Spam Protection on Ultimate Memeber Registration Page', 'spam-blocker' ) ?><br/>
				<?php endif; ?>
				<input type="hidden" name="form" value="honeypot-form" />
				<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
				<?php wp_nonce_field(); ?>
			</form>
		</section>
	</div>
</div>
