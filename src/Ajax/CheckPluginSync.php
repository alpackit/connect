<?php

    namespace Alpackit\Connect\Ajax;

    use Alpackit\Connect\Traits\MakesApiCalls;
    use Alpackit\Connect\Contracts\AjaxListener;

    class CheckPluginSync extends AjaxListener{

        use MakesApiCalls;

        /**
         * Get a list of plugins to sync up
         *
         * @return string
         */
        public function handle()
        {
            $this->check_and_set_access_token();

            try{

                //first, list all packits on staging, with download links:
                $url = 'packits/list';
                $response = $this->call( $url );

                //echo the list:
                echo json_encode( $response['packits'] );
                die();

            }catch( \Throwable $error ){
                wp_die( 'Couldnt get an access token' );
            
            } 
        }

    }