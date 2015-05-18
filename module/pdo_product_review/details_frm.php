<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$dbr = new Database();
$db = new Database();
$tbl = _DB_PREFIX;
$editrow = $dbr->single_data($tbl . "pdo_photo_upload", "crop_id, product_type_id", "id", $_POST['rowID']);
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
                <div class="wrapper">
                    <div class="div50" style="background: #666666;">
                        <h6>Self Variety</h6>
                    </div>
                    <div class="div50" style="background: #666666;">
                        <h6>Checked Variety</h6>
                    </div>
                    <div class="div50">
                        <?php
                        $div_class = '';
                        $sql = "SELECT
                                $tbl" . "pdo_photo_upload.upload_id,
                                $tbl" . "farmer_info.farmer_name,
                                $tbl" . "varriety_info.varriety_name,
                                CASE
                                        WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                        WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                                        WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                END as pdo_type,
                                $tbl" . "pdo_photo_upload.description,
                                $tbl" . "pdo_photo_upload.upload_date,
                                $tbl" . "pdo_photo_upload.image_url,
                                $tbl" . "pdo_photo_upload.`status`
                            FROM
                                $tbl" . "pdo_photo_upload
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
                                LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
                            WHERE
                                $tbl" . "pdo_photo_upload.del_status='0' AND
                                $tbl" . "varriety_info.type=0 AND
                                $tbl" . "pdo_photo_upload.crop_id='" . $editrow['crop_id'] . "' AND
                                $tbl" . "pdo_photo_upload.product_type_id='" . $editrow['product_type_id'] . "'
                                " . $db->get_pri_variety_access($tbl . "pdo_photo_upload") . "
                            ORDER BY $tbl" . "pdo_photo_upload.upload_date DESC
                        ";
//                        WHERE
//                                $tbl" . "pdo_photo_upload.del_status='0' AND
//                                $tbl" . "pdo_product_info.pdo_type='Self' AND
//                                $tbl" . "pdo_product_info.crop_id='" . $editrow['crop_id'] . "' AND
//                                $tbl" . "pdo_product_info.product_type_id='" . $editrow['product_type_id'] . "'
                        if ($db->open()) {
                            $result = $db->query($sql);
                            $sl = 1;
                            while ($row = $db->fetchAssoc($result))
                            {
//                                if ($row['upload_id'] == "") {
//                                    echo "They are is no upload image.";
//                                } else {
                                ?>
                                <div class="<?php echo $div_class; ?>">
                                    <div class="mainbox" onclick="product_image_info('<?php echo $row['upload_id'] ?>');">
                                        <div class="subbox">
                                            <div class="imgbox">
                                                <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" title="<?php echo $row['description']; ?>" />
                                            </div>
                                            <div class="imgtitle">
                                                <h6>
                                                    <?php echo $row['varriety_name']; ?>
                                                    (<?php echo $db->date_formate($row['upload_date']); ?>) - <?php echo $row['pdo_type']; ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                ++$sl;
//                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="div50">
                        <?php
                        $div_class = '';
                        $sql = "SELECT
                                $tbl" . "pdo_photo_upload.upload_id,
                                $tbl" . "farmer_info.farmer_name,
                                $tbl" . "varriety_info.varriety_name,
                                CASE
                                        WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                        WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                                        WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                END as pdo_type,
                                $tbl" . "pdo_photo_upload.description,
                                $tbl" . "pdo_photo_upload.upload_date,
                                $tbl" . "pdo_photo_upload.image_url,
                                $tbl" . "pdo_photo_upload.`status`
                            FROM
                                $tbl" . "pdo_photo_upload
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
                                LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
                            WHERE
                                $tbl" . "pdo_photo_upload.del_status='0' AND
                                $tbl" . "varriety_info.type=1 AND
                                $tbl" . "pdo_photo_upload.crop_id='" . $editrow['crop_id'] . "' AND
                                $tbl" . "pdo_photo_upload.product_type_id='" . $editrow['product_type_id'] . "'
                                " . $db->get_pri_variety_access($tbl . "pdo_photo_upload") . "
                            ORDER BY $tbl" . "pdo_photo_upload.upload_date DESC
                        ";
                        if ($db->open()) {
                            $result = $db->query($sql);
                            $sl = 1;
                            while ($row = $db->fetchAssoc($result))
                            {
                                ?>
                                <div class="<?php echo $div_class; ?>">
                                    <div class="mainbox" onclick="product_image_info('<?php echo $row['upload_id'] ?>');">
                                        <div class="subbox">
                                            <div class="imgbox">
                                                <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" title="<?php echo $row['description']; ?>" />
                                            </div>
                                            <div class="imgtitle">
                                                <h6>
                                                    <?php echo $row['varriety_name']; ?>
                                                    (<?php echo $db->date_formate($row['upload_date']); ?>) - <?php echo $row['pdo_type']; ?>
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                ++$sl;
                            }
                        }
                        ?>
                    </div>
                </div>
                <div id="show_data">

                </div>
                <div id="shadow" onclick="close_image()"></div>
            </div>
        </div>
    </div>
</div>


<script>
    function image_info_pop(){
        $("#shadow").fadeIn();
        $("#show_data").fadeIn();
    }
    function close_image(){
        $("#shadow").fadeOut();
        $("#show_data").fadeOut();
        $("#show_data").html('');
    }
</script>