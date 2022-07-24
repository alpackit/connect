<?php
namespace Alpackit\Connect\Contracts;

abstract class AjaxListener extends EventListener
{

    protected $endpoint = '';
    protected $public = false;

    /**
     * Listen for this ajax call
     *
     * @return void
     */
    public function listen()
    {
        add_action( 'wp_ajax_'.$this->endpoint, [ $this, 'run' ]);

        if( $this->public ){
            add_action( 'wp_ajax_nopriv_'.$this->endpoint, [ $this, 'run' ]);
        }
    }


    /**
     * Run this action
     *
     * @return void
     */
    public function run()
    {
        if( $this->validate() ){
            $this->handle();
        }

        //always die at the end of an ajax request
        die();
    }

    /**
     * Handle this ajax call
     *
     * @return void
     */
    public function handle()
    {
        die;
    }

    /**
     * Validate this action
     *
     * @return boolean
     */
    public function validate() : bool
    {
        return true;
    }


    /**
     * Echo a message back to javascript
     *
     * @param string $message
     * @return void
     */
    public function message( string $message )
    {
        echo json_encode(['error' => false, 'message' => $message ]);
    }


    /**
     * Echo an error back to javascript
     *
     * @param string $message
     * @return void
     */
    public function error( string $message )
    {
        echo json_encode(['error' => true, 'message' => $message ]);
    }

} 