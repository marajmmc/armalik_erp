<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$user_zone = $_SESSION['zone_id'];
$editRow = $db->single_data($tbl . "zi_task", "*", "id", $_POST['rowID']);
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
                            <select id="territory_id" name="territory_id" class="span5" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info";
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
                            <select id="district_id" name="district_id" class="span5" disabled>
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
                            Distributor
                        </label>
                        <div class="controls">
                            <select id="distributor_id" name="distributor_id" class="span5" disabled>
                                <option value="">Select</option>
                                <?php
                                $sql = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0' AND territory_id='".$editRow['territory_id']."' AND zilla_id='".$editRow['district_id']."' order by distributor_name";
                                $distributorDropDownArray = $db->return_result_array($sql);
                                foreach($distributorDropDownArray as $DropDown)
                                {
                                    ?>
                                    <option value="<?php echo $DropDown['fieldkey'];?>" <?php if($DropDown['fieldkey']==$editRow['distributor_id']){echo 'selected';}?>><?php echo $DropDown['fieldtext'];?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Purchase Order
                        </label>
                        <div class="controls">
                            <input type="text" class="span5" disabled name="purchase_order" value="<?php echo $editRow['purchase_order'];?>"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Collection
                        </label>
                        <div class="controls">
                            <input type="text" class="span5" disabled name="collection" value="<?php echo $editRow['collection'];?>"/>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            date
                        </label>
                        <div class="controls">
                            <input class="span5" type="text"  disabled name="entry_date" id="entry_date" value="<?php echo $editRow['task_entry_date'];?>" placeholder="Entry date">
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Activities
                        </label>
                        <div class="controls">
                            <textarea name="activities" disabled class="span6"><?php echo $editRow['activities'];?></textarea>
                            <div class="span2"><img src="../../system_images/zi_task/<?php echo $editRow['activities_image']?>" /></div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Problem
                        </label>
                        <div class="controls">
                            <textarea name="problem" disabled class="span6"><?php echo $editRow['problem'];?></textarea>
                            <div class="span2"><img src="../../system_images/zi_task/<?php echo $editRow['problem_image']?>" /></div>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Recommendation
                        </label>
                        <div class="controls">
                            <textarea name="recommendation" disabled class="span6"><?php echo $editRow['recommendation'];?></textarea>
                        </div>
                    </div>

                    <div class="control-group">
                        <label class="control-label">
                            Solution
                        </label>
                        <div class="controls">
                            <textarea name="solution" class="span6"><?php echo $editRow['solution'];?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>