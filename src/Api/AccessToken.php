<?php

    namespace Alpackit\Connect\Api;

    use Alpackit\Connect\Traits\MakesApiCalls;

    class AccessToken{

        use MakesApiCalls;

        /**
         * Request a new access token
         *
         * @return void
         */
        public function request()
        {
            //first the CSRF cookie:
            //$response = $this->set_csrf_cookie();
            //then the access-token:
            $response = $this->call( 'request-access-token' );
            if( isset( $response->access_token ) ){
                update_option( 'alpackit_access_token', $response->access_token );
                update_option( 'alpackit_access_token_timestamp', time() );
            }
        }


        /**
         * Check if a token exists
         *
         * @return void
         */
        public static function exists()
        {
            $token = get_option('alpackit_access_token', false );
            return $token;
        }

        /**
         * Is the access token valid?
         *
         * @return void
         */
        public static function valid()
        {
            $timestamp = get_option( 'alpackit_access_token_timestamp', 0 );
            return ( $timestamp > strtotime( '-1 day' ) );
        }


        /**
         * Get the CSRF cookie
         *
         * @return void
         */
        public function set_csrf_cookie()
        {
            $url = str_replace( '/api', '', $this->complete_url( 'sanctum/csrf-cookie') );
            $request = wp_remote_get( $url );
            if( !empty( $request['cookies'] ) ){
                foreach( $request['cookies'] as $cookie ){
                    if( $cookie->name == 'XSRF-TOKEN' ){
                        update_option( 'alpackit_csrf_token', $cookie->value );
                    }
                }
            }
        }
    }