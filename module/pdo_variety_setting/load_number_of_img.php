<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
//echo $_SESSION['shop_name_eng']; 
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$number_of_img= $_POST['number_of_img'];

?>
<div style="width: auto; overflow: auto;">
    <table class="" width="" border="" >
        <thead>
        <tr>
            <th colspan="21" >Image Date</th>
        </tr>

        <tr>

            <?php
            $sl=1;
            for($i=0; $i<$number_of_img; $i++)
            {
                $inc_day=($sl*7);
                $time = strtotime($_POST['sowing_date']);
                $final = date("d-m-Y", strtotime($inc_day." day", $time));
                ?>

                <th>
                    <?php echo $sl;?>. <br />
                    <img src="../../system_images/blank_img.png" width="100" style="border: 1px solid rgba(0, 0, 0, 0.33); padding: 5px 10px 5px 10px"/>
                    <br />
                    <div class="input-append">
                        <input readonly="" type="text" name="upload_date[]" id="upload_date<?php echo $i;?>" value="<?php echo $final;?>" class="span10" placeholder="Select Date" validate="Require"  />
<!--            <span class="add-on" id="calcbtn_upload_date--><?php //echo $i;?><!--">-->
<!--                <i class="icon-calendar"></i>-->
<!--            </span>-->
                    </div>

                    <script>
//                        var cal = Calendar.setup({
//                            onSelect: function(cal) { cal.hide() },
//                            fdow :0,
//                            minuteStep:1
//                        });
//                        cal.manageFields("calcbtn_upload_date<?php //echo $i;?>//", "upload_date<?php //echo $i;?>//", "%d-%m-%Y");
                    </script>
                </th>

                <?php
                ++$sl;
            }
            ?>
        </tr>
        </thead>
    </table>

</div>
