
<?php
@session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");

$tbl = _DB_PREFIX;
$db = new Database();

if($_POST['crop_id']!=""){
    $crop_id="AND crop_id='".$_POST['crop_id']."'";
}else{
    $crop_id="AND crop_id=''";
}
if($_POST['product_type_id']!=""){
    $product_type_id="AND product_type_id='".$_POST['product_type_id']."'";
}else{
    $product_type_id="AND product_type_id=''";
}

?>
<table class="table table-condensed table-striped table-bordered table-hover no-margin">
    <thead>
    <tr>
        <th style="width:40%">
            Variety
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
     $sql1 = "SELECT
                    $tbl" . "pdo_product_info.pdo_id,
                    concat_ws(' - ', $tbl" . "pdo_product_info.pdo_name, pdo_type) as varriety_name
                FROM `$tbl" . "pdo_product_info`
                WHERE status='Active' AND del_status='0' $crop_id $product_type_id ORDER BY pdo_name
        ";
    $sql = "
                    select
                    varriety_id as pdo_id,
                    CONCAT_WS(' - ', varriety_name,
                    CASE
                            WHEN type=0 THEN 'ARM'
                            WHEN type=1 THEN 'Check Variety'
                            WHEN type=2 THEN 'Upcoming'
                    END, hybrid) as varriety_name
                    from $tbl" . "varriety_info
                    where
                    status='Active'
                    AND del_status='0'
                    $crop_id
                    $product_type_id
                    ORDER BY order_variety";
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
                    <input type='checkbox'  name='$result_array[pdo_id]' id='$result_array[pdo_id]' value='$result_array[pdo_id]'  />
                    $result_array[varriety_name]
                    <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='$result_array[pdo_id]'/>
                </td>
        </tr>";
            ++$i;
        }
    }
    ?>
    </tbody>
</table>