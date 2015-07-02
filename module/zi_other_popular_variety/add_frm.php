<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;

$user_zone = $_SESSION['zone_id'];
$user_division_query = $db->single_data($tbl.'zone_info', 'division_id', 'zone_id', "$user_zone");
$user_division = $user_division_query['division_id'];
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
                            <input type="hidden" id="division_id" name="division_id" value="<?php echo $user_division;?>" />
                            <input type="hidden" id="zone_id" name="zone_id" value="<?php echo $user_zone;?>" />
                            <input type="hidden" id="farmer_id" name="farmer_id" value="0" />

                            <select id="territory_id" name="territory_id" class="span5" onchange="load_district_by_territory()">
                                <option value="">Select</option>
                                <?php
                                $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where zone_id='$user_zone'";
                                echo $db->SelectList($sql);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            District
                        </label>
                        <div class="controls">
                            <select id="district_id" name="district_id" class="span5" onchange="load_upazilla_by_district()">
                                <option value="">Select</option>
                                <?php

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
                                    $sql_uesr_group = "select
                                    crop_id as fieldkey,
                                    crop_name as fieldtext
                                    from $tbl" . "crop_info
                                    where status='Active' AND del_status='0' order by order_crop";
                                    echo $db->SelectList($sql_uesr_group);
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

                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group other_variety">
                        <label class="control-label">
                            Other Variety
                        </label>
                        <div class="controls">
                            <input type="text" name="other_variety" class="span5" value="" />
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Farmer Name/ Area
                        </label>
                        <div class="controls">
                            <input type="text" name="farmers_name" id="farmers_name" class="span5" value="" onblur="check_farmer_existence()" />
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

<script>
    $(document).ready(function()
    {
        $(document).on("change","#variety_id",function()
        {
            if($(this).val().length>0)
            {
                $(".other_variety").hide();
            }
            else
            {
                $(".other_variety").show();
            }
        });
    });
//
//    var cal = Calendar.setup({
//        onSelect: function(cal) { cal.hide() },
//        fdow :0,
//        minuteStep:1
//    });
//
//    cal.manageFields("calcbtn_sowing_date", "sowing_date", "%d-%m-%Y");

</script>