var SaveStatus=0;

//////////////////  action start  ///////////////
function list(){
    //    alert ('allah is one')
    hide_div();
    $("#list_rec").show();
    loader_start();
    $.post("list.php", function(result){
        //        alert (result)
        if (result){
            SaveStatus=0;
            $("#list_rec").html(result);
            $(".mini-title").html('List');
            loader_close();
            MenuOffOn('off','off','on','on','on','on','on','off','on','on');
        }
    });
}
function Save_Rec()
{
    validateResult=true;
    formValidate();
    if(validateResult){
        if (SaveStatus==1){
            $.post("save.php",$("#frm_area").serialize(), function(result){
                if (result){
                    //                    $("#new_rec").html(result);
                    list();
                    loader_close();
                    reset();
                    alertify.set({
                        delay: 3000
                    });
                    alertify.success("Data Save Successfully");
                    return false;
                }
            });
        }else if(SaveStatus==2){
            $.post("update.php",$("#frm_area").serialize(), function(result){
            
                if (result){
                    //                    $("#edit_rec").html(result);
                    list();
                    loader_close();
                    reset();
                    alertify.set({
                        delay: 3000
                    });
                    alertify.success("Data Update Successfully");
                    return false;
                }
            }); 
        }
    }
}

function Load_form(){
    hide_div();
    $("#new_rec").show();
    loader_start();
    $.post("add_frm.php", function(result){
        if (result){
            SaveStatus=1;
            $("#new_rec").html(result);
            $(".mini-title").html('Add From');
            loader_close();
            MenuOffOn('off','on','off','off','on','on','on','on','on','on');
        }
    });
    
}
function edit_form(){
    if($("#rowID").val()==""){
        alertify.set({
            delay: 3000
        });
        alertify.error("Please Select Any Row In The Table");
        return false;
    }else{
        hide_div();
        $("#edit_rec").show();
        loader_start();
        $.post("edit_frm.php",$("#frm_area").serialize(), function(result){
            if (result){
                SaveStatus=2;
                $("#edit_rec").html(result);
                $(".mini-title").html('Edit From');
                loader_close();
                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
            }
        });
    }
}
function details_form(){
    if($("#rowID").val()==""){
        alertify.set({
            delay: 3000
        });
        alertify.error("Please Select Any Row In The Table");
        return false;
    }else{
        hide_div();
        $("#details_rec").show();
        loader_start();
        $.post("details_frm.php",$("#frm_area").serialize(), function(result){
            if (result){
                SaveStatus=2;
                $("#details_rec").html(result);
                $(".mini-title").html('View Detials From');
                loader_close();
                MenuOffOn('off','off','off','off','on','on','on','on','on','on');
            }
        });
    }
}

function status_alert(){
    reset();
    alertify.confirm("Your task is completed! you want to view task?", function (e) {
        if (e) {
            details_form()
        } else {
            list();
        }
    });
    return false;
}

//////////////////////////////////////// Table Row add delete function ///////////////////////////////
var ExId=0;
function RowIncrement() 
{
    var table = document.getElementById('TaskTable');

    var rowCount = table.rows.length;
    //alert(rowCount);
    var row = table.insertRow(rowCount);
    row.id="T"+ExId;
    row.className="tableHover";
    //alert(row.id);
    var cell1 = row.insertCell(0);
    cell1.innerHTML = "<select id='task_status"+ExId+"' name='task_status[]' class='span12' placeholder='Task Status' validate='Require'>\n\
    <option value=''>Select</option>\n\
    <option value='In-progress'>In-progress</option>\n\
    <option value='Pending'>Pending</option>\n\
    </select><input type='hidden' id='elm_id[]' name='elm_id[]' value=''/>";
    cell1 = row.insertCell(1);
    cell1.innerHTML = "<input type='text' name='comment[]' id='comment"+ExId+"' class='span12' placeholder='Comment' validate='Require' />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(2);
    cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
                                        <i class='icon-white icon-trash'> </i>";
    cell1.style.cursor="default";
    document.getElementById("task_status"+ExId).focus();
    ExId=ExId+1;
    $("#TaskTable").tableDnD();
}

function RowDecrement(tableID,id) 
{
    try {
        var table = document.getElementById(tableID);
        for(var i=1;i<table.rows.length;i++)
        {
            if(table.rows[i].id==id)
            {
                table.deleteRow(i);
            //                showAlert('SA-00106');
            }
        }
    }
    catch(e) {
        alert(e);
    }
}

//////////////////////////////////////// Table Row add delete function ///////////////////////////////