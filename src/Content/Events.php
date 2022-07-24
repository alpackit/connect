<?php

    namespace Alpackit\Connect\Content;

    use Alpackit\Connect\Contracts\EventListener;

    class Events extends EventListener{

        public function listen()
        {
            //make sure the staging url is used in links
            add_filter( 'post_link', [ $this, 'internal_link_to_staging'] , 10, 3 );
            add_filter( 'page_link', [ $this, 'internal_link_to_staging'] , 10, 3 );

            //replace url in the_content on dev environment
            //add_filter( 'the_content', [ $this, 'filter_the_content_in_the_main_loop' ], 1 );

            
        }

        /**
         * Returns the_content with the staging url replaced by the local dev url
         *
         * @return string
         */
        public function filter_the_content_in_the_main_loop( $content ) 
        {

            // Check if we're inside the main loop in a single Post.
            if ( is_singular() && in_the_loop() && is_main_query() ) {
                if( isset( $_ENV['ENVIRONMENT'] ) && $_ENV['ENVIRONMENT'] == 'DEV' && isset( $_ENV['STAGING'] ) ){
                    $stagingdomain = preg_replace('|^(https?:)?//|', '', $_ENV['STAGING']);
                    $devdomain = preg_replace('|^(https?:)?//|', '', WP_SITEURL);
                    $content = str_replace($stagingdomain, $devdomain, $content);
                }
            }

            return $content;
        }

        /**
         * Returns the url of the page or post on the staging-server
         * 
         * @return string
         */
        public function internal_link_to_staging(  $url, $post, $leavename ) 
        { 
            if( isset( $_ENV['ENVIRONMENT'] ) && $_ENV['ENVIRONMENT'] == 'DEV' && isset( $_ENV['STAGING'] ) ){
                $url = wp_make_link_relative($url);
            }
        
            return $url;
        }



    }