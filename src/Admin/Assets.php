<?php

    namespace Alpackit\Connect\Admin;

    use Alpackit\Connect\Contracts\AssetLoader;

    class Assets extends AssetLoader{

        /**
         * Hook into the enqueue_scripts
         *
         * @return void
         */
        public function load()
        {
            add_action('admin_enqueue_scripts', [ $this, 'scripts' ]);
            add_action('admin_enqueue_scripts', [ $this, 'styles' ]);

            //hack test:
            /*add_filter('admin_url', function( $url, $path ){
                if( $path == 'media-new.php' ){
                    return env('alpackit_staging').'/wp-admin/'.$path;
                }
            }, 100, 2);*/
        }


        /**
         * Add alpackit scripts
         *
         * @return void
         */
        public function scripts()
        {
            wp_enqueue_script( 'alpackit_script', $this->path( 'js/main.js' ), ['jquery'] );
        }   


        /**
         * Add alpackit styles
         *
         * @return void
         */
        public function styles()
        {
            wp_enqueue_style( 'alpackit_style', $this->path( 'css/main.css' ) );
        }


        
    }