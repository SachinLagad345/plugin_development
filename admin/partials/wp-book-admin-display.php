<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/SachinLagad345
 * @since      1.0.0
 *
 * @package    Wp_Book
 * @subpackage Wp_Book/admin/partials
 */

// This file should primarily consist of HTML with a little bit of PHP.

/*________________ Add custom meta box __________ */

function wporg_custom_box_html( $post_info ) {
    //$author_name = get_post_meta( $post->ID, 'author_name_meta', true );
    $author_name = $post_info['author_name'];
    $price = $post_info['price'];
    $publisher = $post_info['publisher'];
    $year = $post_info['year'];
    $edition = $post_info['edition'];
    $url = $post_info['url'];
    
    ?>

    <div class="field_class" style="margin:5px 0px;">
        <label for="author_name">Enter Author Name</label>
        <input id="author_name" type="text" name="author_name" value="<?php echo $author_name ?>" required>
    </div>
    <div class="field_class" style="margin:5px 0px;">
        <label for="price">Enter Price</label>
        <input id="price" type="number" name="price" value="<?php echo $price ?>" required>
    </div>
    <div class="field_class" style="margin:5px 0px;">
        <label for="publisher">Enter Publisher</label>
        <input id="publisher" type="text" name="publisher" value="<?php echo $publisher ?>" required>
    </div>
    <div class="field_class" style="margin:5px 0px;">
        <label for="year">Enter Year</label>
        <input id="year" type="number" name="year" value="<?php echo $year ?>" required>
    </div>
    <div class="field_class" style="margin:5px 0px;">
        <label for="edition">Enter Edition</label>
        <input id="edition" type="text" name="edition" value="<?php echo $edition ?>" required>
    </div>
    <div class="field_class" style="margin:5px 0px;">
        <label for="url">Enter URL</label>
        <input id="url" type="text" name="url" value="<?php echo $url ?>" required>
    </div>
<?php
}
?>

<?php 
function book_settings_html()
 {
    if( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == true )
    {
    ?>
        <div class="notice notice-success"><p>Settings Saved Successfully</p></div>
    <?php
    }
    ?>
    <h1> Book Settings Page </h1>
    <h5> Manage all settings of Book </h5>

    <form method='post' action='options.php'>
        <?php settings_fields('book-settings-group'); ?>   <!-- pass same name as passed in register_settings() as first arg $option_group -->

        <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="book_currency">Currency</label></th>
                            <?php $currency_option = get_option( 'book_currency' )?>
                            <td>
                                <select name="book_currency" id="book_currency" class="regular-text">
                                    <option value="Indian Rupees" <?php selected( $currency_option, 'Indian Rupees' ); ?> >Indian Rupees</option>
                                    <option value="US Dollar" <?php selected( $currency_option, 'US Dollar' ); ?> >US Dollar</option>
                                    <option value="UK Pound Sterling" <?php selected( $currency_option, 'UK Pound Sterling' ); ?> >UK Pound Sterling</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="book_no_pages">No. of Books (per page)</label></th>
                            <td><input type="text" class="regular-text" name="book_no_per_page" id="book_no_pages" placeholder="No. of Books" value="<?php echo get_option( 'book_no_per_page' )?>"></td>
                        </tr>
                        <tr>
                            <td><input type="submit" value="Save Settings" class="button-primary"></td>
                        </tr>
                    </tbody>
                </table>
    
    </form>
 
<?php    
}

?>