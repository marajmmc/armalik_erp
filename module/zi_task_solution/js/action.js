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
            if($("#userLevel").val()=="Division")
            {
                MenuOffOn('off','off','on','on','on','on','on','off','on','on');
            }
            else
            {
                MenuOffOn('on','off','on','on','on','on','on','off','on','on');
            }
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

function Existin_data(elm){
    $("#loader").remove();
    $.post("exist_data.php",{name:elm.value}, function(result){
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

function load_territory_by_zone()
{
    $("#territory_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory.php",
    {
        zone_id : $("#zone_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#territory_id").append(result);
        }
    });
}

function load_district_by_territory()
{
    $("#district_id").html('');
    $.post("../../libraries/ajax_load_file/load_territory_assign_district.php",
    {
        zone_id : $("#zone_id").val(), territory_id: $("#territory_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#district_id").append(result);
        }
    });
}

function load_distributor_by_district()
{
    $("#distributor_id").html('');
    $.post("../../libraries/ajax_load_file/load_distributor.php",
    {
        zone_id : $("#zone_id").val(), territory_id: $("#territory_id").val(), zilla_id: $("#district_id").val()
    },

    function(result)
    {
        if(result)
        {
            $("#distributor_id").append(result);
        }
    });
}

