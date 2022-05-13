<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Php version 8.1.5
 *
 * @category   Free
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Sachin Lagad <sachin.lagad@hbwsl.com>
 * @license    GPL-2.0+ www.sachin.com
 * @link       https://github.com/SachinLagad345
 * @since      1.0.0
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

 /**
  * Includes
  */

// for widget class!!
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/widgets.php';

/**
 *  Class for admin
 *
 * @category   Class
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Sachin Lagad <sachin.lagad@hbwsl.com>
 * @license    GPL-2.0+ www.sachin.com
 * @link       https://github.com/SachinLagad345
 * */
class Wp_Book_Admin
{


    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string     $_plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name The name of this plugin.
     * @param string $_version     The version of this plugin.
     */
    public function __construct($_plugin_name, $_version)
    {

        $this->plugin_name = $_plugin_name;
        $this->version     = $_version;
        $this->_myFunction();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since  1.0.0
     * @return Str
     */
    public function enqueueStyles()
    {

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

        wp_enqueue_style(
            $this->plugin_name, plugin_dir_url(__FILE__) . 'css/wp-book-admin.css', 
            array(), $this->version, 'all'
        );

    }

        /**
         * Register the JavaScript for the admin area.
         *
         * @since  1.0.0
         * @return Str
         */
    public function enqueueScripts()
    {

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

        wp_enqueue_script(
            $this->plugin_name, plugin_dir_url(__FILE__) .'js/wp-book-admin.js',
            array('jquery'), $this->version, false
        );

    }

    /**
     * Function to load display file!!
     * 
     * @return Str
     */
    private function _myFunction()
    {
        include_once plugin_dir_path(
            dirname(__FILE__)
        ) .'admin/partials/wp-book-admin-display.php';
    }

    /**
     * _____________ Add custom post type ____________
     * 
     * @return Str
     */
    function createCustomPostType()
    {
        register_post_type(
            'book',
            array(
            'labels'      => array(
            'name'      => __('Book', 'textdomain'),
            'add_new'   => 'Add Books',
            'all_items' => 'All Books',
            ),
            'public'      => true,
            'has_archive' => true,
            'taxonomies'  => array('category', 'post_tag'),
            )
        );
    }

    /**
     * _____________ Add hierarchical custom taxonomy ____________
     * 
     * @return Str
     */
    function registerTaxonomyBooksCategory()
    {
        $labels = array(
        'name'              => _x('Book Categories', 'taxonomy general name'),
        'singular_name'     => _x('Book Category', 'taxonomy singular name'),
        'search_items'      => __('Search Book Category'),
        'all_items'         => __('All Book Categories'),
        'parent_item'       => __('Parent Book Category'),
        'parent_item_colon' => __('Parent Book Category:'),
        'edit_item'         => __('Edit Book Category'),
        'update_item'       => __('Update Book Category'),
        'add_new_item'      => __('Add New Book Category'),
        'new_item_name'     => __('New Book Category Name'),
        'menu_name'         => __('Book Category'),
        );
        $args   = array(
        'hierarchical'      => true, // make it hierarchical (like categories)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'book-category'),
        );
        register_taxonomy('book category', array('post', 'book'), $args);
    }

    /**
     * _____________ Add non-hierarchical custom taxonomy ____________
     * 
     * @return Str
     */
    function registerTaxonomyBooksTag()
    {
        $labels = array(
        'name'          => _x('Book Tags', 'taxonomy general name'),
        'singular_name' => _x('Book Tag', 'taxonomy singular name'),
        'search_items'  => __('Search Book Tag'),
        'all_items'     => __('All Book Tags'),
        'edit_item'     => __('Edit Book Tag'),
        'update_item'   => __('Update Book Tag'),
        'add_new_item'  => __('Add New Book Tag'),
        'new_item_name' => __('New Book Tag Name'),
        'menu_name'     => __('Book Tag'),
        );
        $args   = array(
        'hierarchical'      => false, // make it hierarchical (like categories)!!
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'book-tag'),
        );
        register_taxonomy('book tag', array('post', 'book'), $args);
    }

    /**
     * ________________ Add custom meta box __________ 
     * 
     * @return Str
     */
    function addCustomBox()
    {
        add_meta_box(
            'wporg_box_one',                 // Unique ID!!
            'Add info for Book',      // Box title!!
            array($this, 'customBoxHtmlCallback'),  // Content callback!
            'book'                           // Post type!!
        );
    }

    /**
     * Adding custom box 
     * 
     * @param string $post Current page.
     * 
     * @return Str
     */
    function customBoxHtmlCallback($post)
    {
        wp_nonce_field('save_book_meta_data', 'save_book_meta_data_nonce');

        // using metatype defined in registerCustomTable!!
        // get_metadata(string $meta_type, int $object_id,!!
        //string $meta_key = '', bool $single = false)!!
        $auth_name     = get_metadata(
            'bookinfo', $post->ID, 'author_name_meta', true
        );
        $price_val     = get_metadata('bookinfo', $post->ID, 'price_meta', true);
        $publisher_val = get_metadata('bookinfo', $post->ID, 'publisher_meta', true);
        $year_val      = get_metadata('bookinfo', $post->ID, 'year_meta', true);
        $edition_val   = get_metadata('bookinfo', $post->ID, 'edition_meta', true);
        $url_val       = get_metadata('bookinfo', $post->ID, 'url_meta', true);

        $post_info = array(
            'author_name' => $auth_name,
            'price'       => $price_val,
            'publisher'   => $publisher_val,
            'year'        => $year_val,
            'edition'     => $edition_val,
            'url'         => $url_val,
        );

        wporg_custom_box_html($post_info);
    }

    /** 
     * ____________________ Create custom meta table _______________
     * 
     * @return STR
     */
    static function createCustomTable()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name      = $wpdb->prefix . 'book_meta_data';

        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
            $query = 'CREATE TABLE ' .
            $table_name . "(
				meta_id bigint(20) NOT NULL AUTO_INCREMENT,
				bookinfo_id bigint(20) NOT NULL DEFAULT '0',
				meta_key varchar(255) DEFAULT NULL,
				meta_value longtext,
				PRIMARY KEY  (meta_id),
				KEY bookinfo_id (bookinfo_id),
				KEY meta_key (meta_key)
			)" . $charset_collate . ';';

            // include file upgrade.php to execute query!!
            include_once ABSPATH . 'wp-admin/includes/upgrade.php';

            dbDelta($query);
        }

    }

    /**
     * ______________ register custom table ______________
     *
     * @return Str  
     */
    function registerCustomTable()
    {
        global $wpdb;

        // registered as metadata/metatype now only use bookinfo !!
        // without meta suffix to update and get metadata !!
        $wpdb->bookinfometa = $wpdb->prefix . 'book_meta_data';
    }


    /**
     * ______________ Save meta data to custom table ______________
     *
     * @param string $post_id ID of current page.
     * 
     * @return Str  
     */
    function saveBookMetaData($post_id)
    {
        if (! isset($_POST['save_book_meta_data_nonce'])) {
            return;
        }

        if (! wp_verify_nonce(
            $_POST['save_book_meta_data_nonce'], 'save_book_meta_data'
        )
        ) {
            return;
        }

        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        $author_name = sanitize_text_field($_POST['author_name']);
        $price       = sanitize_text_field($_POST['price']);
        $publisher   = sanitize_text_field($_POST['publisher']);
        $year        = sanitize_text_field($_POST['year']);
        $edition     = sanitize_text_field($_POST['edition']);
        $url         = sanitize_text_field(esc_url($_POST['url']));

        // metatype is registered/defined in registerCustomTable !!
        // update_metadata(string $meta_type, int $object_id, string !!
        // $meta_key, mixed $meta_value, mixed $prev_value = '') !!
        update_metadata('bookinfo', $post_id, 'author_name_meta', $author_name);
        update_metadata('bookinfo', $post_id, 'price_meta', $price);
        update_metadata('bookinfo', $post_id, 'publisher_meta', $publisher);
        update_metadata('bookinfo', $post_id, 'year_meta', $year);
        update_metadata('bookinfo', $post_id, 'edition_meta', $edition);
        update_metadata('bookinfo', $post_id, 'url_meta', $url);
    }

    /**
     * ______________create custom settings menu page _____________
     * 
     * @return STR
     */
    function createCustomSettingsMenuPage()
    {

        // adding menupage in dashboard !!
        // add_menu_page(string $page_title, string $menu_title,  !!
        // string $capability, string $menu_slug, callable $function = '', !!
        // string $icon_url = '', int $position = null) !!
        add_menu_page(
            'Booksmenu', 'Booksmenu', 'manage_options', 'bookmenu',
            'book_settings_html', 'dashicons-chart-pie', 59
        );
    }

    /**
     * Registering book settings
     * 
     * @return Str
     */ 
    function registerBookSettings()
    {

        // register_setting(string $option_group, !!
        // string $option_name, array $args = array()) !!
        // option group is section in wp_options table where !!
        // our $option_name data will be stored !!
        register_setting('book-settings-group', 'book_currency');
        register_setting('book-settings-group', 'book_no_per_page');

    }


    /**
     * Creating guitenberg book widget
     * 
     * @return Str
     */ 
    function bookWidgetRegister()
    {
        // registering widget
        register_widget('My_Wp_Book_Widget');
    }

    /**
     * __________________Register dashboard widget ____________________________
     * 
     * @return Str 
     */
    public function registerBookDashboardWidget()
    {
        // wp_add_dashboard_widget(string $widget_id, string $widget_name, !! 
        // callable $callback, callable $control_callback = null, !!
        // array $callback_args = null, string $context = 'normal', !!
        // string $priority = 'core') !!
        wp_add_dashboard_widget(
            'wp_book_dashboard_widget', 'Book Dashboard Widget',
            array($this, 'createCustomDashboardWidgetCallback')
        );
    }

    /**
     * __________________Create custom dashboard widget ____________________________
     * 
     * @return Str 
     */
    public function createCustomDashboardWidgetCallback()
    {
         global $wpdb;
        $get_term_ids    = $wpdb->get_col(
            "SELECT term_id FROM `wp_term_taxonomy` WHERE taxonomy = 'book category'
            ORDER BY count DESC LIMIT 5"
        );
        $get_term_counts = $wpdb->get_col(
            "SELECT count FROM `wp_term_taxonomy` WHERE taxonomy='book category'
            ORDER BY count DESC LIMIT 5"
        );
        $top_terms_names = array();
        $top_terms_slugs = array();

        foreach ($get_term_ids as $id) {
            // echo $id; !!
            $stored_terms = (array) $wpdb->get_row(
                'SELECT name,  slug FROM `wp_terms` WHERE term_id=' . $id
            );
            // echo var_dump($stored_terms); !!
            array_push($top_terms_names, $stored_terms['name']);
            array_push($top_terms_slugs, $stored_terms['slug']);
        }

        ?>

<ol>
        <?php
        for ($i = 0; $i < count($top_terms_names);$i++) {
             echo "<li style='font-size:20px'><a target='blank' href='" 
             . get_site_url() . '/book-category/' 
             .$top_terms_slugs[ $i ] . "'>" . $top_terms_names[ $i ] . ' count= ' 
             . $get_term_counts[ $i ] . '</li>';
        }
        ?>
</ol>
        <?php
    }
}
