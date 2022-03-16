<?php
namespace Alpackit\Connect\Contracts;

abstract class StaticInstance
{


    /**
     * Static bootstrapped instance.
     *
     * @var \BbqOrders\Contracts\StaticInstance
     */
    public static $instance = null;


    /**
     * Private constructor. Avoid building instances using the
     * 'new' keyword.
     */
    protected function __construct()
    {
    }


    /**
     * Init the Assets Class
     *
     * @return \BbqOrders\Contracts\StaticInstance
     */
    public static function get_instance()
    {

        return static::$instance = new static();

    }


} 