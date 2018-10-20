<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');

class CFX_Model
{
    protected $db;
    protected $common;
    protected $config;

    public function __construct()
    {
        if (ENVIRONMENT == 'development' && !($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET'))
            echo '<!--'.get_class($this).'::initialize()-->'.PHP_EOL;

        $CFX =& get_instance();

        $this->db = $CFX->db;
        $this->common = $CFX->common;
        $this->config = $CFX->config;
    }

    public function class_type()
    {
        return "Model";
    }
}
?>