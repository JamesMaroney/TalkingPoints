<!DOCTYPE html>
<html>
  <head>
    <title>Talking Points</title>
    <!-- <link href='http://fonts.googleapis.com/css?family=Love+Ya+Like+A+Sister&v2' rel='stylesheet' type='text/css'> -->
    <link href='http://fonts.googleapis.com/css?family=Waiting+for+the+Sunrise|Annie+Use+Your+Telescope' rel='stylesheet' type='text/css'>
    <link href="shared.css" rel="stylesheet" type="text/css" />
    <link href="teacher.css" rel="stylesheet" type="text/css" />
    <link href="themes.css" rel="stylesheet" type="text/css" />
    <link href="favicon.ico" rel="icon" type="image/ico" />
    <link href="favicon.ico" rel="shortcut icon" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  </head>
  <body>

    <div id="pg">
      <div id="hd">
        <h1>Talking Points</h1>
      </div>
      <div id="bd"></div>
    </div>
    <!--
    <div id="ft">
      <a href="#">classes</a>
      <a href="#">feedback</a>
    </div>
    -->

    <script type="text/x-jquery-tmpl" id="teacher-panel">
      <h2>${teacher.name}<br />${date}</h2>
      <div class="classes"></div>
      <!-- <a href="javascript:void(0)" id="addClass">+ Add a Class</a> -->
    </script>

    <script type="text/x-jquery-tmpl" id="teacher-class">
      <h3>${klass.name} <span class="handle">instructions for parents &gt;<span class="instructions">To receive daily talking points from ${teacher.name},<br />text <strong>${klass.handle}</strong> to <strong>(404) 919-0173</strong><!-- or email<br /><strong>${klass.handle}</strong> to <strong>abc@xyz.com</strong> --><br />Visit www.xyz.com to learn more about Talking Points.</span></span></h3>
      <ul>
      </ul>
    </script>

    <script type="text/x-jquery-tmpl" id="class-points">
      {{each(i,point) points}}
        <li contenteditable="true">${point}</li>
      {{/each}}
    </script>

    <script type="text/x-jquery-tmpl" id="login">
      <form class="login" action="javascript:void(0)">
        <label for="teacher-name">Teacher's Name:</label><input type="text" id="teacher-name" /><br />
        <label for="teacher-pass">Password:</label><input type="password" class="password" id="teacher-pass" /><br />
        <button>Sign In</button>
      </form>
    </script>


    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.min.js"></script>
    <script type="text/javascript" src="js/underscore.js"></script>
    <script type="text/javascript">
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




      var Cookies = {
        get: function(name){ },
        add: function(name,value,timeout){ },
        remove: function(name) { }
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

      var LoginController = function(appRouter, viewEngine, server){
        this.viewEngine = viewEngine;
        this.router = appRouter;
        this.server = server;
      }
      LoginController.prototype.index = function(){
        var view = this.viewEngine.render("login").into("#bd");
        $("input",view).first().focus();
        var self = this;
        function submit(){
          var f = { name: $('#teacher-name',view), pass: $('#teacher-pass',view) }
          if(!f.name.val()){ f.name.focus(); return false; }
          if(!f.pass.val()){ f.pass.focus(); return false; }

          self.server.checkLogin(f.name.val(),f.pass.val())
            .success(function(response){
              if( response.username )
                self.router.goTo("Teacher")
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






      var TeacherController = function(appRouter, viewEngine, server){
        this.viewEngine = viewEngine;
        this.router = appRouter;
        this.server = server;
      }
      TeacherController.prototype.index = function(){
        /* Helper functions */
        var saveTimeout;
        function scheduleSave(el){
          var body = $("body").addClass("saving");
          if(!saveTimeout) {
            saveTimeout = setTimeout(function(){
              body.removeClass("saving");
              saveTimeout = undefined;
            },2000)
          }
        }
        function appendNewLineTo(e){
          $("<li />").attr({ 'contentEditable': true})
                      .addClass("empty")
                      .html("Click here to create a talking point!")
                      .appendTo(e);
        }
        function insertAnotherLineIfNeeded(e, includeSelf){
          var points = $(e).siblings('li');
          if(includeSelf !== false) points = points.add(e);
          if(points.length < 3 && points.filter(".empty").length == 0){
            appendNewLineTo($(e).parent());
          }
        }
        function isEmpty(e){
          return $(e).text().replace(/\s+/,'') === ""
        }

        function bindEvents(container){
          $(".empty", container)
          .live("focus", function(){
            $(this).html(" ").removeClass("empty").focus();
          })/*
          .live("blur", function(){
            if(isEmpty(this)) $(this).html("Click here to create a talking point!").addClass("empty")
          })*/
        $("li", container)
          .live("keyup", function(){
            e = $(this);
            e.text() ? e.removeClass("empty") : e.addClass("empty");
            insertAnotherLineIfNeeded(this);
            scheduleSave(this);
          })
          .live("blur", function(){
            if(isEmpty(this)){
              insertAnotherLineIfNeeded(this, false);
              $(this).remove();
            }
            scheduleSave();
          })
        }

        var self = this;
        this.server.getTeacherInfo()
          .success(function(teacher){
            var classesContainer = self.viewEngine.render("teacher-panel", {date: new Date().toDateString(), teacher: teacher})
                                                  .into("#bd").find(".classes");
            bindEvents(classesContainer);
            _.each(teacher.classes, function(c){
              var view = self.viewEngine.render("teacher-class", { klass: c, teacher: teacher })
                  .appendTo(classesContainer);
              self.server.getTalkingPoints(c.handle)
                .success(function(response){
                  var pointsView = self.viewEngine.render("class-points", { points: response.points })
                      .into(view.find("ul"));

                  var points = view.find("li");
                  points.length ? insertAnotherLineIfNeeded(view.find("li")) : appendNewLineTo(view.find("ul"));

                  if( $.browser.msie && $.browser.version < 7 ){
                    $(".handle",view).hover(
                      function(){ $(this).addClass('hover'); },
                      function(){ $(this).removeClass('hover'); }
                    );
                  }
                })
            });
          })
      }






      var RemoteServer = function(){

        var user = undefined;

        this.checkLogin = function(username, pass){
          return $.getJSON("?task=userByCredentials", {username: username, password: pass}, function(u){ user = u });
        }
        this.getTalkingPoints = function(handle){
          return $.getJSON("?task=getTalkingPoints", {handle: handle});
        }
        this.getTeacherInfo = function(){
          if(!user) throw Error("Teacher info cannot be retrieved because a user is not logged in");
          return { success: function(f){ f.call(undefined, user) } };
        }
      }




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
        this.getTalkingPoints = w(function(handle) {
          return {
            points: talkingPoints[handle]
          };
        })
        this.getTeacherInfo = w(function(){
          return teachers[user];
        })
        /*
        this.saveTalkingPoints = w(function(handle,points) {
          _.detect(talkingPoints[user].classes,
              function(c){
                return c.handle === handle;
              }).points = points;
        })
        */
      }



      // new AppRouter(Cookies, window, ControllerFactory, new LocalServer()).boot();
      new AppRouter(Cookies, window, ControllerFactory, new RemoteServer()).boot();
    </script>
  </body>
</html>
