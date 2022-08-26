<?php

    namespace Alpackit\Connect\Ajax;

    use Alpackit\Connect\Traits\MakesApiCalls;
    use Alpackit\Connect\Contracts\AjaxListener;

    class GetRemoteFileList extends AjaxListener{

        use MakesApiCalls;

        protected $endpoint = 'get-remote-file-list';

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
                $url = 'packits/list-downloads';
                $packits = $_POST['packits'] ?? [];
                
                if( empty( $packits ) ){
                    $this->error( __( 'No packits to update.', 'alpackit' ) );
                }

                $data = ['packit_ids' => $packits ];
                if( $_POST['workflow_id'] != 0 && $_POST['workflow_id'] !== false && !is_null( $_POST['workflow_id']) ){
                    $data['workflow_id'] = \absint( $_POST['workflow_id'] );
                }
                
                $response = $this->call( $url, $data );
                
                //echo the list:
                echo json_encode( $response );
                die();

            }catch( \Throwable $error ){
                wp_die( 'Couldnt get an access token' );
            
            } 
        }

    }