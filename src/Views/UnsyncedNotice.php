<?php

    namespace Alpackit\Connect\Views;

    use Alpackit\Connect\Contracts\View;

    class UnsyncedNotice extends View{

        protected $template = 'unsynced-notice';

        
        /**
         * Return the correct template data:
         *
         * @return array
         */
        public function get_template_data() : Array
        {
            return [
                'unsynced' => get_option('unsynced_packits', [] )
            ];
        }
        
    }