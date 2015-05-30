<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
?>
<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName() ?></a>
                    <span class="mini-title">

                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-list-alt" data-original-title="Share"> </i>
                    </a>
                </span>

            </div>
            <div class="widget-body">
                <div id="dt_example" class="example_alt_pagination">

                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
                        <thead>
                            <tr>
                                <th style="width:10%">
                                    Employee
                                </th>
                                <th style="width:10%">
                                    Status
                                </th>
                                <th style="width:10%">
                                    From Date
                                </th>
                                <th style="width:10%">
                                    To Date
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <select id="employee_id" name="employee_id" class="span12" placeholder="" validate="Require" onchange="load_dealer_fnc()">
                                        <option value="">Select</option>
                                        <?php
                                        $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info WHERE status='Active' AND del_status='0' AND ($tbl" . "employee_basic_info.user_level='Zone' or $tbl" . "employee_basic_info.user_level='Territory')";
                                        echo $db->SelectList($sql_uesr_group);
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select id="status" name="status" class="span12" placeholder="Status">
                                        <option value="">Select</option>
                                        <option value="Incomplete">Incomplete</option>
                                        <option value="Complete">Complete</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="input-append">
                                        <input type="text" name="from_date" id="from_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())   ?>"  />
                                        <span class="add-on" id="calcbtn_from_date">
                                            <i class="icon-calendar"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="input-append">
                                        <input type="text" name="to_date" id="to_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())   ?>"  />
                                        <span class="add-on" id="calcbtn_to_date">
                                            <i class="icon-calendar"></i>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="12" style="text-align: right;">
                                    <a class="btn btn-small btn-success" data-original-title="" onclick="show_report_fnc()">
                                        <i class="icon-print" data-original-title="Share"> </i> View
                                    </a>
                                </td>
                            </tr>
                        </thead>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div id="div_show_rpt"></div>
    </div>
</div>

<script>
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_from_date", "from_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_to_date", "to_date", "%d-%m-%Y");
</script>