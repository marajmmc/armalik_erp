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

    $("#variety_id").html('');
    $.post("../../libraries/ajax_load_file/load_pdo_variety.php", {
        crop_id: $("#crop_id").val(),
        product_type_id: $("#product_type_id").val()
    }, function(result){
        if(result){
            $("#variety_id").append(result);
        }
    })
}

function fnc_number_of_img()
{
    $("#div_number_of_img").html('');
    $.post("load_number_of_img.php", {
        number_of_img: $("#number_of_img").val()
    }, function(result){
        if(result){
            $("#div_number_of_img").append(result);
        }
    })
}

///////// START CROP IMAGE UPLOADED INFO /////////

function product_image_info(row_id)
{
    //alert(row_id)
    $("#show_data").html('');
    $.post("load_product_image_info.php",
        {
            row_id: row_id
        }, function(result){
            if(result){
                $("#show_data").html(result);
                image_info_pop();
            }
        })
}


function load_comment_fnc(row_id){
    $("#div_comment").html('');
    $.post("load_product_comment.php", {
        row_id: row_id
    }, function(result){
        if(result){
            $("#div_comment").html(result);
        }
    })
}
s
///////// END CROP IMAGE UPLOADED INFO /////////

///////// START FRUIT IMAGE UPLOADED INFO /////////

function product_fruit_image_info(row_id)
{
    //alert(row_id)
    $("#show_data").html('');
    $.post("load_product_image_info_fruit.php",
        {
            row_id: row_id
        }, function(result){
            if(result){
                $("#show_data").html(result);
                image_info_pop();
            }
        })
}


function load_fruit_comment_fnc(row_id){
    $("#div_comment").html('');
    $.post("load_product_comment.php", {
        row_id: row_id
    }, function(result){
        if(result){
            $("#div_comment").html(result);
        }
    })
}


///////// END FRUIT IMAGE UPLOADED INFO /////////

///////// START FRUIT IMAGE UPLOADED INFO /////////

function product_disease_image_info(row_id)
{
    //alert(row_id)
    $("#show_data").html('');
    $.post("load_product_image_info_disease.php",
        {
            row_id: row_id
        }, function(result){
            if(result){
                //alert (result)
                $("#show_data").html(result);
                image_info_pop();
            }
        })
}


function load_disease_comment_fnc(row_id){
    $("#div_comment").html('');
    $.post("load_product_comment.php", {
        row_id: row_id
    }, function(result){
        if(result){
            $("#div_comment").html(result);
        }
    })
}

///////// END FRUIT IMAGE UPLOADED INFO /////////

function image_info_pop(){
    $("#shadow").fadeIn();
    $("#show_data").fadeIn();
}
function close_image(){
    $("#shadow").fadeOut();
    $("#show_data").fadeOut();
    $("#show_data").html('');
}


