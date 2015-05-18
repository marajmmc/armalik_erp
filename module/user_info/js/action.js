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
    if($("#user_pass").val()!=$("#repuser_pass").val()){
        $("#user_pass").css("background", "#FF4A4A");
        $("#repuser_pass").css("background", "#FF4A4A");
        
        $("#user_pass").bind('focus', function() {
            $("#user_pass").css("background", "");
        });
        
        $("#repuser_pass").bind('focus', function() {
            $("#repuser_pass").css("background", "");
        });
        
        reset();
        alertify.set({
            delay: 3000
        });
        alertify.error("Does not mathch password. Please try again");
        return false;
        
    }else{
        validateResult=true;
        formValidate();
        if(validateResult){
            if (SaveStatus==1){
                
                $("#user_pass").each(function () {
                    if(!/[a-z]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Lower Case");
                        return false;
                    }else if(!/[A-Z]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Upper Case");
                        return false;
                    }else if(!/[0-9]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Number");
                        return false;
                    }else if(this.value.length < 7){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum 6 Digit");
                        return false;
                    }else {
                        $.post("save.php",$("#frm_area").serialize(), function(result){
                            if (result){
                                //                                $("#new_rec").html(result);
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
                    }
                });
                
                
            }else if(SaveStatus==2){
                $("#user_pass").each(function () {
                    if(!/[a-z]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Lower Case");
                        return false;
                    }else if(!/[A-Z]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Upper Case");
                        return false;
                    }else if(!/[0-9]/.test(this.value)){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum One Number");
                        return false;
                    }else if(this.value.length < 7){
                        reset();
                        alertify.set({
                            delay: 3000
                        });
                        alertify.error("Please Input Minimum 6 Digit");
                        return false;
                    }else {
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
                });
            }
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
                $(elm).after('<img id="loader" src="../../system_images/loading-orange.gif" />');
                MenuOffOn('off','off','off','off','off','off','on','on','on','on');
                reset();
                alertify.set({
                    delay: 3000
                });
                alertify.error("Exist user name. try again");
                return false;
            }
            else if (result=="Not Found"){
                $("#loader").remove();
                MenuOffOn('off','on','off','off','on','on','on','on','on','on');
            }
        }
    });
}

function load_employee_fnc(){
    $("#ei_id").html('');
    $.post("../../libraries/ajax_load_file/load_employee.php",{
        user_level:$("#user_level").val()
    }, function(result){
        if(result){
            $("#ei_id").append(result);
        }
    });
}


function load_user_type(){
    if($("#user_level").val()=="Zone"){
        $("#div_zone_id").slideDown();
        $("#div_territory_id").slideUp();
        $("#div_warehouse_id").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Territory"){
        $("#div_zone_id").slideDown();
        $("#div_territory_id").slideDown();
        $("#div_warehouse_id").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Distributor"){
        $("#div_zone_id").slideDown();
        $("#div_territory_id").slideDown();
        $("#div_warehouse_id").slideUp();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Warehouse"){
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_warehouse_id").slideDown();
        $("#div_division_id").slideUp();
    }else if($("#user_level").val()=="Division"){
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_warehouse_id").slideUp();
        $("#div_division_id").slideDown();
    }else{
        $("#div_zone_id").slideUp();
        $("#div_territory_id").slideUp();
        $("#div_warehouse_id").slideUp();
        $("#div_division_id").slideUp();
    }
}

//function load_user_type(){
//    if($("#user_level").val()=="Zone"){
//        $("#div_zone_id").slideDown();
//        $("#div_warehouse_id").slideUp();
//        $("#div_territory_id").slideUp();
//    }else if($("#user_level").val()=="Territory"){
//        $("#div_zone_id").slideDown();
//        $("#div_territory_id").slideDown();
//        $("#div_warehouse_id").slideUp();
//    }else if($("#user_level").val()=="Warehouse"){
//        $("#div_warehouse_id").slideDown();
//        $("#div_zone_id").slideUp();
//        $("#div_territory_id").slideUp();
//    }else{
//        $("#div_warehouse_id").slideUp();
//        $("#div_zone_id").slideUp();
//        $("#div_territory_id").slideUp();
//    }
//}

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
        //        alert (result)
        if (result){
            $("#distributor_id").append(result);
        }
    });
}

function load_employee_fnc(){
    $("#employee_id").html('');
    $.post("load_employee.php",{
        user_level:$("#user_level").val(),
        division_id:$("#division_id").val(),
        zone_id:$("#zone_id").val(),
        territory_id:$("#territory_id").val(),
        warehouse_id:$("#warehouse_id").val()
    }, function(result){
        //        alert (result)
        if (result){
            $("#employee_id").append(result);
        }
    });
}

