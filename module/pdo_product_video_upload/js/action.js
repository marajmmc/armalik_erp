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

function load_product_type()
{
    $("#div_variety").html('');
    $("#product_type_id").html('');
    $.post("../../libraries/ajax_load_file/load_product_type.php", {
        crop_id: $("#crop_id").val()
    }, function(result){
        if(result){
            $("#product_type_id").append(result);
        }
    })
}
function load_variety(status)
{
    if($("#product_type_id").val()=="")
    {
        $("#div_variety").html('');
    }
    else
    {
        $("#div_variety").html('');
        $.post("load_variety.php", {
            crop_id: $("#crop_id").val(),
            product_type_id: $("#product_type_id").val()
        }, function(result){
            if(result)
            {
                $("#div_variety").append(result);
            }
        })
    }
}

function load_variety_view()
{
    $("#div_show_variety").html('');
    $(".icon-print").append("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
    $(".icon-print").attr('disable', 'disable');
    $.post("load_varriety.php", {
        crop_id: $("#crop_id").val(),
        product_type_id: $("#product_type_id").val(),
        zone_id: $("#zone_id").val(),
        pdo_year_id: $("#pdo_year_id").val(),
        pdo_season_id: $("#pdo_season_id").val()
    }, function(result){
        if(result)
        {
            $('#div_loader').remove();
            $(".icon-print").attr('disable', '');
            $("#div_show_variety").html(result);
        }
    })
}

