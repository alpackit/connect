<?php
/**
 * Plugin Name:     Alpackit Connect
 * Plugin URI:      alpackit.com
 * Description:     Connects your website to Alpackit
 * Author:          Alpackit
 * Author URI:      alpackit.com
 * Text Domain:     alpackit
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Alpackit Connect
 */

namespace Alpackit\Connect;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// The directory separator.
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);


/**
 * Main class that bootstraps the plugin.
 */
if (!class_exists('PluginIgniter')) {

    class PluginIgniter {
    
        /**
         * Plugin bootstrap instance.
         *
         * @var \Alpackit\Connect\PluginIgniter
         */
        protected static $instance = null;


        /**
         * Plugin directory name.
         *
         * @var string
         */
        protected static $dir_name = '';


        /**
         * Constructor
         */
        protected function __construct(){

            static::$dir_name = static::set_dir_name(__DIR__);

            // Load plugin.
            $this->load();
        
        }


        /**
         * Load the framework classes.
         *
         * @return void
         */
        protected function load(){

            //require the language domain:
            $path = __DIR__ . DS . '/languages/';
            load_plugin_textdomain( 'alpackit', false, $path );

            //get the autoloader
            require( __DIR__ . DS . 'vendor' . DS . 'autoload.php' );

            //setup the listeners
            $this->listen();

            //give off the loaded hook
            do_action( 'alpackit_connect_loaded' );

        }


        /**
         * Initiate static instances and listen to WP events
         *
         * @return void
         */
        protected function listen()
        {
            //set the dot env:
			if( file_exists( trailingslashit( ABSPATH ) . '.env' ) ){
				$dotenv = \Dotenv\Dotenv::createImmutable( ABSPATH );
                $dotenv->load();

                //set the AWS settings
                $this->set_media_offloading();
            }

            //Admin ui: 
            Admin\Menu::get_instance();
            Admin\Assets::get_instance();

            //General
            Plugins\Events::get_instance();
            Content\Events::get_instance();
            
        }


        /**
         * Load the AWS information as a constant
         *
         * @return void
         */
        public function set_media_offloading()
        {
            define( 'AS3CF_SETTINGS', serialize([
                'provider' => 'aws',
                'access-key-id' => $_ENV['AWS_SECRET_ID'],
                'secret-access-key' => $_ENV['AWS_SECRET_KEY'],
            ]));
        }

        /**
         * Always serve up the deleted file
         *
         * @return void
         */
        public function check_deleted()
        {
            if( file_exists( ABSPATH . DS . 'deleted.php' ) ){
                require ABSPATH . DS . 'deleted.php';
                die();
            }
        }

        /*=============================================================*/
        /**             Getters & Setters                              */
        /*=============================================================*/


        /**
         * Init the plugin classes
         *
         * @return \Alpackit\Connect\PluginIgniter
         */
        public static function get_instance(){

            if ( is_null( static::$instance ) ){
                static::$instance = new static();
            }
            return static::$instance;
        }

        /**
         * Set the plugin directory property. This property
         * is used as 'key' in order to retrieve the plugins
         * informations.
         *
         * @param string
         * @return string
         */
        protected static function set_dir_name($path) {

            $parent = static::get_parent_directory_name( dirname($path) );

            $dirName = explode($parent, $path);
            $dirName = substr($dirName[1], 1);

            return $dirName;
        }

        /**
         * Check if the plugin is inside the 'mu-plugins'
         * or 'plugin' directory.
         *
         * @param string $path
         * @return string
         */
        protected static function get_parent_directory_name($path) {

            // Check if in the 'mu-plugins' directory.
            if( $path === WPMU_PLUGIN_DIR ) {
                return 'mu-plugins';
            }

            // Install as a classic plugin.
            return 'plugins';
        }

        

        /**
         * Get the plugin path
         * 
         * @return string
         */
        public static function get_plugin_path(){
        	return __DIR__.DS;
        }

        /**
         * Get the plugin url
         *
         * @return void
         */
        public static function get_plugin_url()
        {
            return plugin_dir_url( __FILE__ );
        }

        /**
         * Returns the directory name.
         *
         * @return string
         */
        public static function get_dir_name(){
            return static::$dir_name;
        }

    }
}



/**
 * Load the main class, when Cuisine is loaded
 *
 */
add_action('muplugins_loaded', function(){
	\Alpackit\Connect\PluginIgniter::get_instance();
});
