<?php

class Config
{
    protected $settings;
 
    public function __construct()
    {
        $this->settings = array();
    }
 
    public static function load($path)
    {
        $config = new static();
 
        if (file_exists($path))
            $config->settings = include $path;
 
        return $config;
    }

    public function get($option, $defaultValue = null)
    {
        if ( ! isset($this->settings[$option]) and ! is_null($defaultValue))
            return $defaultValue;
            
        if ( ! isset($this->settings[$option]))
            return null;
     
        return $this->settings[$option];
    }

    public function set($option, $value)
    {
        $this->settings[$option] = $value;
    }
}
