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
    }