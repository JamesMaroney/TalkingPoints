var RemoteServer = function(){

  var user = undefined;
  var talkingPoints = {};

  this.checkLogin = function(username, pass){
    return $.ajax("?task=userByCredentials", { cache: false, data: {username: username, password: pass}, dataType: "json", success: function(u){ user = u } });
  }
  this.checkLoginToken = function(username, token) {
    return $.ajax("?task=userByTokenCredentials", { cache: false, data: { username: username, token: token }, dataType: "json", success: function(u){ user = u } });
  }
  this.getTalkingPoints = function(handle){
    return $.ajax("?task=getTalkingPoints", { cache: false, data: { handle: handle}, dataType: "json", success: function(p){ talkingPoints[handle] = p.points.join("|")} } );
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
}