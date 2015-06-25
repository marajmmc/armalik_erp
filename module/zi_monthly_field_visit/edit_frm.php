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
                            <input type="hidden" name="id" value="<?php echo $_POST['rowID'];?>">
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
                            <select id="farmer_id" name="farmer_id" class="span5">
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
                                ?>
                                <td>
                                    <div class="control-group">
                                        <label class="control-label">
                                            Picture <label class="label label-success"><?php echo $i;?></label>
                                        </label>

                                        <div class="control-group">
                                            <div class="controls">
                                                <input type="file" class="" name="picture_link_<?php echo $i;?>" />
                                            </div>
                                        </div>

                                        <div class="control-group">
                                            <label class="control-label">
                                                Remarks
                                            </label>
                                            <div class="controls">
                                                <textarea class="span12" name="remarks_<?php echo $i;?>"></textarea>
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

<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable">Others Popular Variety</a>
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
                    <table class="table table-hover" id="adding_elements">
                        <tr>
                            <td>
                                <label class="">Picture</label>
                            </td>

                            <td>
                                <input type="file" class="span12" name="other_picture[]" id="other_picture" />
                            </td>

                            <td>
                                <label class="">Remarks</label>
                            </td>

                            <td>
                                <textarea class="span12" name="other_remarks[]" id="other_remarks"></textarea>
                            </td>

                            <td>
                                <label class="">Date</label>
                            </td>

                            <td>
                                <input type="text" class="span12" name="other_picture_date[]" id="other_picture_date" value="<?php echo $db->date_formate($db->ToDayDate());?>" />
                            </td>

                            <td>
                                <a class="btn btn-warning btn-rect" style="">Delete</a>
                            </td>
                        </tr>
                    </table>

                    <div class="control-group">
                        <input type="button" onclick="RowIncrement()" class="btn btn-success btn-rect pull-right" value="Add More">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var ExId = 0;
    function RowIncrement()
    {
        var table = document.getElementById('adding_elements');

        var rowCount = table.rows.length;

        var row = table.insertRow(rowCount);
        row.id = "T" + ExId;
        row.className = "tableHover";

        var cell1 = row.insertCell(0);
        cell1.innerHTML = "<label class=''>Picture</label>";
        var cell1 = row.insertCell(1);
        cell1.innerHTML = "<input type='file' name='other_picture[]' id='other_picture" + ExId + "' class='span12'/>" +
            "<input type='hidden' id='row_id' name='row_id[]' value=''/>";

        var cell1 = row.insertCell(2);
        cell1.innerHTML = "<label class=''>Remarks</label>";
        var cell1 = row.insertCell(3);
        cell1.innerHTML = "<textarea  class='span12' name='other_remarks[]' id='other_remarks" + ExId + "'></textarea>" +
            "<input type='hidden' id='other_remarks[]' name='other_remarks[]' value=''/>";

        var cell1 = row.insertCell(4);
        cell1.innerHTML = "<label class=''>Date</label>";
        var cell1 = row.insertCell(5);
        cell1.innerHTML = "<input type='text' value='<?php echo $db->date_formate($db->ToDayDate());?>' class='span12' name='other_picture_date[]' id='other_picture_date" + ExId + "' >" +
            "<input type='hidden' id='other_picture_date[]' name='other_picture_date[]' value=''/>";

        cell1 = row.insertCell(6);
        cell1.innerHTML = "<a class='btn btn-warning btn-rect' data-original-title='' onclick=\"RowDecrement('adding_elements','T" + ExId + "')\" >Delete</a>";
        cell1.style.cursor = "default";
        document.getElementById("other_picture" + ExId).focus();
        ExId = ExId + 1;
    }


    function RowDecrement(adding_elements, id)
    {
        try {
            var table = document.getElementById(adding_elements);
            for (var i = 1; i < table.rows.length; i++)
            {
                if (table.rows[i].id == id)
                {
                    table.deleteRow(i);
                }
            }
        }
        catch (e) {
            alert(e);
        }
    }
</script>