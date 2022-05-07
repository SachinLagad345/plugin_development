<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/SachinLagad345
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Sachin Lagad <sachin.lagad@hbwsl.com>
 */
class Wp_Book_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->my_function();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-book-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Book_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Book_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-book-admin.js', array( 'jquery' ), $this->version, false );

	}

	private function my_function()
	 {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/wp-book-admin-display.php';
	 }

	/*_____________ Add custom post type ____________*/

	function wporg_custom_post_type() {
		register_post_type('book',
			array(
				'labels'      => array(
						'name'    => __('Book', 'textdomain'),
						'add_new' => 'Add Books',
						'all_items' => 'All Books'
				),
					'public'      => true,
					'has_archive' => true,
					'taxonomies' => array('category','post_tag')
			)
		);
	}

	/*_____________ Add hierarchical custom taxonomy ____________*/

	function wporg_register_taxonomy_books_category() {
		$labels = array(
			'name'              => _x( 'Book Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Book Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Book Category' ),
			'all_items'         => __( 'All Book Categories' ),
			'parent_item'       => __( 'Parent Book Category' ),
			'parent_item_colon' => __( 'Parent Book Category:' ),
			'edit_item'         => __( 'Edit Book Category' ),
			'update_item'       => __( 'Update Book Category' ),
			'add_new_item'      => __( 'Add New Book Category' ),
			'new_item_name'     => __( 'New Book Category Name' ),
			'menu_name'         => __( 'Book Category' ),
		);
		$args   = array(
			'hierarchical'      => true, // make it hierarchical (like categories)
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'book-category' ],
		);
		register_taxonomy( 'book category',  array('post','book'), $args );
	}

	/*_____________ Add non-hierarchical custom taxonomy ____________*/

	function wporg_register_taxonomy_books_tag() {
		$labels = array(
			'name'              => _x( 'Book Tags', 'taxonomy general name' ),
			'singular_name'     => _x( 'Book Tag', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Book Tag' ),
			'all_items'         => __( 'All Book Tags' ),
			'edit_item'         => __( 'Edit Book Tag' ),
			'update_item'       => __( 'Update Book Tag' ),
			'add_new_item'      => __( 'Add New Book Tag' ),
			'new_item_name'     => __( 'New Book Tag Name' ),
			'menu_name'         => __( 'Book Tag' ),
		);
		$args   = array(
			'hierarchical'      => false, // make it hierarchical (like categories)
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [ 'slug' => 'book-tag' ],
		);
		register_taxonomy( 'book tag', [ 'post','book' ], $args );
	}

	/*________________ Add custom meta box __________ */


	function wporg_add_custom_box() {
	        add_meta_box(
	            'wporg_box_one',                 // Unique ID
	            'Add info for Book',      // Box title
	            array($this,'wporg_custom_box_html_callback'),  // Content callback, must be of type callable
	            'book'                           // Post type
	        );
	}

	function wporg_custom_box_html_callback( $post )
	{
		wp_nonce_field('save_book_meta_data','save_book_meta_data_nonce');

		// using metatype defined in register_custom_table 
		//get_metadata( string $meta_type, int $object_id, string $meta_key = '', bool $single = false )
		$auth_name = get_metadata('bookinfo',$post->ID,'author_name_meta',true);
		$price_val = get_metadata('bookinfo',$post->ID,'price_meta',true);
		$publisher_val = get_metadata('bookinfo',$post->ID,'publisher_meta',true);
		$year_val = get_metadata('bookinfo',$post->ID,'year_meta',true);
		$edition_val = get_metadata('bookinfo',$post->ID,'edition_meta',true);
		$url_val = get_metadata('bookinfo',$post->ID,'url_meta',true);

		$post_info = array(
				'author_name'=> $auth_name,
				'price' => $price_val,
				'publisher' => $publisher_val,
				'year' => $year_val,
				'edition' => $edition_val,
				'url' => $url_val
		);

		wporg_custom_box_html( $post_info );
	}

}
