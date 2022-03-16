<?php
namespace Alpackit\Connect\Contracts;

abstract class AjaxListener extends EventListener
{


    /**
     * WordPress doesn't keep the post-global around on an ajax request, 
     * so we do it this way
     *
     * @return void
     */
    protected function set_post_global()
    {

        global $post;
        if (!isset($GLOBALS['post']) && isset($_POST['post_id'])) {
            $GLOBALS['post'] = new \stdClass();
            $GLOBALS['post']->ID = absint($_POST['post_id']);
        }
    }


} 