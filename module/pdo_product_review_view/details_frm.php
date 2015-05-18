<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$dbr = new Database();
$db = new Database();
$dbcomment = new Database();

$user_id=$_SESSION['user_id'];

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
//                         echo $sql = "SELECT
//                                $tbl" . "pdo_photo_upload.upload_id,
//                                $tbl" . "farmer_info.farmer_name,
//                                $tbl" . "pdo_product_info.pdo_name,
//                                $tbl" . "pdo_product_info.pdo_type,
//                                $tbl" . "pdo_photo_upload.description,
//                                $tbl" . "pdo_photo_upload.upload_date,
//                                $tbl" . "pdo_photo_upload.image_url,
//                                $tbl" . "pdo_photo_upload.`status`
//                            FROM
//                                $tbl" . "pdo_photo_upload
//                                LEFT JOIN $tbl" . "pdo_product_info ON $tbl" . "pdo_product_info.pdo_id = $tbl" . "pdo_photo_upload.pdo_id
//                                LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
//                            WHERE
//                                $tbl" . "pdo_photo_upload.del_status='0' AND
//                                $tbl" . "pdo_product_info.pdo_type='Self' AND
//                                $tbl" . "pdo_product_info.crop_id='" . $editrow['crop_id'] . "' AND
//                                $tbl" . "pdo_product_info.product_type_id='" . $editrow['product_type_id'] . "'
//                            ORDER BY $tbl" . "pdo_photo_upload.upload_date DESC
//                        ";
                        $upload_id = '';
                        $upload = '';
                        $toltips = '';
                        $sql = "SELECT
                                $tbl" . "pdo_photo_upload.upload_id,
                                $tbl" . "farmer_info.farmer_name,
                                $tbl" . "varriety_info.varriety_name,
                                CASE
                                        WHEN $tbl" . "varriety_info.type=0 THEN 'ARM'
                                        WHEN $tbl" . "varriety_info.type=1 THEN 'Check Variety'
                                        WHEN $tbl" . "varriety_info.type=2 THEN 'Upcoming'
                                END as pdo_type,
                                $tbl" . "pdo_photo_upload.upload_date,
                                $tbl" . "pdo_photo_upload.image_url,
                                $tbl" . "pdo_photo_upload.`status`,
                                $tbl" . "pdo_photo_description.description as image_description
                            FROM
                                $tbl" . "pdo_photo_upload
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
                                LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
                                LEFT JOIN $tbl" . "pdo_photo_description ON $tbl" . "pdo_photo_description.upload_id = $tbl" . "pdo_photo_upload.upload_id
                            WHERE $tbl" . "pdo_photo_upload.del_status='0' AND 
                                $tbl" . "varriety_info.type=0
                                AND $tbl" . "pdo_photo_upload.crop_id='".$editrow['crop_id']."'
                                AND $tbl" . "pdo_photo_upload.product_type_id='" . $editrow['product_type_id'] . "'
                                AND $tbl" . "pdo_photo_upload.entry_by='$user_id'
                            GROUP BY $tbl" . "pdo_photo_upload.upload_id
                            ORDER BY $tbl" . "pdo_photo_upload.upload_date DESC
                            ";
                        if ($db->open()) {
                            $result = $db->query($sql);
                            $sl = 1;
                            while ($row = $db->fetchAssoc($result)) {
//                                $toltiparr[] = $row['description'];
//                                $toltipstr = implode(', ', $toltiparr);
//                                $count = count($toltipstr);
//                                for ($i = 0; $i < $count; $i++) {
//                                    echo $toltiparr[$i];
//                                }

                                if ($upload_id == '') {
                                    $upload_id = $row['upload_id'];
                                    ?>
                                    <div class="<?php echo $div_class; ?>">
                                        <div class="mainbox" onclick="product_image_info('<?php echo $row['upload_id'] ?>');">
                                            <div class="subbox">
                                                <div class="imgbox">
                                                    <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" title="<?php echo $row['image_description']; ?>" />
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
                                    //$currentDate = $preDate;
                                } else if ($upload_id == $row['upload_id']) {
                                    //exit;
                                    echo "&nbsp;";
                                } else {
                                    ?>
                                    <div class="<?php echo $div_class; ?>">
                                        <div class="mainbox" onclick="product_image_info('<?php echo $row['upload_id'] ?>');">
                                            <div class="subbox">
                                                <div class="imgbox">
                                                    <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" title="<?php echo $row['image_description']; ?>" />
                                                </div>
                                                <div class="imgtitle">
                                                    <h6>
                                                        <?php echo $row['pdo_name']; ?>
                                                        (<?php echo $db->date_formate($row['upload_date']); ?>) - <?php echo $row['pdo_type']; ?>
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    $upload_id = $row['upload_id'];
                                }
                                ++$sl;
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
                                $tbl" . "pdo_photo_upload.upload_date,
                                $tbl" . "pdo_photo_upload.image_url,
                                $tbl" . "pdo_photo_upload.`status`,
                                $tbl" . "pdo_photo_description.description as image_description
                            FROM
                                $tbl" . "pdo_photo_upload
                                LEFT JOIN $tbl" . "varriety_info ON $tbl" . "varriety_info.varriety_id = $tbl" . "pdo_photo_upload.pdo_id
                                LEFT JOIN $tbl" . "farmer_info ON $tbl" . "farmer_info.farmer_id = $tbl" . "pdo_photo_upload.farmer_id
                                LEFT JOIN $tbl" . "pdo_photo_description ON $tbl" . "pdo_photo_description.upload_id = $tbl" . "pdo_photo_upload.upload_id
                            WHERE
                                $tbl" . "pdo_photo_upload.del_status='0' AND
                                $tbl" . "varriety_info.type=1 AND
                                $tbl" . "pdo_photo_upload.crop_id='" . $editrow['crop_id'] . "' AND
                                $tbl" . "pdo_photo_upload.product_type_id='" . $editrow['product_type_id'] . "'
                                AND $tbl" . "pdo_photo_upload.entry_by='$user_id'
                                GROUP BY $tbl" . "pdo_photo_upload.upload_id
                            ORDER BY $tbl" . "pdo_photo_upload.upload_date DESC

                        ";
                        if ($db->open()) {
                            $result = $db->query($sql);
                            $sl = 1;
                            while ($row = $db->fetchAssoc($result)) {
                                ?>
                                <div class="<?php echo $div_class; ?>">
                                    <div class="mainbox" onclick="product_image_info('<?php echo $row['upload_id'] ?>');">
                                        <div class="subbox">
                                            <div class="imgbox">
                                                <img src="../../system_images/pdo_upload_image/<?php echo $row['image_url']; ?>" title="<?php echo $row['image_description']; ?>" />
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