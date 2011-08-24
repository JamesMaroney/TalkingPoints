var AppRouter = function(cookies, win, ControllerFactory, server){
  this.routeData = { cookies: cookies };
  this.window = win;
  this.controllerFactory = new ControllerFactory(this, server);
  this.server = server;
}
AppRouter.prototype.boot = function(){
  this.controllerFactory.getController("Login")["index"]();
  return this;
}
AppRouter.prototype.goTo = function(){
  this.controllerFactory.getController("Teacher")["index"]();
}




var ViewEngine = {
  render: function(viewName, model, viewData){
    return new View($("#"+viewName), model, viewData);
  }
}


var View = function(viewName, model, viewData){
  this.viewName = viewName;
  this.model = model;
  this.viewData = viewData;
}
View.prototype.into = function(e){
  return $(e).html("").append(this.parse());
}
View.prototype.parse = function(html){
  return $.tmpl(this.viewName, this.model, this.viewData);
}
View.prototype.appendTo = function(e){
  return $(e).append(this.parse());
}


var ControllerFactory = function(appRouter, server){
  this.appRouter = appRouter;
  this.server = server;
}
ControllerFactory.prototype.getController = function(name){
  return new window[name + "Controller"](this.appRouter, ViewEngine, this.server);
}