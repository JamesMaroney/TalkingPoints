<!DOCTYPE html>
<html>
  <head>
    <title>Talking Points</title>
    <link href='http://fonts.googleapis.com/css?family=Waiting+for+the+Sunrise|Annie+Use+Your+Telescope' rel='stylesheet' type='text/css'>
    <link href="teacher.css" rel="stylesheet" type="text/css" />
    <link href="favicon.ico" rel="icon" type="image/ico" />
    <link href="favicon.ico" rel="shortcut icon" />
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
      <div class="classContainer">
          <h3>${klass.name} <span class="handle">instructions for parents &gt;<span class="instructions">To receive daily talking points from ${teacher.name},<br />text <strong>${klass.handle}</strong> to <strong>(404) 919-0173</strong><!-- or email<br /><strong>${klass.handle}</strong> to <strong>abc@xyz.com</strong> --><br />Visit www.xyz.com to learn more about Talking Points.</span></span></h3>
          <ul data-handle="${klass.handle}">
          </ul>
      </div>
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
    <script type="text/javascript" src="js/lib/underscore.js"></script>

    <script type="text/javascript" src="js/framework.js"></script>
    <script type="text/javascript" src="js/lib/cookies.js"></script>

    <script type="text/javascript" src="js/controllers/loginController.js"></script>
    <script type="text/javascript" src="js/controllers/teacherController.js"></script>
    <script type="text/javascript" src="js/servers/localServer.js"></script>
    <script type="text/javascript" src="js/servers/remoteServer.js"></script>
    <script type="text/javascript">
      new AppRouter(Cookies, window, ControllerFactory, new RemoteServer()).boot();
    </script>
  </body>
</html>
