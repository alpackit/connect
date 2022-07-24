<?php

    namespace Alpackit\Connect\Ajax;

    use Alpackit\Connect\Traits\MakesApiCalls;
    use Alpackit\Connect\Contracts\AjaxListener;

    class RequestAccessToken extends AjaxListener{

        use MakesApiCalls;

        protected $endpoint = 'request-access-token';

        /**
         * Get an access token
         *
         * @return string
         */
        public function handle()
        {
            try{
                $url = 'request-access-token';
                $response = $this->call( $url );
                
                //set the csrf and access tokens + add timestamps
                update_option( 'alpackit_access_token', $response->access_token, true );
                update_option( 'alpackit_csrf_token', $response->csrf_token, true );
                update_option( 'alpackit_token_timestamp', time() );
            
            }catch( \Throwable $error ){
                wp_die( 'Couldnt get an access token' );
            
            } 
        }

    }