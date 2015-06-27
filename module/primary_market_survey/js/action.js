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
                    //$("#new_rec").html(result);

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
        }
        else if(SaveStatus==2)
        {
            $.post("update.php",$("#frm_area").serialize(), function(result)
            {
                if (result)
                {
                    //$("#edit_rec").html(result);
                    //list();

                    $("#crop_master_id").val("");
                    $("#product_master_type_id").val("");
                    $("#product_list").html("");

                    loader_close();
                    $("#save_btn").show();
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

function Load_form()
{
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
function edit_form()
{
    if($("#rowID").val()=="")
    {
        alertify.set({
            delay: 3000
        });
        alertify.error("Please Select Any Row In The Table");
        return false;
    }
    else
    {
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

function session_load_fnc()
{
    if($("#userLevel").val()=="Zone")
    {
        session_load_zone();
        session_load_territory();
        //load_district();
    }
    else
    {

    }
}
function session_load_zone()
{
    $("#zone_id").html('');
    $.post("../../libraries/ajax_load_file/session_load_zone.php",function(result){
        if (result){
            $("#zone_id").append(result);
        }
    });
}
function session_load_territory(){
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
        if (result){
            $("#territory_id").append(result);
        }
    });
}
function load_district()
{
    $("#district_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",{zone_id: $('#zone_id').val(), territory_id: $('#territory_id').val()},function(result){
        if (result){
            $("#district_id").append(result);
        }
    });
}

function load_upazilla_fnc()
{
    $("#upazilla_id").html('');
    $.post("../../libraries/ajax_load_file/load_upazilla_info.php",{
        district_id: $("#district_id").val()
    }, function(result){
        if (result){
            $("#upazilla_id").append(result);
        }
    });
}

function load_crop(serial)
{
    $("#crop_id"+serial).empty();
    $("#crop_id"+serial).append('<option value="">Select</option>');

    $("#product_type_id"+serial).empty();
    $("#product_type_id"+serial).append('<option value="">Select</option>');
    $("#varriety_id"+serial).empty();
    $("#varriety_id"+serial).append('<option value="">Select</option>');
    $.post("load_pdo_crop_list.php",{product_type:$("#type"+serial).val()}, function(result)
    {
        if(result)
        {
            $("#crop_id"+serial).append(result);
        }
    })
}

function load_product_type(serial)
{
    $("#product_type_id"+serial).empty();
    $("#product_type_id"+serial).append('<option value="">Select</option>');
    $("#varriety_id"+serial).empty();
    $("#varriety_id"+serial).append('<option value="">Select</option>');

    $.post("load_pdo_product_type_list.php",{product_type:$("#type"+serial).val(), crop_id:$("#crop_id"+serial).val()}, function(result)
    {
        if(result)
        {
            $("#product_type_id"+serial).append(result);
        }
    })
}

function load_pdo_variety(serial)
{
    $("#varriety_id"+serial).html('');
    $("#varriety_id"+serial).append('<option value="">Select</option>');
    $.post("load_pdo_variety_list.php",{product_type:$("#type"+serial).val(), crop_id:$("#crop_id"+serial).val(), product_type_id:$("#product_type_id"+serial).val()}, function(result)
    {
        if(result)
        {
            $("#varriety_id"+serial).append(result);
        }
    })
}
function del_row_item(serial)
{
    reset();
    alertify.confirm("Are you sure, delete this item?", function (e) {
        if (e)
        {
            $.post("del_row_item.php",{id:$("#id_"+serial).val()}, function(result)
            {
                if(result)
                {
                    $("#tr_id_"+serial).remove();
                }
            })
        }
        else
        {

        }
    });
    return false;
}

function load_territory_fnc()
{
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory.php",{
        zone_id:$("#zone_id").val()
    }, function(result){
        if (result){
            $("#territory_id").append(result);
        }
    });
}

function check_exist_data()
{
    if($("#upazilla_id").val()=="")
    {
        $("#product_list").hide();
        MenuOffOn('off','off','off','off','on','on','on','off','on','on');
    }
    else
    {
        $.post("check_exist_data.php",$("#frm_area").serialize(), function(result)
        {
            if (result)
            {
                $("#product_list").hide();
                MenuOffOn('off','off','off','off','on','on','on','on','on','on');
                reset();
                alertify.set({
                    delay: 3000
                });
                alertify.error("Data Exist! Try Again.");
                return false;
            }
            else
            {
                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
            }
        });
    }
}

function load_product_list_add()
{
    $("#product_list").show();
    $("#product_list").html('');
    $.post("load_product_list_add.php",$("#frm_area").serialize(), function(result)
    {
        if (result)
        {
            $("#product_list").html(result);
        }
    });
}

function load_product_list_edit()
{
    $("#product_list").show();
    $("#product_list").html('');
    $.post("load_product_list_edit.php",$("#frm_area").serialize(), function(result)
    {
        if (result)
        {
            $("#product_list").html(result);
        }
    });
}

function load_product_type()
{

    $("#product_master_type_id").html('');
    $.post("../../libraries/ajax_load_file/load_product_type.php", {
        crop_id: $("#crop_master_id").val()
    }, function(result){
        if(result){
            $("#product_master_type_id").append(result);
        }
    })
}