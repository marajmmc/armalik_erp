<?php
session_start();
ob_start();
if ($_SESSION['logged'] != 'yes') {
    $_REQUEST["msg"] = "TimeoutC";
    header("location:../../index.php");
}
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

$sql = "SELECT
            user_id,
            employee_id,
            employee_name,
            user_level,
            division_id,
            zone_id,
            territory_id,
            warehouse_id,
            user_group_id,
            user_name,
            user_pass,
            user_tmp_pass,
            user_expire_date,
            user_status,
            create_date,
            user_photo
        FROM
            $tbl" . "user_login
        WHERE
            user_id='" . $_POST['rowID'] . "'
";
$i = 0;
if ($db->open()) {
    $result = $db->query($sql);
    $result_array = $db->fetchAssoc();
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
                            <label class="control-label" for="user_name">
                                User Name
                            </label>
                            <div class="controls">
                                <input class="span5" disabled="" type="text" name="user_name" id="user_name" placeholder="User Name" validate="Require" value="<?php echo $result_array['user_name']; ?>" />
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_group_id">
                                Group Name
                            </label>
                            <div class="controls controls-row">
                                <select id="user_group_id" name="user_group_id" disabled="" class="span5" validate="Require" placeholder="Group Name">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select ug_id as fieldkey, ug_name as fieldtext from $tbl" . "user_group group by ug_name";
                                    echo $db->SelectList($sql_uesr_group, $result_array['user_group_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_unit_id">
                                Level
                            </label>
                            <div class="controls controls-row">
                                <select id="user_level" name="user_level" disabled="" class="span5" onchange="load_user_type();load_employee_fnc()" validate="Require" placeholder="User Type">
                                    <option value="">Select</option>
                                    <option value="Marketing" <?php
                                if ($result_array['user_level'] == "Marketing") {
                                    echo "selected='selected'";
                                }
                                    ?> >Marketing</option>
                                    <option value="Warehouse" <?php
                                if ($result_array['user_level'] == "Warehouse") {
                                    echo "selected='selected'";
                                }
                                    ?> >Warehouse</option>
                                    <option value="Division" <?php
                                if ($result_array['user_level'] == "Division") {
                                    echo "selected='selected'";
                                }
                                    ?> >Division</option>
                                    <option value="Zone" <?php
                                        if ($result_array['user_level'] == "Zone") {
                                            echo "selected='selected'";
                                        }
                                    ?> >Zone</option>
                                    <option value="Territory" <?php
                                        if ($result_array['user_level'] == "Territory") {
                                            echo "selected='selected'";
                                        }
                                    ?> >Territory</option>
                                    <option value="Distributor" <?php
                                        if ($result_array['user_level'] == "Distributor") {
                                            echo "selected='selected'";
                                        }
                                    ?> >Distributor</option>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_warehouse_id" style="display: none;">
                            <label class="control-label" for="warehouse_id">
                                Select Warehouse
                            </label>
                            <div class="controls">
                                <select id="warehouse_id" name="warehouse_id" disabled="" class="span5" placeholder="Warehouse" onchange="load_employee_fnc()">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select warehouse_id as fieldkey, warehouse_name as fieldtext from $tbl" . "warehouse_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $result_array['warehouse_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_division_id" style="display: none;">
                            <label class="control-label" for="division_id">
                                Division
                            </label>
                            <div class="controls">
                                <select disabled="" id="division_id" name="division_id" class="span5" placeholder="division">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select division_id as fieldkey, division_name as fieldtext from $tbl" . "division_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $result_array['division_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_zone_id" style="display: none;">
                            <label class="control-label" for="zone_id">
                                Zone
                            </label>
                            <div class="controls">
                                <select id="zone_id" name="zone_id" disabled="" class="span5" placeholder="Zone" onchange="load_territory_fnc();load_employee_fnc()">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select zone_id as fieldkey, zone_name as fieldtext from $tbl" . "zone_info WHERE status='Active' AND del_status='0'";
                                    echo $db->SelectList($sql_uesr_group, $result_array['zone_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group" id="div_territory_id" style="display: none;">
                            <label class="control-label" for="territory_id">
                                Territory
                            </label>
                            <div class="controls">
                                <select id="territory_id" name="territory_id" disabled="" class="span5" placeholder="Territory"  onchange="load_distributor_fnc();load_employee_fnc()">
                                    <option value="">Select</option>
                                    <?php
                                    $sql_uesr_group = "select territory_id as fieldkey, territory_name as fieldtext from $tbl" . "territory_info where status='Active' AND del_status='0' AND zone_id='" . $result_array['zone_id'] . "'";
                                    echo $db->SelectList($sql_uesr_group, $result_array['territory_id']);
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="employee_id">
                                Name
                            </label>
                            <div class="controls controls-row">
                                <select id="employee_id" name="employee_id" disabled="" class="span5" validate="Require" placeholder="Employee Name" onchange="load_employee_name()">
                                    <option value="">Select</option>
                                    <?php
                                    if ($result_array['user_level'] == "Distributor") {
                                        $sql_uesr_group = "select distributor_id as fieldkey, distributor_name as fieldtext from $tbl" . "distributor_info where status='Active' AND del_status='0'";
                                        echo $db->SelectList($sql_uesr_group, $result_array['employee_id']);
                                    } else {
                                        $sql_uesr_group = "select employee_id as fieldkey, employee_name as fieldtext from $tbl" . "employee_basic_info where status='Active' AND del_status='0'";
                                        echo $db->SelectList($sql_uesr_group, $result_array['employee_id']);
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="user_status">
                                User Status
                            </label>
                            <div class="controls controls-row">
                                <select id="user_status" name="user_status" disabled="" class="span5" validate="Require"placeholder="User Status">
                                    <option value="">Select</option>
                                    <option value="Active" <?php
                                if ($result_array['user_status'] == "Active") {
                                    echo "selected='selected'";
                                }
                                    ?> >Active</option>
                                    <option value="In Active" <?php
                                        if ($result_array['user_status'] == "In Active") {
                                            echo "selected='selected'";
                                        }
                                    ?> >In Active</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php }
?>
<script>
    $(document).ready(function(){
        load_user_type();
    });
</script>