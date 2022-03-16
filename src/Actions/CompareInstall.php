<?php

    namespace Alpackit\Connect\Actions;

    use Alpackit\Connect\Contracts\Action;

    class CompareInstall extends Action{

        /**
         * Handle the compare install request
         *
         * @return void
         */
        public function handle()
        {
            $response = $this->call( '/compare-install/'. $this->env['project_id'] );

            //handle any actions we need to take: 
            if( !is_null( $response ) ){


            }
        }
    }