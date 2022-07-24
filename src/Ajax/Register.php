<?php

    namespace Alpackit\Connect\Ajax;

    use Alpackit\Connect\Contracts\StaticInstance;

    class Register extends StaticInstance{

        /**
         * array of all ajax endpoints
         *
         * @var array
         */
        protected $endpoints;


        /**
         * Register all endpoints:
         */
        public function __construct()
        {
            $this->endpoints = [
                'get_remote_file_list'    => GetRemoteFileList::get_instance(),
                'sync_media'              => SyncMedia::get_instance(),
            ];
        }


        /**
         * Get the available endpoints
         *
         * @return void
         */
        public function get_endpoints()
        {
            return $this->endpoints;
        }


        /**
         * Get the slug of an endpoint
         *
         * @param string $endpoint
         * @return string
         */
        public function get_slug( $endpoint )
        {            

            $instance = $this->get( $endpoint );
            if( !is_null( $instance ) ){
                return $instance->endpoint;
            }

            return '';
        }

        
        /**
         * Return a single endpoint
         *
         * @param string $endpoint
         * @return Endpoint
         */
        public function get( $endpoint )
        {
            if( isset( $this->endpoints[ $endpoint ] ) ){
                return $this->endpoints[ $endpoint];
            }
        }

    }