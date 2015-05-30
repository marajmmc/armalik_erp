<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$info=$db->single_data_w($tbl . "product_sale_target", "$tbl" . "product_sale_target.zone_id, $tbl" . "product_sale_target.year_id  ", " $tbl" . "product_sale_target.sale_target_id='" . $_POST['rowID'] . "' AND
        $tbl" . "product_sale_target.del_status='0' AND $tbl" . "product_sale_target.channel='Zone'");
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget span">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
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
                        <label class="control-label" for="zone_id">
                            Zone
                        </label>
                        <div class="controls">
                            <select id="zone_id" name="zone_id" class="span5" placeholder="Zone" validate="Require">
                                <!--                                <option value="">Select</option>-->
                                <?php
                                $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE zone_id='".$info['zone_id']."' AND status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $info['zone_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="start_date">
                            Select Year
                        </label>
                        <div class="controls">
                            <select id="year_id" name="year_id" class="span5" placeholder="Zone" validate="Require">
                                <option value="">Select</option>
                                <?php
                                $sql_uesr_group = "select year_id as fieldkey, year_name as fieldtext from $tbl" . "year WHERE year_id='".$info['year_id']."' AND status='Active' AND del_status='0'";
                                echo $db->SelectList($sql_uesr_group, $info['year_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                            <tr>
                                <th style="width:15%">
                                    Crop
                                </th>
                                <th style="width:15%">
                                    Product Type
                                </th>
                                <th style="width:15%">
                                    Variety
                                </th>
                                <th style="width:15%">
                                    Price(TK/Kg)
                                </th>
                                <th style="width:15%">
                                    Target(Kg)
                                </th>
                                <th style="width:15%">
                                    value(TK)
                                </th>
                                <th style="width:5%">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tr >
                                <td>
                                    <select id='crop_id' name='crop_id' class='span12' placeholder='Crop' onchange='load_product_type()'>
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' ORDER BY $tbl" . "crop_info.order_crop";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id='product_type_id' name='product_type_id' class='span12' placeholder='Product type' onchange='load_varriety_fnc()'>
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id='varriety_id' name='varriety_id' class='span12' placeholder='Zone' >
                                        <?php
                                        echo "<option value=''>Select</option>";
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type='text' name='price' maxlength='50' id='price' class='span12' value='' validate='Require' />
                                </td>
                                <td>
                                    <input type='text' name='quantity' maxlength='50' id='quantity' class='span12' value='' validate='Require' />
                                </td>
                                <td>
                                    <input type='text' name='value' maxlength='50' id='value' class='span12' value='' validate='Require' />
                                </td>
                                <td>
                                    <a class='btn btn-warning2' data-original-title='' onclick="add_product()">
                                        <i class='icon-white icon-plus'> </i> Add
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <div id="div_show_product">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        load_product();
    })
</script>