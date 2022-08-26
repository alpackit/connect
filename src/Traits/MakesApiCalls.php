<?php

    namespace Alpackit\Connect\Traits;

    use Alpackit\Connect\Api\AccessToken;

    trait MakesApiCalls{

        /**
         * Check and set an access token to core
         *
         * @return void
         */
        public function check_and_set_access_token()
        {
            if( !AccessToken::valid() ){
                ( new AccessToken() )->request();
            }
        }

        /**
         * Remote call the Alpackit api:
         *
         * @return void
         */
        public function call( $url, $data = [] )
        {
            add_filter('https_ssl_verify', [ $this, 'needs_ssl_check' ]);

            //place the call:
            try{
                $response = $this->post( $url, $data );

            //unauthenticated request: 
            }catch( UnauthenticatedRequestException $error ){
                //echo $error ;
                
                
            }catch( \Throwable $error ){
                //dd( $error );
            
            }

            //return the response body:
            return $response;
        }


        /**
         * Post a request to the alpackit api
         *
         * @param string $url
         * @param array $data
         * @return Array (json)
         */
        public function post( $url, $args = [] )
        {
            //prepare the body of our request:
            $data = $this->sanitize_data( $args );
            
            $slug = $url;
            $request = wp_remote_post( $this->complete_url( $url ), $data );
            
            if( is_wp_error( $request ) ){
                throw new \Exception( $request->get_error_message() );

            //we need to ask for a new api token using the old one: 
            }else if( isset( $request['body']['message'] ) && $request['body']['message'] == 'Unauthenticated' ){
                throw new UnauthenticatedRequestException();
            }

            //turn it into an object:
            return json_decode( $request['body'] );
        }


        /**
         * Return the request body with all authenticatables
         *
         * @param array $args
         * @return array
         */
        public function sanitize_data( $args ) : array
        {
            $data = [];
            $data['method'] = 'POST';
            $data['body'] = $args;

            //add authentication information:
            $data = $this->add_authentication( $data );

            //expect json to return
            $data['headers']['content-type'] = 'application/json';
            $data['body'] = json_encode( $data['body'] );

            //return
            return $data;
        }


        /**
         * Add the authentication
         *
         * @param array $data
         * @return array
         */
        public function add_authentication( $data )
        {
            $data['body']['api_key'] = env('ALPACKIT_API_KEY') ?? '';
            $data['body']['api_secret'] = env('ALPACKIT_API_SECRET') ?? '';
            $data['body']['alpackit_user_id'] = $this->get_user_id();
        
            //set the access token if we have it:
            $access_token = get_option('alpackit_access_token', false );
            if( $access_token !== false && !is_null( $access_token ) && $access_token !== '' ){
                $data['headers']['authorization'] = 'Bearer '.$access_token;
            }

            //set the csrf token if we have it:
            $csrf_token = get_option('alpackit_csrf_token', false );
            if( $csrf_token !== false && !is_null( $csrf_token ) && $csrf_token !== '' ){
                $data['headers']['X-XSRF-TOKEN'] = $csrf_token;
            }

            return $data;
        }


        
        /**
         * Get the alpackit user id:
         *
         * @return string
         */
        public function get_user_id()
        {
            $user_id = get_current_user_id() ?? 1;
            $alpackit_user = get_user_meta( $user_id, 'alpackit_user_id', true );
            
            if( !$alpackit_user ){
                //throw an error here
            }

            return $alpackit_user;
        }


        /**
         * Return the url for our api
         *
         * @param string $url
         * @return void
         */
        public function complete_url( $url )
        {
            return 'http://alpackit.test/api/'. $url ;
        }

        /**
         * Disable SSL check on local
         *
         * @return void
         */
        public function needs_ssl_check( $value )
        {
            if( env('ALPACKIT_ENVIRONMENT') == 'LOCAL' ){
                return false;
            }

            return $value;
        }
    }