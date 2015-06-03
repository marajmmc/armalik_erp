<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbrow = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbrow->single_data($tbl . "territory_assign_district", "*", "territory_id", $_POST['rowID']);
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
                                //echo "<option value=''>Select</option>";
                                $sqlpri="SELECT zone_id as fieldkey,
                                                zone_name as fieldtext
                                            FROM ait_zone_info
                                            WHERE
                                            del_status=0
                                            AND status='Active'
                                            AND zone_id='".$editrow['zone_id']."'";
                                echo $db->SelectList($sqlpri, $editrow['zone_id']);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="territory_id">
                            Territory
                        </label>
                        <div class="controls">
                            <select disabled id="territory_id" name="territory_id" class="span5" placeholder="Territory" validate="Require">
                                <?php
                                //echo "<option value=''>Select</option>";
                                $sqlpri="SELECT territory_id as fieldkey,
                                                territory_name as fieldtext
                                            FROM ait_territory_info
                                            WHERE
                                            del_status=0
                                            AND status='Active'
                                            AND zone_id='".$editrow['zone_id']."'
                                            AND territory_id='".$editrow['territory_id']."'";
                                echo $db->SelectList($sqlpri, $editrow['territory_id']);
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
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    $access = $dbzoneacs->single_data_w($tbl . "territory_assign_district", "zilla_id", "zilla_id='$result_array[zillaid]' AND zone_id='$editrow[zone_id]' AND territory_id='$editrow[territory_id]'");

                                    if ($result_array['zillaid'] == $access['zilla_id'])
                                    {
                                        $acs_check = "checked='checked'";
                                        $bg_color="style='color: red;'";
                                    }
                                    else
                                    {
                                        $acs_check = "";
                                        $bg_color="";
                                    }
                                    echo "<tr class='row_hover' $rowcolor $bg_color>
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