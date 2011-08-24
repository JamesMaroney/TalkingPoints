var LoginController = function(appRouter, viewEngine, server){
  this.viewEngine = viewEngine;
  this.router = appRouter;
  this.server = server;
}
LoginController.prototype.index = function(){
  var self = this;

  var userAuthToken;
  if(userAuthToken = Cookies.get("uat")){
    userAuthToken = userAuthToken.split("|");
    this.server.checkLoginToken(userAuthToken[0], userAuthToken[1])
      .success(function(user){
          if(user.username){
            self.router.goTo("Teacher");
          } else {
            Cookies.remove("uat");
            self.login();
          }
        })
  } else {
    this.login();
  }
}
LoginController.prototype.login = function(){
  var self = this;
  var view = this.viewEngine.render("login").into("#bd");
  $("input",view).first().focus();
  function submit(){
    var f = { name: $('#teacher-name',view), pass: $('#teacher-pass',view) }
    if(!f.name.val()){ f.name.focus(); return false; }
    if(!f.pass.val()){ f.pass.focus(); return false; }

    self.server.checkLogin(f.name.val(),f.pass.val())
      .success(function(response){
        if( response.username ){
          Cookies.add("uat", [response.username, response.token].join("|"))
          self.router.goTo("Teacher")
        }
      });
  }
  $('form',view).bind('submit', submit);
  if($.browser.msie && $.browser.version < 8){
    $('button',view).bind('click', submit);
    $('input',view).bind('keyup',function(ev){
      if( ev.keyCode === 13 ){
        submit();
        return false;
      }
    })
  }
}