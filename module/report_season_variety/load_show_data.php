<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
if ($_POST['session_id'] != "") {
    $session_id = "AND $tbl" . "session_info.session_id='" . $_POST['session_id'] . "'";
} else {
    $session_id = "";
}
if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "session_info.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "session_info.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "session_info.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}
if ($_POST['product_status'] != "") {
    $product_status = "AND $tbl" . "session_info.product_status='" . $_POST['product_status'] . "'";
} else {
    $product_status = "";
}
?>
<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php';?>
    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

        <thead>
            <tr>
                <th style="width:1%">
                    No
                </th>
                <th style="width:10%">
                    Season
                </th>
                <th style="width:10%">
                    Product Type
                </th>
                <th style="width:10%">
                    Variety
                </th>
                <th style="width:10%">
                    Crop
                </th>
                <th style="width:15%">
                    Season Period
                </th>
                <th style="width:10%">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql = "SELECT
                    $tbl" . "session_info.session_id,
                    $tbl" . "session_info.session_name,
                    $tbl" . "session_info.from_date,
                    $tbl" . "session_info.to_date,
                    $tbl" . "session_info.session_color,
                    $tbl" . "session_info.status,
                    $tbl" . "session_info.product_status,
                    $tbl" . "crop_info.crop_name,
                    $tbl" . "product_type.product_type,
                    $tbl" . "varriety_info.varriety_name
                FROM
                    $tbl" . "session_info
                    LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "session_info.crop_id
                    LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "session_info.product_type_id
                    LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "session_info.varriety_id
                WHERE $tbl" . "session_info.del_status='0' $session_id $crop_id $product_type_id $varriety_id $product_status
                        ";
            if ($db->open()) {
                $result = $db->query($sql);
                $i = 1;
                while ($result_array = $db->fetchAssoc()) {
//                    if ($i % 2 == 0) {
//                        $rowcolor = "gradeC";
//                    } else {
//                        $rowcolor = "gradeA success";
//                    }
                    ?>
                    <tr style="color:<?php echo $db->get_season_color($result_array['session_color']) ?>; pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["session_id"] ?>', '<?php echo $i; ?>')" >
                        <td>
                            <?php echo $i; ?>
                        </td>
                        <td><?php echo $result_array['session_name']; ?></td>
                        <td><?php echo $result_array['crop_name']; ?></td>
                        <td><?php echo $result_array['product_type']; ?></td>
                        <td><?php echo $result_array['varriety_name']; ?></td>
                        <td><?php echo $db->date_formate($result_array['from_date']) . " To " . $db->date_formate($result_array['to_date']); ?></td>
                        <td><?php echo $result_array['product_status']; ?></td>
                    </tr>
                    <?php
                    ++$i;
                }
            }
            ?>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php';?>
</div>