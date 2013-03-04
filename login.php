<?php
session_start();
session_destroy();
session_unset();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Login Page</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/smoothness/jquery-ui-1.9.2.custom.css" />

        <style type="text/css">
            label, input { display:block; }
            input.text { margin-bottom:12px; width:95%; padding: .4em; }
            input.password { margin-bottom:12px; width:95%; padding: .4em; }
            fieldset { padding:0 20px; border:0; margin: 25px 0 25px 0}
            h2 {font-family: Arial,Helvetica,sans-serif;font-style: normal;font-weight: lighter;color: #bcbcbc;text-align: center;}
            #message {color:red;}
        </style>
    </head>
    <body class="loginBackground">
        <!--[if lt IE 7]>
           <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
       <![endif]-->
        <div class="loginBox">
            <h2>GROUPON Deal Bank System</h2>
            <div class="box">                
                <form action="php/data.php" type="post" id="form1">
                    <fieldset>
                        <div id="message"></div>
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="text ui-widget-content ui-corner-all" required/>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="text ui-widget-content ui-corner-all" required/>
                        <input type="submit" value="login" name="submit" id="submit" formmethod="post"/>
                    </fieldset>
                </form>
            </div>
        </div>
<!--        <script src="js/vendor/modernizr-2.6.2.min.js"></script>-->
        <script
        src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script type="text/javascript" src="js/ui/jquery-ui.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#submit").click(function() {
                    var action = $("#form1").attr('action');
                    var form_data = {
                        username: $("#username").val(),
                        password: $("#password").val(),
                        is_ajax: 1
                    };
                    $.ajax({
                        type: "POST",
                        url: action,
                        data: form_data,
                        success: function(response) {
                            if(response == 'success')
                                window.location = "/BA_Automation/";
                            else
                                $("#message").html("<p class='error'>Invalid username and/or password.</p>");	
                        }
                    });
		
                    return false;
                });
            });
        </script>
    </body>
</html>