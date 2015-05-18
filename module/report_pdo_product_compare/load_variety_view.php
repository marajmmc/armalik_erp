<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$crop_id=$_POST['crop_id'];
$product_type_id=$_POST['product_type_id'];
?>
<table class="table" >
    <tr>
        <td>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th class="21" style="text-align: center">ARM Variety</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql="
                    SELECT
                        ait_varriety_info.crop_id,
                        ait_varriety_info.product_type_id,
                        ait_varriety_info.varriety_id,
                        ait_varriety_info.varriety_name
                    FROM ait_varriety_info
                    WHERE
                        ait_varriety_info.type=0 AND
                        ait_varriety_info.del_status=0 AND
                        ait_varriety_info.crop_id='$crop_id' AND
                        ait_varriety_info.product_type_id='$product_type_id'
                    ";
                if($db->open()) {
                    $result = $db->query($sql);
                    while ($row = $db->fetchArray($result))
                    {
                        ?>
                        <tr>
                            <th>
                                <input type="checkbox" id="variety_id[]" name="variety_id[]" value="<?php echo $row['varriety_id'];?>" /> <?php echo $row['varriety_name'];?>
                            </th>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>

            </table>
        </td>
        <td>
            <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">
                <thead>
                <tr>
                    <th class="21" style="text-align: center">Competitor Variety</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $sql="
                    SELECT
                        ait_varriety_info.crop_id,
                        ait_varriety_info.product_type_id,
                        ait_varriety_info.varriety_id,
                        ait_varriety_info.varriety_name
                    FROM ait_varriety_info
                    WHERE
                        ait_varriety_info.type=1 AND
                        ait_varriety_info.del_status=0 AND
                        ait_varriety_info.crop_id='$crop_id' AND
                        ait_varriety_info.product_type_id='$product_type_id'
                    ";
                if($db->open()) {
                    $result = $db->query($sql);
                    while ($row = $db->fetchArray($result))
                    {
                        ?>
                        <tr>
                            <th>
                                <input type="checkbox" id="variety_name_txt[]" name="variety_name_txt[]" value="<?php echo $row['varriety_id'];?>" /> <?php echo $row['varriety_name'];?>
                            </th>
                        </tr>
                    <?php
                    }
                }
                ?>
                </tbody>

            </table>
        </td>

    </tr>
    <tr>
        <td style="text-align: right" colspan="21">
            <a class="btn btn-small btn-success" data-original-title="" onclick="show_report_fnc()">
                <i class="icon-print" data-original-title="Share"> </i> View
            </a>
        </td>
    </tr>
</table>


