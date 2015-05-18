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

function load_variety_view(){

    $("#div_show_variety").html('');
    $(".icon-print").append("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
    $(".icon-print").attr('disable', 'disable');
    $.post("load_varriety.php",$("#frm_area").serialize(), function(result){
        if(result)
        {
            $('#div_loader').remove();
            $(".icon-print").attr('disable', '');
            $("#div_show_variety").html(result);
        }
    })
}

function show_report_fnc(){
    $('#div_show_rpt').html('');
    $(".icon-print").append("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
    $(".icon-print").attr('disable', 'disable');
    //if($('#crop_id').val()=="" || $('#product_type_id').val()=="")
    //{
    //    alert ('Please select crop & product type! try again.');
    //}
    //else
    //{

    $.post('load_show_data.php', $("#frm_area").serialize(), function(result){
        if(result)
        {
            $('#div_loader').remove();
            $(".icon-print").attr('disable', '');
            $('#div_show_rpt').html(result);
        }
    })
    //}
}

function print_rpt(){
    URL="../../libraries/print_page/Print_a4_Eng.php?selLayer=PrintArea";
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=yes,scrollbars=yes ,location=0,statusbar=0 ,menubar=yes,resizable=1,width=880,height=600,left = 20,top = 50');");        
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


function load_zone()
{
    $("#zone_id").html('');
    $.post("../../libraries/ajax_load_file/load_zone_info_user_access.php",{division_id: $("#division_id").val()}, function(result){
        if (result){
            $("#zone_id").append(result);
        }
    });
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
function load_district()
{
    $("#district_id").html('');
    $.post("../../libraries/ajax_load_file/load_zone_assign_district.php",{zone_id: $('#zone_id').val()},function(result){
        if (result){
            $("#district_id").append(result);
        }
    });
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