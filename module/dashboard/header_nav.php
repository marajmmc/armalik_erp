<?php
//$db = new Database();
//$tbl = _DB_PREFIX;
//$ipinfo = "SELECT
//id,
//ei_id,
//unit_id,
//user_name,
//create_date,
//ip,
//mac
//FROM `$tbl" . "user_sync`
//where ei_id='" . $_SESSION['ei_id'] . "'
//order by id desc";
//if ($db->open()) {
//    $resultip = $db->query($ipinfo);
//    $rowip = $db->fetchArray($resultip);
//}
?>
<header>
    <a href="../../module/dashboard/dashboard.php" class="logo">
        <img src="../../system_images/logo/company-logo.png" alt="Logo" width="3%"/> 
    </a>

    <div class="user-profile">
        <a data-toggle="dropdown" class="dropdown-toggle">
            <?php // if ($_SESSION['ei_image_url'] == "") { ?>
                <!--<img src="../../module/employee_info/ei_photo/<?php // echo $_SESSION['ei_image_url'];  ?>" alt="Profile-Image">-->
            <?php // } else { ?>
            <img src="../../system_images/profile.png" alt="Profile-Image">
            <?php // } ?>
            <span class="caret"></span>
        </a>
        <ul class="dropdown-menu pull-right">
            <!--            <li>
                            <a href="#">
                                View Profile
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                Change Password
                            </a>
                        </li>-->
            <?php
            if ($_SESSION['user_id'] == "UI-000003") {
                ?>
                <li>
                    <a href="../../libraries/lib/db_backup.php">
                        Database Backup
                    </a>
                </li>
            <?php } ?>
            <li>
                <a href="../../module/dashboard/log_out.php">
                    Logout
                </a>
            </li>
        </ul>
    </div>
    <ul class="mini-nav">
        <!--        <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="">User: <?php // echo $rowip['user_name'];  ?></div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="">IP: <?php // echo $rowip['ip'];  ?></div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="">MAC: <?php // echo $rowip['mac'];  ?></div>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="">Login: <?php // echo $rowip['create_date'];  ?></div>
                    </a>
                </li>-->


        <!--        <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe040;"></div>
                        <span class="info-label" id="quickMessages">
                            3
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe04c;"></div>
                        <span class="info-label-green" id="quickAlerts">
                            5
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#">
                        <div class="fs1" aria-hidden="true" data-icon="&#xe037;"></div>
                        <span class="info-label-orange" id="quickShop">
                            9
                        </span>
                    </a>
                </li>-->
    </ul>
</header>