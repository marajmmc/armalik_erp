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
//
//function load_varriety_fnc(){
//    $("#varriety_id").html('');
//    $.post("../../libraries/ajax_load_file/load_varriety.php",{
//        crop_id:$("#crop_id").val(),
//        product_type_id:$("#product_type_id").val()
//    }, function(result){
//        if (result){
//            $("#varriety_id").append(result);
//        }
//    });
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
//
//function load_pack_size_fnc(){
//    $("#pack_size").html('');
//    $.post("../../libraries/ajax_load_file/load_pack_size.php",{
//        crop_id:$("#crop_id").val(),
//        product_type_id:$("#product_type_id").val(),
//        varriety_id:$("#varriety_id").val()
//    }, function(result){
//        if (result){
//            $("#pack_size").append(result);
//        }
//    });
//}
//
//function show_report_fnc(){
//    $('#div_show_rpt').html('');
//    $.post('load_show_data.php', $("#frm_area").serialize(), function(result){
//        if(result){
//            $('#div_show_rpt').html(result);
//        }
//    })
//}
//
////////////  start zone, territory, distributor load function //////////////
//
//function load_territory_fnc(){
//    $("#territory_id").html('');
//    $.post("../../libraries/ajax_load_file/load_territory.php",{
//        zone_id:$("#zone_id").val()
//    }, function(result){
//        if (result){
//            $("#territory_id").append(result);
//        }
//    });
//}
//
//
//function load_distributor_fnc(){
//    $("#distributor_id").html('');
//    $.post("../../libraries/ajax_load_file/load_distributor.php",{
//        zone_id:$("#zone_id").val(),
//        territory_id:$("#territory_id").val()
//    }, function(result){
//        if (result){
//            $("#distributor_id").append(result);
//        }
//    });
//}
//
//function session_load_fnc(){
//    if($("#userLevel").val()=="Zone"){
//        session_load_zone();
//        session_load_territory();
//    }else if($("#userLevel").val()=="Territory"){
//        session_load_zone();
//        session_load_territory();
//        session_load_distributor();
//    }else if($("#userLevel").val()=="Distributor"){
//        session_load_zone();
//        session_load_territory();
//        session_load_distributor();
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
//function session_load_territory(){
//    $("#territory_id").html('');
//    $.post("../../libraries/ajax_load_file/session_load_territory.php", function(result){
//        if (result){
//            $("#territory_id").append(result);
//        }
//    });
//}
//function session_load_distributor(){
//    $("#distributor_id").html('');
//    $.post("../../libraries/ajax_load_file/session_load_distributor.php", function(result){
//        if (result){
//            $("#distributor_id").append(result);
//        }
//    });
//}
//
////////////  end zone, territory, distributor load function //////////////
//
//function print_rpt(){
//    URL="../../libraries/print_page/Print_a4_Eng.php?selLayer=PrintArea";
//    day = new Date();
//    id = day.getTime();
//    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=yes,scrollbars=yes ,location=0,statusbar=0 ,menubar=yes,resizable=1,width=880,height=600,left = 20,top = 50');");
//}