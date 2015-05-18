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
            document.getElementById('frm_area').action = 'save.php';
        }else{
            document.getElementById('frm_area').action = 'update.php';
        }
        $('#frm_area').submit();
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.success("Data Save Successfully");
        return false;
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

function load_user_type(){
    if($("#user_level").val()=="Zone"){
        $("#div_zone_id").slideDown();
        $("#div_warehouse_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_division").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Territory"){
        $("#div_zone_id").slideDown();
        $("#div_territory_id").slideDown();
        $("#div_warehouse_id").slideUp();
        $("#div_division").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Warehouse"){
        $("#div_warehouse_id").slideDown();
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_division").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Division"){
        $("#div_warehouse_id").slideUp();
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_division").slideDown();
        $("#div_division_id").slideDown();
    }else{
        $("#div_warehouse_id").slideUp();
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_division").slideUp();
        $("#div_division_id").slideUp();
    }
}

function zone_access_fnc(){
    $("#div_division").html('');
    $.post("load_zone_list.php",function(result){
        if(result){
            $("#div_division").html(result);
        }
    })
}

function load_territory_fnc(){
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory.php",{
        zone_id:$("#zone_id").val()
    }, function(result){
        if (result){
            $("#territory_id").append(result);
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
    cell1.innerHTML = "<input type='text' name='degree_name[]'  id='degree_name"+ExId+"' class='span12' />\n\
    <input type='hidden' id='row_id[]' name='row_id[]' value='' class='span12' placeholder='Degree Name'/>\n\
    <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='"+ExId+"'/>";
    cell1 = row.insertCell(1);
    cell1.innerHTML = "<input type='text' class='span12' name='degree_obtain[]' id='degree_obtain"+ExId+"' placeholder='Degree Obtain' />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(2);
    cell1.innerHTML = "<input type='text' class='span12' name='passing_year[]' id='passing_year"+ExId+"' placeholder='Passing Year' />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(3);
    cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
                                        <i class='icon-white icon-trash'> </i>";
    cell1.style.cursor="default";
    document.getElementById("degree_name"+ExId).focus();
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