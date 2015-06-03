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
                            <select id="zone_id" name="zone_id" class="span5" placeholder="" onchange="load_territory_fnc()" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sqlpri="SELECT zone_id as fieldkey,
                                                zone_name as fieldtext
                                            FROM ait_zone_info
                                            WHERE status='Active'";
                                echo $db->SelectList($sqlpri);
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
                            <select id="territory_id" name="territory_id" class="span5" placeholder="Territory" validate="Require">
                                <option value="">Select</option>

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
                            if ($db->open()) {
                                $result = $db->query($sql);
                                $tmp = '';
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }

                                    echo "<tr class='row_hover' $rowcolor>
                                                    <td align='left'>
                                                        <input type='checkbox'  name='$result_array[zillaid]' id='$result_array[zillaid]' value='$result_array[zillaid]'  />
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

