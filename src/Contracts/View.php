<?php

    namespace Alpackit\Connect\Contracts;

    abstract class View{

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