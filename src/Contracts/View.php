<?php

    namespace Alpackit\Connect\Contracts;

    //use Alpackit\Connect\Traits\DisplaysErrors;
    use \Alpackit\Connect\PluginIgniter;
    use \Alpackit\Connect\Actions\RequestAccessToken;

    abstract class View{

         //use DisplaysErrors;


        /**
         * Template file
         *
         * @var string
         */
        protected $template = '';



        /**
         * Render the template for this view
         *
         * @return void
         */
        public function render()
        {
            if( !$this->validate() ){
                wp_die( 'Your request isn\'t valid' );
            }
            
            $this->load_template();
        }


        /**
         * Load in any template if we have any
         *
         * @return void
         */
        public function load_template()
        {
            
            if( $this->template !== '' && !is_null( $this->template ) ){
                \get_alpackit_template( $this->template, $this->get_template_data() );
            
            }
        }


        /**
         * Validate request
         *
         * @return boolean
         */
        public function validate() : bool
        {
            $access_token = \get_option('alpackit_access_token', false );
            //if there's no access token, request that first:
            if( !$access_token ){

                $access_token = ( new RequestAccessToken() )->get_access_token();
                update_option( 'alpackit_access_token', $access_token );

            }

            return true;
        }

        /**
         * Returns the data needed for the template
         *
         * @return Array
         */
        public function get_template_data() : Array
        {
            return [];
        }        
    }