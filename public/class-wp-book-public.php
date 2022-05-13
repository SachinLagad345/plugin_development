<?php

/**
 * The public-facing functionality of the plugin.
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
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @category   Free
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Sachin Lagad <sachin.lagad@hbwsl.com>
 * @license    GPL-2.0+ www.sachin.com
 * @link       https://github.com/SachinLagad345
 * @since      1.0.0
 */
class Wp_Book_Public
{

    /**
     * The ID of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_plugin_name    The ID of this plugin.
     */
    private $_plugin_name;

    /**
     * The version of this plugin.
     *
     * @since  1.0.0
     * @access private
     * @var    string    $_version    The current version of this plugin.
     */
    private $_version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $_plugin_name The name of the plugin.
     * @param string $_version     The version of this plugin.
     */
    public function __construct($_plugin_name, $_version)
    {

        $this->plugin_name = $_plugin_name;
        $this->version = $_version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
            $this->plugin_name, plugin_dir_url(__FILE__)
             .'css/wp-book-public.css', array(), $this->version, 'all'
        );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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
            $this->plugin_name, plugin_dir_url(__FILE__) 
            .'js/wp-book-public.js', array('jquery'),
            $this->version, false
        );

    }

    /**
     * Returns the information of book to shortcode named book.
     *
     * @param array $atts Contains the attributes passed in shortcode
     * 
     * @return Str
     */
    public function createBookShortcode($atts)
    {

        $attributes = shortcode_atts(
            array(
            'id'          => null,
            'author_name' => '',
            'publisher'   => '',
            'year'        => '',
            'tag'         => '',
            'category'    => '',
            'edition'     => '',
            'url'         => '',
            ),
            $atts
        );

        if ($attributes['category'] !== "" || $attributes["tag"] !== "") {
            $args = [
            'p'              => $attributes['id'],
            'post_type'      => 'book',
            'post_status'    => 'publish',
            'posts_per_page' => get_option('book_no_per_page'),
            'tax_query'      => [
            'relation' => 'OR',
            [
            'taxonomy'         => 'book category',
            'field'            => 'slug',
            'terms'            => explode(',', $attributes['category']),
            'include_children' => true,
            'operator'         => 'IN',
            ],
            [
            'taxonomy'         => 'book tag',
            'field'            => 'slug',
            'terms'            => explode(',', $attributes['tag']),
            'include_children' => false,
            'operator'         => 'IN',
            ],
            ],
            ];
        } else if ($attributes['author_name'] !== "" || $attributes["publisher"]!==""
            || $attributes["year"] !== "" || $attributes["edition"] !== ""
            || $attributes["url"] !== ""
        ) {
            $metaqueryarr = array('relation' => 'AND');
            if ($attributes['author_name'] !== "") {
                $temparr = ['key'     => 'author_name_meta', 
                'value'   => explode(',',  $attributes['author_name']),
                'compare' => '=',];
                array_push($metaqueryarr,  $temparr);
            }

            if ($attributes['publisher'] !== "") {
                $temparr = ['key'     => 'publisher_meta',
                'value'   => explode(',', $attributes['publisher']),
                'compare' => '=',];
                array_push($metaqueryarr,  $temparr);
            }

            if ($attributes['year'] !== "") {
                $temparr = ['key'     => 'year_meta',
                'value'   => $attributes['year']];
                array_push($metaqueryarr,  $temparr);
            }

            if ($attributes['edition'] !== "") {
                $temparr = ['key'     => 'edition_meta',
                'value'   => explode(',', $attributes['edition']),
                'compare' => '=',];
                array_push($metaqueryarr,  $temparr);
            }

            if ($attributes['url'] !== "") {
                $temparr = ['key'     => 'url_meta',  
                'value'   => explode(',', $attributes['url']),
                'compare' => '=',];
                array_push($metaqueryarr,  $temparr);
            }

            $args = [
                'p'              => $attributes['id'],
                'post_type'      => 'book',
                'post_status'    => 'publish',
                'posts_per_page' => get_option('book_no_per_page'),
                'meta_query'     => $metaqueryarr
            ];
        } else {
               $args = array(
               'p'              => $attributes['id'],
               'post_type'      => 'book',
               'post_status'    => 'publish',
               'posts_per_page' => get_option('book_no_per_page'),
               );
        }

        $content = '';

        $query = new WP_Query($args);
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $currency = get_option('book_currency');
                $book_metadata = get_metadata('bookinfo', get_the_ID());
                $currency_in_no = get_metadata(
                    'bookinfo', get_the_ID(), 'price_meta', true
                );
                if ($book_metadata['publisher_meta'][0] ==='' || $currency_in_no===''
                    || $book_metadata['year_meta'][0] === '' 
                    || $book_metadata['edition_meta'][0] === ''
                    || $book_metadata['url_meta'][0] === ''
                ) {
                    $book_metadata['publisher_meta'][0] = 'N.A.';
                    $price = "N.A.";
                    //$book_metadata['year'][0] = 'N.A.';
                    $book_metadata['edition'][0] = 'N.A.';
                    $book_metadata['url'][0] = '';
                } else {
                    if ($currency === 'US Dollar') {
                        $price = '$' . (int) $currency_in_no * 0.013; 
                    }
                    if ($currency === 'Indian Rupees') {
                        $price = '&#8377;' . (int) $currency_in_no;
                    }
                    if ($currency === 'UK Pound Sterling') {
                        $price = '&#163;' . (int) $currency_in_no * 0.010;
                    }
                }

                $content .= '<div>';
                $content .= '<h3 style="text-align:center">' 
                .get_the_title() . '</h3>';
                $content .= '<table>';
                $content .= '<tbody>';
                $content .= '<tr>';
                $content .= "<td><p>Price: " . $currency_in_no . "</p></td>";
                $content .= "<td><p>Publisher: " .$book_metadata['publisher_meta'][0]
                ."</p></td>";
                $content .= "</tr>";
                $content .= "<tr>";
                $content .= "<td><p>Year: " . $book_metadata['year_meta'][0] 
                ."</p></td>";
                $content .= "<td><p>Edition: " . $book_metadata['edition_meta'][0]
                ."</p></td>";
                $content .= "</tr>";
                $content .= "<tr>";
                $content .='<td colspan="2"><p style="text-align:center">
                For more information: <a href="'.$book_metadata['url_meta'][0] 
                .'" target="_blank">'
                .$book_metadata['url_meta'][0] . '</p></td>';
                $content .= "</tr>";
                $content .= "</tbody>";
                $content .= "</table>";
                $content .= "</div>";
            }
            wp_reset_postdata();
        } else {
            $content .= '<p style="color:red; text-align:center">
            No Book Found....</p>';
        }

        return $content;
    }
}
