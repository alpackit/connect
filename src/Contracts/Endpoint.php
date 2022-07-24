<?php

    namespace Alpackit\Connect\Contracts;

    abstract class Endpoint extends StaticInstance{

        /**
         * Endpoint url
         */
        public $endpoint;

        /**
         * Constructor:
         */
        public function __construct()
        {
            add_action( 'parse_request', [ $this, 'parse' ]);    
        }

        /**
         * Listen for API events:
         *
         * @return void
         */
        public function parse()
        {
            if( $this->check_endpoint() ){

                if( !$this->validate() ){
                    $this->unvalid_endpoint();
                }

                $this->before_render();
                
                $this->render();
                die();

            }
        }


        /**
         * Render this endpoint
         *
         * @return void
         */
        public function render()
        {
            echo $this->get_view()->render() ?? '';
            die();
        }


        /**
         * Things to run before a render
         *
         * @return void
         */
        public function before_render()
        {
            return;
        }

        /**
         * Return the correct view
         *
         * @return View
         */
        public function get_view()
        {
            return;
        }


        /**
         * Validate this endpoint
         *
         * @return bool
         */
        public function validate()
        {
           return true; 
        }


        /**
         * Handle an unvalidated endpoint
         *
         * @return void
         */
        public function unvalid_endpoint()
        {
            wp_die( 'You have no permission to view this.' );
        }


        /**
         * Check an endpoint
         *
         * @return bool
         */
        public function check_endpoint()
        {
            global $wp;   
            if( $wp->request == $this->endpoint ){
                $GLOBALS['current_endpoint'] = $this;
                return true;
            }
            
            return false;
        }
    }