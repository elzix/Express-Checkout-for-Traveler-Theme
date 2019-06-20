<?php
/**
 * Created by:
 * User: Erix Kivuti
 * Date: 18-06-2019
 * Time: 3:38 AM
 */

/*
Plugin Name: Traveler Mula
Description: A wordpress plugin for merchants to integrate <a href="https://shops.mula.africa/site/"> Mula </a> into Traveler Theme, offering customers a pan-african variety of payment options.
Version: 0.1
Author: Erix Kivuti
Author URI: https://erixkivuti.men
License: GPLv2 or later
Text Domain: traveler-mula
*/

class Traveler_Mula_Payment
{
    public $pluginUrl = '';
    public $pluginPath = '';
    public $customFolder = 'traveler-mula';

    public function __construct()
    {
        $this->pluginPath = trailingslashit(plugin_dir_path(__FILE__));
        $this->pluginUrl = trailingslashit(plugin_dir_url(__FILE__));

        add_action('plugins_loaded', [$this, '_pluginSetup']);
        add_action('init', [$this, '_pluginLoader'], 20);
        add_action('wp_enqueue_scripts', [$this, '_pluginEnqueue']);
        add_action( 'plugins_loaded', [ $this, '_pluginsLoaded', ], 10 );
    }

    public function _pluginSetup()
    {
        load_plugin_textdomain('traveler-mula', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function _pluginLoader()
    {
        if (class_exists('STTravelCode') && class_exists('STAbstactPaymentGateway')) {
            require_once($this->pluginPath . 'inc/mula.php');
        }
    }

    public function _pluginEnqueue()
    {
        wp_register_script( 'checkout-script', $this->pluginUrl . 'assets/js/checkout.js', '', '1.0', 1 );
        if(is_page( 'checkout' ))
            wp_enqueue_script('checkout-script'); // Enqueue it! 
    }

    public function loadTemplate($name, $data = null)
    {
        if (is_array($data))
            extract($data);

        $template = $this->pluginPath . 'views/' . $name . '.php';

        if (is_file($template)) {
            $templateCustom = locate_template($this->customFolder . '/views/' . $name . '.php');
            if (is_file($templateCustom)) {
                $template = $templateCustom;
            }
            ob_start();

            require($template);

            $html = @ob_get_clean();

            return $html;
        }


    }

    public static function get_inst()
    {
        static $instance;

        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}

Traveler_Mula_Payment::get_inst();