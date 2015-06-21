<?php

//////////////////  Start Make by Md. Maraj Hossain ///////////////
class Database {

// Connection parameters

    var $host = "";
    var $user = "";
    var $password = "";
    var $database = "";
    var $persistent = false;
// Database connection handle 
    var $conn = NULL;
// Database Select handle 
    var $dba = NULL;
// Query result 
    var $result = false;

//    function DB($host, $user, $password, $database, $persistent = false)
    function Database() {
        $config = new Config();

        $this->host = $config->host;
        $this->user = $config->user;
        $this->password = $config->password;
        $this->database = $config->database;
    }

    function open() {
        // Choose the appropriate connect function 
        if ($this->persistent) {
            $func = 'mysql_pconnect';
        } else {
            $func = 'mysql_connect';
        }
        if ($this->persistent) {
            $func = 'mysql_pconnect';
        } else {
            $func = 'mysql_connect';
        }

        // Connect to the MySQL server 


        $this->conn = @$func($this->host, $this->user, $this->password) or die("Could not connect to server");
        //exit;

        if (!$this->conn) {
//            header("Location: error.html");
            echo "Could not connect to server";
            exit;
            return false;
        } else {
            mysql_query("SET CHARACTER SET utf8");
            mysql_query("SET SESSION collation_connection ='utf8_general_ci'");
        }

        // Select the requested database 
        $this->dba = @mysql_select_db($this->database, $this->conn) or die("Could not select database");
        if (!$this->dba) {
            echo "Could not select database";
            return false;
        }

        return true;
    }

    function close() {
        return (@mysql_close($this->conn));
    }

    function error() {
        return (mysql_error());
    }

    function query($sql = '')
    {

        $this->result = @mysql_query($sql, $this->conn) or die(mysql_error());
        //        $this->close();
        return ($this->result != false);
    }

    function affectedRows() {
        return (@mysql_affected_rows($this->conn));
    }

    function numRows() {
        return (@mysql_num_rows($this->result));
    }

    function executeSQL($sql) {
        $this->open();
        $result = mysql_query($sql) or die("Query Fails:"
                        . "<li> Errno=" . mysql_errno()
                        . "<li> ErrDetails=" . mysql_error()
                        . "<li>Query=" . $sql);
    }

    function MaxID($table_name, $field, $mask) {
        $this->open();
        $sql = "SELECT $field FROM `$table_name`";
        $result = $this->query($sql);
        $count = $this->numRows();
        $MaxID = $mask . ($count + 1);
        return $MaxID;
        $this->close();
    }

    function NewMaxID($table_name, $field, $mask) {
        $this->open();
        $sql = "SELECT MAX(substr($field,4)) + 1 as MaxID FROM $table_name";
        $result = $this->query($sql);
        $TempMaxID = $this->singleFieldData('MaxID');
        if ($TempMaxID != '') {
            if (strlen($TempMaxID) == 1)
                $MaxID = $mask . $TempMaxID;
            else if (strlen($TempMaxID) == 2)
                $MaxID = substr($mask, 0, -2) . $TempMaxID;
            else if (strlen($TempMaxID) == 3)
                $MaxID = substr($mask, 0, -2) . $TempMaxID;
            else if (strlen($TempMaxID) == 4)
                $MaxID = substr($mask, 0, -4) . $TempMaxID;
        }
        else {
            $MaxID = $mask . '1';
        }

        //$MaxID = $mask . ($count + 1);
        return $MaxID;
    }

    /*
      @@ Function of MAX ID For ASC Module
     */

    function getMaxID_eight_digit($table_name, $field) {
        $this->open();
        $sql = "SELECT MAX(CAST(SUBSTRING($field,6)AS SIGNED)) AS max_field FROM `$table_name`";
        $result = $this->query($sql);
        $row = $this->fetchAssoc($result);
        $count = $row['max_field'];
        $MaxID = $count + 1;
        $MaxID = str_pad($MaxID, 8, '0', STR_PAD_LEFT);
        return $MaxID;
        $this->close();
    }

    function getMaxID_six_digit($table_name, $field) {
        $this->open();
        $sql = "SELECT MAX(CAST(SUBSTRING($field,4)AS SIGNED)) AS max_field FROM `$table_name`";
        $result = $this->query($sql);
        $row = $this->fetchAssoc($result);
        $count = $row['max_field'];
        $MaxIDVN = $count + 1;
        $MaxIDVN = str_pad($MaxIDVN, 6, '0', STR_PAD_LEFT);
        return $MaxIDVN;
        $this->close();
    }

    function Get_CustMaxID($table_name, $field, $digit, $group) {

        if ($group != "") {
            $grouptxt = "GROUP BY $field";
        } else {
            $grouptxt = "";
        }
        $cast_digit = ($digit - 2);
        $this->open();
        $sql = "SELECT MAX(CAST(SUBSTRING($field,$cast_digit)AS SIGNED)) AS max_field FROM `$table_name` $grouptxt";
        //exit;
        $result = $this->query($sql);
        $row = $this->fetchAssoc($result);
        $count = $row['max_field'];
        $MaxIDVN = $count + 1;
        $MaxIDVN = str_pad($MaxIDVN, $digit, '0', STR_PAD_LEFT);
        return $MaxIDVN;
        $this->close();
    }

    function SelectList($strSql, $selected = '') {
        $this->open();
        $result = $this->query($strSql);
        $selectData = "";
        while ($row = $this->fetchAssoc($result)) {
            if ($row["fieldkey"] == $selected) {
                $selectData.="<option value='$row[fieldkey]' selected='selected'>$row[fieldtext]</option>";
            } else {
                $selectData.="<option value='$row[fieldkey]'>$row[fieldtext]</option>";
            }
        }
        return $selectData;
        $this->close();
    }

    function return_result_array($sql)
    {
        $data=array();

        if($this->open())
        {
            $result=$this->query($sql);
            $row=$this->fetchAssoc($result);
            $data[]=$row;
            while($row=$this->fetchAssoc($result))
            {
                $data[]=$row;
            }
        }
        return $data;
    }

    function system_event_log($comid, $user_id, $ei_id, $maxID, $ref_no, $tbl_name, $event, $remarks) {
        $tbl = _DB_PREFIX;
        $taskname = $this->Get_Auto_TaskName();
        $InsertSQL = "INSERT INTO `$tbl" . "system_event_log` 
                    (
                        company_id,
                        user_id,
                        employee_id,
                        master_id,
                        referance_no,
                        table_name,
                        module_id,
                        task_id,
                        task_name,
                        event,
                        event_date,
                        entry_date,
                        remarks
                    )values(
                        '" . $comid . "',
                        '" . $user_id . "',
                        '" . $ei_id . "',
                        '" . $maxID . "',
                        '" . $ref_no . "',
                        '" . $tbl_name . "',
                        '" . @$_SESSION['sm_id'] . "',
                        '" . @$_SESSION['st_id'] . "',
                        '" . $taskname . "',
                        '" . $event . "',
                        '" . $this->getCurrentDateTime() . "',
                        '" . $this->ToDayDate() . "',
                        '" . $remarks . "'
                    )";
        $this->open();
        $this->query($InsertSQL);
        $this->freeResult();
        $this->close();
    }

////////// start existing data edit by maraj //////////////////
    function single_data($table_name, $field, $find_field, $find_value) {
        $this->open();
        $sql = "SELECT $field FROM `$table_name` WHERE $find_field='$find_value'";
        $result = $this->query($sql);
        return $row_result = $this->fetchAssoc($result);
        $this->close();
    }

    function single_data_w($table_name, $field, $find_field) {
        $this->open();
        $sql = "SELECT $field FROM `$table_name` WHERE $find_field";
        $result = $this->query($sql);
        return $row_result[] = $this->fetchArray($result);
        $this->close();
    }

    function select_query_list_grid($table, $field, $tabtd, $tabclass, $tabid) {
        $tabrow = "";
        $tabtdparameter = "";
        $parameter = "";
        $fieldcount = count($field);
        $tabtdcount = count($tabtd);
        for ($a = 0; $a < $tabtdcount; $a++) {
            $tabtdparameter = $tabtdparameter . "<th>" . $tabtd[$a] . "</th>";
        }
        for ($j = 0; $j < $fieldcount; $j++) {
            $parameter = $parameter . $field[$j];
        }

        $tabrow = $tabrow . " " . "<table id='$tabid' class='$tabclass'>
                <thead>
                    <tr>" . $tabtdparameter;

        $tabrow = $tabrow . " " . "</tr></thead>
                <tbody>";

        $select = "SELECT";
        $sql = $select . " " . $parameter . " " . "FROM `$table` ";
        if ($this->open()) {
            $result = $this->query($sql);
            $i = 0;
            $rowfield = '';
            $bodytag = str_replace(",", " ", $field);
            $first_array = trim(current($bodytag));
            while ($result_array = $this->fetchAssoc()) {
                $tabrow = $tabrow . " " . "<tr id='tr_id$i' onclick='get_rowID($result_array[$first_array], $i)' ondblclick='details_form();' class='pointer'>";
                for ($k = 0; $k < $fieldcount; $k++) {
                    $tabrow = $tabrow . " " . "<td>" . $result_array[trim($bodytag[$k])] . "</td>";
                }
                $tabrow = $tabrow . " " . "</tr>";
                ++$i;
            }
        }
        $tabrow = $tabrow . " " . "</tbody></table>";
        return $tabrow;
    }

    function data_insert($tab, $rowfield) {
        $rfield = '';
        $rvalue = '';
        foreach ($rowfield as $field => $value) {
            $rfield = $rfield . " " . $field;
            $rvalue = $rvalue . " " . $value;
        }
        $tab = "insert into `$tab`(";
        $val = ")Values(";
        $end = ")";
        $InsertSQL = $tab . " " . $rfield . " " . $val . " " . $rvalue . " " . $end;
        $this->open();
        echo $this->query($InsertSQL);
        $this->freeResult();
        $this->close();
    }

    function data_update($tab, $rowfield, $wherefield) {
        $rfield = '';
        $where = '';
        $wherecondition = '';
        $whereval = '';
        foreach ($rowfield as $field => $value) {
            $rfield = $rfield . " " . $field . "=" . $value . ",";
            $blank = "";
            $comma = ',';
            $fieldval = preg_replace(strrev("/$comma/"), strrev($blank), strrev($rfield), 1);
        }
        foreach ($wherefield as $wf => $wv) {
            if (count($wherefield) > 1) {
                $and = "AND";
            } else {
                $and = "";
            }
            $blank = "";
            $wherecondition = $wf . "=" . $wv . " " . $and;
            $whereval = preg_replace(strrev("/$and/"), strrev($blank), strrev($wherecondition), 1);
        }
        $tab = "update `$tab` set";
        $val = strrev($fieldval) . " where ";
        $end = strrev($whereval);
        $UpdateSQL = $tab . " " . $val . " " . $end;
        $this->open();
        echo $this->query($UpdateSQL);
        $this->freeResult();
        $this->close();
    }

    function TaskName($ModuleID, $TaskID) {
        $this->open();
        $tbl = _DB_PREFIX;
        $sql = "SELECT
                    st_name
                FROM
                    $tbl" . "system_task
                WHERE
                    st_id='$TaskID' AND st_sm_id='$ModuleID'
                ";
        $result = $this->query($sql);
        $row_result = $this->fetchAssoc($result);
        return $row_result['st_name'];
        $this->close();
    }

    function Get_Auto_TaskName() {
        $this->open();
        $tbl = _DB_PREFIX;

        $sql = "SELECT
                    st_name,
                    st_icon
                FROM
                    $tbl" . "system_task
                WHERE
                    st_id='" . @$_SESSION['st_id'] . "' AND st_sm_id='" . @$_SESSION['sm_id'] . "'
                ";
        $result = $this->query($sql);
        $row_result = $this->fetchAssoc($result);
        $st_icon = $row_result['st_icon'];
        //        if ($st_icon != '') {
        //            $task_icon = "<img  width=25 height=25 src='../../system_images/task_icon/" . $st_icon . "'>";
        //        } else {
        //            $task_icon = '<a class="btn btn-small" data-original-title="No Icon" title="No Icon">
        //                                                            <i class="icon-warning-sign" data-original-title="Share"> </i>
        //                                                        </a>';
        //        }
        //        @$task_name_icon = $task_icon .  $row_result['st_name'];
        @$task_name_icon = $row_result['st_name'];
        $this->close();
        return $task_name_icon;
    }

////////// end existing data edit by maraj //////////////////
////////// start get account head name edit by maraj //////////////////
    function get_act_head_number($act_type) {
        $this->open();
        $tbl = _DB_PREFIX;
        if ($act_type == 'Asset') {
            $sql = "SELECT
                        count(head_name) as icrID
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head_type='$act_type'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = "10" . $row_result['icrID'] + 1;
        } elseif ($act_type == 'Liability') {
            $sql = "SELECT
                        count(head_name) as icrID
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head_type='$act_type'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = "20" . $row_result['icrID'] + 1;
        } elseif ($act_type == 'Income') {
            $sql = "SELECT
                        count(head_name) as icrID
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head_type='$act_type'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = "30" . $row_result['icrID'] + 1;
        } elseif ($act_type == 'Expense') {
            $sql = "SELECT
                        count(head_name) as icrID
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head_type='$act_type'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = "40" . $row_result['icrID'] + 1;
        } elseif ($act_type == 'Equity') {
            $sql = "SELECT
                        count(head_name) as icrID
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head_type='$act_type'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = "50" . $row_result['icrID'] + 1;
        } else {
            $hn = '';
        }

        return $hn;
        $this->close();
    }

    function get_act_subhead_number($act_head)
    {
        $this->open();
        $tbl = _DB_PREFIX;

        $sql = "SELECT
                        count(sub_account_head) as icrID
                    FROM
                        $tbl" . "account_sub_head
                    WHERE
                        account_head='$act_head'";
        $result = $this->query($sql);
        $row_result = $this->fetchAssoc($result);

        $strlen = $act_head;
        if (strlen($strlen) == "3") {
            $hn = $strlen . "00" . $row_result['icrID'] + 1;
        } else if (strlen($strlen) == "4") {
            $hn = $strlen . "0" . $row_result['icrID'] + 1;
        } else if (strlen($strlen) == "5") {
            $hn = $strlen . $row_result['icrID'] + 1;
        } else {
            $hn = '';
        }

        return $hn;
        $this->close();
    }

    function get_act_head_name($act_id, $level = 0) {
        $this->open();
        $tbl = _DB_PREFIX;
        if ($level == '1') {
            $sql = "SELECT
                        head_name
                    FROM
                        $tbl" . "accounts_head
                    WHERE
                        account_head='$act_id'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = $row_result['head_name'];
        } elseif ($level == '2') {
            $sql = "SELECT
                        sub_head_name
                    FROM
                        $tbl" . "account_sub_head
                    WHERE
                        sub_account_head='$act_id'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = $row_result['sub_head_name'];
        } elseif ($level == '3') {
            $sql = "SELECT
                        sub_sub_head_name
                    FROM
                        $tbl" . "account_sub_sub_head
                    WHERE
                        sub_sub_account_head='$act_id'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = $row_result['sub_sub_head_name'];
        } elseif ($level == '4') {
            $sql = "SELECT
                        four_level_head_name
                    FROM
                        $tbl" . "account_four_level_head
                    WHERE
                        four_level_head='$act_id'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = $row_result['four_level_head_name'];
        } elseif ($level == '5') {
            $sql = "SELECT
                        five_level_head_name
                    FROM
                        $tbl" . "account_five_level_head
                    WHERE
                        five_level_head='$act_id'";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $hn = $row_result['five_level_head_name'];
        } else {
            $hn = '';
        }

        return $hn;
        $this->close();
    }

    function get_act_reverse_name($voucher_no, $type) {
        $this->open();
        $tbl = _DB_PREFIX;
        if ($type == "Debit") {
            $trnstype = "Credit";
        } elseif ($type == "Credit") {
            $trnstype = "Debit";
        } else {
            $trnstype = "";
        }
        $sql = "SELECT
                        account_name
                    FROM
                        $tbl" . "voucher_master
                    WHERE
                        voucher_no='$voucher_no' and transection_type='$trnstype'";
        $result = $this->query($sql);
        $row_result = $this->fetchAssoc($result);
        $hn = $row_result['account_name'];
        return $hn;
        $this->close();
    }

    function get_act_reverse_description($voucher_no, $type) {
        $this->open();
        $tbl = _DB_PREFIX;
        if ($type == "Debit") {
            $trnstype = "Credit";
        } elseif ($type == "Credit") {
            $trnstype = "Debit";
        } else {
            $trnstype = "";
        }
        $sql = "SELECT
                        description
                    FROM
                        $tbl" . "voucher_master
                    WHERE
                        voucher_no='$voucher_no' and transection_type='$trnstype'";
        $result = $this->query($sql);
        $row_result = $this->fetchAssoc($result);
        $hn = $row_result['description'];
        return $hn;
        $this->close();
    }

    function get_zone_access($TblName)
    {
        $tbl = _DB_PREFIX;
        $employee_id = $_SESSION['employee_id'];
        if ($_SESSION['user_level'] == "Division")
        {
            $access = "AND $TblName" . ".zone_id IN (SELECT zone_id FROM $tbl" . "zone_user_access WHERE $tbl" . "zone_user_access.status='Active' AND $tbl" . "zone_user_access.del_status='0' AND $tbl" . "zone_user_access.employee_id='$employee_id')";
        }
        else
        {
            $access = '';
        }
        return $access;
    }

    function get_pri_variety_access($TblName)
    {
        $tbl = _DB_PREFIX;
        $employee_id = $_SESSION['employee_id'];
        $dpt=$this->single_data_w($tbl."employee_basic_info", "department", "employee_id='$employee_id'");
        if ($dpt['department'] == "R&D Farm")
        {
            $access = "AND $tbl"."pdo_photo_upload.pdo_id in
            (
            SELECT $tbl"."assign_variety_pri.variety_id FROM $tbl"."assign_variety_pri
            WHERE
            $tbl"."assign_variety_pri.employee_id='$employee_id' AND
            $tbl"."assign_variety_pri.crop_id=$TblName.crop_id AND
            $tbl"."assign_variety_pri.product_type_id=$TblName.product_type_id
            )";
        }
        else
        {
            $access = '';
        }
        return $access;
    }

    function get_product_stock($year=NULL, $warehouse = NULL, $crop = NULL, $product_type = NULL, $variety = NULL, $pack_size = NULL, $quantity = NULL)
    {
        $tbl = _DB_PREFIX;
        if ($year != "" && $warehouse != "" && $crop != "" && $product_type != "" && $variety != "" && $pack_size != "")
        {
            $sql="SELECT
                        pi.id,
                        $tbl"."warehouse_info.warehouse_name,
                        $tbl"."crop_info.crop_name,
                        $tbl"."product_type.product_type,
                        $tbl"."varriety_info.varriety_name,
                        $tbl"."product_pack_size.pack_size_name,
                        pi.crop_id,
                        pi.product_type_id,
                        pi.varriety_id,
                        pi.pack_size,
                        (
                        SELECT SUM(ppi.quantity) FROM $tbl"."product_purchase_info AS ppi
                        WHERE
                        ppi.year_id=pi.year_id AND
                        ppi.warehouse_id=pi.warehouse_id AND
                        ppi.crop_id=pi.crop_id AND
                        ppi.product_type_id = pi.product_type_id AND
                        ppi.varriety_id = pi.varriety_id AND
                        ppi.pack_size = pi.pack_size
                        ) AS Total_HQ_Purchase_Quantity,
                        (
                        SELECT SUM(ppoi.approved_quantity) FROM $tbl"."product_purchase_order_invoice AS ppoi
                        WHERE
                        ppoi.year_id=pi.year_id AND
                        ppoi.warehouse_id=pi.warehouse_id AND
                        ppoi.crop_id=pi.crop_id AND
                        ppoi.product_type_id = pi.product_type_id AND
                        ppoi.varriety_id = pi.varriety_id AND
                        ppoi.pack_size = pi.pack_size
                        ) AS Total_Sales_Quantity,
                        (
                        SELECT SUM(ppob.quantity) FROM $tbl"."product_purchase_order_bonus AS ppob
                        WHERE
                        ppob.year_id=pi.year_id AND
                        ppob.warehouse_id=pi.warehouse_id AND
                        ppob.crop_id=pi.crop_id AND
                        ppob.product_type_id = pi.product_type_id AND
                        ppob.varriety_id = pi.varriety_id AND
                        ppob.pack_size = pi.pack_size
                        ) AS Total_Bonus_Quantity,
                        (
                        SELECT SUM(pin.damage_quantity) FROM $tbl"."product_inventory AS pin
                        WHERE
                        pin.year_id=pi.year_id AND
                        pin.warehouse_id=pi.warehouse_id AND
                        pin.crop_id=pi.crop_id AND
                        pin.product_type_id = pi.product_type_id AND
                        pin.varriety_id = pi.varriety_id AND
                        pin.pack_size = pi.pack_size
                        ) AS Total_Short_Quantity,
                        (
                        SELECT SUM(pin.access_quantity) FROM $tbl"."product_inventory AS pin
                        WHERE
                        pin.year_id=pi.year_id AND
                        pin.warehouse_id=pi.warehouse_id AND
                        pin.crop_id=pi.crop_id AND
                        pin.product_type_id = pi.product_type_id AND
                        pin.varriety_id = pi.varriety_id AND
                        pin.pack_size = pi.pack_size
                        ) AS Total_Access_Quantity
                    FROM
                        $tbl"."product_info AS pi
                        LEFT JOIN $tbl"."warehouse_info ON $tbl"."warehouse_info.warehouse_id = pi.warehouse_id
                        LEFT JOIN $tbl"."crop_info ON $tbl"."crop_info.crop_id = pi.crop_id
                        LEFT JOIN $tbl"."product_type ON $tbl"."product_type.product_type_id = pi.product_type_id
                        LEFT JOIN $tbl"."varriety_info ON $tbl"."varriety_info.varriety_id = pi.varriety_id
                        LEFT JOIN $tbl"."product_pack_size ON $tbl"."product_pack_size.pack_size_id = pi.pack_size
                    WHERE
                        pi.del_status=0
                        AND pi.year_id='$year'
                        AND pi.warehouse_id='$warehouse'
                        AND pi.crop_id='$crop'
                        AND pi.product_type_id='$product_type'
                        AND pi.pack_size='$pack_size'
                        AND pi.varriety_id='$variety'
                    GROUP BY
                        pi.crop_id, pi.product_type_id, pi.varriety_id, pi.pack_size
                    ORDER BY
                        $tbl"."warehouse_info.warehouse_id,
                        $tbl"."crop_info.order_crop,
                        $tbl"."product_type.order_type,
                        $tbl"."varriety_info.order_variety";
            $result = $this->query($sql);
            $row_result = $this->fetchAssoc($result);
            $current_stock=($row_result['Total_HQ_Purchase_Quantity']-(($row_result['Total_Sales_Quantity']+$row_result['Total_Bonus_Quantity']+$row_result['Total_Access_Quantity'])-$row_result['Total_Short_Quantity']));
            if ($quantity <= $current_stock)
            {
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
        else
        {
            return FALSE;
        }
    }

    function get_valid_product_stock_table($warehouse = NULL, $crop = NULL, $product_type = NULL, $varreity = NULL, $pack_size = NULL)
    {
        $tbl = _DB_PREFIX;
        if (!empty($warehouse) && !empty($crop) && !empty($product_type) && !empty($varreity) && !empty($pack_size))
        {
            $sql = "SELECT
                        current_stock_qunatity
                    FROM
                        $tbl" . "product_stock
                    WHERE
                        status='Active' AND del_status='0' AND
                        warehouse_id='$warehouse' AND
                        crop_id='$crop' AND
                        product_type_id='$product_type' AND
                        varriety_id='$varreity' AND
                        pack_size='$pack_size'
                ";
            if($this->open())
            {
                $result = $this->query($sql);
                $row_result = $this->numRows($result);
                //$quty = $row_result['current_stock_qunatity'];
                if ($row_result>0)
                {
                    return TRUE;
                }
                else
                {
                    return FALSE;
                }
            }
        }
        else
        {
            return FALSE;
        }
    }

////////// start get account head name edit by maraj //////////////////

    function date_formate($date) 
    {
        if(!empty($date))
        {
            $datec = explode('-', $date);
            $date_index = $datec[2] . "-" . $datec[1] . "-" . $datec[0];
        }
        else
        {
            $date_index="";
        }

        return $date_index;
    }

    function DB_date_convert_year($date) {
        $datec = explode('-', $date);
        $date_index = $datec[0];
        return $date_index;
    }

    function getTime() {
        date_default_timezone_set('Asia/Dhaka');
        return $ctime = time();
    }

    function ToDayDate() {
        return date('Y-m-d', $this->getTime());
    }

    function getCurrentDateTime() {
        return date('Y-m-d H:i:s', $this->getTime());
    }

    function get_increment_DMY($date1, $date2, $type1) {
        if ($type1 == "") {
            $type = "";
            return false;
        } else {
            $type = $type1;
        }
        if ($type == "year") {
            $dformate = "Y";
        } else if ($type == "month") {
            $dformate = "m";
        } else if ($type == "day") {
            $dformate = "d";
        } else {
            $dformate = "d-m-Y";
        }
        $time1 = strtotime($date1);
        $time2 = strtotime($date2);
        $fstdate = date('m-Y', $time1);
        $senddate = date('m-Y', $time2);
        if ($fstdate != $senddate) {
            $my = date('mY', $time2);
            $months = array(date($dformate, $time1));
            while ($time1 < $time2) {
                $time1 = strtotime(date('Y-m-d', $time1) . " +1 $type");
                if (date('mY', $time1) != $my && ($time1 < $time2))
                    $months[] = date($dformate, $time1);
            }
            $months[] = date($dformate, $time2);
        }else {
            $months[] = date($dformate, $time2);
        }
        $deff_month = implode("~", $months);
        return $deff_month;
    }

    function numCols() {
        return @mysql_num_fields($this->result);
        $this->close();
    }

    function fieldName($field) {
        return (@mysql_field_name($this->result, $field));
    }

    function singleFieldData($field) {
        $str = mysql_result($this->result, 0, $field);
        return $str;
        $this->close();
    }

    function insertID() {
        return (@mysql_insert_id($this->conn));
        $this->close();
    }

    function fetchObject() {
        return (@mysql_fetch_object($this->result, MYSQL_ASSOC));
        $this->close();
    }

    function fetchArray() {
        return (@mysql_fetch_array($this->result, MYSQL_BOTH));
        $this->close();
    }

    function fetchAssoc() {
        return (@mysql_fetch_assoc($this->result));
        $this->close();
    }

    function freeResult() {
        return (@mysql_free_result($this->result));
        $this->close();
    }

    function user_type($ei, $and) {
        if ($ei == "ei_id") {
            if ($_SESSION['user_type'] == "Group") {
                return $ei_field = "";
            } elseif ($_SESSION['user_type'] == "User") {
                return $ei_field = "$and ei_id='$_SESSION[ei_id]'";
            }
        } elseif ($ei == "employee_id") {
            if ($_SESSION['user_type'] == "Group") {
                return $employee_field = "";
            } elseif ($_SESSION['user_type'] == "User") {
                return $employee_field = "$and employee_id='$_SESSION[ei_id]'";
            }
        } elseif ($ei == "read_only") {
            if ($_SESSION['user_type'] == "Group") {
                return $read_only = "";
            } elseif ($_SESSION['user_type'] == "User") {
                return $read_only = "readonly=''";
            }
        }
    }

    function common_bangla($str) {
        if ($str == "Male") {
            return "প�?র�?ষ";
        } elseif ($str == "Female") {
            return "মহিলা";
        } elseif ($str == "Yes") {
            return "হ�?যা�?";
        } elseif ($str == "No") {
            return "না";
        } elseif ($str == "Married") {
            return "বিবাহিত";
        } elseif ($str == "Un_Married") {
            return "অবিবাহিত";
        } elseif ($str == "Islam") {
            return "ইসলাম";
        } elseif ($str == "Hindu") {
            return "হিন�?দ�?";
        } elseif ($str == "Buddhists") {
            return "বৌদ�?ধ";
        } elseif ($str == "Christians") {
            return "খৃষ�?ঠান";
        } elseif ($str == "Active") {
            return "চলমান";
        } elseif ($str == "In-Active" || $str == "InActive") {
            return "স�?থগিত";
        }
    }

    function getBanglaToEnglish($unicodeNumber) {
        $englishNumber = mb_convert_encoding($unicodeNumber, "HTML-ENTITIES", "UTF-8");
        $englishNumber = str_replace('&#2534;', '0', $englishNumber);
        $englishNumber = str_replace('&#2535;', '1', $englishNumber);
        $englishNumber = str_replace('&#2536;', '2', $englishNumber);
        $englishNumber = str_replace('&#2537;', '3', $englishNumber);
        $englishNumber = str_replace('&#2538;', '4', $englishNumber);
        $englishNumber = str_replace('&#2539;', '5', $englishNumber);
        $englishNumber = str_replace('&#2540;', '6', $englishNumber);
        $englishNumber = str_replace('&#2541;', '7', $englishNumber);
        $englishNumber = str_replace('&#2542;', '8', $englishNumber);
        $englishNumber = str_replace('&#2543;', '9', $englishNumber);
        return $englishNumber;
    }

    function getEnglishToBangla($str) {
        $engNumber = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0, '');
        $bangNumber = array('১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০', '');
        $converted = str_replace($engNumber, $bangNumber, $str);
//        $converted = str_replace('০', '0', $str);
//        $converted = str_replace('১', '1', $str);
//        $converted = str_replace('২', '2', $str);
//        $converted = str_replace('৩', '3', $str);
//        $converted = str_replace('৪', '4', $str);
//        $converted = str_replace('৫', '5', $str);
//        $converted = str_replace('৬', '6', $str);
//        $converted = str_replace('৭', '7', $str);
//        $converted = str_replace('৮', '8', $str);
//        $converted = str_replace('৯', '9', $str);
        return $converted;
    }

    function getBanglaMonthName($month) {
        $monthName = array('01' => 'জান�?য়ারি',
            '02' => 'ফেব�?র�?য়ারি',
            '03' => 'মার�?চ',
            '04' => '�?প�?রিল',
            '05' => 'মে',
            '06' => 'জ�?ন',
            '07' => 'জ�?লাই',
            '08' => 'আগষ�?ট',
            '09' => 'সেপ�?টেম�?বর',
            '10' => 'অক�?টোবর',
            '11' => 'নভেম�?বর',
            '12' => 'ডিসেম�?বর',
            '1' => 'জান�?য়ারি',
            '2' => 'ফেব�?র�?য়ারি',
            '3' => 'মার�?চ',
            '4' => '�?প�?রিল',
            '5' => 'মে',
            '6' => 'জ�?ন',
            '7' => 'জ�?লাই',
            '8' => 'আগষ�?ট',
            '9' => 'সেপ�?টেম�?বর',
            '0' => '',
            '' => '');
        return $monthName[$month];
    }

    function number_convert_inword($number) {
        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 100000);  /* Millions (giga) */
        $number -= $Gn * 100000;
        $kn = floor($number / 1000);     /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);      /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);       /* Tens (deca) */
        $n = $number % 10;               /* Ones */

        $res = "";

        if ($Gn) {
            $res .= $this->number_convert_inword($Gn) . " Lacs";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") .
                    $this->number_convert_inword($kn) . " Thousand";
        }

        if ($Hn) {
            $res .= (empty($res) ? "" : " ") .
                    $this->number_convert_inword($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
            "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
            "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
            "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }

    //////////////////  Start  Copy Dir Function ///////////////
    function recurse_copy($src, $dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ( $file = readdir($dir))) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    function rename_task_name($crrent_name, $rename) {
        rename($crrent_name, $rename);
    }

    //////////////////  End  Copy Dir Function ///////////////

    function get_season_color($color_name) {
        if ($color_name == "Yellow") {
            return $color = "#FFFF00";
        } else if ($color_name == "Green") {
            return $color = "#008000";
        } else if ($color_name == "Purple") {
            return $color = "#800080";
        } else if ($color_name == "Red") {
            return $color = "#FF0000";
        } else if ($color_name == "Magenta") {
            return $color = "#FF00FF";
        } else if ($color_name == "Blue") {
            return $color = "#0000FF";
        } else if ($color_name == "Pink") {
            return $color = "#FAAFBE";
        } else {
            return $color = "";
        }
    }

    function get_division($selected='', $where='')
    {
        $tbl=_DB_PREFIX;
        if(@$_SESSION['user_level'] == "Division")
        {
            $division="AND $tbl" . "division_info.division_id='".@$_SESSION['division_id']."'";
        }
        else if(@$_SESSION['user_level'] == "Zone")
        {
            $division="AND $tbl" . "zone_user_access.zone_id='".@$_SESSION['zone_id']."'";
        }
        else if($where)
        {
            $division="AND ait_division_info.division_id='".$where."'";
        }
        else
        {
            echo "<option value=''>Select</option>";
            $division="";
        }

        $this->open();

        $sql_division="
                        SELECT
                            ait_division_info.division_name as fieldtext,
                            ait_division_info.division_id as fieldkey
                        FROM
                          ait_division_info
                        WHERE
                          ait_division_info.status='Active' AND ait_division_info.del_status=0
                          $division
                        GROUP BY ait_division_info.division_id
                        ";

        if($selected)
        {
            echo $this->SelectList($sql_division, $selected);
        }
        else
        {
            echo $this->SelectList($sql_division);
        }
        $this->close();
    }

    function get_crop($selected='', $where='')
    {
        $tbl=_DB_PREFIX;
        if($where)
        {
            $where_condition="AND $tbl" . "crop_info.crop_id='".$where."'";
        }
        else
        {
            echo "<option value=''>Select</option>";
            $where_condition="";
        }

        $this->open();

        $sql_crop = "select crop_id as fieldkey, crop_name as fieldtext from $tbl" . "crop_info where status='Active' AND del_status='0' $where_condition order by order_crop";

        if($selected)
        {
            echo $this->SelectList($sql_crop, $selected);
        }
        else
        {
            echo $this->SelectList($sql_crop);
        }
        $this->close();
    }

    function get_fiscal_year($selected='', $where='')
    {
        $tbl=_DB_PREFIX;
        if($where)
        {
            $where_condition="AND $tbl" . "year.year_id='".$where."'";
        }
        else
        {
            //echo "<option value=''>Select</option>";
            $where_condition="";
        }

        $this->open();

        $sql_crop = "select year_id as fieldkey, year_name as fieldtext from $tbl" . "year where status='Active' AND del_status='0' $where_condition order by year_id";

        if($selected)
        {
            echo $this->SelectList($sql_crop, $selected);
        }
        else
        {
            echo $this->SelectList($sql_crop);
        }
        $this->close();
    }

    function get_zone_assign_district($selected='', $zilla_id='', $zone_id, $territory_id)
    {
        $this->open();
        $tbl=_DB_PREFIX;
        if($zilla_id)
        {
            $zilla_id="AND $tbl" . "zilla.zillaid='$zilla_id'";
        }
        else
        {
            $zilla_id='';
            echo "<option value=''>Select</option>";
        }
        $sql_zilla = "SELECT
                                $tbl" . "zilla.zillaid as fieldkey,
                                $tbl" . "zilla.zillanameeng as fieldtext
                            FROM
                                $tbl" . "territory_assign_district
                                LEFT JOIN $tbl" . "zilla ON $tbl" . "zilla.zillaid = $tbl" . "territory_assign_district.zilla_id
                            WHERE
                                $tbl" . "territory_assign_district.del_status=0
                                $zilla_id
                                AND $tbl" . "zilla.visible=0
                                AND $tbl" . "territory_assign_district.status='Active'
                                AND $tbl" . "territory_assign_district.zone_id='".$zone_id."' AND $tbl" . "territory_assign_district.territory_id='".$territory_id."'
                        ";

        if($selected)
        {
            echo $this->SelectList($sql_zilla, $selected);
        }
        else
        {
            echo $this->SelectList($sql_zilla);
        }
        $this->close();
    }

    function get_crop_warehouse($selected='', $crop_id='', $warehouse_id, $year_id)
    {
        $this->open();
        $tbl=_DB_PREFIX;
        if($crop_id)
        {
            $crop_id="AND $tbl" . "product_info.crop_id='".$crop_id."'";
        }
        else
        {
            $crop_id='';
            echo "<option value=''>Select</option>";
        }
        $sql_crop = "SELECT
                            $tbl" . "crop_info.crop_id AS fieldkey,
                            $tbl" . "crop_info.crop_name AS fieldtext
                        FROM
                            $tbl" . "product_info
                            LEFT JOIN $tbl" . "crop_info ON $tbl" . "crop_info.crop_id = $tbl" . "product_info.crop_id
                        WHERE
                                $tbl" . "product_info.warehouse_id='".$warehouse_id."'
                                AND $tbl" . "product_info.year_id='".$year_id."'
                                $crop_id
                                AND $tbl" . "crop_info.`status`='Active' AND $tbl" . "product_info.crop_id IN (SELECT $tbl" . "product_pricing.crop_id FROM $tbl" . "product_pricing WHERE $tbl" . "product_pricing.`status`='Active')
                        GROUP BY $tbl" . "crop_info.crop_id
                        ORDER BY $tbl" . "crop_info.order_crop
                        ";

        if($selected)
        {
            echo $this->SelectList($sql_crop, $selected);
        }
        else
        {
            echo $this->SelectList($sql_crop);
        }
        $this->close();
    }

    function get_month_array()
    {
        $month[1]='January';
        $month[2]='February';
        $month[3]='March';
        $month[4]='April';
        $month[5]='May';
        $month[6]='June';
        $month[7]='July';
        $month[8]='August';
        $month[9]='September';
        $month[10]='October';
        $month[11]='November';
        $month[12]='December';
        return $month;
    }

    function get_week_days()
    {
        $week[1] = 'Saturday';
        $week[2] = 'Sunday';
        $week[3] = 'Monday';
        $week[4] = 'Tuesday';
        $week[5] = 'Wednesday';
        $week[6] = 'Thursday';
        return $week;
    }
}


//////////////////  Start Make by Md. Maraj Hossain ///////////////
?>