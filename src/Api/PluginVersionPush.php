<?php

    namespace Alpackit\Connect\Api;

    use Alpackit\Connect\Traits\MakesApiCalls;

    class PluginVersionPush{

        use MakesApiCalls;

        protected $data;

        /**
         * Constructor
         */
        public function __construct()
        {
            $data = [];

            //check if we have an access token before hand:
            if( !AccessToken::valid() ){
                ( new AccessToken() )->request();
            }
        }

        /**
         * Collect the information
         *
         * @return void
         */
        public function collect()
        {
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
                $this->data[ $slug ] = $version;
            }
            return $this;
        }

        /**
         * Send the data
         *
         * @return void
         */
        public function push()
        {
            $data = [
                'packits' => $this->data,
            ];

            //push plugin versions: 
            $this->call( 'packits/version-push', $data );
            echo 'hi';
        }

    }