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
            MenuOffOn('on','off','on','on','on','on','on','off','on','on');
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
                    //$("#edit_rec").html(result);
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


function Existin_data(elm){
    $("#loader").remove();
    $.post("exist_data.php",{name:elm.value}, function(result){
        if (result){
            if (result=="Found"){
//                ME_Alert('Alert', 'Exiting Your Data');
                notification_msg('Exiting Your Data');
                $(elm).after('<img id="loader" src="../../img/icons/25x25/loader.gif" />');
                MenuOffOn('off','off','off','off','off','off','on','on','on','on');
            }
            else if (result=="Not Found"){
                $("#loader").remove();
                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
            }
        }
    });
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
    cell1.innerHTML = "<input type='text' name='territory_name[]' maxlength='50' id='territory_name"+ExId+"' class='span12'/>\n\
    <input type='hidden' id='territory_id[]' name='territory_id[]' value=''/>\n\
    <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='"+ExId+"'/>";
    cell1 = row.insertCell(1);
    cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
                                        <i class='icon-white icon-trash'> </i>";
    cell1.style.cursor="default";
    document.getElementById("territory_name"+ExId).focus();
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