<?php
/**
 * P4 Metabox Register Class
 *
 * @package P4CT
 */

/**
 * Class P4CT_Metabox_Register
 */
class P4CT_Metabox_Register {

	/**
	 * Meta box prefix.
	 *
	 * @var string $prefix
	 */
	private $prefix = 'p4_';

	/**
	 * P4CT_Metabox_Register constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Class hooks.
	 */
	private function hooks() {
		add_action( 'cmb2_admin_init', [ $this, 'register_p4_meta_box' ] );
		add_filter( 'cmb2_show_on', [ $this, 'be_taxonomy_show_on_filter' ], 10, 2 );
	}

	/**
	 * Register P4 meta box(es).
	 */
	public function register_p4_meta_box() {
		$this->register_sidebar_metabox();
		$this->register_project_metabox();
		$this->register_tip_metabox();
		$this->register_team_metabox();
		$this->register_post_metabox();
		$this->register_main_options_metabox();
	}

	/**
	 * Registers sidebar meta box(es).
	 */
	public function register_sidebar_metabox() {

		$cmb_sidebar = new_cmb2_box(
			[
				'id'           => $this->prefix . 'metabox_sidebar',
				'title'        => __( 'Post extra attributes', 'planet4-child-theme-backend' ),
				'object_types' => [ 'post' ], // Post type.
				// 'show_on' => array(
				//  'key' => 'taxonomy',
				//  'value' => array(
				//      'p4-page-type' => array( 'update' )
				//  ),
				// ),
				'context'       => 'side',
				'priority'      => 'low',
			]
		);

		$cmb_sidebar->add_field( array(
			'name'             => esc_html__( 'Project related', 'cmb2' ),
			'desc'             => esc_html__( 'Select a project connected to this post (optional)', 'cmb2' ),
			'id'               => $this->prefix . 'select_project_related',
			'type'             => 'select',
			'show_option_none' => true,
			'options'          => $this->generate_post_select( 'post', 'project' ),
		) );

	}

	/**
	 * Registers project meta box(es).
	 */
	public function register_project_metabox() {

		$cmb_project = new_cmb2_box( array(
			'id'           => 'p4-gpea-project-box',
			'title'        => 'Information about current project',
			'object_types' => array( 'page' ), // post type
			'show_on'      => array( 'key' => 'page-template', 'value' => 'page-templates/project.php' ),
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'high',  //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );

		$cmb_project->add_field( array(
			'name'             => esc_html__( 'Start date', 'cmb2' ),
			'desc'             => esc_html__( 'The date the project started (textual)', 'cmb2' ),
			'id'               => 'p4-gpea_project_start_date',
			'type'             => 'text',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

		$cmb_project->add_field( array(
			'name'             => esc_html__( 'Zone interested', 'cmb2' ),
			'desc'             => esc_html__( 'Country, city or place involved by the project', 'cmb2' ),
			'id'               => 'p4-gpea_project_localization',
			'type'             => 'text',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

		$cmb_project->add_field( array(
			'name'             => esc_html__( 'Project percentage', 'cmb2' ),
			'desc'             => esc_html__( 'Percentage of completition of the project', 'cmb2' ),
			'id'               => 'p4-gpea_project_percentage',
			'type'             => 'text',
			'attributes' => array(
				'type' => 'number',
				'pattern' => '\d*',
			),
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

	}

	/**
	 * Registers tip meta box(es).
	 */
	public function register_tip_metabox() {

		$cmb_tip = new_cmb2_box( array(
			'id'           => 'p4-gpea-tip-box',
			'title'        => 'Tip card',
			'object_types' => array( 'post' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'high',  //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
			'show_on' => array(
				'key' => 'taxonomy',
				'value' => array(
					'p4_post_attribute' => array( 'tip' ),
				),
			),
		) );

		$cmb_tip->add_field( array(
			'name'             => esc_html__( 'Frequency pledge', 'cmb2' ),
			'desc'             => esc_html__( 'Will be displayed in the tip card, in the top', 'cmb2' ),
			'id'               => 'p4-gpea_tip_frequency',
			'type'             => 'text',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

		$cmb_tip->add_field( array(
			'name'             => esc_html__( 'Tip icon', 'cmb2' ),
			'desc'             => esc_html__( 'Icon/image shown in the card', 'cmb2' ),
			'id'               => 'p4-gpea_tip_icon',
			'type'             => 'file',
			// Optional.
			'options'          => [
				'url' => false,
			],
			'text'             => [
				'add_upload_file_text' => __( 'Add Tip Image', 'planet4-child-theme-backend' ),
			],
			'query_args'       => [
				'type' => 'image',
			],
			'preview_size' => 'small',
		) );
	}

	/**
	 * Registers team meta box(es).
	 */
	public function register_team_metabox() {

		$cmb_team = new_cmb2_box( array(
			'id'           => 'p4-gpea-team-box',
			'title'        => 'Team extra info',
			'object_types' => array( 'post' ), // post type
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'high',  //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
			'show_on' => array(
				'key' => 'taxonomy',
				'value' => array(
					'p4-page-type' => array( 'team' )
				),
			),
		) );

		$cmb_team->add_field( array(
			'name'             => esc_html__( 'Role', 'cmb2' ),
			'desc'             => esc_html__( 'Role in the staff', 'cmb2' ),
			'id'               => 'p4-gpea_team_role',
			'type'             => 'text',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

		$cmb_team->add_field( array(
			'name'             => esc_html__( 'Citation', 'cmb2' ),
			'desc'             => esc_html__( 'Will be displayed in testimonials carousel', 'cmb2' ),
			'id'               => 'p4-gpea_team_citation',
			'type'             => 'textarea',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

	}

	/**
	 * Registers post meta box(es).
	 */
	public function register_post_metabox() {

		$cmb_post = new_cmb2_box( array(
			'id'           => 'p4-gpea-post-box',
			'title'        => 'Information about current post',
			'object_types' => array( 'post' ),
			'context'      => 'normal', //  'normal', 'advanced', or 'side'
			'priority'     => 'high',  //  'high', 'core', 'default' or 'low'
			'show_names'   => true, // Show field names on the left
		) );

		$cmb_post->add_field( array(
			'name'             => esc_html__( 'Reading time', 'cmb2' ),
			'desc'             => esc_html__( 'Specify the time extimated to read the article (i.e. 4 min)', 'cmb2' ),
			'id'               => 'p4-gpea_post_reading_time',
			'type'             => 'text',
			// 'sanitization_cb' => 'intval',
			// 'escape_cb'       => 'intval',
		) );

	}

	/**
	 * Registers main option meta box(es).
	 */
	public function register_main_options_metabox() {

		$cmb_options = new_cmb2_box( array(
			'id'           => 'gpea_main_options_page',
			'title'        => esc_html__( 'GPEA Options', 'gpea_theme' ),
			'object_types' => array( 'options-page' ),

			/*
			 * The following parameters are specific to the options-page box
			 * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
			 */

			'option_key'      => 'gpea_options', // The option key and admin menu page slug.
			// 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
			// 'menu_title'      => esc_html__( 'Options', 'cmb2' ), // Falls back to 'title' (above).
			'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
			// 'capability'      => 'manage_options', // Cap required to view options-page.
			// 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
			// 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
			// 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
			// 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
			// 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
			// 'message_cb'      => 'yourprefix_options_page_message_callback',
		) );

		/**
		 * Options fields ids only need to be unique within these boxes.
		 * Prefix is not needed.
		 */
		$cmb_options->add_field( array(
			'name'    => esc_html__( 'Background image for "Values" section', 'gpea_theme' ),
			'desc'    => esc_html__( 'Specify the image to be used as background', 'gpea_theme' ),
			'id'      => 'gpea_values_section_bg_image',
			'type'    => 'file',
		) );

		$cmb_options->add_field( array(
			'name'    => esc_html__( 'Description text for generic footer', 'gpea_theme' ),
			'desc'    => esc_html__( 'Description text for generic footer', 'gpea_theme' ),
			'id'      => 'gpea_decription_generic_footer_text',
			'type'    => 'text',
		) );

	}

	/**
	 * Taxonomy show_on filter
	 * @author Bill Erickson
	 * @link https://github.com/CMB2/CMB2/wiki/Adding-your-own-show_on-filters
	 *
	 * @param bool $display
	 * @param array $metabox
	 * @return bool display metabox
	 */
	public function be_taxonomy_show_on_filter( $display, $meta_box ) {
		if ( ! isset( $meta_box['show_on']['key'], $meta_box['show_on']['value'] ) ) {
			return $display;
		}

		if ( 'taxonomy' !== $meta_box['show_on']['key'] ) {
			return $display;
		}

		$post_id = 0;

		// If we're showing it based on ID, get the current ID.
		if ( isset( $_GET['post'] ) ) {
			$post_id = $_GET['post'];
		} elseif ( isset( $_POST['post_ID'] ) ) {
			$post_id = $_POST['post_ID'];
		}

		if ( ! $post_id ) {
			return $display;
		}

		foreach ( (array) $meta_box['show_on']['value'] as $taxonomy => $slugs ) {
			if ( ! is_array( $slugs ) ) {
				$slugs = array( $slugs );
			}

			$display = false;
			$terms = wp_get_object_terms( $post_id, $taxonomy );
			foreach ( $terms as $term ) {
				if ( in_array( $term->slug, $slugs, true ) ) {
					$display = true;
					break;
				}
			}

			if ( $display ) {
				break;
			}
		}

		return $display;
	}

	/**
	 * Fetches posts to use with cmb2
	 * TODO optimize
	 *
	 * @param string $post_type
	 * @param string $post_attribute
	 * @return bool  display metabox
	 */
	private function generate_post_select( $post_type, $post_attribute ) {
		$post_type_object = get_post_type_object( $post_type );
		$label = $post_type_object->label;
		$posts = get_posts(
			array(
				'post_type'        => $post_type,
				'post_status'      => 'publish',
				'suppress_filters' => false,
				'posts_per_page'   => -1,
				'tax_query'        => array(
					array(
						'taxonomy' => 'p4_post_attribute',
						'field'    => 'slug',
						'terms'    => $post_attribute,
					),
				),
			)
		);
		$output = array();
		foreach ( $posts as $post ) {
			$postid = $post->ID;
			$output[ $postid ] = $post->post_title;
		}
		return $output;
	}

}