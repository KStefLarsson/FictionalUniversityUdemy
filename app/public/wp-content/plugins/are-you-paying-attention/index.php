<?php  

/*
    Plugin Name: Are You Paying Attention Quiz
    Description: Give your readers a multiple choice question.
    Version: 1.0
    Author: Stefan
    Author URI: https://www/udemy/user/bradshiff/
*/

if ( ! defined( 'ABSPATH' ) ) exit;  // Exit if accessed directly



class AreYouPayingAttention {
    function __construct() {
        add_action('init', array($this, 'adminAssets'));
    }

    function adminAssets() {
        register_block_type(__DIR__, array(
            'render_callback' => array($this, 'theHTML')
        ));
    }

    function theHTML($attributes) {
        // frontend filer ska inte laddas på admin sidan
        if (!is_admin()) {
            // Lägger till frontend javascript fil endast på sidor där blocket körs.
            wp_enqueue_script('attentionFrontend', plugin_dir_url(__FILE__) . 'build/frontend.js', array('wp-element'));
        }

        ob_start(); ?>
        <div class="paying-attention-update-me">
            <pre style="display: none;"><?php echo wp_json_encode($attributes) ?></pre>
        </div>
        <?php return ob_get_clean();
    }
}

$areYouPayingAttention = new AreYouPayingAttention();