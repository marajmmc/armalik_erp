
<script src="system_js/jquery.min.js"></script>
<script src="system_js/form_js.js"></script>
<html lang="en">

    <!--
  <![endif]-->

    <!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:21:38 GMT -->
    <head>
        <meta charset="utf-8">
        <title>
            ::.. AR Malik Login V2.0.0 ..::
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
                <!--<img src="system_images/logo/logo.png" alt="Logo" width="9%"/>-->
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
                                            <a class="fs1" aria-hidden="true">
                                                <i class="icon-user" data-original-title="Share"> </i>
                                            </a>
                                        </span>
                                    </div>
                                    <div class="widget-body">
                                        <div class="span3">&nbsp;</div>
                                        <div class="span6">
                                            <div class="sign-in-container" id="login_box">
                                                <form action="#" class="login-wrapper" method="post">
                                                    <div class="header">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <h3>
                                                                    AR Malik V2.0.0
                                                                    <img src="system_images/logo/company-logo.png" alt="Logo" class="pull-right" width="10%"/> 
                                                                </h3>
                                                                <p>Fill out the form below to login.</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="content">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <input id="user_name" name="user_name" class="input span12 email" placeholder="User Name" validate="Require" onkeypress="checkEnter(event); capLock(event)" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <input id="user_passwd" name="user_passwd" class="input span12 password" placeholder="Password" validate="Require" onkeypress="checkEnter(event); capLock(event)" type="password">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <a class="link" href="#" id="msg"></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="actions">
                                                        <input class="btn btn-danger" name="Login" type="button" value="Login"  onclick="check_user()" >
                                                        <a class="link" href="#" onclick="forget_password_fnc()">Forgot Password?</a>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </form>
                                            </div>

                                            <div class="sign-in-container" id="forget_pass_box" style="display: none">
                                                <form action="#" class="login-wrapper" method="post">
                                                    <div class="header">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <h3>
                                                                    AR Malik 
                                                                    <img src="system_images/logo/company-logo.png" alt="Logo" class="pull-right" width="10%"/> 
                                                                </h3>
                                                                <p>Fill out the form below to password reset request.</p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="content">
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <input id="reset_user_name" name="reset_user_name" class="input span12 email" placeholder="User Name" onkeypress="checkEnterReset(event);capLock(event)" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <div class="row-fluid">
                                                            <div class="span12">
                                                                <a class="link" href="#" id="resetmsg"></a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="actions">
                                                        <input class="btn btn-danger" name="Login" type="button" value="Password Reset"  onclick="password_reset_fnc()" >
                                                        <input class="btn btn-info" name="Login" type="button" value="Back"  onclick="login_back_fnc()" style="float: left;" >
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
                            <button class="btn btn-small  btn-block" type="button"  style="padding-left: 2px;">
                                <?php
//                                include_once 'calendar.php';
                                ?>
                            </button>
                            <button class="btn btn-warning btn-block" type="button">
                                Term & Condition
                            </button>
                            <!-- Modal -->
                        </div>
                    </div>
                </div>
                <!--/.fluid-container-->
            </div>
        </div>
            <?php include_once 'module/dashboard/footer.php'; ?>


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
        $("#msg").html('')
        validateResult=true;
        formValidate();
        if(validateResult){
            var user_name=$('#user_name').val();
            var passwd=$('#user_passwd').val();
            $.post('libraries/lib/CheckUser.php', {user_session_login:user_name, user_session_password:passwd}, function(result){
//                alert (result);
                var data=$.trim(result);
                if(data=="Please Wait ..."){
                    window.location.href = "module/dashboard/dashboard.php";
                }else{
                    $("#msg").html(data)
                }
            });
        }
    }
    
    function password_reset_fnc(){
    
        $("#resetmsg").html('')
        var user_name=$('#reset_user_name').val();
        $.post('libraries/lib/forget_password.php', {user_session_login:user_name}, function(result){
            var data=$.trim(result);
            $("#resetmsg").html(data)
            //                if(data=="Please Wait ..."){
            //                    window.location.href = "module/dashboard/dashboard.php";
            //                    $("#resetmsg").html('Request Send Successfully')
            //                }else{
            //                    $("#resetmsg").html(data)
            //                }
        });
    }
    
    function checkEnter(e)
    {    
        var unicode=e.charCode? e.charCode : e.keyCode        
        if (unicode==13)
        {
            check_user();
        }
    }
    function checkEnterReset(e)
    {    
        var unicode=e.charCode? e.charCode : e.keyCode        
        if (unicode==13)
        {
            password_reset_fnc();
        }
    }
    function datetimeshow(){
        $.post("datetime.php", function(result){
            if(result){
                $("#datetime").html(result);
            } 
        });
    }
    function forget_password_fnc(){
        $("#login_box").slideUp();
        $("#forget_pass_box").slideDown();
    }
    function login_back_fnc(){
        $("#login_box").slideDown();
        $("#forget_pass_box").slideUp();
    }

    function capLock(e)
    {
        kc = e.keyCode?e.keyCode:e.which;
        sk = e.shiftKey?e.shiftKey:((kc == 16)?true:false);
        if(((kc >= 65 && kc <= 90) && !sk)||((kc >= 97 && kc <= 122) && sk))
        {
            $('#msg').html('Caps Lock is on.');
            $("#user_name").val('');
            $("#user_passwd").val('');
        }
        else
        {
            $('#msg').html('');
        }
    }
</script>
