var SaveStatus=0;

//////////////////  action start  ///////////////
function list(){
    hide_div();
    $("#list_rec").show();
    loader_start();
    $.post("list.php", function(result){
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
                    //                            $("#new_rec").html(result);
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



function selectallTask(obj)
{
    var count=obj.value.length;
    if(obj.checked==true)
    {
        for (i=0;i<document.frm_area.elements.length;i++)
        {
            var chkstring=new String(document.frm_area.elements[i].name);
            if((obj.value+"-")==chkstring.substr(0,(count+1)))
            {
                document.frm_area.elements[i].checked=true;
            }
        }
    }
    else{
        for (i=0;i<document.frm_area.elements.length;i++)
        {
            var chkstring=new String(document.frm_area.elements[i].name);
            if((obj.value+"-")==chkstring.substr(0,(count+1)))
            {
                document.frm_area.elements[i].checked=false;
            }
        }
    }   
}

function selectallEvents(obj,module)
{
    var count=obj.value.length;
    if(obj.checked==true)
    {
        for (i=0;i<document.frm_area.elements.length;i++)
        {
            var chkstring=new String(document.frm_area.elements[i].name);
            if((obj.value+"-")==chkstring.substr(0,(count+1)))
            {
                document.frm_area.elements[i].checked=true;
            }
            if(document.frm_area.elements[i].name==module)
            {
                document.frm_area.elements[i].checked=true;
            }
        }
    }
    else{
        for (i=0;i<document.frm_area.elements.length;i++)
        {
            var chkstring=new String(document.frm_area.elements[i].name);
            if((obj.value+"-")==chkstring.substr(0,(count+1)))
            {
                document.frm_area.elements[i].checked=false;
            }
        }
    }   
}

function selectallModuleTask(obj,module,task)
{
    if(obj.checked==true)
    {
        for (i=0;i<document.frm_area.elements.length;i++)
        {
                
            if(document.frm_area.elements[i].name==module)
            {
                document.frm_area.elements[i].checked=true;
            }
            if(document.frm_area.elements[i].name==task)
            {
                document.frm_area.elements[i].checked=true;
            }
        }
    }  
}
