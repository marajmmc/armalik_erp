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
            if($("#userLevel").val()=="Division")
            {
                MenuOffOn('off','off','on','on','on','on','on','off','on','on');
            }
            else
            {
                MenuOffOn('on','off','on','on','on','on','on','off','on','on');
            }
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
                    $("#new_rec").html(result);
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

function load_territory_by_zone()
{
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory.php",
    {
        zone_id : $("#zone_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#territory_id").append(result);
        }
    });
}

function load_district_by_territory()
{
    $("#district_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",
    {
        zone_id : $("#zone_id").val(), territory_id: $("#territory_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#district_id").append(result);
        }
    });
}

function load_upazilla_by_district()
{
    $("#upazilla_id").html('');
    $.post("../../libraries/ajax_load_file/load_upazilla.php",
    {
        zilla_id: $("#district_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#upazilla_id").append(result);
        }
    });
}

function load_type_by_crop()
{
    $("#type_id").html('');
    $.post("../../libraries/ajax_load_file/load_product_type.php",
    {
        crop_id: $("#crop_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#type_id").append(result);
        }
    });
}

function load_variety_by_crop_type()
{
    $("#variety_id").html('');
    $.post("../../libraries/ajax_load_file/load_varriety.php",
    {
        crop_id:$("#crop_id").val(), product_type_id: $("#type_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#variety_id").append(result);
        }
    });
}