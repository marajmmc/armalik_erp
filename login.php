<?php
include_once 'libraries/lib/ie_selection.php';
?>
<script src="system_js/jquery.min.js"></script>
<html lang="en">

    <!--
  <![endif]-->

    <!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:21:38 GMT -->
    <head>
        <meta charset="utf-8">
        <title>
            Blue Moon - Responsive Admin Dashboard
        </title>

        <meta name="description" content="">
        <meta name="author" content="">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
        <!-- bootstrap css -->
        <script type="text/javascript" src="../../html5shiv.googlecode.com/svn/trunk/html5.js">
        </script>
        <link href="icomoon/style.css" rel="stylesheet">
        <link href="system_css/main.css" rel="stylesheet"> <!-- Important. For Theming change primary-color variable in main.css  -->
        <!--[if lte IE 7]>
        <script src="css/icomoon-font/lte-ie7.js">
        </script>
        <![endif]-->
        
    </head>
    <body>
        <header>
            <a href="index.html" class="logo">
                <img src="system_images/logo/logo.png" alt="Logo"/>
            </a>
            <div class="user-profile">
                
                
            </div>
            
        </header>
        <div class="container-fluid">
            <div class="dashboard-container">

                <div class="dashboard-wrapper">
                    <div class="left-sidebar">

                        <div class="row-fluid">

                            <div class="span12">
                                <div class="widget">
                                    <div class="widget-header">
                                        <div class="title">
                                            Login
                                        </div>
                                        <span class="tools">
                                            <a class="fs1" aria-hidden="true" data-icon="&#xe090;"></a>
                                        </span>
                                    </div>
                                    <div class="widget-body">
                                        <div class="span3">&nbsp;</div>
                                        <div class="span6">
                                            <div class="sign-in-container">
                                                <form action="#" class="login-wrapper" method="post">
                                                    <div class="header">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <h3>Login<img src="img/logo1.png" alt="Logo" class="pull-right"></h3>
                                                                <p>Fill out the form below to login.</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="content">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <input class="input span12 email" placeholder="Email" required="required" type="email" value="">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <input class="input span12 password" placeholder="Password" required="required" type="password">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="actions">
                                                        <input class="btn btn-danger" name="Login" type="submit" value="Login" >
                                                        <a class="link" href="#">Forgot Password?</a>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="span3">&nbsp;</div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                        </div>


                    </div>



                    <div class="right-sidebar">

                        <div class="wrapper">
                            <a href="#myModal" role="button" id="datetime" class="btn btn-large btn-warning2 btn-block" data-toggle="modal">
                                
                            </a>
                            <button class="btn btn-warning btn-block" type="button" style="padding-left: 5px;">
                               <?php 
                               include_once 'calendar.php';
                               ?>
                            </button>
<!--                            <button class="btn btn-small btn-success btn-block" type="button">
                                Small button <br />
                                Small button <br />
                                Small button <br />
                                Small button <br />
                                Small button <br />
                            </button>
                            <button class="btn btn-small btn-warning2 btn-block" type="button">
                                Small button <br />
                                Small button <br />
                                Small button <br />
                            </button>-->
                            <!-- Modal -->
                        </div>
                    </div>
                </div>
                <!--/.fluid-container-->
            </div>
            <?php include_once 'module//dashboard/footer.php';?>


    </body>

    <!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:21:39 GMT -->
</html>
<script>
    $(document).ready(function(){
        setInterval(function(){
        datetimeshow();
        }, 1000);
    });
    function check_user(){
        var user_name=$('#user_name').val();
        var passwd=$('#passwd').val();
        $.post('user_login.php', {name:user_name, pass:passwd}, function(result){
            if(result=="Found"){
                window.location.href = "module/dash_board/home.php";
            }else{
                alert (result);
            }
        });
    }
    function datetimeshow(){
        $.post("datetime.php", function(result){
           if(result){
               $("#datetime").html(result);
           } 
        });
    }
</script>
