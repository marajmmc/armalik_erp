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

function load_distributor_fnc()
{
    $("#distributor_id").html('');
    $.post("../../libraries/ajax_load_file/load_distributor.php",
    {
        zone_id:$("#zone_id").val(),
        territory_id:$("#territory_id").val(),
        zilla_id:$("#zilla_id").val()
    },
    function(result)
    {
        if (result)
        {
            $("#distributor_id").append(result);
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
    else if($("#userLevel").val()=="Division")
    {
        session_load_zone();
    }
    else
    {
        
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
            distributor_due_balance();
        }
    });
}

function load_product_type(serial)
{
    $("#product_type_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php",
    {
        crop_id: $("#crop_id"+serial).val()
    },
    function(result)
    {
        if(result)
        {
            $("#product_type_id"+serial).append(result);
        }
    })
}

function load_varriety_fnc(serial)
{
    $("#varriety_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_varriety.php",
    {
        crop_id:$("#crop_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val()
    },
    function(result)
    {
        //        alert (result)
        if (result)
        {
            $("#varriety_id"+serial).append(result);
        }
    });
}

function load_pack_size_fnc(serial){
    $("#pack_size"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
        crop_id:$("#crop_id"+serial).val(),
        varriety_id:$("#varriety_id"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        if (result){
            $("#pack_size"+serial).append(result);
        }
    });
}

function load_product_price_fnc(serial){
    $("#price"+serial).val('');
    $.post("../../libraries/ajax_load_file/load_product_price.php",{
        crop_id:$("#crop_id"+serial).val(),
        varriety_id:$("#varriety_id"+serial).val(),
        pack_size:$("#pack_size"+serial).val(),
        product_type_id:$("#product_type_id"+serial).val()
    }, function(result){
        if (result){
            $("#price"+serial).val(result);
        }
    });
}

function load_product_total_price(serial)
{
    var price=parseFloat($("#price"+serial).val());
    var quantity=parseFloat($("#quantity"+serial).val());
    var total_price=(price*quantity);
    $("#total_price"+serial).val(total_price);
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
    $.post("../../libraries/ajax_load_file/load_po_varriety.php",{
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
    var quantity=parseFloat($("#quantity_"+serial).val());
    var total_price=(price*quantity);
    $("#total_price_"+serial).val(total_price);
}

function del_product(serial,elm_id){
    //    alert (serial)
    reset();
    alertify.confirm("Are You Sure .... Delete this product?", function (e) {
        if (e) {
            $.post("del_product.php", {
                elm_id: elm_id
            }, function(result){
                //                alert (result)
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
    alertify.confirm("This purchase order has been approved. you want to view purchase order?", function (e) {
        if (e) {
            details_form()
        } else {
            list();
        }
    });
    return false;
}

function send_status_approved(){
    reset();
    alertify.confirm("This purchase order not approved! you want to edit purchase order?", function (e) {
        if (e) {
            edit_form()
        } else {
            list();
        }
    });
    return false;
}

////////// START EDIT DUPLICATE FUNCTINON ///////////

function distributor_due_balance(){
    $("#lbl_distributor_due_balance").html('');
    $.post("../../libraries/ajax_load_file/load_distributor_purchase_value.php", {
        distributor_id: $("#distributor_id").val(),
        zilla_id: $("#zilla_id").val()
    }, function(result){
        if(result){
            $("#lbl_distributor_due_balance").html(result);
            distributor_credit_limit()
        //            var data = result.split(':')
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
            var data = result.split(':');
            $("#txt_distributor_due_balance").val($.trim(data[1]));
        }
    })
}

function sum_value(){
    var sum= 0;
    var inputs= document.getElementsByName('total_price[]');
    var sumapp=parseFloat($("#txt_distributor_due_balance").val());
    for (var i= inputs.length; i-->0;) {
        var v= inputs[i].value.split(',').join('.').split(' ').join('');
        sum+= +v;
        $("#total_value").val(sum);
    }
    if( sum>sumapp){
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
function bonus_load_product_type(serial)
{
    $("#bonus_product_type_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php",
    {
        crop_id: $("#bonus_crop_id"+serial).val()
    },
    function(result)
    {
        if(result)
        {
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

function bonus_load_product_type_(serial){
    $("#bonus_product_type_id_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_product_type.php", {
        crop_id: $("#bonus_crop_id_"+serial).val()
    }, function(result){
        if(result){
            $("#bonus_product_type_id_"+serial).append(result);
        }
    })
}

function bonus_load_varriety_fnc_(serial){
    $("#bonus_varriety_id_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_po_varriety.php",{
        crop_id:$("#bonus_crop_id_"+serial).val(),
        product_type_id:$("#bonus_product_type_id_"+serial).val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#bonus_varriety_id_"+serial).append(result);
        }
    });
}

function bonus_load_pack_size_fnc_(serial){
    $("#bonus_pack_size_"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_pack_size_fnc.php",{
        crop_id:$("#bonus_crop_id_"+serial).val(),
        varriety_id:$("#bonus_varriety_id_"+serial).val(),
        product_type_id:$("#bonus_product_type_id_"+serial).val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        if (result){
            $("#bonus_pack_size_"+serial).append(result);
        }
    });
}
function po_send(serial,elm_id){
//        alert (elm_id)
    reset();
    alertify.confirm("Are You Sure .... Send This PO For Approved?", function (e) {
        if (e) {
            $.post("po_send.php", {
                elm_id: elm_id
            }, function(result){
                //alert (result)
                var data = result.trim();
                if(data=="Success")
                {
                    $("#tr_id"+serial).remove();
                }
                else
                {
                    alertify.error("PO Successfully Not Send! Please Try Again.");
                }
            })
        } else {
        //            alertify.error("You've clicked Cancel");
        }
    });
    return false;
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

function load_crop_warehouse(serial)
{
    $("#crop_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_crop_warehouse.php",
    {
        warehouse_id:$("#warehouse_id").val(),
        year_id:$("#year_id").val()
    },
    function(result)
    {
        if (result)
        {
            $("#crop_id"+serial).append(result);
        }
    });
}
function load_crop_warehouse_bonus(serial)
{
    $("#bonus_crop_id"+serial).html('');
    $.post("../../libraries/ajax_load_file/load_crop_warehouse.php",
    {
        warehouse_id:$("#warehouse_id").val(),
        year_id:$("#year_id").val()
    },
    function(result)
    {
        if (result)
        {
            $("#bonus_crop_id"+serial).append(result);
        }
    });
}
function load_district_fnc()
{
    $("#zilla_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",{zone_id: $('#zone_id').val(), territory_id: $('#territory_id').val()},function(result)
    {
        if (result)
        {
            $("#zilla_id").append(result);
        }
    });
}
function load_bonus()
{
    $("#div_bonus").html("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
    $("#div_bonus").html('');
    $.post("load_bonus.php",{row_bonus_id:$("#rowID").val()},function(result)
    {
        if (result)
        {
            $('#div_loader').remove();
            $("#div_bonus").append(result);
        }
    });
}