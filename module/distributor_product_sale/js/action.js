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
            MenuOffOn('on','off','off','on','on','on','on','off','on','on');
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
//                                        $("#new_rec").html(result);
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

function load_distributor_fnc(){
    $("#distributor_id").html('');
    $.post("../../libraries/ajax_load_file/load_distributor.php",{
        zone_id:$("#zone_id").val(),
        territory_id:$("#territory_id").val()
    }, function(result){
        if (result){
            $("#distributor_id").append(result);
        }
    });
}

function session_load_fnc(){
    if($("#userLevel").val()=="Zone"){
        session_load_zone();
        session_load_territory();
    }else if($("#userLevel").val()=="Territory"){
        session_load_zone();
        session_load_territory();
        session_load_distributor();
    }else if($("#userLevel").val()=="Distributor"){
        session_load_zone();
        session_load_territory();
        session_load_distributor();
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
function session_load_territory(){
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
        if (result){
            $("#territory_id").append(result); 
        }
    });
}
function session_load_distributor(){
    $("#distributor_id").html('');
    $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
        if (result){
            $("#distributor_id").append(result); 
            load_dealer_fnc();
        }
    });
}

function load_dealer_fnc(){
    $("#dealer_id").html('');
    $.post("../../libraries/ajax_load_file/load_dealer.php",{
        distributor_id: $("#distributor_id").val()
    }, function(result){
        if (result){
//                        alert (result)
            $("#dealer_id").append(result); 
        }
    });
}

function load_product_type(serial){
    $("#product_type_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php", {
        crop_id: $("#crop_id"+serial).val(),
        distributor_id:$("#distributor_id").val()
    }, function(result){
        if(result){
            $("#product_type_id"+serial).append(result);
        }
    })
}

function load_varriety_fnc(serial){
    $("#varriety_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_distributor_varriety.php",{
        crop_id:$("#crop_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val(),
        distributor_id:$("#distributor_id").val()
    }, function(result){
        //                alert (result)
        if (result){
            $("#varriety_id"+serial).append(result);
        }
    });
}

function load_pack_size_fnc(serial){
    $("#pack_size"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_distributor_pack_size.php",{
        distributor_id:$("#distributor_id").val(),
        crop_id:$("#crop_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val(),
        varriety_id:$("#varriety_id"+serial).val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#pack_size"+serial).append(result);
        }
    });
}

function load_product_price_fnc(serial){
    $("#price"+serial).val('');
    $("#purchase_price"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_product_price.php",{
        distributor_id:$("#distributor_id").val(),
        crop_id:$("#crop_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val(),
        varriety_id:$("#varriety_id"+serial).val(),
        pack_size:$("#pack_size"+serial).val()
    }, function(result){
        if (result){
            $("#price"+serial).val(result);
            $("#purchase_price"+serial).val(result);
        }
    });
}
function load_product_stock_fnc(serial){
    $("#current_quantity"+serial).val('');
    $.post("load_distributor_current_stock.php",{
        distributor_id:$("#distributor_id").val(),
        crop_id:$("#crop_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val(),
        varriety_id:$("#varriety_id"+serial).val(),
        pack_size:$("#pack_size"+serial).val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#current_quantity"+serial).val(result);
        }
    });
}

function load_product_total_price(serial){
    var price=parseInt($("#price"+serial).val());
    var quantity=parseInt($("#quantity"+serial).val());
    var current_quantity=parseInt($("#current_quantity"+serial).val());
    var total_price=(price*quantity);
    $("#total_price"+serial).val(total_price);
    if(current_quantity<quantity){
        $("#total_price"+serial).css("background", "#FF4A4A");
        $("#div_add_more").fadeOut();
        MenuOffOn('off','off','off','off','off','off','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Out of stock");
        return false;
    }else{
        $("#total_price"+serial).css("background", "");
        $("#div_add_more").fadeIn();
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }
}

////////// START EDIT DUPLICATE FUNCTINON ///////////
//
//function load_varriety_fnc_(serial){
//    $("#varriety_id_"+serial).html('');
//    $.post("../../libraries/ajax_load_file/load_varriety.php",{
//        crop_id:$("#crop_id_"+serial).val()
//    }, function(result){
//        //        alert (result)
//        if (result){
//            $("#varriety_id_"+serial).append(result);
//        }
//    });
//}
//
//function load_pack_size_fnc_(serial){
//    $("#pack_size_"+serial).html('');
//    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
//        crop_id:$("#crop_id_"+serial).val(),
//        varriety_id:$("#varriety_id_"+serial).val()
//    }, function(result){
//        if (result){
//            $("#pack_size_"+serial).append(result);
//        }
//    });
//}
//
//function load_product_price_fnc_(serial){
//    $("#price_"+serial).val('');
//    $.post("../../libraries/ajax_load_file/load_product_price.php",{
//        crop_id:$("#crop_id_"+serial).val(),
//        varriety_id:$("#varriety_id_"+serial).val(),
//        pack_size:$("#pack_size_"+serial).val()
//    }, function(result){
//        if (result){
//            $("#price_"+serial).val(result);
//        }
//    });
//}
//
//function load_product_total_price_(serial){
//    var price=parseInt($("#price_"+serial).val());
//    var quantity=parseInt($("#quantity_"+serial).val());
//    var total_price=(price*quantity);
//    $("#total_price_"+serial).val(total_price);
//}

function del_product(serial,elm_id){
    reset();
    alertify.confirm("Are You Sure .... Delete this product?", function (e) {
        if (e) {
            $.post("del_product.php", {
                elm_id: elm_id
            }, function(result){
                if(result){
                    $("#tr_elm_id"+serial).fadeOut();
                }
            })
        } else {
        //            alertify.error("You've clicked Cancel");
        }
    });
    return false;
}

////////// START EDIT DUPLICATE FUNCTINON ///////////

function load_crop_fnc(serial){
    $("#crop_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_distributor_crop.php", {
        distributor_id:$("#distributor_id").val()
    }, function(result){
        if(result){
            $("#crop_id"+serial).append(result);
        }
    })
}

//function load_distributor_current_stock(serial){
//    $("#current_quantity"+serial).val('');
//    $.post("../../libraries/ajax_load_file/load_distributor_current_stock.php",{
//        crop_id:$("#crop_id_"+serial).val(),
//        varriety_id:$("#varriety_id_"+serial).val(),
//        pack_size:$("#pack_size_"+serial).val()
//    }, function(result){
//        if (result){
//            $("#current_quantity"+serial).val(result);
//        }
//    });
//}

function sum_value(){
//    var ground_price= 0;
//    var ground_total_quantity= 0;
//    var inputs= document.getElementsByName('price[]');
//    var inputsq= document.getElementsByName('quantity[]');
//    for (var i= inputs.length; i-->0;) {
//        var ground_pricev= inputs[i].value.split(',').join('.').split(' ').join('');
//        var ground_total_quantityv= inputsq[i].value.split(',').join('.').split(' ').join('');
//        ground_price+= +ground_pricev;
//        ground_total_quantity+= +ground_total_quantityv;
//        $("#ground_price").val(ground_price);
//        $("#ground_total_quantity").val(ground_total_quantity);
//    }
}