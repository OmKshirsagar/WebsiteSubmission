<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

add_action( 'after_setup_theme', 'lalita_background_setup' );
/**
 * Overwrite parent theme background defaults and registers support for WordPress features.
 *
 */
function lalita_background_setup() {
	add_theme_support( "custom-background",
		array(
			'default-color' 		 => '222222',
			'default-image'          => get_stylesheet_directory_uri().'/img/mainbg.gif',
			'default-repeat'         => 'repeat',
			'default-position-x'     => 'left',
			'default-position-y'     => 'top',
			'default-size'           => 'auto',
			'default-attachment'     => '',
			'wp-head-callback'       => '_custom_background_cb',
			'admin-head-callback'    => '',
			'admin-preview-callback' => ''
		)
	);
}

/**
 * Overwrite theme URL
 *
 */
function lalita_theme_uri_link() {
	return 'https://wpkoi.com/manasa-wpkoi-wordpress-theme/';
}

/**
 * Overwrite parent theme's blog header function
 *
 */
add_action( 'lalita_after_header', 'lalita_blog_header_image', 11 );
function lalita_blog_header_image() {

	if ( ( is_front_page() && is_home() ) || ( is_home() ) ) { 
		$blog_header_image 			=  lalita_get_setting( 'blog_header_image' ); 
		$blog_header_title 			=  lalita_get_setting( 'blog_header_title' ); 
		$blog_header_text 			=  lalita_get_setting( 'blog_header_text' ); 
		$blog_header_button_text 	=  lalita_get_setting( 'blog_header_button_text' ); 
		$blog_header_button_url 	=  lalita_get_setting( 'blog_header_button_url' ); 
		if ( $blog_header_image != '' ) { ?>
		<div class="page-header-image grid-parent page-header-blog">
			<div class="page-header-blog-inner">
				<div class="page-header-blog-content-h grid-container">
					<div class="page-header-blog-content">
					<?php if ( ( $blog_header_title != '' ) || ( $blog_header_text != '' ) ) { ?>
						<div class="page-header-blog-text">
							<?php if ( $blog_header_title != '' ) { ?>
                            <h2><?php echo wp_kses_post( $blog_header_title ); ?></h2>
                            <div class="clearfix"></div>
                            <?php } ?>
                            <?php if ( $blog_header_title != '' ) { ?>
                            <p><?php echo wp_kses_post( $blog_header_text ); ?></p>
                            <div class="clearfix"></div>
                            <?php } ?>
                        </div>
                        <div class="page-header-blog-button">
							<?php if ( $blog_header_button_text != '' ) { ?>
                            <a class="read-more button" href="<?php echo esc_url( $blog_header_button_url ); ?>"><?php echo esc_html( $blog_header_button_text ); ?></a>
                            <?php } ?>
                        </div>
					<?php } ?>
					</div>
                    <div class="page-header-blog-image">
                    	<img src="<?php echo esc_url($blog_header_image); ?>" />
                    </div>
				</div>
			</div>
		</div>
		<?php
		}
	}
}

if ( ! function_exists( 'manasa_remove_parent_dynamic_css' ) ) {
	add_action( 'init', 'manasa_remove_parent_dynamic_css' );
	/**
	 * The dynamic styles of the parent theme added inline to the parent stylesheet.
	 * For the customizer functions it is better to enqueue after the child theme stylesheet.
	 */
	function manasa_remove_parent_dynamic_css() {
		remove_action( 'wp_enqueue_scripts', 'lalita_enqueue_dynamic_css', 50 );
	}
}

if ( ! function_exists( 'manasa_enqueue_parent_dynamic_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'manasa_enqueue_parent_dynamic_css', 50 );
	/**
	 * Enqueue this CSS after the child stylesheet, not after the parent stylesheet.
	 *
	 */
	function manasa_enqueue_parent_dynamic_css() {
		$css = lalita_base_css() . lalita_font_css() . lalita_advanced_css() . lalita_spacing_css() . lalita_no_cache_dynamic_css();

		// escaped secure before in parent theme
		wp_add_inline_style( 'lalita-child', $css );
	}
}

// Extra cutomizer functions
if ( ! function_exists( 'manasa_customize_register' ) ) {
	add_action( 'customize_register', 'manasa_customize_register' );
	function manasa_customize_register( $wp_customize ) {
		
		// Add navigation extra button text
		$wp_customize->add_setting(
			'manasa_settings[nav_btn_text]',
			array(
				'default' => '',
				'type' => 'option',
				'sanitize_callback' => 'esc_html'
			)
		);

		$wp_customize->add_control(
			'manasa_settings[nav_btn_text]',
			array(
				'type' => 'text',
				'label' => __( 'Extra button text', 'manasa' ),
				'section' => 'lalita_layout_navigation',
				'settings' => 'manasa_settings[nav_btn_text]',
				'priority' => 25
			)
		);
		
		// Add navigation extra button url
		$wp_customize->add_setting(
			'manasa_settings[nav_btn_url]',
			array(
				'default' => '',
				'type' => 'option',
				'sanitize_callback' => 'esc_url'
			)
		);

		$wp_customize->add_control(
			'manasa_settings[nav_btn_url]',
			array(
				'type' => 'text',
				'label' => __( 'Extra button URL', 'manasa' ),
				'section' => 'lalita_layout_navigation',
				'settings' => 'manasa_settings[nav_btn_url]',
				'priority' => 25
			)
		);
		
		// Dotted divider function
		$wp_customize->add_setting(
			'manasa_settings[img_effect]',
			array(
				'default' => true,
				'type' => 'option',
				'sanitize_callback' => 'manasa_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'manasa_settings[img_effect]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Blog image effect', 'manasa' ),
				'section' => 'lalita_blog_section',
				'priority' => 29
			)
		);
		
		// Socials on footer
		$wp_customize->add_setting(
			'manasa_settings[socials_display_bottom]',
			array(
				'default' => false,
				'type' => 'option',
				'sanitize_callback' => 'manasa_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'manasa_settings[socials_display_bottom]',
			array(
				'type' => 'checkbox',
				'label' => __( 'Display on footer bar', 'manasa' ),
				'section' => 'lalita_socials_section'
			)
		);
		
	}
}

if ( ! function_exists( 'manasa_sanitize_checkbox' ) ) {
	/**
	 * Sanitize checkbox values.
	 *
	 */
	function manasa_sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}

if ( ! function_exists( 'manasa_navigation_button' ) ) {
	add_filter( 'wp_nav_menu_items', 'manasa_navigation_button', 11, 2 );
	/**
	 * Add the extra button to the navigation.
	 *
	 */
	function manasa_navigation_button( $nav, $args ) {
		// Get Customizer settings
		$manasa_settings = get_option( 'manasa_settings' );
		
		// If our primary menu is set, add the extra button.
		if ( ( $args->theme_location == 'primary' ) && ( $manasa_settings['nav_btn_url'] != '' ) ) {
			return $nav . '<li class="wpkoi-nav-btn-h"><a class="wpkoi-nav-btn button" href="' . esc_url( $manasa_settings['nav_btn_url'] ) . '">' . esc_html( $manasa_settings['nav_btn_text'] ) . '</a></li>';
		}
		
	    return $nav;
	}
}

if ( ! function_exists( 'manasa_body_classes' ) ) {
	add_filter( 'body_class', 'manasa_body_classes' );
	/**
	 * Adds custom classes to the array of body classes.
	 *
	 */
	function manasa_body_classes( $classes ) {
		// Get Customizer settings
		$manasa_settings = get_option( 'manasa_settings' );
		
		// Return if dotted divider function is not exist
		if ( ! isset( $manasa_settings['img_effect'] ) ) {
			return $classes;
		}
		
		// Dotted divider function
		if ( $manasa_settings['img_effect'] == true ) {
			$classes[] = 'manasa-img-effect';
		}
		
		return $classes;
	}
}

add_action( 'lalita_before_copyright', 'lalita_footer_bar', 15 );
/**
 * Rebuild our footer bar with social bar
 *
 */
function lalita_footer_bar() {
	// Get Customizer settings
	$manasa_settings = get_option( 'manasa_settings' );
	if ( ! isset( $manasa_settings['socials_display_bottom'] ) ) {
		$socials_display_bottom = false;
	} else {
		$socials_display_bottom = $manasa_settings['socials_display_bottom'];
	}
	
	if ( ( ! is_active_sidebar( 'footer-bar' ) ) && ( $socials_display_bottom != true ) ) {
		return;
	}
	?>
	<div class="footer-bar">
		<?php dynamic_sidebar( 'footer-bar' ); 
		// Add social bar to footer if checked
		if ( $socials_display_bottom == true ) {
			do_action( 'lalita_social_bar_action' );
		} ?>
	</div>
	<?php
}

if ( ! function_exists( 'manasa_footer_classes' ) ) {
	add_filter( 'lalita_footer_class', 'manasa_footer_classes' );
	/**
	 * Adds classes to the footer if footer bar is empty, but social bar is active.
	 *
	 */
	function manasa_footer_classes( $classes ) {
		// Get Customizer settings
		$manasa_settings = get_option( 'manasa_settings' );
		
		// Return if social bar function on footer is false or empty
		if ( ! isset( $manasa_settings['socials_display_bottom'] ) ) {
			return $classes;
		}
		
		// Return if footer bar is not empty. No needs for action in this case.
		if ( is_active_sidebar( 'footer-bar' ) ) {
			return $classes;
		}
		
		$classes[] = 'site-footer';

		// Get theme options
		$lalita_settings = wp_parse_args(
			get_option( 'lalita_settings', array() ),
			lalita_get_defaults()
		);
		$footer_layout = $lalita_settings['footer_layout_setting'];

		if ( $footer_layout == 'contained-footer' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		// Footer bar
		$classes[] = 'footer-bar-active';
		$classes[] = 'footer-bar-align-right';

		return $classes;
	}
}
