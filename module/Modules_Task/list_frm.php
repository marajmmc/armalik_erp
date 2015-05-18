<?php
include_once '../../libraries/lib/inclue_system_file.php';
$db = new Database();
$db2 = new Database();
?>

<html lang="en">

    <!--
  <![endif]-->

    <!-- Mirrored from iamsrinu.com/bluemoon-admin-theme/ui-elements.html by HTTrack Website Copier/3.x [XR&CO'2010], Thu, 14 Nov 2013 14:20:41 GMT -->
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport">
        <?php include_once '../../module/dashboard/top_css_js.php'; ?>
    </head>
    <body>
        <?php include_once '../../module/dashboard/header_nav.php'; ?>
        <div class="container-fluid">
            <div class="dashboard-container">
                <?php include_once '../../module/dashboard/top_menu.php'; ?>
                <div class="dashboard-wrapper">
                    <div class="left-sidebar">

                        <?php include_once '../../module/dashboard/button.php'; ?>
                        <form id="frm_area" name="frm_area" action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" id="rowID" name="rowID" value="" />
                            <input type="hidden" id="userLevel" name="userLevel" value="<?php echo $_SESSION["user_level"]?>"/>
                            <div id="info_msg" name="info_msg" class="" ></div>
                            <div id="loader_div" class="form_loader" ></div>
                            <div id="list_rec" class="rec_view" ></div>
                            <div id="new_rec" class="rec_view"></div>
                            <div id="edit_rec" class="rec_view"></div>
                            <div id="details_rec" class="rec_view"></div>
                        </form>

                    </div>

                    <!--Start Right Site Panel-->
                    <div class="right-sidebar">
                        <?php include_once '../../module/dashboard/dashboard_rightsite_bar.php'; ?>
                    </div>
                    <!--End Right Site Panel-->
                </div>
            </div>
            <!--/.fluid-container-->
        </div>

        <?php include_once '../../module/dashboard/footer.php'; ?>
        <?php include_once '../../module/dashboard/footer_css_js.php'; ?>

    </body>

    
</html>

<script>
    $(document).ready(function(){
        list(); 
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.log("<?php echo $_SESSION['task_name'];?>"+" Grid View");
        return false;
        
    });
</script>