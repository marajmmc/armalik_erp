<?php
session_start();
ob_start();
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$tbl = _DB_PREFIX;
$sql = "SELECT
            $tbl" . "session_info.id,
            $tbl" . "session_info.session_id,
            $tbl" . "session_info.session_name,
            $tbl" . "session_info.from_date,
            $tbl" . "session_info.to_date,
            $tbl" . "session_info.crop_id,
            $tbl" . "session_info.product_type_id,
            $tbl" . "session_info.varriety_id,
            $tbl" . "session_info.product_status,
            $tbl" . "session_info.session_color,
            $tbl" . "session_info.session_from_date,
            $tbl" . "session_info.session_to_date,
            $tbl" . "session_info.`status`,
            $tbl" . "session_info.del_status,
            $tbl" . "session_info.entry_by,
            $tbl" . "session_info.entry_date
        FROM
            $tbl" . "session_info
        WHERE $tbl" . "session_info.session_id='" . $_POST['rowID'] . "'
";
if ($db->open()) {
    $result = $db->query($sql);
    while ($row = $db->fetchAssoc($result)) {
        $id[] = $row['id'];
        $session_id = $row['session_id'];
        $session_name = $row['session_name'];
        $from_date = $db->date_formate($row['from_date']);
        $to_date = $db->date_formate($row['to_date']);
        $crop_id = $row['crop_id'];
        $product_type_id = $row['product_type_id'];
        $varriety_id = $row['varriety_id'];
        $product_status[] = $row['product_status'];
        $session_color[] = $row['session_color'];
        $session_from_date[] = $db->date_formate($row['session_from_date']);
        $session_to_date[] = $db->date_formate($row['session_to_date']);
    }
}
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
                        <label class="control-label" for="session_name">
                            Season Name
                        </label>
                        <div class="controls">
                            <input disabled="" class="span9" type="text" name="session_name" id="session_name" value="<?php echo $session_name ?>" placeholder="Session Name" validate="Require">
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="designation_title_bn">
                            Season Period
                        </label>
                        <div class="controls">
                            <div class="input-append">
                                <input disabled="" type="text" name="from_date" id="from_date" value="<?php echo $from_date ?>" class="span9" placeholder="From Date"  />
                                <span class="add-on" id="calcbtn_from_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                            <div class="input-append">
                                <input type="text" name="to_date" id="to_date" value="<?php echo $to_date ?>" class="span9" placeholder="To Date"  />
                                <span class="add-on" id="calcbtn_to_date">
                                    <i class="icon-calendar"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="crop_id">
                            Crop
                        </label>
                        <div class="controls">
                            <select disabled="" id="crop_id" name="crop_id" class="span5" placeholder="Crop" validate="Require" onchange="load_product_type()">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active'";
                                echo $db->SelectList($sql_uesr_group, $crop_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="product_type_id">
                            Product Type
                        </label>
                        <div class="controls">
                            <select disabled="" id="product_type_id" name="product_type_id" class="span5" placeholder="Product Type" onchange="load_varriety_fnc()" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select product_type_id as fieldkey, product_type as fieldtext from $tbl" . "product_type where status='Active' AND del_status='0' AND crop_id='$crop_id'";
                                echo $db->SelectList($sql_uesr_group, $product_type_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="varriety_id">
                             Variety
                        </label>
                        <div class="controls">
                            <select disabled="" id="varriety_id" name="varriety_id" class="span5" placeholder="Select Variety" validate="Require">
                                <?php
                                echo "<option value=''>Select</option>";
                                $sql_uesr_group = "select varriety_id as fieldkey, varriety_name as fieldtext from $tbl" . "varriety_info where status='Active' AND del_status='0' AND crop_id='$crop_id' AND product_type_id='$product_type_id'";
                                echo $db->SelectList($sql_uesr_group, $varriety_id);
                                ?>
                            </select>
                            <span class="help-inline">
                                *
                            </span>
                        </div>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="wrapper">
                        <table class="table table-condensed table-striped table-bordered table-hover no-margin" id="TaskTable">
                            <thead>
                                <tr>
                                    <th style="width:10%">
                                        Select Status 
                                    </th>
                                    <th style="width:10%">
                                        Select Color
                                    </th>
                                    <th style="width:10%">
                                        From Date
                                    </th>
                                    <th style="width:10%">
                                        To Date
                                    </th>
                                </tr>
                                <?php
                                $count = count($product_status);
                                for ($i = 0; $i < $count; $i++) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?>">
                                        <td>
                                            <select disabled="" id='product_status_' name='product_status[]' class='span12' placeholder='Select Status' validate='Require'>
                                                <option value=''>Select</option>
                                                <option value='Before Start' <?php
                                if ($product_status[$i] == "Before Start") {
                                    echo "selected='selected'";
                                }
                                    ?> >Before Start</option>
                                                <option value='Peak' <?php
                                                    if ($product_status[$i] == "Peak") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Peak</option>
                                                <option value='Off Peak' <?php
                                                    if ($product_status[$i] == "Off Peak") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Off Peak</option>
                                                <option value='Finish' <?php
                                                    if ($product_status[$i] == "Finish") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Finish</option>
                                            </select>
                                            <input type='hidden' id='id_' name='id[]' value='<?php echo $id[$i] ?>'/>
                                        </td>
                                        <td>
                                            <select disabled="" id='session_color' name='session_color[]' class='span12' placeholder='Select Issue' validate='Require'>
                                                <option value=''>Select</option>
                                                <option value='Yellow' <?php
                                                    if ($session_color[$i] == "Yellow") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Yellow</option>
                                                <option value='Green' <?php
                                                    if ($session_color[$i] == "Green") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Green</option>
                                                <option value='Purple' <?php
                                                    if ($session_color[$i] == "Purple") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Purple</option>
                                                <option value='Red' <?php
                                                    if ($session_color[$i] == "Red") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Red</option>
                                                <option value='Magenta' <?php
                                                    if ($session_color[$i] == "Magenta") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Magenta</option>
                                                <option value='Blue' <?php
                                                    if ($session_color[$i] == "Blue") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Blue</option>
                                                <option value='Pink' <?php
                                                    if ($session_color[$i] == "Pink") {
                                                        echo "selected='selected'";
                                                    }
                                    ?> >Pink</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class='input-append'>
                                                <input disabled="" type='text' name='session_from_date[]' id='session_from_date_<?php echo $i; ?>' value="<?php echo $session_from_date[$i] ?>" class='span12' placeholder='From Date' />
                                                <span class='add-on' id='calcbtn_session_from_date_<?php echo $i; ?>'>
                                                    <i class='icon-calendar'></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class='input-append'>
                                                <input disabled="" type='text' name='session_to_date[]' id='session_to_date_<?php echo $i; ?>' value="<?php echo $session_to_date[$i] ?>" class='span12' placeholder='From Date' />
                                                <span class='add-on' id='calcbtn_session_to_date_<?php echo $i; ?>'>
                                                    <i class='icon-calendar'></i>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
