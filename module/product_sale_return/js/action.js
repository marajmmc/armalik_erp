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
            MenuOffOn('off','off','on','off','on','on','on','off','on','on');
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
                    //$("#edit_rec").html(result);
                    list();
                    loader_close();
                    reset();
                    alertify.set({
                        delay: 3000
                    });
                    alertify.success(result);
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
        product_type_id:$("#product_type_id_"+serial).val()
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
    var quantity=parseFloat($("#quantity_"+serial).val());
    var return_quantity=parseFloat($("#return_quantity_"+serial).val());
    var total_price=(price*return_quantity);
    $("#total_price_"+serial).val(total_price);
    
    if(quantity<return_quantity){
        $("#return_quantity_"+serial).val('0');
        $("#return_quantity_"+serial).focus();
        MenuOffOn('off','off','off','off','off','on','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Sale product stock not available");
        return false;
        
    }else{
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
        var sum= 0;
        var inputs= document.getElementsByName('return_quantity[]');
        for (var i= inputs.length; i-->0;) {
            var v= inputs[i].value.split(',').join('.').split(' ').join('');
            sum+= +v;
            $("#total_return_quantity").val(sum);
        }
    }
}

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

function load_current_stock_fnc(crop_id, product_type_id, varriety_id, pack_size, serial){
    $("#current_stock_"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_po_wh_current_stock.php", {
        crop_id:crop_id,
        product_type_id:product_type_id,
        varriety_id:varriety_id,
        pack_size:pack_size
    }, function(result){
        if(result){
            //            alert (result)
            var stock=parseInt(result);
            var rp_qnty=parseInt($("#quantity_"+serial).val());
            var exist_qnty=parseInt(exist_qnty)+parseInt(stock);
            
            $("#current_stock_"+serial).val(stock);
            if(stock<rp_qnty){
                $("#quantity_"+serial).css("background", "#FF4A4A");
            }else{
                $("#quantity_"+serial).css("background", "");
            }
            sum_value()
        }
    })
}

function sum_value(){
    var sum= 0;
    var sumapp= 0;
    var sumgp= 0;
    var inputs= document.getElementsByName('current_stock[]');
    var inputapp= document.getElementsByName('quantity[]');
    var inputgp= document.getElementsByName('total_price[]');
    for (var i= inputs.length; i-->0;) {
        var v= inputs[i].value.split(',').join('.').split(' ').join('');
        sum+= +v;
        sumapp+= +inputapp[i].value;
        sumgp+= +inputgp[i].value;
        $("#total_current_stock").val(sum);
        $("#tquantity").val(sumapp);
        $("#ground_total_price").val(sumgp);
    }
    if( sum<sumapp){
        MenuOffOn('off','off','off','off','off','on','on','off','on','on');
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Product stock not available");
        return false;
    }else{
        MenuOffOn('off','on','off','off','on','on','on','on','on','on');
    }
    var distbalance=parseInt($("#distributor_balance").val());
    if(distbalance<sumgp){
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Distributor credit limit is over!");
        return false;
    }
}
////////// START EDIT DUPLICATE FUNCTINON ///////////