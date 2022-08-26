<?php

    namespace Alpackit\Connect\Traits;

    use Exact\Exceptions\FileSystemNotAvailableException;

    trait UsesFileSystem{

        /**
         * Copy of the global file system
         *
         * @var WP_Filesystem
         */
        protected $system;

        /**
         * Constructor
         */
        public function init_file_system()
        {
            if( !function_exists( 'request_filesystem_credentials' ) ){
                require ABSPATH . 'wp-admin/includes/file.php';
            }
            
            if( ( $creds = \request_filesystem_credentials( admin_url(), '', false, false, null ) ) === false ) {
                throw new FileSystemNotAvailableException( 'Filesystem credentials aren\'t available' );
				return; // stop processing here
			}

			if ( ! WP_Filesystem( $creds ) ) {
				\request_filesystem_credentials( admin_url(), '', true, false, null);
				return;
            }
            
            global $wp_filesystem;
            
            if( !isset( $wp_filesystem ) ){
                throw new FileSystemNotAvailableException( 'The file class can only be called after the wp hook' );
                return;
            }

            $this->system = $wp_filesystem;
        }
    }