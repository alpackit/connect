<?php

    namespace Alpackit\Connect\Api;

    use Alpackit\Connect\Traits\MakesApiCalls;

    class PluginVersionFetch{

        use MakesApiCalls;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->check_and_set_access_token();
        }

        /**
         * Collect the information
         *
         * @return void
         */
        public function collect()
        {
            $plugin_data = [];
            $plugins = get_option('active_plugins');
            foreach( $plugins as $plugin ){
                //get the slug:
                $slug = explode( '/', $plugin );
                $slug = $slug[0];

                //get the data:
                $path = WP_PLUGIN_DIR . DS . $plugin;
                $data = get_plugin_data( $path );
                $version = $data['Version'];

                //set data:
                $plugin_data[ $slug ] = $version;
            }

            return $plugin_data;
        }

        /**
         * Send the data
         *
         * @return void
         */
        public function fetch()
        {
            //fetch plugin versions: 
            $listed_packits = $this->call( 'packits/list', $data );
            $unsynced = [];

            //if we have credible data:
            if( is_array( $listed_packits ) && !empty( $listed_packits ) ){
                
                //check against our local data:
                $local_packits = $this->collect();
                
                foreach( $listed_packits as $packit ){

                    if( !isset( $local_packits[ $packit->slug ] ) ){
                        $unsynced[] = $packit;   
                    //we have a version mishmash!
                    }else if( $packit->pivot->version !== $local_packits[ $packit->slug ] ){
                        $packit = (array)$packit;
                        $packit['local_version'] = $local_packits[ $packit['slug'] ];
                        $unsynced[] = $packit;
                    }
                }
            }

            update_option( 'unsynced_packits', $unsynced );
        }

    }