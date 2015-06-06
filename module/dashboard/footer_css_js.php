<script src="../../system_js/jquery.min.js"></script>
<script src="../../system_js/bootstrap.js"></script> 
<script type="text/javascript" src="../../system_js/alertify.min.js"></script>

<script type="text/javascript">
    
    
    //Tooltip
    $('a').tooltip('hide');

    //Popover
    $('.popover-pop').popover('hide');


    //Collapse
    $('#myCollapsible').collapse({
        toggle: false
    })

    //Tabs
    $('.myTabBeauty a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
    })


    //Dropdown
    $('.dropdown-toggle').dropdown();


    //Alertify JS
    $ = function (id) {
        return document.getElementById(id);
    },
    reset = function () {
        $("toggleCSS").href = "system_css/alertify.core.css";
        alertify.set({
            labels: {
                ok: "OK",
                cancel: "Cancel"
            },
            delay: 5000,
            buttonReverse: false,
            buttonFocus: "ok"
        });
    };

    // Standard Dialogs
//    $("alert").onclick = function () {
//        reset();
//        alertify.alert("This is an alert Dialog");
//        return false;
//    };

//    $("confirm").onclick = function () {
//        reset();
//        alertify.confirm("This is a confirm dialog", function (e) {
//            if (e) {
//                alertify.success("You've clicked OK");
//            } else {
//                alertify.error("You've clicked Cancel");
//            }
//        });
//        return false;
//    };

//    $("prompt").onclick = function () {
//        reset();
//        alertify.prompt("This is a prompt dialog", function (e, str) {
//            if (e) {
//                alertify.success("You've clicked OK and typed: " + str);
//            } else {
//                alertify.error("You've clicked Cancel");
//            }
//        }, "Default Value");
//        return false;
//    };

    // Standard Dialogs
//    $("notification").onclick = function () {
//        reset();
//        alertify.log("Standard log message");
//        return false;
//    };

//    $("success").onclick = function () {
//        reset();
//        alertify.success("Success log message");
//        return false;
//    };

//    $("error").onclick = function () {
//        reset();
//        alertify.error("Error log message");
//        return false;
//    };

    // Custom Properties
//    $("delay").onclick = function () {
//        reset();
//        alertify.set({
//            delay: 10000
//        });
//        alertify.log("Hiding in 10 seconds");
//        return false;
//    };
//
//    $("forever").onclick = function () {
//        reset();
//        alertify.log("Will stay until clicked", "", 0);
//        return false;
//    };

    //Alertify JS end
     
</script>
<script src="../../system_js/jquery.min.js"></script>
<script src="../../system_js/jquery.scrollUp.js"></script>
<script src="../../system_js/custom.js"></script>


<script>

    //ScrollUp
    $(document).ready(function () {
        $.scrollUp({
            scrollName: 'scrollUp', // Element ID
            topDistance: '300', // Distance from top before showing element (px)
            topSpeed: 300, // Speed back to top (ms)
            animation: 'fade', // Fade, slide, none
            animationInSpeed: 400, // Animation in speed (ms)
            animationOutSpeed: 400, // Animation out speed (ms)
            scrollText: 'Scroll to top', // Text for element
            activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
        });
    });


</script>
<!--////////////////// START TABLE CSS & JS           /////////////////////-->
<script src="../../system_js/jquery.min.js"></script>
<script src="../../system_js/jquery.scrollUp.js"></script>
<!--<script src="../../system_js/custom.js"></script>-->
<script src="../../system_js/jquery.dataTables.js"></script>
<!--////////////////// END TABLE CSS & JS           /////////////////////-->

<!--/////////////////////////     start calender css, js file link ///////////////////////-->
<link rel="stylesheet" type="text/css" href="../../plugins/calender/css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="../../plugins/calender/css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="../../plugins/calender/css/steel/steel.css" />
<script type="text/javascript" src="../../plugins/calender/js/jscal2.js"></script>
<script type="text/javascript" src="../../plugins/calender/js/lang/en.js"></script>
<!--/////////////////////////     end calender css, js file link ///////////////////////-->