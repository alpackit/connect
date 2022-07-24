<?php

    namespace Alpackit\Connect\Admin;

    use Alpackit\Connect\Contracts\EventListener;
    use Alpackit\Connect\Views\PullContent;
    use Alpackit\Connect\Views\PullMedia;

    class Menu extends EventListener{

        /**
         * Listen for the admin menu actions
         *
         * @return void
         */
        public function listen()
        {
            // Admin bar: 
            add_action( 'init', [ $this, 'handle_request' ]);
            add_action( 'admin_bar_menu', [ $this, 'register_admin_bar' ], 900 );
            add_action( 'admin_menu', [ $this, 'register_pages' ], 900 );
        }


        public function register_pages()
        {
            //add menu page: 
            add_menu_page( 
                'Alpackit', 
                __( 'Alpackit', 'alpackit-connect' ), 
                'manage_options', 
                'alpackit', 
                '__return_false', 
                'dashicons-pets',
                150
            );


            add_submenu_page( 
                'alpackit',
                __( 'Pull content', 'alpackit-connect' ),
                __( 'Pull content', 'alpackit-connect' ), 
                'manage_options', 
                'pull-content', 
                function(){
                    ( new PullContent() )->render();
                },
                1
            );


            add_submenu_page( 
                'alpackit',
                __( 'Pull media', 'alpackit-connect' ),
                __( 'Pull media', 'alpackit-connect' ), 
                'manage_options', 
                'pull-media', 
                function(){
                    ( new PullMedia() )->render();
                },
                2
            );
        }



        /**
         * Register the admin bar menu
         *
         * @return void
         */
        public function register_admin_bar( $admin_bar )
        {
            $admin_bar->add_menu([
                'id'    => 'alpackit-connect',
                'title' => 'Alpackit',
                'href'  => '#'
            ]);


            $url = wp_nonce_url( admin_url(), 'alpackit-pull-content', '_apc_nonce' );
            $admin_bar->add_menu([
                'id'        => 'alpackit-pull-content',
                'parent'    => 'alpackit-connect',
                'title'     => __( 'Pull content', 'alpackit' ),
                'href'      => $url 
            ]);

            $url = wp_nonce_url( admin_url(), 'alpackit-pull-media', '_apc_nonce' );
            $admin_bar->add_menu([
                'id'        => 'alpackit-pull-media',
                'parent'    => 'alpackit-connect',
                'title'     => __( 'Pull media', 'alpackit' ),
                'href'      => $url
            ]);
        }

        /**
         * Handle an incoming request
         *
         * @return void
         */
        public function handle_request()
        {
            if( isset( $_GET['_apc_nonce'] ) ){

                $nonce = $_GET['_apc_nonce' ];
                $controller = null;

                if( wp_verify_nonce( $nonce, 'alpackit-pull-content' ) ){
                    
                }else if( wp_verify_nonce( $nonce, 'alpackit-pull-media' ) ){

                }

                //check if we have a controller
                if( is_null( $controller ) ){
                    wp_die( 'Something went wrong' );
                }

                $controller->render();
                die();
            }
        }

    }