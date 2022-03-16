<?php
namespace Alpackit\Connect;

class Autoloader
{

    /**
     * Load the initial static files:
     *
     * @return void
     */
    public function load()
    {
        

    }


    /**
     * Register the autoloader
     *
     * @return BbqOrders\Autoloader
     */
    public function register()
    {
        spl_autoload_register(function ($class) {

            if ( stripos( $class, __NAMESPACE__ ) === 0 ) {

                $filePath = str_replace( '\\', DS, substr( $class, strlen( __NAMESPACE__ ) ) );
                include( __DIR__ . DS . 'src' . $filePath . '.php' );

            }

        });

        return $this;
    }
}