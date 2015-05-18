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
            MenuOffOn('off','off','off','on','on','on','on','off','on','on');
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

//function Save_Rec()
//{
//    validateResult=true;
//    formValidate();
//    if(validateResult){
//        if (SaveStatus==1){
//            $.post("save.php",$("#frm_area").serialize(), function(result){
//                if (result){
//                    //$("#new_rec").html(result);
//                    list();
//                    loader_close();
//                    reset();
//                    alertify.set({
//                        delay: 3000
//                    });
//                    alertify.success("Data Save Successfully");
//                    return false;
//                }
//            });
//        }else if(SaveStatus==2){
//            $.post("update.php",$("#frm_area").serialize(), function(result){
//
//                if (result){
//                    //$("#edit_rec").html(result);
//                    list();
//                    loader_close();
//                    reset();
//                    alertify.set({
//                        delay: 3000
//                    });
//                    alertify.success("Data Update Successfully");
//                    return false;
//                }
//            });
//        }
//    }
//}

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
function add_comment_fnc(row_id){
    $("#div_comment").html('');
    $.post("save_product_comment.php", {
        row_id: row_id,
        comment: $("#comment").val()
    }, function(result){
        if(result){
            //alert (result)
            load_comment_fnc(row_id);
            $("#comment").val('');
        }
    })
}

function product_comment_delete(row_id){
    var answer = confirm ("Are you sure delete your comment?")
    if (answer){
        $.post("delete_product_comment.php", {
            row_id: row_id
        }, function(result){
            if(result){
                $("#tr_id"+row_id).fadeOut();
            }
        })
    }else{

    }

}
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
function add_fruit_comment_fnc(row_id)
{
    //alert (row_id)
    $("#div_comment").html('');
    $.post("save_product_comment.php", {
        row_id: row_id,
        comment: $("#comment").val()
    }, function(result){
        if(result){
            //alert (result)
            load_fruit_comment_fnc(row_id);
            $("#comment").val('');
        }
    })
}

function product_fruit_comment_delete(row_id)
{
    //alert (row_id)
    var answer = confirm ("Are you sure delete your comment?")
    if (answer){
        $.post("delete_product_comment.php", {
            row_id: row_id
        }, function(result){
            if(result){
                $("#tr_id"+row_id).fadeOut();
            }
        })
    }else{

    }

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
function add_disease_comment_fnc(row_id)
{
    //alert (row_id)
    $("#div_comment").html('');
    $.post("save_product_comment.php", {
        row_id: row_id,
        comment: $("#comment").val()
    }, function(result){
        if(result){
            //alert (result)
            load_disease_comment_fnc(row_id);
            $("#comment").val('');
        }
    })
}

function product_fruit_comment_delete(row_id)
{
    //alert (row_id)
    var answer = confirm ("Are you sure delete your comment?")
    if (answer){
        $.post("delete_product_comment.php", {
            row_id: row_id
        }, function(result){
            if(result){
                $("#tr_id"+row_id).fadeOut();
            }
        })
    }else{

    }

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