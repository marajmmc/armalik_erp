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
            MenuOffOn('off','off','off','off','on','on','on','off','on','on');
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
//    if($("#rowID").val()==""){
//        alertify.set({
//            delay: 3000
//        });
//        alertify.error("Please Select Any Row In The Table");
//        return false;
//    }else{
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
//    }
}


function Existin_data(elm){
    $("#loader").remove();
    $.post("exist_data.php",{
        warehouse_id:$("#warehouse_id").val(),
        crop_id:$("#crop_id").val(),
        product_type_id:$("#product_type_id").val(),
        varriety_id:$("#varriety_id").val(),
        pack_size: elm.value
    }, function(result){
        if (result){
            if (result=="Found"){
                $(elm).after('<img id="loader" src="../../system_images/loading-orange.gif" />');
                MenuOffOn('off','off','off','off','off','off','on','on','on','on');
                reset();
                alertify.set({
                    delay: 3000
                });
                alertify.error("All ready stock in this product! you can try another product");
                return false;
            }
            else if (result=="Not Found"){
                $("#loader").remove();
                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
            }
        }
    });
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

//function delete_product(row_id, serial)
//{
//    $.post("../../libraries/ajax_load_file/load_product_current_stock.php",
//    {
//        crop_id:$("#crop_id"+serial).val(),
//        product_type_id:$("#product_type_id"+serial).val(),
//        varriety_id:$("#varriety_id"+serial).val(),
//        pack_size:$("#pack_size"+serial).val(),
//        warehouse_id:$("#warehouse_id"+serial).val()
//    },
//    function(result)
//    {
//        if (result)
//        {
//            var current_quantity = parseFloat(result);
//            var purchase_quantity = parseFloat($("#purchase_quantity"+serial).val());
//            var total_quantity=(current_quantity-purchase_quantity);
//            if(current_quantity<purchase_quantity)
//            {
//                var con_msg="Sorry your current quantity not available. Your current quantity is:"+current_quantity+", delete quantity is:"+purchase_quantity+", short quantity is:"+total_quantity;
//                reset();
//                alertify.alert(con_msg, function (e)
//                {
//                    if (e)
//                    {
//
//                    }
//                });
//                return false;
//            }
//            else
//            {
//                var con_msg="Are You Sure .... Delete this requisition? Your current quantity is:"+current_quantity+", delete quantity is:"+purchase_quantity+", total quantity is:"+total_quantity;
//                reset();
//                alertify.confirm(con_msg, function (e)
//                {
//                    if (e)
//                    {
//                        $.post("delete_product.php",
//                        {
//                            row_id:row_id,
//                            crop_id:$("#crop_id"+serial).val(),
//                            product_type_id:$("#product_type_id"+serial).val(),
//                            varriety_id:$("#varriety_id"+serial).val(),
//                            pack_size:$("#pack_size"+serial).val(),
//                            warehouse_id:$("#warehouse_id"+serial).val(),
//                            purchase_quantity:$("#purchase_quantity"+serial).val()
//                        },
//                        function(result)
//                        {
//                            if(result)
//                            {
//                                $("#tr_id"+serial).fadeOut();
//                            }
//                        })
//                    }
//                    else
//                    {
//
//                    }
//                });
//                return false;
//            }
//        }
//    });
//}

function delete_product(row_id)
{
    var con_msg="Are You Sure .... Delete this requisition?";
    reset();
    alertify.confirm(con_msg, function (e)
    {
        if (e)
        {
            $.post("delete_product.php",
                {
                    row_id:row_id
                },
                function(result)
                {
                    if(result)
                    {
                        alert (result);
                        list();
                    }
                })
        }
        else
        {

        }
    });
    return false;
}