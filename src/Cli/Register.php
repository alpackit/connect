<?php

    namespace Alpackit\Connect\Cli;

    use WP_CLI;
    use Alpackit\Connect\Contracts\EventListener;

    class Register extends EventListener{

        public function listen()
        {
            if( defined( 'WP_CLI' ) && WP_CLI ){
                WP_CLI::add_command( 'alpackit versions push', [ '\\Alpackit\\Connect\\Cli\\VersionPush', 'push' ]);
            }
        }
    }