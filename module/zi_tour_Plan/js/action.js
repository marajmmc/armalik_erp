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
function details_form()
{
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
    function session_load_zone(){
        $("#zone_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_zone.php",function(result){
            if (result){
                $("#zone_id").append(result);
            }
        });
    }
    function session_load_fnc()
    {
        if($("#userLevel").val()=="Zone")
        {
            session_load_zone();
            session_load_territory();
        }
        else if($("#userLevel").val()=="Territory")
        {
            session_load_zone();
            session_load_territory();
            session_load_distributor();
        }
        else if($("#userLevel").val()=="Distributor")
        {
            session_load_zone();
            session_load_territory();
            session_load_distributor();
        }
        else
        {

        }
    }

    function session_load_territory()
    {
        $(".territory_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
            if (result){
                $(".territory_id").append(result);
            }
        });
    }
    function session_load_distributor()
    {
        $("#distributor_id").html('');
        $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
        if (result)
            {
                $("#distributor_id").append(result);
                distributor_due_balance();
            }
        });
    }





