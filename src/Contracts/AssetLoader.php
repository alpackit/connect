<?php
namespace Alpackit\Connect\Contracts;


abstract class AssetLoader extends StaticInstance
{


    /**
     * Private constructor. Avoid building instances using the
     * 'new' keyword.
     */
    protected function __construct()
    {
        $this->load();
    }


    /**
     * Listen to events
     *
     * @return void
     */
    abstract public function load();


    /**
     * Return the path to an asset
     *
     * @param string $file
     * @return void
     */
    public function path( $file )
    {
        $base = \Alpackit\Connect\PluginIgniter::get_plugin_url();
        return $base . 'assets'. DS . 'dist' . DS . $file;
    }

} 