<?

describe("Initialization", function(){

  it("should require at least one parameter", function(){
    expect( function(){
      new ConfigManager();
    } )->to_throw("Exception", ConfigManager::InvalidConstructionException);
  });

});

describe("An instance", function(){
  $loadedConfig = new stdClass();
  $loadedConfig->myKey = "myVal";
  $config = new ConfigManager($loadedConfig);

  it("should delegate to it's internal configuration object for config entry requests", function() use($config, $loadedConfig){
    expect($config->myKey)->to_be($loadedConfig->myKey);
  });
});