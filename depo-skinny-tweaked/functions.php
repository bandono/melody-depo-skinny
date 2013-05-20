<?php
/**
 * @package WordPress
 * @subpackage DePo_Skinny_Tweaked
 */

/**
 * Make theme available for translation
 * Translations can be filed in the /languages/ directory
 */
load_theme_textdomain( 'depo-skinny-tweaked', TEMPLATEPATH . '/languages' );

$locale = get_locale();
$locale_file = TEMPLATEPATH . "/languages/$locale.php";
if ( is_readable( $locale_file ) )
	require_once( $locale_file );

/**
 * Theme default initial values
 */

add_action( 'after_setup_theme', 'depotwk_setup' );

function depotwk_setup() {
	$depotwk_preset = array();
	$depotwk_preset['post-stats-desc-page'] = get_bloginfo('wpurl').'/about-stats';
	$depotwk_preset['post-stats-href-title'] = 'to-date stats retrieved one hour ago';
	return $depotwk_preset;
}

/**
 * Admin Theme Page.
 * 
 * @since Depo Skinny Tweaked 2.2
 */
add_action( 'admin_menu', 'depotwk_add_theme_page' );

function depotwk_add_theme_page() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == basename( __FILE__) ) {
		if ( isset( $_POST['action'] ) && 'save' == $_POST['action'] ) {
			check_admin_referer( 'depotwk-stats' );
			$options = depotwk_setup();
			if ( '' != $_POST['post-stats-desc-page'] ) {
				update_option( 'depotwk-post-stats-desc-page', esc_attr( $_POST['post-stats-desc-page'] ) );
			}
			else {
				update_option( 'depotwk-post-stats-desc-page', $options['post-stats-desc-page'] );
			}

			wp_redirect( "themes.php?page=functions.php&saved=true" );
			die;
		}
	}
	add_theme_page( __( 'Theme Options', 'depo-skinny-tweaked' ), __( 'Theme Options', 'depo-skinny-tweaked' ), 'edit_theme_options', basename( __FILE__), 'depotwk_theme_page' );
}

function depotwk_theme_page() {
	if ( isset( $_REQUEST['saved'] ) ) {
		echo '<div id="message" class="updated fade"><p><strong>'.__( 'Options saved.', 'depo-skinny-tweaked' ).'</strong></p><p><strong>Stats desc. :  </strong>'. get_option( 'depotwk-post-stats-desc-page' ) .'</p>';
		echo '<p><strong>href title desc. :  </strong>'. get_option( 'depotwk-post-stats-href-title' ) .'</p></div>';
	}
	?>
	<div class='wrap'>
	<form method="post" action="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>">
		<?php wp_nonce_field( 'depotwk-stats' ); ?>
		<p><label for="post-stats-desc-page"><?php _e( 'Page URL hyperlinked in post view count:', 'depo-skinny-tweaked' ); ?></label>
		<input type="text" name="post-stats-desc-page" value="<?php echo get_option( 'depotwk-post-stats-desc-page' ); ?>" id="post-stats-desc-page" />
		<p><label for="post-stats-href-title"><?php _e( 'Title to use in < a href title=...', 'depo-skinny-tweaked' ); ?></label>
		<input type="text" name="post-stats-href-title" value="<?php echo get_option( 'depotwk-post-stats-href-title' ); ?>" id="post-stats-href-title" />
		<br /><small><?php _e( 'Leaving these fields blank will insert default values' ); ?></small></p>
		<p><input type="hidden" name="action" value="save" /> <input type="submit" name="submit" value="<?php esc_attr_e( 'Submit',  'depo-skinny-tweaked' ); ?>" id="submit" /></p>

	</form>
	</div>
<?php }

if ( ! function_exists( 'depotwk_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * Modified from twentytwelve_comment() in Twenty Twelve 1.1 theme
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Depo Skinny Tweaked 2.2
 */
function depotwk_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'depo-skinny-tweaked' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'depo-skinny-tweaked' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					echo get_avatar( $comment, 44 );
					printf( '<cite class="fn">%1$s %2$s</cite>',
						get_comment_author_link(),
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span> ' . __( 'Post author', 'depo-skinny-tweaked' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'depo-skinny-tweaked' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'depo-skinny-tweaked' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'depo-skinny-tweaked' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'depo-skinny-tweaked' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

/**
 * Additional options for wp-stats-view-counter integration
 * to retrieve Jetpack stats and update locally every 1 hour
 * 
 * @since Depo Skinny Tweaked 2.2
 */
add_filter( 'view_counter_transient_expiration', 'depotwk_view_counter_transient_expiration', 10, 1 );

function depotwk_view_counter_transient_expiration( $hours ) {

    return 1; // time in hours

}
 
