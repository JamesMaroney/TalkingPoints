<?php
require_once("underscore.php");

class ConfigManager {

  const InvalidConstructionException = "ConfigManager must be provided at least one config root path or a configuration object in it's constructor.";
  const ConfigurationReadOnlyException = "The configuration is read-only. The configuration files must be edited to modify the configuration.";

  public $config;

  public function __construct(){
    $params = func_get_args();
    if(count($params) == 0 ) throw new Exception(self::InvalidConstructionException);

    if(count($params) == 1 && get_class($params[0]) == "stdClass") {
      $this->config = $params[0];
    } else {
      $configFiles = __::map(func_get_args(), function($path){ return "$path/config"; });
      $configContents = array_merge(
        array((object) array()),
        __::map($configFiles, function($configFile){ return json_decode(file_get_contents($configFile)); })
      );
      $this->config = call_user_func_array( array("__", "extend"), $configContents);
    }
  }

  public function __get($key){
    return $this->config->$key;
  }

  public function __set($key, $val){
    throw new Exception( self::ConfigurationReadOnlyException );
  }
}