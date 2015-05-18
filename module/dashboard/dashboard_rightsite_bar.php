<?php
$db = new Database();
$tbl = _DB_PREFIX;
$ipinfo = "SELECT
                id,
                employee_id,
                user_name,
                user_level,
                create_date,
                ip,
                mac
            FROM `$tbl" . "user_sync`
            WHERE employee_id='" . $_SESSION['employee_id'] . "'
            ORDER BY id desc";
if ($db->open()) {
    $resultip = $db->query($ipinfo);
    $rowip = $db->fetchArray($resultip);
}
?>


    <div class="wrapper">
        <ul class="month-income">
            <li>
                <span class="icon-block blue-block">
                    <b class="fs1" aria-hidden="true" ></b>
                </span>
                <h5>
                    User Name: 

<!--                    <small class="info-fade">
    Developer
</small>-->
                </h5>
                <p>
                    <?php echo $rowip['user_name']; ?>
                </p>
            </li>
<!--            <li>
                <span class="icon-block green-block">
                    <b class="fs1" aria-hidden="true" ></b>
                </span>
                <h5>
                    User Level: 

                    <small class="info-fade">
    Developer
</small>
                </h5>
                <p>
                    <?php // echo $rowip['user_level']; ?>
                </p>
            </li>-->
            <li>
                <span class="icon-block orange-block">
                    <b class="fs1" aria-hidden="true" ></b>
                </span>
                <h5>
                    IP Address: 

<!--                    <small class="info-fade">
    Developer
</small>-->
                </h5>
                <p>
                    <?php echo $rowip['ip']; ?>
                </p>
            </li>
            <li>
                <span class="icon-block green-block">
                    <b class="fs1" aria-hidden="true"></b>
                </span>
                <h5>
                    Mac Address: 

<!--                    <small class="info-fade">
    Developer
</small>-->
                </h5>
                <p>
                    <?php echo $rowip['mac']; ?>
                </p>
            </li>
            <li>
                <span class="icon-block yellow-block">
                    <b class="fs1" aria-hidden="true" ></b>
                </span>
                <h5>
                    Login Time: 

<!--                    <small class="info-fade">
    Developer
</small>-->
                </h5>
                <p>
                    <?php echo $rowip['create_date']; ?>
                </p>
            </li>
        </ul>
    </div>
<!--    <div class="wrapper">
        <ul class="stats">
            <li>
                <div class="left">
                    <h4>
                        15,859
                    </h4>
                    <p>
                        Unique Visitors
                    </p>
                </div>
                <div class="chart">
                    <span id="unique-visitors">
                        2, 4, 1, 7, 9, 8, 2, 3, 5, 6
                    </span>
                </div>
            </li>
            <li>
                <div class="left">
                    <h4>
                        $47,830
                    </h4>
                    <p>
                        Monthly Sales
                    </p>
                </div>
                <div class="chart">
                    <span id="monthly-sales">
                        3, 9, 8, 5, 3, 5, 2, 3, 4, 7
                    </span>
                </div>
            </li>
            <li>
                <div class="left">
                    <h4>
                        $98,846
                    </h4>
                    <p>
                        Current balance
                    </p>
                </div>
                <div class="chart">
                    <span id="current-balance">
                        3, 5, 8, 5, 3, 5, 2, 9, 6, 8
                    </span>
                </div>
            </li>
            <li>
                <div class="left">
                    <h4>
                        18,846
                    </h4>
                    <p>
                        Registrations
                    </p>
                </div>
                <div class="chart">
                    <span id="registrations">
                        3, 9, -8, 5, -3, 5, -2, 9, 6, 8
                    </span>
                </div>
            </li>
            <li>
                <div class="left">
                    <h4>
                        22,571
                    </h4>
                    <p>
                        Site Visits
                    </p>
                </div>
                <div class="chart">
                    <span id="site-visits">
                        2, 5, -4, 6, -3, 5, -2, 7, 9, 5
                    </span>
                </div>
            </li>
        </ul>
    </div>-->


