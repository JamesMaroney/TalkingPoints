var LocalServer = function(){
  teachers = {
    james: {
      name: "Mr. Maroney",
      classes: [
        { name: "First Period", handle: "xyz123" }
      ]
    },
    ddimsdale: {
      name: "Mr. Dimsdale",
      classes: [
        { name: "My Class", handle: "akhd" }
      ]
    }
  };
  talkingPoints = {
    xyz123: [ "first point", "second point" ]
  }
  user = undefined;

  window.dump = function(){
    console.log( { teachers: teachers, talkingPoints: talkingPoints, user: user } )
  }

  function w(func){
    return function(){
      var result = func.apply(undefined, arguments);
      return { success: function(f){ f(result); } }
    }
  }

  this.checkLogin = w(function(name,pass) {
    if(!teachers[name]){
      teachers[name] = { name: name, classes: [ ] }
    }
    user = name;
    return teachers[name];
  })
  this.checkLoginToken = w(function(name,token){
    if(teachers[name].token == token) return teachers[name];
  })
  this.getTalkingPoints = w(function(handle) {
    return {
      points: talkingPoints[handle]
    };
  })
  this.getTeacherInfo = w(function(){
    return teachers[user];
  })
  this.saveTalkingPoints = w(function(handle,points) {
    talkingPoints[handle] = points;
    return {  };
  })
  this.checkServerStatus = w(function(){
    return { acceptingSubmissions: true }
  })
}