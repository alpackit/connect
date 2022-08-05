<?php

    namespace Alpackit\Connect\Admin;

    use Alpackit\Connect\Contracts\EventListener;

    class Events extends EventListener{
        
        
        public function listen()
        {
            add_action( 'admin_init', [ $this, 'check_bucket' ]);
        }


        public function check_bucket()
        {
            $bucket = get_option('alpackit_bucket', '' );
            if( $bucket == '' ){

                //register a new bucket:
                global $as3cf;
                //set the right variables:
                $name = sanitize_title( get_bloginfo( 'name' ) );
                $region = 'eu-west-1';

                //remote create and then save the bucket:
                $response = $as3cf->create_bucket( $name, $region );
                if( !is_wp_error( $response ) ){
                    $as3cf->save_bucket( $name, true, $region );
                    update_option( 'alpackit_bucket', $name );
                }
            }
        }
    
    }