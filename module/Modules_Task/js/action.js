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
    cell1.innerHTML = "<input type='text' name='textTsName[]' maxlength='50' id='textTsName"+ExId+"' class='span12'/>\n\
    <input type='hidden' id='st_id[]' name='st_id[]' value=''/>\n\
    <input type='hidden' id='elmIndex[]' name='elmIndex[]' value='"+ExId+"'/>";
    cell1 = row.insertCell(1);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskAdd[]' id='chkTaskAdd"+ExId+"' value='add' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(2);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskSave[]' id='chkTaskEdit"+ExId+"' value='save' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(3);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskEdit[]' id='chkTaskEdit"+ExId+"' value='edit' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(4);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskView[]' id='chkTaskView"+ExId+"' value='details' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(5);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskDelete[]' id='chkTaskDelete"+ExId+"' value='delete' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(6);
    cell1.innerHTML = "<input type='checkbox' class='' name='chkTaskReport[]' id='chkTaskReport"+ExId+"' value='report' checked />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(7);
    cell1.innerHTML = "<input type='file' name='st_icon[]' maxlength='250' id='st_icon"+ExId+"'  validate='Require' class='span12' />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(8);
    cell1.innerHTML = "<input type='text' name='textPrma[]' maxlength='250' id='textPrma"+ExId+"'  validate='Require' class='span12' />";
    cell1.style.cursor="default";
    cell1 = row.insertCell(9);
    cell1.innerHTML = "<a class='btn btn-warning2' data-original-title='' onclick=\"RowDecrement('TaskTable','T"+ExId+"')\">\n\
                                        <i class='icon-white icon-trash'> </i>";
    cell1.style.cursor="default";
    document.getElementById("textTsName"+ExId).focus();
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