
<?php

//session_status();
//if ($_SESSION['logged'] != 'yes') {
//    $_REQUEST["msg"] = "TimeoutC";
//    header("location:../../index.php");
//}

require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db=new Database();
$tbl=_DB_PREFIX;
$sql = "SELECT
            $tbl" . "product_purchase_order_request.id,
            $tbl" . "product_purchase_order_request.purchase_order_id,
            $tbl" . "product_purchase_order_request.purchase_order_date,
            $tbl" . "product_purchase_order_request.warehouse_id,
            $tbl" . "product_purchase_order_request.year_id,
            $tbl" . "product_purchase_order_request.zilla_id,
            $tbl" . "product_purchase_order_request.zone_id,
            $tbl" . "product_purchase_order_request.territory_id,
            $tbl" . "product_purchase_order_request.distributor_id,
            $tbl" . "product_purchase_order_request.crop_id,
            $tbl" . "product_purchase_order_request.product_type_id,
            $tbl" . "product_purchase_order_request.varriety_id,
            $tbl" . "product_purchase_order_request.pack_size,
            $tbl" . "product_purchase_order_request.price,
            $tbl" . "product_purchase_order_request.quantity,
            $tbl" . "product_purchase_order_request.total_price,
            $tbl" . "product_purchase_order_request.remark,
            $tbl" . "product_purchase_order_request.`status`,
                $tbl" . "product_pack_size.pack_size_name
        FROM `$tbl" . "product_purchase_order_request`
            left join $tbl" . "product_pack_size on $tbl" . "product_pack_size.pack_size_id=$tbl" . "product_purchase_order_request.pack_size
        WHERE $tbl" . "product_purchase_order_request.purchase_order_id='" . $_POST['row_bonus_id'] . "' AND $tbl" . "product_purchase_order_request.del_status='0'
";
if ($db->open())
{
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result))
    {
        $elm_id[] = $row['id'];
        $warehouse_id = $row['warehouse_id'];
        $year_id = $row['year_id'];
        $zilla_id = $row['zilla_id'];
        $purchase_order_id = $row['purchase_order_id'];
        $remark = $row['remark'];
        $status = $row['status'];
        $purchase_order_date = $row['purchase_order_date'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $crop_id[] = $row['crop_id'];
        $product_type_id[] = $row['product_type_id'];
        $varriety_id[] = $row['varriety_id'];
        $pack_size[] = $row['pack_size'];
        $price[] = $row['price'];
        $quantity[] = $row['quantity'];
        $total_price[] = $row['total_price'];
        $pack_size_name[] = $row['pack_size_name'];
    }
}

?>

<h3><u>Product Bonus Information</u></h3>
<!--                            <div class="controls controls-row">-->
<!--                                <span class="label label label-info" style="cursor: pointer; float: right" onclick="RowIncrement()"> + Add More </span>-->
<!--                            </div>-->

<table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
    <thead>
    <tr>
        <th style="width:10%">
            Crop
        </th>
        <th style="width:10%">
            Product Type
        </th>
        <th style="width:10%">
            Variety
        </th>
        <th style="width:10%">
            Pack Size(gm)
        </th>
        <th style="width:10%">
            Qty(pieces)
        </th>
        <!--                                        <th style="width:10%">-->
        <!--                                            Stock in(kg)-->
        <!--                                        </th>-->
        <!--                                        <th style="width:5%">-->
        <!--                                            Action-->
        <!--                                        </th>-->
    </tr>
    </thead>

    <?php
    $db_bonus=new Database();
    $db_bonus_product=new Database();
    $sl=0;
    $quantity_gm=0;
    $rowcount = count($crop_id);
    for ($i = 0; $i < $rowcount; $i++)
    {
        $quantity_gm=$quantity[$i]*$pack_size_name[$i];
        $bonus=$db_bonus->single_data_w($tbl."bonus_role_setup", "bonus_rule_id, from_quantity, to_quantity", "from_quantity='$quantity_gm' AND crop_id='$crop_id[$i]' AND product_type_id='$product_type_id[$i]' AND varriety_id='$varriety_id[$i]' AND status='Active' AND del_status=0");
        //$from_quantity=$bonus['from_quantity'];
        //$to_quantity=$bonus['to_quantity'];

        //if(($from_quantity<=($quantity[$i]*$pack_size_name[$i]) && $to_quantity>=($quantity[$i]*$pack_size_name[$i])))
        //{
        $sql_bonus_product="SELECT
                                ait_bonus_role_setup_details.id,
                                ait_bonus_role_setup_details.bonus_rule_id,
                                ait_bonus_role_setup_details.crop_id,
                                ait_bonus_role_setup_details.product_type_id,
                                ait_bonus_role_setup_details.varriety_id,
                                ait_bonus_role_setup_details.pack_size,
                                ait_bonus_role_setup_details.bonus_quantity,
                                ait_bonus_role_setup_details.`status`,
                                ait_bonus_role_setup_details.del_status,
                                ait_crop_info.crop_name,
                                ait_product_type.product_type,
                                ait_varriety_info.varriety_name,
                                ait_product_pack_size.pack_size_name
                            FROM
                                ait_bonus_role_setup_details
                                LEFT JOIN ait_crop_info ON ait_crop_info.crop_id = ait_bonus_role_setup_details.crop_id
                                LEFT JOIN ait_product_type ON ait_product_type.product_type_id = ait_bonus_role_setup_details.product_type_id
                                LEFT JOIN ait_varriety_info ON ait_varriety_info.varriety_id = ait_bonus_role_setup_details.varriety_id
                                LEFT JOIN ait_product_pack_size ON ait_product_pack_size.pack_size_id = ait_bonus_role_setup_details.pack_size
                            WHERE
                                ait_bonus_role_setup_details.bonus_rule_id='".$bonus['bonus_rule_id']."'
                                AND ait_bonus_role_setup_details.crop_id='$crop_id[$i]'
                                AND ait_bonus_role_setup_details.product_type_id='$product_type_id[$i]'
                                AND ait_bonus_role_setup_details.varriety_id='$varriety_id[$i]'
                                AND ait_bonus_role_setup_details.`status`='Active'
                                AND ait_bonus_role_setup_details.del_status=0

                            ";
        if($db_bonus_product->open())
        {
            $result_bonus=$db_bonus_product->query($sql_bonus_product);
            while($row=$db_bonus_product->fetchAssoc($result_bonus))
            {
                ++$sl;
                ?>
                <tr class='' id="tr_elm_bonus_id<?php echo $sl; ?>">
                    <td>
                        <select id='bonus_crop_id_<?php echo $sl; ?>' name='bonus_crop_id[]' class='span12' placeholder='Crop' onchange='bonus_load_product_type_("<?php echo $sl; ?>")'>
                            <?php
                            //echo "<option value=''>Select</option>";
                            //$sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                            //echo $db->SelectList($sql_uesr_group, $row['crop_id']);
                            ?>
                            <?php
                            $db_crop=new Database();
                            $db_crop->get_crop_warehouse($row['crop_id'],$row['crop_id'],$warehouse_id, $year_id);
                            ?>
                        </select>
                        <input type='hidden' id='bonus_id[]' name='bonus_id[]' value='<?php echo $row['id']; ?>'/>
                    </td>
                    <td>
                        <select id='bonus_product_type_id_<?php echo $sl; ?>' name='bonus_product_type_id[]' class='span12' placeholder='Crop' onchange='bonus_load_varriety_fnc_("<?php echo $sl; ?>")'>
                            <?php
                            //echo "<option value=''>Select</option>";
                            $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND crop_id='$row[crop_id]' AND product_type_id='".$row['product_type_id']."'";
                            echo $db->SelectList($sql_uesr_group, $row['product_type_id']);
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id='bonus_varriety_id_<?php echo $sl; ?>' name='bonus_varriety_id[]' class='span12' placeholder='Zone' onchange='bonus_load_pack_size_fnc_("<?php echo $sl; ?>")'>
                            <?php
                            //echo "<option value=''>Select</option>";
                            $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND varriety_id='".$row['varriety_id']."'";
                            echo $db->SelectList($sql_uesr_group, $row['varriety_id']);
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id='bonus_pack_size_<?php echo $sl; ?>' name='bonus_pack_size[]' class='span12' placeholder='Zone' onchange='bonus_load_product_price_fnc_("<?php echo $sl; ?>");'>
                            <?php
                            //echo "<option value=''>Select</option>";
                            $sql_uesr_group = "select pack_size_id as fieldkey, pack_size_name as fieldtext from $tbl" . "product_pack_size where status='Active' AND pack_size_id='$row[pack_size]'";
                            echo $db->SelectList($sql_uesr_group, $row['pack_size']);
                            ?>
                        </select>
                        <!--                                                        <input type='hidden' id='bonus_pack_size_name_--><?php //echo $sl; ?><!--' name='bonus_pack_size_name[]' value=''/>-->
                    </td>
                    <td>
                        <input readonly type='text' name='bonus_quantity[]' maxlength='50' id='bonus_quantity_<?php echo $sl; ?>' class='span12' value='<?php echo $row['bonus_quantity']; ?>' onblur='load_bonus_product_qnantity_("<?php echo $sl; ?>")'  validate='Require' />
                    </td>
                    <!--                                                    <td>-->
                    <!--                                                        <input type='text' name='bonus_stock[]' maxlength='50' id='bonus_stock_--><?php //echo $sl; ?><!--' class='span12' value='' readonly="" />-->
                    <!--                                                    </td>-->
                    <!--                                                    <td>-->
                    <!--                                                        <a class='btn btn-warning2' data-original-title='' onclick="del_bonus_product('--><?php //echo $sl; ?><!--','--><?php //echo $row['id']; ?><!--')">-->
                    <!--                                                            <i class='icon-white icon-trash'> </i>-->
                    <!--                                                        </a>-->
                    <!--                                                    </td>-->
                </tr>
            <?php
            }
        }
        //}
    }
    ?>
</table>