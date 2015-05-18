<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
?>

<script src="../../system_js/jquery-1.7.1.js"></script>
<script src="../../system_js/jquery-ui.js"></script>
<link rel="stylesheet" href="../../system_css/autocomplete.css" />
<script>
    $(document).ready(function() {
        $('#invoice_id').autocomplete({source:'autoload_inv.php', minLength:1});
    });
</script>
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
                                    Invoice No
                                </th>
                                <th style="width:10%">
                                    &nbsp;
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id='invoice_id' name='invoice_id' class='span12' placeholder='Invoice No' />
                                </td>
                                <td style="">
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
    $(document).ready(function(){
        //        session_load_fnc()
    });
    
    //    var cal = Calendar.setup({
    //        onSelect: function(cal) { cal.hide() },
    //        fdow :0,
    //        minuteStep:1
    //    });
    //    cal.manageFields("calcbtn_from_date", "from_date", "%d-%m-%Y");
    //    cal.manageFields("calcbtn_to_date", "to_date", "%d-%m-%Y");
    
</script>