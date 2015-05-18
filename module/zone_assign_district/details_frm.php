<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbrow = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbrow->single_data($tbl . "zone_assign_district", "*", "zone_id", $_POST['rowID']);
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
                        <label class="control-label" for="farmer_id">
                            Zone Name
                        </label>
                        <div class="controls">
                            <select disabled id="zone_id" name="zone_id" class="span5" placeholder="Select Farmer" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sqlpri="SELECT zone_id as fieldkey,
                                                zone_name as fieldtext
                                            FROM ait_zone_info
                                            WHERE status='Active'";
                                echo $db->SelectList($sqlpri, $editrow['zone_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>

                    <div class="control-group">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                            <thead>
                            <tr>
                                <th style="width:40%">
                                    District
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $dbzoneacs=new Database();

                            $sql = "SELECT
                                        zillaid,
                                        zillanameeng
                                    FROM `$tbl" . "zilla`
                                    WHERE
                                        visible=0
                                    ORDER BY zillanameeng
                                    ";
                            $i = 0;
                            if ($dbrow->open()) {
                                $result = $dbrow->query($sql);
                                $tmp = '';
                                while ($result_array = $dbrow->fetchAssoc()) {
//                                    if ($i % 2 == 0) {
//                                        $rowcolor = "gradeC";
//                                    } else {
//                                        $rowcolor = "gradeA success";
//                                    }
                                    $access = $dbzoneacs->single_data_w($tbl . "zone_assign_district", "zilla_id", "zilla_id='$result_array[zillaid]' AND zone_id='$editrow[zone_id]'");

                                    if ($result_array['zillaid'] == $access['zilla_id']) {
                                        $acs_check = "checked='checked'";
                                        $rowcolor = "gradeA success";
                                    } else {
                                        $acs_check = "";
                                        $rowcolor = "gradeC";
                                    }
                                    echo "<tr class='row_hover $rowcolor'>
                                                    <td align='left'>
                                                        <input disabled type='checkbox' $acs_check  name='$result_array[zillaid]' id='$result_array[zillaid]' value='$result_array[zillaid]'  />
                                                        $result_array[zillanameeng]
                                                        <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$result_array[zillaid]'/>
                                                    </td>
                                            </tr>";
                                    ++$i;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>