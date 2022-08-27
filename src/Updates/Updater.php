<?php

    namespace Alpackit\Connect\Updates;

    use Alpackit\Connect\Traits\UsesFileSystem;

    class Updater{

        use UsesFileSystem;

        /**
         * Packit download information
         *
         * @var array
         */
        protected $packit;

        /**
         * Construct
         *
         * @param array $packit
         */
        public function __construct( $packit )
        {
            $this->packit = $packit;
            $this->init_file_system();
        }


        /**
         * Update a packit
         *
         * @return void
         */
        public function update()
        {
            $response = [];
            //download the zip first: 
            $package = str_replace( 'https://', 'http://', $this->packit['download_link'] );
            $local_path = ABSPATH . $this->packit['local_path'].'.zip';

            //add zip to local:
            $result = file_put_contents( $local_path, file_get_contents( $package ) );
            $response[] = ['message' => 'file copy', 'result' => $result ];

            $packit_root = ABSPATH . $this->packit['folder'];
            $packit_path = ABSPATH . $this->packit['local_path'];

            $move = $this->system->move( $packit_path, $packit_path.'_2' );

            $response[] = ['message' => 'move folder', 'result' => $move ];

            unzip_file( $local_path, $packit_root );

            $response[] = ['message' => 'file unzipped'];
            //clean up:
            $this->system->delete( $local_path );
            $this->system->delete( $packit_path.'_2', true );

            return $response;
        }
    }