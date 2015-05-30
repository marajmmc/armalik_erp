<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$total_price="";
$total_quantity="";
$total_value="";
$sql = "SELECT
            $tbl" . "product_sale_target.id,
            $tbl" . "product_sale_target.sale_target_id,
            $tbl" . "product_sale_target.zone_id,
            $tbl" . "product_sale_target.territory_id,
            $tbl" . "product_sale_target.distributor_id,
            $tbl" . "product_sale_target.year_id,
            $tbl" . "product_sale_target.start_date,
            $tbl" . "product_sale_target.end_date,
            $tbl" . "product_sale_target.crop_id,
            $tbl" . "product_sale_target.product_type_id,
            $tbl" . "product_sale_target.varriety_id,
            $tbl" . "product_sale_target.price,
            $tbl" . "product_sale_target.quantity,
            $tbl" . "product_sale_target.value,
            $tbl" . "product_sale_target.`status`,
            $tbl" . "product_sale_target.del_status,
            $tbl" . "product_sale_target.entry_by,
            $tbl" . "product_sale_target.entry_date,
            $tbl" . "crop_info.crop_name,
            $tbl" . "product_type.product_type,
            $tbl" . "varriety_info.varriety_name
        FROM
            $tbl" . "product_sale_target
            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_sale_target.crop_id
            LEFT JOIN $tbl" . "product_type ON $tbl" . "product_type.product_type_id = $tbl" . "product_sale_target.product_type_id
            LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "product_sale_target.varriety_id
        WHERE $tbl" . "product_sale_target.sale_target_id='" . $_POST['rowID'] . "' AND
        $tbl" . "product_sale_target.del_status='0' AND $tbl" . "product_sale_target.channel='Zone'
        ORDER BY
            $tbl" . "crop_info.order_crop,
            $tbl" . "product_type.order_type,
            $tbl" . "varriety_info.order_variety

        ";
if ($db->open())
{
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result))
    {
        $elm_id[] = $row['id'];
        $sale_target_id = $row['sale_target_id'];
        $zone_id = $row['zone_id'];
        $territory_id = $row['territory_id'];
        $distributor_id = $row['distributor_id'];
        $year_id = $row['year_id'];
        //$end_date = $row['end_date'];
        $crop_id[] = $row['crop_id'];
        $product_type_id[] = $row['product_type_id'];
        $varriety_id[] = $row['varriety_id'];

        $crop_name[] = $row['crop_name'];
        $product_type_name[] = $row['product_type'];
        $variety_name[] = $row['varriety_name'];

        $price[] = $row['price'];
        $quantity[] = $row['quantity'];
        $value[] = $row['value'];
        $total_price=$total_price+$row['price'];
        $total_quantity=$total_quantity+$row['quantity'];
        $total_value=$total_value+$row['value'];
    }
}
?>

<table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
    <thead>
    <tr>
        <th style="width:15%">
            Crop
        </th>
        <th style="width:15%">
            Product Type
        </th>
        <th style="width:15%">
            Variety
        </th>
        <th style="width:15%">
            Price(TK/Kg)
        </th>
        <th style="width:15%">
            Target(Kg)
        </th>
        <th style="width:15%">
            value(TK)
        </th>
        <th style="width:5%">
            Action
        </th>
    </tr>
    </thead>
    <?php
    $rowcount = count($elm_id);
    for ($i = 0; $i < $rowcount; $i++)
    {
        //                                if ($i % 2 == 0)
        //                                {
        //                                    $rowcolor = "gradeC";
        //                                }
        //                                else
        //                                {
        //                                    $rowcolor = "gradeA success";
        //                                }
        ?>
        <tr id="tr_elm_id<?php echo $i; ?>">
            <td>
                <?php echo $crop_name[$i];?>
            </td>
            <td>
                <?php echo $product_type_name[$i];?>
            </td>
            <td>
                <?php echo $variety_name[$i];?>
            </td>
            <td>
                <?php echo $price[$i]; ?>
            </td>
            <td>
                <?php echo $quantity[$i]; ?>
            </td>
            <td>
                <?php echo $value[$i]; ?>
            </td>
            <td>
                <input type="button" value="Del" onclick="del_product('<?php echo $i; ?>','<?php echo $elm_id[$i]; ?>')" />
                <!--                                        <a class='btn btn-warning2' data-original-title='' onclick="del_product('--><?php //echo $i; ?><!--','--><?php //echo $elm_id[$i]; ?><!--')">-->
                <!--                                            <i class='icon-white icon-trash'> </i>-->
                <!--                                        </a>-->
            </td>
        </tr>
    <?php
    }
    ?>
</table>
<table class="table table-condensed table-striped table-bordered table-hover no-margin">
    <tfoot>
    <tr>
        <th style="width:15%">&nbsp;</th>
        <th style="width:15%">&nbsp;</th>
        <th style="width:15%">&nbsp;</th>
        <th style="width:15%" >
            <label style="text-align: right;">
                Yearly Target
            </label>
        </th>
        <!--                                    <th style="width:15%">-->
        <!--                                        <input readonly="" class="span" type="text" name="total_price" id="total_price" placeholder="Total Price" value="--><?php //echo $total_price?><!--" />-->
        <!--                                    </th>-->
        <th style="width:15%">
            <input readonly="" class="span" type="text" name="total_quantity" id="total_quantity" placeholder="Total Qty" value="<?php echo $total_quantity?>" />
        </th>
        <th style="width:15%">
            <input readonly="" class="span" type="text" name="total_value" id="total_value" placeholder="Total value" value="<?php echo $total_value?>" />
        </th>
        <th style="width:5%">&nbsp;</th>
    </tr>
    </tfoot>
</table>