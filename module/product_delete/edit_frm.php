<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$row_id = $db->single_data($tbl . "product_info", '*', 'id', $_POST['rowID']);
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
                    <div id="dt_example" class="example_alt_pagination">
                        <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

                            <thead>
                                <tr>
                                    <th style="width:5%">
                                        Warehouse
                                    </th>
                                    <th style="width:5%">
                                        Crop
                                    </th>
                                    <th style="width:5%">
                                        Product Type
                                    </th>
                                    <th style="width:5%">
                                        Variety
                                    </th>
                                    <th style="width:5%">
                                        Pack Size(gm)
                                    </th>
                                    <th style="width:5%; text-align: right">
                                        Purchase Qty(pieces)
                                    </th>
                                    <th style="width:1%">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $INQ = '0';
                                $warehouse_name = '';
                                $crop_name = '';
                                $product_type = '';
                                $sql = "SELECT
                                        $tbl" . "product_purchase_info.id,
                                        $tbl" . "product_purchase_info.product_id,
                                        $tbl" . "product_purchase_info.warehouse_id,
                                        $tbl" . "product_purchase_info.crop_id,
                                        $tbl" . "product_purchase_info.product_type_id,
                                        $tbl" . "product_purchase_info.varriety_id,
                                        $tbl" . "product_purchase_info.pack_size,
                                        $tbl" . "warehouse_info.warehouse_name,
                                        $tbl" . "crop_info.crop_name,
                                        $tbl" . "product_type.product_type,
                                        $tbl" . "varriety_info.varriety_name,
                                        $tbl" . "product_pack_size.pack_size_name,
                                        $tbl" . "product_purchase_info.quantity
                                    FROM
                                        $tbl" . "product_purchase_info
                                        LEFT JOIN $tbl" . "warehouse_info ON $tbl" . "warehouse_info.warehouse_id = $tbl" . "product_purchase_info.warehouse_id
                                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_purchase_info.crop_id
                                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_purchase_info.product_type_id
                                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_purchase_info.varriety_id
                                        LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_info.pack_size
                                    WHERE
                                        $tbl" . "product_purchase_info.crop_id='" . $row_id['crop_id'] . "'  AND 
                                        $tbl" . "product_purchase_info.product_type_id='" . $row_id['product_type_id'] . "'  AND 
                                        $tbl" . "product_purchase_info.varriety_id='" . $row_id['varriety_id'] . "'  AND 
                                        $tbl" . "product_purchase_info.pack_size='" . $row_id['pack_size'] . "'  AND 
                                    $tbl" . "product_purchase_info.del_status='0' 
                                    ORDER BY $tbl" . "product_purchase_info.warehouse_id, $tbl" . "product_purchase_info.crop_id, $tbl" . "product_purchase_info.product_type_id, $tbl" . "product_purchase_info.varriety_id,$tbl" . "product_purchase_info.pack_size 
";
                                if ($db->open()) {
                                    $result = $db->query($sql);
                                    $i = 1;
                                    while ($result_array = $db->fetchAssoc()) {
                                        if ($i % 2 == 0) {
                                            $rowcolor = "gradeC";
                                        } else {
                                            $rowcolor = "gradeA success";
                                        }
//                                    $INQ = $INQ + $result_array['quantity'];
                                        ?>
                                        <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                                            <td>
                                                <?php
                                                if ($warehouse_name == '') {
                                                    echo $result_array['warehouse_name'];
                                                    $warehouse_name = $result_array['warehouse_name'];
                                                    //$currentDate = $preDate;
                                                } else if ($warehouse_name == $result_array['warehouse_name']) {
                                                    //exit;
                                                    echo "&nbsp;";
                                                } else {
                                                    echo $result_array['warehouse_name'];
                                                    $warehouse_name = $result_array['warehouse_name'];
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($crop_name == '') {
                                                    echo $result_array['crop_name'];
                                                    $crop_name = $result_array['crop_name'];
                                                    //$currentDate = $preDate;
                                                } else if ($crop_name == $result_array['crop_name']) {
                                                    //exit;
                                                    echo "&nbsp;";
                                                } else {
                                                    echo $result_array['crop_name'];
                                                    $crop_name = $result_array['crop_name'];
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if ($product_type == '') {
                                                    echo $result_array['product_type'];
                                                    $product_type = $result_array['product_type'];
                                                    //$currentDate = $preDate;
                                                } else if ($product_type == $result_array['product_type']) {
                                                    //exit;
                                                    echo "&nbsp;";
                                                } else {
                                                    echo $result_array['product_type'];
                                                    $product_type = $result_array['product_type'];
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $result_array['varriety_name']; ?></td>
                                            <td><?php echo $result_array['pack_size_name']; ?></td>
                                            <td style="text-align: right;">
                                                <?php echo $result_array['quantity']; ?>
                                                <input type="hidden" id="warehouse_id<?php echo $i; ?>" name="warehouse_id[]" value="<?php echo $result_array['warehouse_id']; ?>" />
                                                <input type="hidden" id="crop_id<?php echo $i; ?>" name="crop_id[]" value="<?php echo $result_array['crop_id']; ?>" />
                                                <input type="hidden" id="product_type_id<?php echo $i; ?>" name="product_type_id[]" value="<?php echo $result_array['product_type_id']; ?>" />
                                                <input type="hidden" id="varriety_id<?php echo $i; ?>" name="varriety_id[]" value="<?php echo $result_array['varriety_id']; ?>" />
                                                <input type="hidden" id="pack_size<?php echo $i; ?>" name="pack_size[]" value="<?php echo $result_array['pack_size']; ?>" />
                                                <input type="hidden" id="purchase_quantity<?php echo $i; ?>" name="purchase_quantity[]" value="<?php echo $result_array['quantity']; ?>" />
                                            </td>
                                            <td>
                                                <a class='btn btn-warning2' data-original-title='' onclick="delete_product('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
                                                    <i class='icon-white icon-trash'> </i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
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
