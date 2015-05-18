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

//
//function Existin_data(elm){
//    $("#loader").remove();
//    $.post("exist_data.php",{name:elm.value}, function(result){
//        if (result){
//            if (result=="Found"){
////                ME_Alert('Alert', 'Exiting Your Data');
//                notification_msg('Exiting Your Data');
//                $(elm).after('<img id="loader" src="../../img/icons/25x25/loader.gif" />');
//                MenuOffOn('off','off','off','off','off','off','on','on','on','on');
//            }
//            else if (result=="Not Found"){
//                $("#loader").remove();
//                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
//            }
//        }
//    });
//}

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
    $.post("../../libraries/ajax_load_file/load_pack_size.php",{
        crop_id:$("#crop_id").val(),
        product_type_id:$("#product_type_id").val(),
        varriety_id:$("#varriety_id").val()
    }, function(result){
        if (result){
            $("#pack_size").append(result);
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
function load_pdo_variety(){

    $("#pdo_id").html('');
    $.post("../../libraries/ajax_load_file/load_variety_hybrid.php", {
        crop_id: $("#crop_id").val(),
        product_type_id: $("#product_type_id").val()
    }, function(result){
        if(result){
            $("#pdo_id").append(result);
        }
    })
}

function product_image_info(row_id){
    $("#show_data").html('');
    $.post("../../libraries/ajax_load_file/load_product_image_info.php", {
        row_id: row_id
    }, function(result){
        if(result){
            $("#show_data").html(result);
            image_info_pop();
        }
    })
}