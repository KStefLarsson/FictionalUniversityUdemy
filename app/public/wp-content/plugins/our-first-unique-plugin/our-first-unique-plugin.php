<?php  

/*
    Plugin Name: Our Test Plugin
    Description: A truly amazing plugin.
    Version: 2.0
    Author: Stefan
    Author URI: https://www/udemy/user/bradshiff/
    Text Domain: wcpdomain
    Domain Path: /languages
*/

class WordCountAndTimePlugin {

    function __construct() {
        add_action('admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'ifWrap'));
        add_action('init', array($this, 'languages'));
    }

    function languages() {
        load_plugin_textdomain('wcpdomain', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    function ifWrap($content) {
        if (is_main_query() AND is_single() AND 
        (
            get_option('wcp_wordcount', '1') OR 
            get_option('wcp_charactercount', '1') OR 
            get_option('wcp_readtime', '1')       
        )) {
            return $this -> createHTML($content);
        }
        return $content;
    } 

    // Genererar HTML på single (blog) sidan.
    function createHTML($content) {
        $html = '<h3>' . esc_html(get_option('wcp_headline', 'Post Statistics')) . '</h3><p>';

        // Hämta word count en gång, då både word count och readtime behöver det.
        if (get_option('wcp_wordcount', '1') OR get_option('wcp_readtime', '1')) {
            $wordCount = str_word_count(strip_tags($content));
        }

        // om wordcount är ikryssad visas antal ord 
        if (get_option('wcp_wordcount', '1')) {
            $html .= esc_html__('This post has', 'wcpdomain') . ' ' . $wordCount . ' ' . __('words', 'wcpdomain') . '<br>';
        }

        // om charactercount är ikryssad visas antal karatärer
        if (get_option('wcp_charactercount', '1')) {
            $html .= 'This post has ' . strlen(strip_tags($content)) . ' characters.<br>';
        }

        // om readtime är ikryssad visas antal minuter det uppskattas att läsa stycket.
        if (get_option('wcp_readtime', '1')) {
        $html .= 'This post will take about ' . round($wordCount/225) . ' minute(s) to read.<br>';
        }

        $html .= '</p>';

        if (get_option('wcp_location', '0') == '0') {
            return $html . $content;
        }
        return $content . $html;
    }

    function settings() {
        add_settings_section('wcp_first_section', null, null, 'word-count-settings-page');

        // Inställningar för location dropdown menu
        add_settings_field('wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanatizeLocation'), 'default' => '0'));

        // Inställningar för Rubrik
        add_settings_field('wcp_headline', 'Headline Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting('wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Statistics'));

        // Inställningar för första checkboxen (Word Count)
        add_settings_field('wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('functionName' => 'wcp_wordcount'));
        register_setting('wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        // Inställningar för andra checkboxen (Character Count)
        add_settings_field('wcp_charactercount', 'Character Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('functionName' => 'wcp_charactercount'));
        register_setting('wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));

        // Inställningar för andra checkboxen (Read Time)
        add_settings_field('wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('functionName' => 'wcp_readtime'));
        register_setting('wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    // Kollar så värdet som skickas in är antingen 0 eller 1. Skulle någon försöka skicka in något annat värde kommer ett felmeddelande,
    // samt att vi returnrerar värdet som var sedan innan. Om användaren skickar in ett korrekt värde returneras det. 
    function sanatizeLocation($input) {
        if ($input != '0' AND $input != '1') {
            add_settings_error('wcp_location', 'wcp_location_error', 'Display location must be either beginning or end.');
            return get_option('wcp_location');
        }
        return $input;
    }

    // Hämtar värdet från databasen och sätter den tilll ikryssad som default, sparar det nya värdet och uppdaterar databasen. 
    // $args[functionName] skickar med namnet på funktionen så wordpress vet vilken checkbox som blir ändrad.
    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['functionName'] ?>" value="1" <?php checked(get_option($args['functionName'], '1')) ?>>
    <?php }

    // En funktion för att döpa rubriken
    function headlineHTML() { ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr(get_option('wcp_headline')) ?>">
    <?php }

    // Gör en dropdownlista med två val
    function locationHTML() { ?>
        <select name="wcp_location">
            <option value="0" <?php selected(get_option('wcp_location'), '0') ?>>Beginning of post</option>
            <option value="1" <?php selected(get_option('wcp_location'), '1') ?>>End of post</option>
        </select>
    <?php }

    function adminPage() {
        // 1:a argumentet anger vad som står högst upp i fliken på browsern.
        // 2:a argumentet anger namnet under Settings menyn.
        // 3:e avser vem som ska ha tillåtelse att se och använda pluginet
        // 4:e anger slug, det sista i url:n
        // 5:e är funktions-namnet på funktionen som ska göra något.
        add_options_page('Word Count Settings', __('Word Count', 'wcp_domain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
    }
    
    function ourHTML() { ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>
            <form action="options.php" method="POST">
                <?php
                    settings_fields('wordcountplugin');
                    do_settings_sections('word-count-settings-page');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }
}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

?>