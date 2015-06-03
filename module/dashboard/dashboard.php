<?php
include_once '../../libraries/lib/inclue_system_file.php';
?>

<html lang="en">

    <!--
  <![endif]-->

    <!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/index.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:19:00 GMT -->
    <head>
        <meta charset="utf-8">
        <title>
            Soft-BD
        </title>

        <meta name="description" content="">
        <meta name="author" content="">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
        <!-- bootstrap css -->
        <!--<script type="text/javascript" src="../../html5shiv.googlecode.com/svn/trunk/html5.js">-->
    </script>
    <link href="../../icomoon/style.css" rel="stylesheet">

    <link href="../../system_css/wysiwyg/bootstrap-wysihtml5.css" rel="stylesheet">
    <link href="../../system_css/wysiwyg/wysiwyg-color.css" rel="stylesheet">
    <link href="../../system_css/main.css" rel="stylesheet"> <!-- Important. For Theming change primary-color variable in main.css  -->
    <link href="../../system_css/charts-graphs.css" rel="stylesheet">
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','../../www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-40304444-1', 'iamsrinu.com');
        ga('send', 'pageview');

    </script>
</head>
<body>
    <?php include_once 'header_nav.php'; ?>
    <div class="container-fluid">
        <div class="dashboard-container">
            <?php include_once 'top_menu.php'; ?>
            <div class="dashboard-wrapper">

                <div class="left-sidebar">
                    <?php include_once 'dashbord_manu.php'; ?>
                    <?php // include_once 'dashbord_overview_circle.php'; ?>
                    <?php // include_once 'dashbord_mailbox.php'; ?>
                    <?php // include_once 'dashbord_todo_list.php'; ?>
                    <?php // include_once 'dashboard_chat.php'; ?>
                    <?php // include_once 'dashboard_notification.php'; ?>
                </div>
                <div class="right-sidebar">
                    <?php include_once 'dashboard_rightsite_bar.php'; ?>
                </div>
            </div>
        </div>
        <!--/.fluid-container-->
    </div>
    <?php include_once 'footer.php'; ?>

    <script src="../../system_js/wysiwyg/wysihtml5-0.3.0.js">
    </script>
    <script src="../../system_js/jquery.min.js">
    </script>
    <script src="../../system_js/bootstrap.js">
    </script>
    <script src="../../system_js/wysiwyg/bootstrap-wysihtml5.js">
    </script>
    <script src="../../system_js/jquery.scrollUp.js">
    </script>


    <!-- Google Visualization JS -->
    <script type="text/javascript" src="https://www.google.com/jsapi">
    </script>

    <!-- Easy Pie Chart JS -->
    <script src="../../system_js/jquery.easy-pie-chart.js">
    </script>

    <!-- Sparkline JS -->
    <script src="../../system_js/jquery.sparkline.js">
    </script>

    <!-- Tiny Scrollbar JS -->
    <script src="../../system_js/tiny-scrollbar.js">
    </script>

    <!-- Custom JS -->
    <script src="../../system_js/custom.js">
    </script>


    <script type="text/javascript">
//        //ScrollUp
//        $(function () {
//            $.scrollUp({
//                scrollName: 'scrollUp', // Element ID
//                topDistance: '300', // Distance from top before showing element (px)
//                topSpeed: 300, // Speed back to top (ms)
//                animation: 'fade', // Fade, slide, none
//                animationInSpeed: 400, // Animation in speed (ms)
//                animationOutSpeed: 400, // Animation out speed (ms)
//                scrollText: 'Scroll to top', // Text for element
//                activeOverlay: false // Set CSS color to display scrollUp active point, e.g '#00FFFF'
//            });
//        });
//
//        $(document).ready(function () {
//            pie_chart();
//        });
//        //Animated Pie Charts
//        function pie_chart()
//        {
////            $(function ()
////            {
////                //create instance
////                $('.chart1').easyPieChart({
////                    animate: 2000,
////                    barColor: '#74b749',
////                    trackColor: '#dddddd',
////                    scaleColor: '#74b749',
////                    size: 140,
////                    lineWidth: 6
////                });
////                //update instance after 5 sec
//////                setTimeout(function () {
//////                    $('.chart1').data('easyPieChart').update(50);
//////                }, 5000);
//////                setTimeout(function () {
//////                    $('.chart1').data('easyPieChart').update(70);
//////                }, 10000);
//////                setTimeout(function () {
//////                    $('.chart1').data('easyPieChart').update(30);
//////                }, 15000);
//////                setTimeout(function () {
//////                    $('.chart1').data('easyPieChart').update(90);
//////                }, 19000);
//////                setTimeout(function () {
//////                    $('.chart1').data('easyPieChart').update(40);
//////                }, 32000);
////            });
////
////            $(function ()
////            {
////                //create instance
////                $('.chart2').easyPieChart({
////                    animate: 2000,
////                    barColor: '#ed6d49',
////                    trackColor: '#dddddd',
////                    scaleColor: '#ed6d49',
////                    size: 140,
////                    lineWidth: 6
////                });
////                //update instance after 5 sec
//////                setTimeout(function () {
//////                    $('.chart2').data('easyPieChart').update(90);
//////                }, 10000);
//////                setTimeout(function () {
//////                    $('.chart2').data('easyPieChart').update(40);
//////                }, 18000);
//////                setTimeout(function () {
//////                    $('.chart2').data('easyPieChart').update(70);
//////                }, 28000);
//////                setTimeout(function () {
//////                    $('.chart2').data('easyPieChart').update(50);
//////                }, 32000);
//////                setTimeout(function () {
//////                    $('.chart2').data('easyPieChart').update(80);
//////                }, 40000);
////            });
////
////            $(function () {
////                //create instance
////                $('.chart3').easyPieChart({
////                    animate: 2000,
////                    barColor: '#0daed3',
////                    trackColor: '#dddddd',
////                    scaleColor: '#0daed3',
////                    size: 140,
////                    lineWidth: 6
////                });
////                //update instance after 5 sec
//////                setTimeout(function () {
//////                    $('.chart3').data('easyPieChart').update(20);
//////                }, 9000);
//////                setTimeout(function () {
//////                    $('.chart3').data('easyPieChart').update(59);
//////                }, 20000);
//////                setTimeout(function () {
//////                    $('.chart3').data('easyPieChart').update(38);
//////                }, 35000);
//////                setTimeout(function () {
//////                    $('.chart3').data('easyPieChart').update(79);
//////                }, 49000);
//////                setTimeout(function () {
//////                    $('.chart3').data('easyPieChart').update(96);
//////                }, 52000);
////            });
////
////            $(function () {
////                //create instance
////                $('.chart4').easyPieChart({
////                    animate: 2000,
////                    barColor: '#ffb400',
////                    trackColor: '#dddddd',
////                    scaleColor: '#ffb400',
////                    size: 140,
////                    lineWidth: 6
////                });
////                //update instance after 5 sec
//////                setTimeout(function () {
//////                    $('.chart4').data('easyPieChart').update(40);
//////                }, 6000);
//////                setTimeout(function () {
//////                    $('.chart4').data('easyPieChart').update(67);
//////                }, 14000);
//////                setTimeout(function () {
//////                    $('.chart4').data('easyPieChart').update(43);
//////                }, 23000);
//////                setTimeout(function () {
//////                    $('.chart4').data('easyPieChart').update(80);
//////                }, 36000);
//////                setTimeout(function () {
//////                    $('.chart4').data('easyPieChart').update(66);
//////                }, 41000);
////            });
////
////
////            $(function () {
////                //create instance
////                $('.chart5').easyPieChart({
////                    animate: 3000,
////                    barColor: '#F63131',
////                    trackColor: '#dddddd',
////                    scaleColor: '#F63131',
////                    size: 140,
////                    lineWidth: 6,
////                });
////                //update instance after 5 sec
////                setTimeout(function () {
////                    $('.chart5').data('easyPieChart').update(30);
////                }, 9000);
////                setTimeout(function () {
////                    $('.chart5').data('easyPieChart').update(87);
////                }, 19000);
////                setTimeout(function () {
////                    $('.chart5').data('easyPieChart').update(28);
////                }, 27000);
////                setTimeout(function () {
////                    $('.chart5').data('easyPieChart').update(69);
////                }, 39000);
////                setTimeout(function () {
////                    $('.chart5').data('easyPieChart').update(99);
////                }, 47000);
////            });
//        }
//
//
//        //Tooltip
//        $('a').tooltip('hide');
//        $('i').tooltip('hide');
//
//
//        //Tiny Scrollbar
//        $('#scrollbar').tinyscrollbar();
//        $('#scrollbar-one').tinyscrollbar();
//        $('#scrollbar-two').tinyscrollbar();
//        $('#scrollbar-three').tinyscrollbar();
//
//
//
//        //Tabs
//        $('#myTab a').click(function (e) {
//            e.preventDefault();
//            $(this).tab('show');
//        })
//
//        // SparkLine Graphs-Charts
//        $(function () {
//            $('#unique-visitors').sparkline('html', {
//                type: 'bar',
//                barColor: '#ed6d49',
//                barWidth: 6,
//                height: 30
//            });
//            $('#monthly-sales').sparkline('html', {
//                type: 'bar',
//                barColor: '#74b749',
//                barWidth: 6,
//                height: 30
//            });
//            $('#current-balance').sparkline('html', {
//                type: 'bar',
//                barColor: '#ffb400',
//                barWidth: 6,
//                height: 30
//            });
//            $('#registrations').sparkline('html', {
//                type: 'bar',
//                barColor: '#0daed3',
//                barWidth: 6,
//                height: 30
//            });
//            $('#site-visits').sparkline('html', {
//                type: 'bar',
//                barColor: '#f63131',
//                barWidth: 6,
//                height: 30
//            });
//        });
//
//        //wysihtml5
//        $('#wysiwyg').wysihtml5();
//

    </script>


</body>

<!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/index.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:20:16 GMT -->
</html>