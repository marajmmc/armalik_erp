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

function load_varriety_fnc(){
    $("#varriety_id").html('');
    $.post("../../libraries/ajax_load_file/load_varriety.php",{
        crop_id:$("#crop_id").val(),
        product_type_id:$("#product_type_id").val()
    }, function(result){
        if (result){
            $("#varriety_id").append(result);
        }
    });
}


function load_product_type()
{

    $("#product_type_id").html('');
    $.post("../../libraries/ajax_load_file/load_product_type.php", {
        crop_id: $("#crop_id").val()
    }, function(result){
        if(result){
            $("#product_type_id").append(result);
        }
    })
}
function load_variety_txt_fnc()
{
    //alert ($('[name="product_category"]:checked').val())
    $("#variety_name_txt").html('');
    $.post("load_variety_txt.php",
        {
            zone_id: $("#zone_id").val(),
            crop_id: $("#crop_id").val(),
            product_type_id: $("#product_type_id").val()
        }, function(result){
        if(result)
        {
            $("#variety_name_txt").append(result);
        }
    })
}
function load_pdo_variety()
{
    $("#variety_id").html('');
    $.post("../../libraries/ajax_load_file/load_varriety.php", {
        crop_id: $("#crop_id").val(),
        product_type_id: $("#product_type_id").val()
    }, function(result){
        if(result){
            $("#variety_id").append(result);
        }
    })
}

function load_company_name()
{
    $("#hybrid").val('');
    $("#company_name").val('');
    $("#cultivation_period_start").val('');
    $("#cultivation_period_end").val('');
    $.post("load_company_name.php", {
        zone_id: $("#zone_id").val(),
        product_category: $('[name="product_category"]:checked').val(),
        crop_id: $("#crop_id").val(),
        product_type_id: $("#product_type_id").val(),
        variety_id: $("#variety_id").val(),
        variety_name_txt: $("#variety_name_txt").val()
    }, function(result){
        if(result)
        {
//            alert (result)
            var data= $.trim(result);
            var record=data.split('~');
            $("#company_name").val(record[0]);
            $("#cultivation_period_start").val(record[1]);
            $("#cultivation_period_end").val(record[2]);
            $("#special_characteristics").val(record[3]);
            $("#hybrid").val(record[4]);
            if(record[5]=="")
            {
                $("#image_url").attr('src','../../system_images/blank_img.png');
            }
            else
            {
                $("#image_url").attr('src','../../system_images/pdo_upload_image/pdo_product_characteristic/'+record[5]);
            }

        }
    })
}

function load_upazilla_fnc()
{
    $("#upazilla_id").html('');
    $.post("../../libraries/ajax_load_file/load_upazilla_info.php",{
        division_id: $("#division_id").val(),
        district_id: $("#district_id").val()
    }, function(result){
        if (result){
            $("#upazilla_id").append(result);
        }
    });
}


function session_load_fnc(){
    if($("#userLevel").val()=="Zone"){
        session_load_zone();
        //session_load_territory();
        //}else if($("#userLevel").val()=="Territory"){
        //    session_load_zone();
        //    session_load_territory();
        //    session_load_distributor();
        //}else if($("#userLevel").val()=="Distributor"){
        //    session_load_zone();
        //    session_load_territory();
        //    session_load_distributor();
    }else{

    }
}
function session_load_zone(){
    $("#zone_id").html('');
    $.post("../../libraries/ajax_load_file/session_load_zone.php",function(result){
        if (result){
            $("#zone_id").append(result);
        }
    });
}
//function session_load_territory(){
//    $("#territory_id").html('');
//    $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
//        if (result){
//            $("#territory_id").append(result);
//        }
//    });
//}
//function session_load_distributor(){
//    $("#distributor_id").html('');
//    $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
//        if (result){
//            $("#distributor_id").append(result);
//            distributor_due_balance();
//        }
//    });
//}

function load_district()
{
    document.getElementById("crop_id").selectedIndex = "0";
    document.getElementById("product_type_id").selectedIndex = "0";
    document.getElementById("variety_id").selectedIndex = "0";
    document.getElementById("variety_name_txt").selectedIndex = "0";
    $("#district_id").html('');
    $.post("../../libraries/ajax_load_file/load_zone_assign_district.php",{zone_id: $('#zone_id').val()},function(result){
        if (result){
            $("#district_id").append(result);
        }
    });
}


