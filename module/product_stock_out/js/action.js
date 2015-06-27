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
            MenuOffOn('on','off','off','off','on','on','on','off','on','on');
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
                if (result)
                {
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
    //        hide_div();
    //        $("#details_rec").show();
    //        loader_start();
    //        $.post("details_frm.php",$("#frm_area").serialize(), function(result){
    //            if (result){
    //                SaveStatus=2;
    //                $("#details_rec").html(result);
    //                $(".mini-title").html('View Detials From');
    //                loader_close();
    //                MenuOffOn('off','off','off','off','on','on','on','on','on','on');
    //            }
    //        });
    }
}


function Existin_data(elm){
    $("#loader").remove();
    $.post("exist_data.php",{
        name:elm.value
    }, function(result){
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

function load_product_type(){
    $("#product_type_id").html('');
    $.post("../../libraries/ajax_load_file/load_product_type.php", {
        crop_id: $("#crop_id").val()
    }, function(result){
        if(result){
            $("#product_type_id").append(result);
        }
    })
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

function load_pack_size_fnc(){
    $("#pack_size").html('');
    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
        crop_id:$("#crop_id").val(),
        product_type_id:$("#product_type_id").val(),
        varriety_id:$("#varriety_id").val()
    }, function(result){
        if (result){
            $("#pack_size").append(result);
        }
    });
}

function load_current_stock_fnc(){
    $("#current_stock_qunatity").val('');
    $("#current_stock_qunatity_tmp").val('');
    $.post("../../libraries/ajax_load_file/load_product_current_stock.php",{
        crop_id:$("#crop_id").val(),
        product_type_id:$("#product_type_id").val(),
        varriety_id:$("#varriety_id").val(),
        pack_size:$("#pack_size").val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#current_stock_qunatity").val(result);
            $("#current_stock_qunatity_tmp").val(result);
        }
    });
}

function calc_between_val_fnc(){
    var current_stock= parseInt($("#current_stock_qunatity_tmp").val());
    var exist_stock= parseInt($("#damage_quantity").val());
    if(current_stock<exist_stock){
        MenuOffOn('off','off','off','off','off','off','on','on','on','on');
        alertify.set({
            delay: 3000
        });
        alertify.error("Please not: damage quantity can't be graterthan current stock");
        return false;
    }else{
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }
}

function quantity_type_fnc(){
    if($("#quantity_type").val()=="Short Qty"){
        $("#div_damage_quantity").slideDown();
        $("#div_access_quantity").slideUp();
        $("#damage_quantity").val('0');
        $("#access_quantity").val('0');
    }else if($("#quantity_type").val()=="Access Qty"){
        $("#div_damage_quantity").slideUp();
        $("#div_access_quantity").slideDown();
        $("#damage_quantity").val('0');
        $("#access_quantity").val('0');
    }else{
        $("#div_damage_quantity").slideUp();
        $("#div_access_quantity").slideUp();
        $("#damage_quantity").val('0');
        $("#access_quantity").val('0');
    }
}
function inventory_purpose(){
    $("#distributor_id").html('');
    if($("#purpose").val()=="Sample Purpose"){
        $.post("../../libraries/ajax_load_file/session_load_distributor.php",{
            division_id: $("#division_id").val()
        }, function(result){
            if (result){
                $("#div_distributor_id").slideDown();
                $("#distributor_id").append(result); 
                $("#distributor_id").append("<option value='others'>Others</option>");
            }
        });
        
    }else{
//        $("#distributor_id").attr('validate','');
        $("#div_distributor_id").slideUp();
    }
}