<!--/////////////////////////     start calender css, js file link ///////////////////////-->
<link rel="stylesheet" type="text/css" href="css/jscal2.css" />
<link rel="stylesheet" type="text/css" href="css/border-radius.css" />
<link rel="stylesheet" type="text/css" href="css/steel/steel.css" />
<script type="text/javascript" src="js/jscal2.js"></script>
<script type="text/javascript" src="js/lang/en.js"></script>
<!--/////////////////////////     end calender css, js file link ///////////////////////-->
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<br />
<input type="text" id="datafield" name="datafield" value="" />


<script type="text/javascript">
    
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("datafield", "datafield", "%d-%m-%Y");
</script>   