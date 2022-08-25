<?php

    namespace Alpackit\Connect\Plugins;

    use Alpackit\Connect\Api\PluginVersionPush;
    use Alpackit\Connect\Contracts\EventListener;

    class Events extends EventListener{

        public function listen()
        {
            //check for the plugins activated hook:
            add_action( 'activated_plugin', [ $this, 'on_activate' ], 100, 2);
            add_action( 'deactivated_plugin', [ $this, 'on_deactivate' ], 100, 2);
                
            //filter the plugins to load:
            add_action('admin_init',function(){

                //don't deactivate non-existing plugins on plugins.php
                add_filter( 'option_active_plugins', [ $this, 'filter_plugins' ]);

                
                //trigger a version push to alpackit core:
                //if( get_option('last_alpackit_version_push', 0 ) < strtotime( '-10 hours' ) ){ 
                    //run the logic:
                    $version_push = new PluginVersionPush();
                    $version_push->collect()->push();

                    //save the timestamp: 
                    update_option( 'last_alpackit_version_push', time() );
                //}


                //show non-active plugins by other users / systems here:
                //add_filter( 'all_plugins', [ $this, 'add_non_local_plugins' ]);
            });


        }

        /**
         * When a plugin gets activated
         *
         * @param string $plugin
         * @param boolean $network_wide
         * @return void
         */
        public function on_activate( $plugin, $network_wide )
        {
            $recent = get_option( 'recent_plugins', [] );
            if( !in_array( $plugin, array_keys( $recent ) ) ){

                $path = WP_CONTENT_DIR . '/plugins/'.$plugin;
                $data = get_plugin_data( $path );

                $recent[ $plugin ] = $data;
                $recent[ $plugin ]['slug'] = $plugin;
            }

            update_option( 'recent_plugins', $recent );
        }


        /**
         * Remove on deactivate
         *
         * @return void
         */
        public function on_deactivate( $plugin )
        {
            $recent = get_option( 'recent_plugins', [] );
            if( isset( $recent[ $plugin ] ) ){
                unset( $recent[ $plugin ] );
                update_option( 'recent_plugins', $recent );
            }
        }

        /**
         * Filter out non-active plugins
         *
         * @param array $plugins
         * @return void
         */
        public function filter_plugins( $plugins )
        {
            if( !is_array( $plugins ) ){
                $plugins = array();
            }

            if ( \is_multisite() && \current_user_can( 'manage_network_plugins' ) ) {
                $network_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
                $plugins         = array_merge( $plugins, array_keys( $network_plugins ) );
            }

            if ( empty( $plugins ) ) {
                return array();
            }

            $invalid = array();

            // Invalid plugins should not get deactivated.
            foreach ( $plugins as $key => $plugin ) {
                $result = \validate_plugin( $plugin );
                if( \is_wp_error( $result ) ) {
                    unset( $plugins[ $key ] );
                }
            }
            return $plugins;
        }


        /**
         * Get the non local plugins
         *
         * @return void
         */
        public function add_non_local_plugins( $plugins )
        {
            $recent = get_option( 'recent_plugins', []);
            foreach( $recent as $key => $plugin ){
                if( !isset( $plugins[ $key ] ) ){
                    $plugins[ $key ] = $plugin;
                } 
            }

            return $plugins;
        }

        /**
         * Trigger an alpackit version push
         *
         * @return void
         */
        public function trigger_version_push()
        {
            
        }

    }