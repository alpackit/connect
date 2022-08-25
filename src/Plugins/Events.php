<?php

    namespace Alpackit\Connect\Plugins;

    use Alpackit\Connect\Api\PluginVersionPush;
    use Alpackit\Connect\Api\PluginVersionFetch;
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

                
                if( env('ALPACKIT_ENVIRONMENT') == 'LOCAL' ){
                    $this->fetch_version_list();
                }else if( env('ALPACKIT_ENVIRONMENT') == 'STAGING' ){
                    $this->push_version_list();
                }

                //check for unsynced plugins and throw a notice:
                add_action('admin_notices', [ $this, 'add_unsynced_notice' ]);

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


        public function add_unsynced_notice()
        {
            global $pagenow;
            $unsynced = get_option( 'unsynced_packits', [] );
            
            if( !empty( $unsynced ) ){
                if ( $pagenow == 'index.php' || $pagenow == 'update-core.php' ) {
                    echo '<div class="notice notice-warning is-dismissible packit-warning">';
                        echo '<h3>';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10.5v3.75m-9.303 3.376C1.83 19.126 2.914 21 4.645 21h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 4.88c-.866-1.501-3.032-1.501-3.898 0L2.697 17.626zM12 17.25h.007v.008H12v-.008z" />
                            </svg>';
                            echo esc_html__( 'Unsynced packits', 'alpackit' );
                        echo '</h3>';

                        echo '<p>'.esc_html__( 'You have plugins that are out of sync with their versions on ', 'alpackit');
                            echo '<a href="'.\esc_attr( env('ALPACKIT_STAGING_URL') ).'" target="_blank">Alpackit staging</a>';
                        echo '.</p>';
                        echo '<div class="alpackit-link-wrapper">';
                            echo '<a href="#" class="button button-primary">'.esc_html__( 'Automatically sync them', 'alpackit').'</a>';
                            echo '<button class="link" id="reveal-unsynced-data">'.esc_html__( 'More information', 'alpackit' ).'</a>';
                        echo '</div>';
                        echo '<ul class="alpackit-update-overview">';
                            foreach( $unsynced as $packit ){
                                echo '<li>'.$packit['name'];
                                echo ' - <b>staging version:</b> '.$packit['pivot']->version;
                                echo ' - <b>local version:</b> '.$packit['local_version'];
                                echo '</li>';
                            }
                        echo '<ul>';
                    echo '</div>';
                }
            } 
        }


        /**
         * Fetch a list of plugin versions on staging:
         *
         * @return void
         */
        public function fetch_version_list()
        {
            //trigger a version push to alpackit core:
            if( get_option('last_alpackit_version_fetch', 0 ) < strtotime( '-2 hours' ) ){ 
                //run the logic:
                $plugin_list = new PluginVersionFetch();
                $plugin_list->fetch();

                //save the timestamp: 
                update_option( 'last_alpackit_version_fetch', time() );
            }
        }


        /**
         * Push a list of plugin versions to alpackit core
         *
         * @return void
         */
        public function push_version_list()
        {
            //trigger a version push to alpackit core:
            if( get_option('last_alpackit_version_push', 0 ) < strtotime( '-10 hours' ) ){ 
                //run the logic:
                $version_push = new PluginVersionPush();
                $version_push->collect()->push();

                //save the timestamp: 
                update_option( 'last_alpackit_version_push', time() );
            }
        }

    }