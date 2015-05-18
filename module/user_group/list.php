<?php
require_once("../../libraries/lib/database.inc.php");
require_once("../../libraries/lib/config.inc.php");
require_once("../../libraries/lib/functions.inc.php");
$db = new Database();
$db2 = new Database();
$tbl = _DB_PREFIX;
?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <a id="dynamicTable"><?php echo $db->Get_Auto_TaskName()?></a>
                    <span class="mini-title">
                        
                    </span>
                </div>
                <span class="tools">
                    <a class="btn btn-small" data-original-title="">
                        <i class="icon-list-alt" data-original-title="Share"> </i>
                    </a>
                </span>

            </div>
            <div class="widget-body">
                <div id="dt_example" class="example_alt_pagination">
                    <table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">

                        <thead>
                            <tr>
                                <th style="width:5%">
                                    Sl No
                                </th>
                                <th style="width:20%">
                                    User Group Name
                                </th>
                                <th style="width:10%">
                                    Modules
                                </th>
                                <th style="width:16%">
                                    Task
                                </th>
                                <th style="width:5%" class="hidden-phone">
                                    User
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT
                                ug_id,
                                ug_name,
                                count(distinct up_sm_id) as up_sm_id,
                                count(distinct up_st_id) as up_st_id    
                                FROM $tbl" . "user_group
                                GROUP BY ug_name, ug_id";

                            if ($db->open()) {
                                $result = $db->query($sql);
                                $i = 1;
                                while ($result_array = $db->fetchAssoc()) {
                                    if ($i % 2 == 0) {
                                        $rowcolor = "gradeC";
                                    } else {
                                        $rowcolor = "gradeA success";
                                    }
                                    ?>
                                    <tr class="<?php echo $rowcolor ?> pointer" id="tr_id<?php echo $i; ?>" onclick="get_rowID('<?php echo $result_array["ug_id"] ?>', '<?php echo $i; ?>')" ondblclick="details_form();">
                                        <td>
                                            <?php echo $i; ?>
                                        </td>
                                        <td><?php echo $result_array['ug_name']; ?></td>
                                        <td><?php echo $result_array['up_sm_id']; ?></td>
                                        <td><?php echo $result_array['up_st_id']; ?></td>
                                        <td class="hidden-phone">
                                            <span class="tools">
                                                <a href="#myModal" role="button" class="btn btn-small" data-toggle="modal" onclick="fnc_pop_usergroup('<?php echo $result_array["ug_id"] ?>','<?php echo $result_array["ug_name"] ?>')">
                                                    <i class="icon-user" data-original-title="Share"> </i>
                                                </a>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php
                                    ++$i;
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="clearfix">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            ×
        </button>
        <h3 id="myModalLabel">
            Task List
        </h3>
    </div>
    <div class="modal-body">
        <p id="div_userlist">
            One fine body…
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">
            Close
        </button>
        <!--                                <button class="btn btn-primary" >
                                            Save changes
                                        </button>-->
    </div>
</div>
<script type="text/javascript">
    //Data Tables
    $(document).ready(function () {
        $('#data-table').dataTable({
            "sPaginationType": "full_numbers"
        });
    });

    jQuery('.delete-row').click(function () {
        var conf = confirm('Continue delete?');
        if (conf) jQuery(this).parents('tr').fadeOut(function () {
            jQuery(this).remove();
        });
        return false;
    });
    
    function fnc_pop_usergroup(groupid,groupname){
        $("#myModalLabel").html('');
        $("#div_userlist").html('allah is one');
        $.post("load_userlist.php",{groupid:groupid},function(result){
            if(result){
                $("#myModalLabel").html(groupname+" Group");
                $("#div_userlist").html(result);
            }
        });
        
    }
    function fnc_pop_close(){
        $(".userbox").fadeOut();
        //        list();
    }
    
    function fnc_delete_usergroup(userid, serial){
        var answer = confirm ("Are you sure remove user in user group?")
        if (answer){
            $.post("load_delete_userlist.php",{id:userid},function(result){
                if(result){
                    $("#tr_id"+serial).hide();
                    //                    list(); 
                }
            });
        }else{
            
        }
     
    }
</script>

