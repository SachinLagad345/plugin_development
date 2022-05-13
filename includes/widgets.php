<?php

/**
 * The file that extends WP_Widget class for registering custom widgets
 *
 * A class definition that provides all necessary functions overridden for 
 * our custom widget 
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
 *  Class for widget
 *
 * @category   Class
 * @package    Wp_Book
 * @subpackage Wp_Book/admin
 * @author     Sachin Lagad <sachin.lagad@hbwsl.com>
 * @license    GPL-2.0+ www.sachin.com
 * @link       https://github.com/SachinLagad345
 */

class My_Wp_Book_Widget extends WP_Widget
{

    /**
     * Constructor function
     * 
     * @return void
     */
    public function __construct()
    {
         
         $widget_options = array(
             'classname' => 'my_wp_book_widget_class', //widget html container class
             'description' => 'This widget displays books by category'
         );

         //WP_Widget::__construct(string $id_base, string $name, 
         // array $widget_options = array(), array $control_options = array())
         //In our case WP_Widget is parent
         parent::__construct('my_wp_book_widget_id', 'Books', $widget_options);
        
    }


    /**
     * Handles the back-end of the widget(admin)
     * 
     * @param object $instance Current instance of form
     * 
     * @return Str
     */
    public function form($instance)
    {
             // Set widget defaults.
             $defaults = [
                 'title'  => '', 
                 'text'   => '', 
                 'select' => '', 
             ];
             // Parse current settings with defaults.
             extract(wp_parse_args((array) $instance,  $defaults)); ?>
     
             <?php
                // Text Field.
                ?>
             <p>
                 <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">
                 <?php __('Text:',  'wp-book'); ?></label>
                 <input class="widefat" 
                 id="<?php echo esc_attr($this->get_field_id('text')); ?>" 
                 name="<?php echo esc_attr($this->get_field_name('text')); ?>" 
                 type="text" 
                 value="<?php echo esc_attr($text); ?>" />
             </p>
     
             <?php
                // Dropdown.
                ?>
             <p>
                 <label for="<?php echo $this->get_field_id('select'); ?>">
                 <?php __('Select',  'wp-book'); ?></label>
                 <select name="<?php echo $this->get_field_name('select'); ?>" 
                 id="<?php echo $this->get_field_id('select'); ?>" class="widefat">

                     <?php
                        $categories = get_terms(
                            ['taxonomy' => 'book category', 'hide_empty' => true]
                        );
                     foreach ($categories as $category) {
                            echo '<option value="'.esc_attr($category->name)
                            .'" id="'.esc_attr($category->name).'" '
                            .selected($select,  $category->name,  false)
                            .'>'.$category->name.'</option>';
                     }
                        ?>
                 </select>
                 </ul>
             </p>
             <?php
     
    }
     

    /**
     * Updating the options
     * 
     * @param object $newInstance new instance of form to be set
     * @param object $oldInstance previous instance of form
     * 
     * @return Str
     */
    public function update($newInstance,  $oldInstance)
    {
            $instance          = $oldInstance;
        $instance['title'] = '';
        if (isset($newInstance['title']) == true) {
                $instance['title'] = wp_strip_all_tags($newInstance['title']);
        }
    
            $instance['text'] = '';
        if (isset($newInstance['text']) == true) {
                $instance['text'] = wp_strip_all_tags($newInstance['text']);
        }
    
            $instance['select'] = '';
        if (isset($newInstance['select']) == true) {
                $instance['select'] = wp_strip_all_tags($newInstance['select']);
        }
    
            return $instance;
    
    }//end update()
    
    /**
     * Handles the front-end of the widget
     * 
     * @param array  $args     arguments data 
     * @param object $instance instance of form
     * 
     * @return Str
     */
    public function widget($args, $instance)
    {
        extract($args);
    
        // Check the widget options.
         $text = '';
        if (isset($instance['text']) == true) {
                $text = $instance['text'];
        }
    
        $select = '';
        if (isset($instance['select']) == true) {
            $select = $instance['select'];
        }

        echo $text ." of category " .$select;
    
            $id_count = 0;
        if ($select == true) {
                $args  = [
                    'post_type'   => 'book',
                    'post_status' => 'publish',
                    'tax_query'   => [
                        [
                            'taxonomy' => 'book category',
                            'field'    => 'slug',
                            'terms'    => $select,
                        ],
                    ],
                ];
                $query = new WP_Query($args);
    
                if ($query->have_posts() == true) {
                    // echo "<h1>inside widget</h1>";
                    while ($query->have_posts() == true) {
                        $curr_post = $query->the_post();
                        $id_count++;
                        ?>
                        <h5><a href="<?php the_permalink($curr_post); ?>">
                        <?php echo $id_count . '.' . get_the_title($curr_post); ?>
                        </a></h5>
                        <?php
                    }
                }
                wp_reset_query();
        }
    }
}