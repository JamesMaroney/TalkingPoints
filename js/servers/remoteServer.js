var RemoteServer = function(){

  var user = undefined;
  var talkingPoints = {};

  this.checkLogin = function(username, pass){
    return $.ajax("?task=userByCredentials", { data: {username: username, password: pass}, cache: false, dataType: "json", success: function(u){ debugger; user = u }, converters: { "text json": function(input){ return jQuery.parseJSON(input.replace(/<script.*$/,'')) } } });
  }
  this.checkLoginToken = function(username, token) {
    return $.ajax("?task=userByTokenCredentials", { data: { username: username, token: token }, cache: false , dataType: "json", success: function(u){ user = u }, converters: { "text json": function(input){ return jQuery.parseJSON(input.replace(/<script.*$/,'')) } } });
  }
  this.getTalkingPoints = function(handle){
    return $.ajax("?task=getTalkingPoints", { data: { handle: handle}, cache: false, dataType: "json", success: function(p){ talkingPoints[handle] = p.points.join("|")}, converters: { "text json": function(input){ return jQuery.parseJSON(input.replace(/<script.*$/,'')) } } } );
  }
  this.getTeacherInfo = function(){
    if(!user) throw Error("Teacher info cannot be retrieved because a user is not logged in");
    return { success: function(f){ f.call(undefined, user) } };
  }
  this.saveTalkingPoints = function(handle,points){
    var newhash = points.join("|");
    if(!talkingPoints[handle] || talkingPoints[handle] != newhash){
      talkingPoints[handle] = newhash;
      var cmdData = { task: "saveFeed", handle: handle, points: points };
      return $.ajax({
        type: "post",
        data: cmdData,
        dataType: "json"
      });
    } else {
      return { success: function(f){ }}
    }
  }
  this.checkServerStatus = function(){
    return $.ajax("?task=systemStatus", { cache: false, dataType: "json", converters: { "text json": function(input){ return jQuery.parseJSON(input.replace(/<script.*$/,'')) } } } );
  }
}