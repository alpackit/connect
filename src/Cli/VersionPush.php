<?php

    namespace Alpackit\Connect\Cli;

    use \WP_CLI;
    use Alpackit\Connect\Api\PluginVersionPush;

    class VersionPush extends \WP_CLI_Command{

        /**
         * Process all courses
         * 
         * @subcommand process
         */
        public function push( $args, $assoc_args )
        {            
            //run the logic:
            $version_push = new PluginVersionPush();
            $version_push->collect()->push();

            //save the timestamp: 
            update_option( 'last_alpackit_version_push', time() );

            \WP_CLI::success( "Packit versions pushed." );
        }
    }