<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$dbM = new Database();
$tbl = _DB_PREFIX;
$division_zone_str="Head Office";
if ($_POST['crop_id'] != "") {
    $crop_id = "AND $tbl" . "product_sale_target.crop_id='" . $_POST['crop_id'] . "'";
} else {
    $crop_id = "";
}
if ($_POST['product_type_id'] != "") {
    $product_type_id = "AND $tbl" . "product_sale_target.product_type_id='" . $_POST['product_type_id'] . "'";
} else {
    $product_type_id = "";
}
if ($_POST['varriety_id'] != "") {
    $varriety_id = "AND $tbl" . "product_sale_target.varriety_id='" . $_POST['varriety_id'] . "'";
} else {
    $varriety_id = "";
}

if ($_POST['zone_id'] != "") {
    $zone_id = "AND $tbl" . "product_sale_target.zone_id='" . $_POST['zone_id'] . "'";
} else {
    $zone_id = "";
}

if ($_POST['division_id'] != "") {
    $division_id = "AND $tbl" . "product_sale_target.zone_id IN (select zone_id from $tbl" . "zone_user_access where division_id='" . $_POST['division_id'] . "')";
    $division=$db->single_data_w($tbl."division_info", "division_name","division_id='".$_POST['division_id']."'");
    $division_zone_str.=", Division: ". $division['division_name'];
} else {
    $division_id = "";
}
if ($_POST['division_id'] != "") {
    $division_ida = "AND $tbl" . "product_purchase_order_challan.zone_id IN (select zone_id from $tbl" . "zone_user_access where division_id='" . $_POST['division_id'] . "')";
} else {
    $division_ida = "";
}

if ($_POST['zone_id'] != "") {
    $zone_ida = "AND $tbl" . "product_purchase_order_challan.zone_id='" . $_POST['zone_id'] . "'";
    $zone_group="$tbl" . "zone_info.zone_name,";
    $zone=$db->single_data_w($tbl."zone_info", "zone_name","zone_id='".$_POST['zone_id']."'");
    $division_zone_str.=", Zone: ". $zone['zone_name'];
} else {
    $zone_ida = "";
    $zone_group="";
}

//if ($_POST['division_id'] != "" || $_POST['zone_id'] != "") {
//    $zone_group="$tbl" . "zone_info.zone_name,";
//} else {
//    $zone_group="";
//}

@$fdate = $db->DB_date_convert_year($db->date_formate($_POST['from_date']));
@$tdate = $db->DB_date_convert_year($db->date_formate($_POST['to_date']));
@$fyear = $db->date_formate($_POST['from_date']);
@$tyear = $db->date_formate($_POST['to_date']);

if ($_POST['from_date'] != "" || $_POST['to_date'] != "") {
    $date="AND $tbl" . "product_purchase_order_challan.challan_date BETWEEN '$fyear' AND '$tyear'";
    $year="AND substr($tbl" . "product_sale_target.start_date,1,4) BETWEEN '$fdate' AND '$tdate'";
    $date_str="Sales Target From Date: " . $_POST['from_date'] . " To Date: ".$_POST['to_date'];
} else {
    $date="";
    $year="";
    $date_str="Product Sale Target (2014)";
}


?>

<a class="btn btn-small btn-success" data-original-title="" onclick="print_rpt()" style="float: right;">
    <i class="icon-print" data-original-title="Share"> </i> Print
</a>
<div id="PrintArea" style="background-color: white;" >
    <?php include_once '../../libraries/print_page/Print_header.php'; ?>
    <div style="text-align: center;">
        <b>
            <u>
                <?php
                echo $division_zone_str;  echo "&nbsp; &nbsp; ".$date_str;
                ?>
            </u>
        </b>
    </div>


    <table class="table table-condensed table-striped table-hover table-bordered pull-left report" id="data-table">

        <thead>
            <tr>
<!--                --><?php
//                if($zone_group!="" || $division_id!="")
//                {
//                ?>
<!--                <th style="width:5%">-->
<!--                    Zone-->
<!--                </th>-->
<!--                --><?php //}?>
                <th style="width:5%">
                    Crop
                </th>
                <th style="width:5%">
                    Product Type
                </th>
                <th style="width:5%">
                    Variety
                </th>
                <th style="width:5%; text-align: right;">
                    Price(TK/Kg)
                </th>
                <th style="width:5%; text-align: right;">
                    Target(Kg)
                </th>
                <th style="width:5%; text-align: right;">
                    Achieve(Kg)
                </th>
                <th style="width:5%; text-align: right;">
                    Target Value(TK)
                </th>
                <th style="width:5%; text-align: right;">
                    Achieve(TK)
                </th>
                <th style="width:5%; text-align: right;">
                    Achieve(%)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $price = 0;
            $qnty = 0;
            $aqnty = 0;
            $tprice = 0;
            $atprice = 0;
            $totalp = 0;
            $persentance = 0;
            $invoice_id = '';
            $invoice_date = '';
            $zone_name = '';
            $crop_name = '';
            $product_type = '';
            $varriety_name = '';
            $territory_name = '';
            $distributor_name = '';
            $tpersentance = 0;
            $sl = 1;
            $show_zone=true;
            $sql = "SELECT
                        $tbl" . "product_sale_target.zone_id,
                        $tbl" . "product_sale_target.start_date,
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id,
                        $tbl" . "product_sale_target.price AS price,
                        SUM($tbl" . "product_sale_target.quantity) AS quantity,
                        SUM($tbl" . "product_sale_target.value) AS value,
                        $tbl" . "zone_info.zone_name,
                        $tbl" . "crop_info.crop_name,
                        $tbl" . "product_type.product_type,
                        $tbl" . "varriety_info.varriety_name,
                        (SELECT
                            SUM($tbl" . "product_purchase_order_challan.total_price)
                        FROM $tbl" . "product_purchase_order_challan
                        WHERE
                            $tbl" . "product_purchase_order_challan.crop_id=$tbl" . "product_sale_target.crop_id AND
                            $tbl" . "product_purchase_order_challan.product_type_id=$tbl" . "product_sale_target.product_type_id AND
                            $tbl" . "product_purchase_order_challan.varriety_id=$tbl" . "product_sale_target.varriety_id AND
                            $tbl" . "product_purchase_order_challan.`status`='Received' AND
                            $tbl" . "product_purchase_order_challan.del_status='0'
                            " . $db->get_zone_access($tbl . "product_purchase_order_challan") . " $division_ida $zone_ida $date
                        GROUP BY
                            $zone_group
                            $tbl" . "product_purchase_order_challan.crop_id,
                            $tbl" . "product_purchase_order_challan.product_type_id,
                            $tbl" . "product_purchase_order_challan.varriety_id
                        ) AS sale_target_persentance,
                        (SELECT
                            SUM(($tbl" . "product_purchase_order_challan.quantity * $tbl" . "product_pack_size.pack_size_name)/1000)
                        FROM $tbl" . "product_purchase_order_challan
                            LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_purchase_order_challan.pack_size
                        WHERE
                            $tbl" . "product_purchase_order_challan.crop_id=$tbl" . "product_sale_target.crop_id AND
                            $tbl" . "product_purchase_order_challan.product_type_id=$tbl" . "product_sale_target.product_type_id AND
                            $tbl" . "product_purchase_order_challan.varriety_id=$tbl" . "product_sale_target.varriety_id AND
                            $tbl" . "product_purchase_order_challan.`status`='Received' AND
                            $tbl" . "product_purchase_order_challan.del_status='0'
                            " . $db->get_zone_access($tbl . "product_purchase_order_challan") . " $division_ida $zone_ida $date
                        GROUP BY
                            $zone_group
                            $tbl" . "product_purchase_order_challan.crop_id,
                            $tbl" . "product_purchase_order_challan.product_type_id,
                            $tbl" . "product_purchase_order_challan.varriety_id
                        ) AS achieve_target_kg
                    FROM
                        $tbl" . "product_sale_target
                        LEFT JOIN $tbl" . "zone_info ON $tbl" . "zone_info.zone_id = $tbl" . "product_sale_target.zone_id
                        LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_sale_target.crop_id
                        LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_sale_target.product_type_id
                        LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_sale_target.varriety_id
                    WHERE $tbl" . "product_sale_target.del_status='0' AND
                        $tbl" . "product_sale_target.`status`='Active' AND
                        $tbl" . "product_sale_target.channel='Zone'
                        $zone_id " . $db->get_zone_access($tbl . "product_sale_target") . "
                        $division_id $year
                        $crop_id $product_type_id  $varriety_id
                    GROUP BY
                        $zone_group
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id
                    ORDER BY
                        $zone_group
                        $tbl" . "product_sale_target.crop_id,
                        $tbl" . "product_sale_target.product_type_id,
                        $tbl" . "product_sale_target.varriety_id
";

            /////////////// MAX PACK SIZE
            /*
              (SELECT
              max($tbl" . "product_pack_size.pack_size_name)
              FROM
              $tbl" . "product_info
              LEFT JOIN $tbl" . "product_pack_size ON $tbl" . "product_pack_size.pack_size_id = $tbl" . "product_info.pack_size
              WHERE
              $tbl" . "product_info.crop_id=$tbl" . "product_sale_target.crop_id AND
              $tbl" . "product_info.product_type_id=$tbl" . "product_sale_target.product_type_id AND
              $tbl" . "product_info.varriety_id=$tbl" . "product_sale_target.varriety_id
              GROUP BY
              $tbl" . "product_info.crop_id,
              $tbl" . "product_info.product_type_id,
              $tbl" . "product_info.varriety_id
              ) AS max_pack_size
             *
             */
            /////////////// MAX PACK SIZE

            if ($db->open()) {
                $result = $db->query($sql);
                $i = 1;
                while ($result_array = $db->fetchAssoc()) {
                    if ($i % 2 == 0) {
                        $rowcolor = "gradeC";
                    } else {
                        $rowcolor = "gradeA success";
                    }
//                    $distributor_id = $result_array['distributor_id'];
                    $crop_id = $result_array['crop_id'];
                    $product_type_id = $result_array['product_type_id'];
                    $varriety_id = $result_array['varriety_id'];
                    $start_date = $result_array['start_date'];

                    @$price = $price + $result_array['price'];
                    @$qnty = $qnty + $result_array['quantity'];

                    @$tprice = $tprice + $result_array['value'];
                    @$atprice = $atprice + $result_array['sale_target_persentance'];

//                    $atkg = ($result_array['achieve_target_kg'] * $result_array['max_pack_size']) / 1000;
                    @$aqnty = $aqnty + $result_array['achieve_target_kg'];

                    @$persentance = (($result_array['sale_target_persentance'] / $result_array['value']) * 100);
                    @$tpersentance = $tpersentance + $persentance;

//                    if ($zone_name == '')
//                    {
//                        echo "<tr><td colspan='9' style='background:#68A541'><b style='width: 50%; color: #fff; float:left;'> Zone: ".$result_array['zone_name'].  "</b> <b style='width: 50%; color: #fff; float:right;text-align: right;'>Sales Target From Date: " . $_POST['from_date'] . " To Date: ".$_POST['to_date']."</b></td></tr>";
//                        $zone_name = $result_array['zone_name'];
//                        //$currentDate = $preDate;
//                    } else if ($zone_name == $result_array['zone_name']) {
//                        //exit;
//                        echo "&nbsp;";
//                    } else {
//                        echo "<tr><td colspan='9' style='background:#68A541'><b style='width: 50%; color: #fff; float:left;'> Zone: ".$result_array['zone_name'].  "</b> <b style='width: 50%; color: #fff; float:right;text-align: right;'>Sales Target From Date: " . $_POST['from_date'] . " To Date: ".$_POST['to_date']."</b></td></tr>";
//                        $zone_name = $result_array['zone_name'];
//                    }
                    ?>
                    <tr class="<?php echo $rowcolor; ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["id"] ?>', '<?php echo $i; ?>')">
<!--                        --><?php
//                        if($zone_group!="" || $division_id!="")
//                        {
//                        ?>
<!--                            <td>-->
<!--                                --><?php
//                                if ($zone_name == '') {
//                                    echo $result_array['zone_name'];
//                                    $zone_name = $result_array['zone_name'];
//                                    //$currentDate = $preDate;
//                                } else if ($zone_name == $result_array['zone_name']) {
//                                    //exit;
//                                    echo "&nbsp;";
//                                } else {
//                                    echo $result_array['zone_name'];
//                                    $zone_name = $result_array['zone_name'];
//                                }
//                                ?>
<!--                            </td>-->
<!--                            --><?php
//                        }
//                        ?>

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
                        <td>
                            <?php
                            if ($varriety_name == '') {
                                echo $result_array['varriety_name'];
                                $varriety_name = $result_array['varriety_name'];
                                //$currentDate = $preDate;
                            } else if ($varriety_name == $result_array['varriety_name']) {
                                //exit;
                                echo "&nbsp;";
                            } else {
                                echo $result_array['varriety_name'];
                                $varriety_name = $result_array['varriety_name'];
                            }
                            ?>
                        </td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($result_array['price'], 2); ?></td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($result_array['quantity'], 2); ?></td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($result_array['achieve_target_kg'], 2); ?></td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($result_array['value'], 2); ?></td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($result_array['sale_target_persentance'], 2); ?></td>
                        <td style="width:5%; text-align: right;"><?php echo number_format($persentance, 2); ?></td>
                    </tr>
                    <?php
                    ++$i;
                    ++$sl;
                }
            }

            ?>
        <tfoot>
            <tr>
                <td colspan="4<?php //if($zone_group!="" || $division_id!=""){echo "5";}else{echo "4";}?>" style="text-align: right;">Total: </td>
                <!--<td style="text-align: right;"><?php // echo number_format($price, 2)    ?></td>-->
                <td style="text-align: right;"><?php echo number_format(@$qnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format(@$aqnty, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format(@$tprice, 2) ?></td>
                <td style="text-align: right;"><?php echo number_format(@$atprice, 2) ?></td>
                <td style="text-align: right;">
                    <?php
                    $totalp = ((@$atprice / @$tprice) * 100);
                    echo number_format(@$totalp, 2);
                    ?>
                </td>
            </tr>
<!--            <tr>
                <td colspan="15" style="text-align: right;">In word: <?php // echo $db->number_convert_inword($tprice)   ?> Only</td>
            </tr>-->
        </tfoot>
        </tbody>
    </table>
    <?php include_once '../../libraries/print_page/Print_footer.php'; ?>
</div>