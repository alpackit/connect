<?php

    namespace Alpackit\Connect\Ajax;

    use Alpackit\Connect\Updates\Updater;
    use Alpackit\Connect\Contracts\AjaxListener;

    class SyncPackit extends AjaxListener{

        protected $endpoint = 'sync-packit';

        /**
         * Sync a single packit
         * 
         * @return string
         */
        public function handle()
        {
            $cursor = $_POST['cursor'] ?? 0;
            $list = $_POST['packit_list'] ?? '';
            $list = json_decode( stripslashes( $list ), true );
            $data = $list[ $cursor ];

            $response = ( new Updater( $data ) )->update();

            if( is_array( $response ) ){

                $this->no_longer_unsynced( $data );
                $this->message( 'Packit '.$data['slug'].' updated.');
            }else{
                $this->error( 'somethign wieakls ' );
            }

            
        }

        /**
         * Save the fact that we updated this packit
         *
         * @param array $data
         * @return void
         */
        public function no_longer_unsynced( $data )
        {
            $unsynced = get_option('unsynced_packits', [] );
            foreach( $unsynced as $key => $synced ){
                if( !is_array( $synced ) ){
                    $synced = (array)$synced;
                }

                if( $synced['id'] == $data['origin_id'] ){
                    unset( $unsynced[ $key ] );
                }
            }

            update_option( 'unsynced_packits', $unsycned );
        }

    }