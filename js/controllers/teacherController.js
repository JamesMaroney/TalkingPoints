var TeacherController = function(appRouter, viewEngine, server){
  this.viewEngine = viewEngine;
  this.router = appRouter;
  this.server = server;
}
TeacherController.prototype.index = function(){
  /* Helper functions */
  var saveTimeout;
  var server = this.server;
  var self = this;
  
  function scheduleSave(ul, handle){
    if(!saveTimeout) {
      saveTimeout = setTimeout(function(){
        var points = $(ul).find("li").not(".empty").map(function(){ return $(this).text() }).get();
        server.saveTalkingPoints(handle, points || []);
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

  function bindEvents(container, handle){
    $(".empty", container)
      .live("focus", function(){
        $(this).html(" ").removeClass("empty").focus();
      })
    $("li", container)
      .live("keyup", function(){
        e = $(this);
        e.text() ? e.removeClass("empty") : e.addClass("empty");
        insertAnotherLineIfNeeded(this);
        scheduleSave($(this).parents('ul'), handle);
      })
      .live("blur", function(){
        var ul = $(this).parents('ul');
        if(isEmpty(this)){
          insertAnotherLineIfNeeded(this, false);
          $(this).remove();
        }
        scheduleSave(ul, handle);
      })
  }

  function checkServerStatus(){
    self.server.checkServerStatus()
      .success(function(status){
          if(!status.acceptingSubmissions){
            disableSubmissions()
          }
        })
  }

  var disabled = false;
  function disableSubmissions(){
    if(disabled) return;
    disabled = true;
    $("#bd ul").unbind();
    $("#bd *[contenteditable=true]").attr('contenteditable', false);
    $("#bd .empty").remove();
    $("#bd").prepend("<span class='status'>Submissions are no longer being accepted for the day.</span>")
  }

  checkServerStatus();
  setInterval(checkServerStatus,5000);

  this.server.getTeacherInfo()
    .success(function(teacher){
      var classesContainer = self.viewEngine.render("teacher-panel", {date: new Date().toDateString(), teacher: teacher})
                                            .into("#bd").find(".classes");
      _.each(teacher.classes, function(c){
        var view = self.viewEngine.render("teacher-class", { klass: c, teacher: teacher })
            .appendTo(classesContainer);
        self.server.getTalkingPoints(c.handle)
          .success(function(response){
            var pointsView = self.viewEngine.render("class-points", { points: response.points })
                .into(view.find("ul"));

            bindEvents(pointsView, c.handle);
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
