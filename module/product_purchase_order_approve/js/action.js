var SaveStatus=0;
var exist_qnty=0;
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
            MenuOffOn('off','off','on','on','on','on','on','off','on','on');
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
                    //                    list();
                    //                    loader_close();
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
                    if(result=="VALIDATE"){
                        //                        $("#edit_rec").html(result);
                        list();
                        loader_close();
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.success("Data Update Successfully");
                        return false;
                    }else if(result=="NOT_VALIDATE"){
                        //                        $("#edit_rec").html(result);
                        list();
                        loader_close();
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Data Update Not Successfully");
                        return false;
                    }else if(result=="INVOICE_EXIST"){
                        //                        $("#edit_rec").html(result);
                        list();
                        loader_close();
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("This invoice exist.");
                        return false;
                    }else{
                        //                        $("#edit_rec").html(result);
                        list();
                        loader_close();
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.success("Please again approve this purchase order.");
                        return false;
                    }
                    
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
                distributor_due_balance_status();
                distributor_credit_limit();
                distributor_total_buy_amount();
            }
        });
    }
//    $("#save_btn").html('<i class="icon-white icon-hdd"></i> Approved PO');

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
        }
    });
}


function bonus_load_product_type(serial){
    $("#bonus_product_type_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php", {
        crop_id: $("#bonus_crop_id"+serial).val()
    }, function(result){
        if(result){
            $("#bonus_product_type_id"+serial).append(result);
        }
    })
}

function bonus_load_varriety_fnc(serial){
    $("#bonus_varriety_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_varriety.php",{
        crop_id:$("#bonus_crop_id"+serial).val(),
        product_type_id:$("#bonus_product_type_id"+serial).val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#bonus_varriety_id"+serial).append(result);
        }
    });
}

function bonus_load_pack_size_fnc(serial){
    $("#bonus_pack_size"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
        crop_id:$("#bonus_crop_id"+serial).val(),
        varriety_id:$("#bonus_varriety_id"+serial).val(),
        product_type_id:$("#bonus_product_type_id"+serial).val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        if (result){
            $("#bonus_pack_size"+serial).append(result);
        }
    });
}
////////// START EDIT DUPLICATE FUNCTINON ///////////

function load_product_type_(serial){
    $("#product_type_id_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php", {
        crop_id: $("#crop_id_"+serial).val()
    }, function(result){
        if(result){
            $("#product_type_id_"+serial).append(result);
        }
    })
}

function load_varriety_fnc_(serial){
    $("#varriety_id_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_varriety.php",{
        crop_id:$("#crop_id_"+serial).val(),
        product_type_id:$("#product_type_id_"+serial).val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#varriety_id_"+serial).append(result);
        }
    });
}

function load_pack_size_fnc_(serial){
    $("#pack_size_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
        crop_id:$("#crop_id_"+serial).val(),
        varriety_id:$("#varriety_id_"+serial).val(),
        product_type_id:$("#product_type_id_"+serial).val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        if (result){
            $("#pack_size_"+serial).append(result);
        }
    });
}

function load_product_price_fnc_(serial){
    $("#price_"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_product_price.php",{
        crop_id:$("#crop_id_"+serial).val(),
        varriety_id:$("#varriety_id_"+serial).val(),
        pack_size:$("#pack_size_"+serial).val(),
        product_type_id:$("#product_type_id_"+serial).val()
    }, function(result){
        if (result){
            $("#price_"+serial).val(result);
        }
    });
}

function load_product_total_price_(serial){
    var price=parseFloat($("#price_"+serial).val());
    var quantity=parseFloat($("#approved_quantity_"+serial).val());
    var crnt_stock=parseFloat($("#current_stock_"+serial).val());
    var total_price=(price*quantity);
    var current_stock=parseFloat(($("#pack_size_name_"+serial).val()*quantity)/1000);
    
    $("#total_price_"+serial).val(total_price);
    
    if(crnt_stock<current_stock){
        $("#approved_quantity_"+serial).css("background", "#FF4A4A");
        MenuOffOn('off','off','off','off','off','on','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Product stock not available");
        return false;
    }else{
        $("#approved_quantity_"+serial).css("background", "");
    }  
}

function load_current_stock_fnc(crop_id, product_type_id, varriety_id, pack_size, serial){
    $("#current_stock_"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_po_current_stock.php", {
        crop_id:crop_id,
        product_type_id:product_type_id,
        varriety_id:varriety_id,
        pack_size:pack_size,
        purchase_order_id: $("#purchase_order_id").val(),
        warehouse_id: $("#warehouse_id").val()
    }, function(result){
        if(result){
//            alert (result)
            var stock=parseFloat(result);
            var rp_qnty=parseFloat($("#quantity_"+serial).val());
            var exist_qnty=parseFloat(exist_qnty)+parseFloat(stock);
            var current_stock=parseFloat(($("#pack_size_name_"+serial).val()*rp_qnty)/1000);

            $("#current_stock_"+serial).val(stock);
            
            if(stock<current_stock){
                
                $("#quantity_"+serial).css("background", "#FF4A4A");
                
                MenuOffOn('off','off','off','off','off','on','on','off','on','on');
                reset();
                alertify.set({
                    delay: 3000
                });
                alertify.error("Product stock not available");
                return false;
                
            }else{
                
                $("#quantity_"+serial).css("background", "");
                
            }
            sum_value()
        }
    })
}


function del_product(serial,elm_id){
    reset();
    alertify.confirm("Are You Sure .... Delete this product?", function (e) {
        if (e) {
            $.post("del_product.php", {
                elm_id: elm_id
            }, function(result){
                if(result){
                    $("#tr_elm_id"+serial).remove();
                }
            })
        } else {
        //            alertify.error("You've clicked Cancel");
        }
    });
    return false;
}

function send_status(){
    reset();
    alertify.confirm("This purchase order has been approved", function (e) {
        if (e) {
            details_form()
        } else {
            list();
        }
    });
    return false;
}


function load_bonus_current_stock_fnc(serial){
    $("#bonus_stock"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_po_bonus_current_stock_in_kg.php", {
        warehouse_id:$("#warehouse_id").val(),
        purchase_order_id:$("#purchase_order_id").val(),
        crop_id:$("#bonus_crop_id"+serial).val(),
        product_type_id:$("#bonus_product_type_id"+serial).val(),
        varriety_id:$("#bonus_varriety_id"+serial).val(),
        pack_size:$("#bonus_pack_size"+serial).val()
    }, function(result){
        if(result)
        {
            var stock=parseFloat(result);
            $("#bonus_stock"+serial).val(stock);
        }
    })
}

function load_pack_size_name(serial){
    $.post("../../libraries/ajax_load_file/load_pack_size_name.php", {
        pack_size_id:$("#bonus_pack_size"+serial).val()
    }, function(result){
        if(result){
            $("#bonus_pack_size_name"+serial).val(result);
        }
    })
}

function load_bonus_product_qnantity(serial){

    var quantity=parseFloat($("#bonus_quantity"+serial).val());
    var bonus=parseFloat($("#bonus_stock"+serial).val());
    var current_stock=parseFloat(($("#bonus_pack_size_name"+serial).val()*quantity)/1000);
    if(bonus<current_stock){
        $("#bonus_quantity"+serial).css("background", "#FF4A4A");
        MenuOffOn('off','off','off','off','off','off','on','off','on','on');
    }else{
        $("#bonus_quantity"+serial).css("background", "");
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }  
}

function sum_value(){
    var sum= 0;
    var sumapp= 0;
    var sumgp= 0;
    var sumps= 0;
    var inputs= document.getElementsByName('current_stock[]');
    var inputapp= document.getElementsByName('approved_quantity[]');
    var inputgp= document.getElementsByName('total_price[]');
    var inputps= document.getElementsByName('pack_size_name[]');
    var sumdue=parseFloat($("#txt_distributor_due_balance").val());
    for (var i= inputs.length; i-->0;) {
        var v= inputs[i].value.split(',').join('.').split(' ').join('');
        sum+= +v;
        sumapp+= +inputapp[i].value;
        sumgp+= +inputgp[i].value;
        sumps+= +inputps[i].value;
        var appst=+(parseFloat(inputapp[i].value)* parseFloat(inputps[i].value))/1000;
        $("#total_current_stock").val(parseInt(sum));
        $("#total_approve_qunty").val(sumapp);
        $("#ground_total_price").val(sumgp);
    }
    if( sum<appst){
        MenuOffOn('off','off','off','off','off','on','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Product stock not available. try again");
        return false;
    }else{
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }
    if( sumgp>sumdue){
        //        MenuOffOn('off','off','off','off','off','on','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Your credit limit is over! please try again.");
        return false;
    }else{
    //        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }
}
////////// START EDIT DUPLICATE FUNCTINON ///////////


function distributor_due_balance_status(){
    $("#lbl_distributor_due_balance").html('');
    $.post("../../libraries/ajax_load_file/load_distributor_purchase_value.php", {
        distributor_id: $("#distributor_id").val()
    }, function(result){
        if(result){
            $("#lbl_distributor_due_balance").html(result);
        //            sum_value();
        //            var data = result.split(':');
        //            $("#txt_distributor_due_balance").val($.trim(data[1]));
        }
    })
}

function distributor_credit_limit(){
    $("#lbl_distributor_total_credit_limit").html('');
    $.post("../../libraries/ajax_load_file/load_distributor_total_credit_limit.php", {
        distributor_id: $("#distributor_id").val()
    }, function(result){
        if(result){
            $("#lbl_distributor_total_credit_limit").html(result);
            var data = result.split(':');
            $("#txt_distributor_due_balance").val($.trim(data[1]));
        //            sum_value();
        }
    })
}
function distributor_total_buy_amount(){
//    $("#lbl_distributor_total_buy_amount").html('');
//    $.post("../../libraries/ajax_load_file/load_distributor_total_buy_amount.php", {
//        distributor_id: $("#distributor_id").val()
//    }, function(result){
//        if(result){
//            $("#lbl_distributor_total_buy_amount").html(result);
//        //            sum_value();
//        }
//    })
}

function load_pack_size_name_(serial){
    $.post("../../libraries/ajax_load_file/load_pack_size_name.php", {
        pack_size_id:$("#bonus_pack_size_"+serial).val()
    }, function(result){
        if(result){
            $("#bonus_pack_size_name_"+serial).val(result);
        }
    })
}

function load_current_stock_bonus_fnc(crop_id, product_type_id, varriety_id, pack_size, serial, po_id){
    $("#bonus_stock_"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_po_bonus_current_stock_in_kg.php", {
        purchase_order_id:po_id,
        crop_id:crop_id,
        product_type_id:product_type_id,
        varriety_id:varriety_id,
        pack_size:pack_size,
        warehouse_id: $("#warehouse_id").val()
    }, function(result){
        if(result){
            var stock=parseFloat(result);
            $("#bonus_stock_"+serial).val(stock);
        }
    })
}

function load_bonus_product_qnantity_(serial){

    var quantity=parseFloat($("#bonus_quantity_"+serial).val());
    var bonus=parseFloat($("#bonus_stock_"+serial).val());
    var current_stock=parseFloat(($("#bonus_pack_size_name_"+serial).val()*quantity)/1000);
    if(bonus<current_stock){
        $("#bonus_quantity_"+serial).css("background", "#FF4A4A");
        MenuOffOn('off','off','off','off','off','off','on','off','on','on');
    }else{
        $("#bonus_quantity_"+serial).css("background", "");
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }  
}

function del_bonus_product(serial,elm_id){
    //    alert (serial)
    reset();
    alertify.confirm("Are You Sure .... Delete this bonus product?", function (e) {
        if (e) {
            $.post("del_bonus_product.php", {
                elm_id: elm_id
            }, function(result){
                //alert (result)
                if(result){
                    $("#tr_elm_bonus_id"+serial).remove();
                }
            })
        } else {
        //            alertify.error("You've clicked Cancel");
        }
    });
    return false;
}

function del_purchase_order(serial,elm_id){
    //    alert (serial)
    reset();
    alertify.confirm("Are You Sure .... Delete this purchase order?", function (e) {
        if (e) {
            $.post("del_purchase_order.php", {
                elm_id: elm_id
            }, function(result){
                //alert (result)
                if(result){
                    $("#tr_id"+serial).remove();
                }
            })
        } else {
        //            alertify.error("You've clicked Cancel");
        }
    });
    return false;
}