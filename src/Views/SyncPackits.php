<?php

    namespace Alpackit\Connect\Views;

    use Alpackit\Connect\Contracts\View;
    use Alpackit\Connect\Updates\Updater;

    class SyncPackits extends View{

        protected $template = 'sync-packits';

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