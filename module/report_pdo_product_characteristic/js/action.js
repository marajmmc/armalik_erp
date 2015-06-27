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

//function load_upazilla_fnc()
//{
//    $("#upazilla_id").html('');
//    $.post("../../libraries/ajax_load_file/load_upazilla_info.php",{
//        division_id: $("#division_id").val(),
//        district_id: $("#district_id").val()
//    }, function(result){
//        if (result){
//            $("#upazilla_id").append(result);
//        }
//    });
//}
//
//function show_report_fnc()
//{
//    $('#div_show_rpt').html('');
//    $(".icon-print").append("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
//    $(".icon-print").attr('disable', 'disable');
//    //if($('#crop_id').val()=="" || $('#product_type_id').val()=="")
//    //{
//    //    alert ('Please select crop & product type! try again.');
//    //}
//    //else
//    //{
//
//        $.post('load_show_data.php', $("#frm_area").serialize(), function(result){
//            if(result)
//            {
//                $('#div_loader').remove();
//                $(".icon-print").attr('disable', '');
//                $('#div_show_rpt').html(result);
//            }
//        })
//    //}
//
//
//}
//
////////////  start zone, territory, distributor load function //////////////
//
//function session_load_fnc(){
//    if($("#userLevel").val()=="Zone"){
//        session_load_zone();
//        //session_load_territory();
//    //}else if($("#userLevel").val()=="Territory"){
//    //    session_load_zone();
//    //    session_load_territory();
//    //    session_load_distributor();
//    //}else if($("#userLevel").val()=="Distributor"){
//    //    session_load_zone();
//    //    session_load_territory();
//    //    session_load_distributor();
//    }else{
//
//    }
//}
//
//function session_load_zone(){
//    $("#zone_id").html('');
//    $.post("../../libraries/ajax_load_file/session_load_zone.php",function(result){
//        if (result){
//            $("#zone_id").append(result);
//        }
//    });
//}
////function session_load_territory(){
////    $("#territory_id").html('');
////    $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
////        if (result){
////            $("#territory_id").append(result);
////        }
////    });
////}
////function session_load_distributor(){
////    $("#distributor_id").html('');
////    $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
////        if (result){
////            $("#distributor_id").append(result);
////        }
////    });
////}
//
////////////  end zone, territory, distributor load function //////////////
//
//function print_rpt(){
//    URL="../../libraries/print_page/Print_a4_Eng.php?selLayer=PrintArea";
//    day = new Date();
//    id = day.getTime();
//    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=yes,scrollbars=yes ,location=0,statusbar=0 ,menubar=yes,resizable=1,width=880,height=600,left = 20,top = 50');");
//}
//
//function load_product_type(){
//
//    $("#product_type_id").html('');
//    $.post("../../libraries/ajax_load_file/load_product_type.php", {
//        crop_id: $("#crop_id").val()
//    }, function(result){
//        if(result){
//            $("#product_type_id").append(result);
//        }
//    })
//}
//function load_zone()
//{
//    $("#zone_id").html('');
//    $.post("../../libraries/ajax_load_file/load_zone_info_user_access.php",{division_id: $("#division_id").val()}, function(result){
//        if (result){
//            $("#zone_id").append(result);
//        }
//    });
//}
//function load_district()
//{
//    $("#district_id").html('');
//    $.post("../../libraries/ajax_load_file/load_zone_assign_district.php",{zone_id: $('#zone_id').val()},function(result){
//        if (result){
//            $("#district_id").append(result);
//        }
//    });
//}
