<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_zone = $_SESSION['zone_id'];
$editRow = $db->single_data($tbl . "zi_crop_farmer_setup", "*", "id", $_POST['rowID']);
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
                            Territory
                        </label>
                        <div class="controls">
                            <select id="territory_id" name="territory_id" class="span5" onchange="load_district_by_territory()">
                                <option value="">Select</option>
                                <?php
                                $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                echo $db->SelectList($sql, $editRow['territory_id']);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" onchange="load_distributor_by_district()">
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
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Upazilla
                        </label>
                        <div class="controls">
                            <select id="upazilla_id" name="upazilla_id" class="span5">
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
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Crop
                        </label>
                        <div class="controls">
                            <select id="crop_id" name="crop_id" class="span5" onchange="load_type_by_crop()">
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
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Type
                        </label>
                        <div class="controls">
                            <select id="type_id" name="type_id" class="span5" onchange="load_variety_by_crop_type()">
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
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Variety
                        </label>
                        <div class="controls">
                            <select id="variety_id" name="variety_id" class="span5">
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
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Farmer's Name
                        </label>
                        <div class="controls">
                            <input type="text" name="farmers_name" value="<?php echo $editRow['farmers_name'];?>" class="span5" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Address
                        </label>
                        <div class="controls">
                            <textarea name="farmers_address" class="span5"><?php echo $editRow['farmers_address'];?></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Contact No.
                        </label>
                        <div class="controls">
                            <input type="text" name="farmers_contact" class="span5" value="<?php echo $editRow['contact_no'];?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>