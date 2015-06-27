<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_zone = $_SESSION['zone_id'];
$editRow = $db->single_data($tbl . "zi_monthly_field_visit_setup", "*", "id", $_POST['rowID']);
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title"></span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <div class="control-group">
                        <label class="control-label">
                            Division
                        </label>
                        <div class="controls">
                            <select id="division_id" name="division_id" class="span5" onchange="load_zone_by_division()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info";
                                echo $db->SelectList($sql, $editRow['division_id']);
                                ?>
                            </select>
                            <input type="hidden" name="id" value="<?php echo $_POST['rowID'];?>">
                            <input type="hidden" name="division_id" value="<?php echo $editRow['division_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" onchange="load_territory_by_zone()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info";
                                echo $db->SelectList($sql, $editRow['zone_id']);
                                ?>
                            </select>
                            <input type="hidden" name="zone_id" value="<?php echo $editRow['zone_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" onchange="load_district_by_territory()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                echo $db->SelectList($sql, $editRow['territory_id']);
                                ?>
                            </select>
                            <input type="hidden" name="territory_id" value="<?php echo $editRow['territory_id'];?>">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" onchange="load_distributor_by_district()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql_user_group = "SELECT
                                    $tbl" . "zilla.zillaid as fieldkey,
                                    $tbl" . "zilla.zillanameeng as fieldtext
                                    FROM
                                    $tbl" . "territory_assign_district
                                    LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "territory_assign_district.zilla_id
                                    WHERE
                                    $tbl" . "territory_assign_district.del_status=0
                                    AND $tbl" . "zilla.visible=0
                                    AND $tbl" . "territory_assign_district.status='Active'
                                    AND $tbl" . "territory_assign_district.territory_id='".$editRow['territory_id']."'
                                    ";
                                echo $db->SelectList($sql_user_group, $editRow['district_id']);
                                ?>
                            </select>
                            <input type="hidden" name="district_id" value="<?php echo $editRow['district_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Upazilla
                        </label>
                        <div class="controls">
                            <select id="upazilla_id" name="upazilla_id" class="span5" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "SELECT
                                    $tbl" . "upazilla.upazilaid as fieldkey,
                                    $tbl" . "upazilla.upazilanameeng as fieldtext
                                    FROM
                                    $tbl" . "upazilla

                                    WHERE
                                    $tbl" . "upazilla.visible=0
                                    AND $tbl" . "upazilla.zillaid='".$editRow['district_id']."'
                                    ";
                                echo $db->SelectList($sql, $editRow['upazilla_id']);
                                ?>
                            </select>
                            <input type="hidden" name="upazilla_id" value="<?php echo $editRow['upazilla_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" onchange="load_type_by_crop()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select
                                    crop_id as fieldkey,
                                    crop_name as fieldtext
                                    from $tbl" . "crop_info
                                    where status='Active' AND del_status='0' order by order_crop";
                                echo $db->SelectList($sql, $editRow['crop_id']);
                                ?>
                            </select>
                            <input type="hidden" name="crop_id" value="<?php echo $editRow['crop_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Type
                        </label>
                        <div class="controls">
                            <select id="type_id" name="type_id" class="span5" onchange="load_variety_by_crop_type()" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select
                                    product_type_id as fieldkey,
                                    product_type as fieldtext
                                    from $tbl" . "product_type
                                    where status='Active' AND del_status='0' AND crop_id= '".$editRow['crop_id']."' order by order_type";
                                echo $db->SelectList($sql, $editRow['product_type_id']);
                                ?>
                            </select>
                            <input type="hidden" name="type_id" value="<?php echo $editRow['product_type_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Variety
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select
                                    varriety_id as fieldkey,
                                    varriety_name as fieldtext
                                    from $tbl" . "varriety_info
                                    where status='Active' AND del_status='0' AND crop_id= '".$editRow['crop_id']."' AND product_type_id= '".$editRow['product_type_id']."' order by order_variety";
                                echo $db->SelectList($sql, $editRow['variety_id']);
                                ?>
                            </select>
                            <input type="hidden" name="variety_id" value="<?php echo $editRow['variety_id'];?>" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Farmer's Name
                        </label>
                        <div class="controls">
                            <select id="farmer_id" name="farmer_id" class="span5" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select
                                    id as fieldkey,
                                    farmers_name as fieldtext
                                    from $tbl" . "zi_crop_farmer_setup
                                    where status='1' AND del_status='0' AND territory_id = '".$editRow['territory_id']."' AND district_id = '".$editRow['district_id']."' AND upazilla_id = '".$editRow['upazilla_id']."' AND crop_id= '".$editRow['crop_id']."' AND product_type_id= '".$editRow['product_type_id']."'";
                                echo $db->SelectList($sql, $editRow['farmer_id']);
                                ?>
                            </select>
                            <input type="hidden" name="farmer_id" value="<?php echo $editRow['farmer_id'];?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable">Pictures</a>
                    <span class="mini-title"></span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-plus-sign" data-original-title="Share"> </i>
                    </a>
                </span>
            </div>
            <div class="form-horizontal no-margin">
                <div class="widget-body">
                    <table>
                        <tr>
                            <?php
                            $sowing_date_str = strtotime($editRow['sowing_date']);
                            $total = $editRow['no_of_pictures'];
                            for($i=1; $i<=$total; $i++)
                            {
                                $existing = $db->single_data_w($tbl.'zi_monthly_field_visit_pictures','picture_link, remarks, picture_date', "farmer_id=".$_POST['rowID']." AND picture_number=$i");
                                ?>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">
                                            Picture <label class="label label-success"><?php echo $i;?></label>
                                        </label>

                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="file" class="" name="picture_link_<?php echo $i;?>" onchange="readURL(this, <?php echo $i;?>)" />
                                                <?php
                                                if(isset($existing['picture_link']) && strlen($existing['picture_link'])>0)
                                                {
                                                    ?>
                                                    <div class="span3"><img id="img_up<?php echo $i;?>" height="100" width="100" src="../../system_images/zi_field_visit/<?php echo $existing['picture_link'];?>" /></div>
                                                <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <div class="span3"><img id="img_up<?php echo $i;?>" height="100" width="100" src="../../system_images/zi_field_visit/no_image.jpg" /></div>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">
                                                Remarks
                                            </label>
                                            <div class="controls">
                                                <textarea class="span12" name="remarks_<?php echo $i;?>"><?php echo $existing['remarks'];?></textarea>
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">
                                                Date
                                            </label>
                                            <div class="controls">
                                                <input type="text" class="span12" name="picture_date_<?php echo $i;?>" value="<?php echo date('d-m-Y', ($sowing_date_str+($i*$editRow['interval_days'])*24*3600));?>" />
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                if($i%2 == 0)
                                {
                                    echo '</tr>';
                                    echo '<tr>';
                                }
                            }
                            ?>
                        <input type="hidden" name="total" value="<?php echo $total;?>" />
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function readURL(input, sl)
    {
        if (input.files && input.files[0])
        {
            var reader = new FileReader();

            reader.onload = function  ( e )
            {
                $('#img_up'+sl).attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>