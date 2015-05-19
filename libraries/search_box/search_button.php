
<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table">
    <thead>
    <tr>
        <td colspan="12" style="text-align: right;">
            <a class="btn btn-small btn-success" data-original-title="" onclick="show_report_fnc()">
                <i class="icon-print" data-original-title="Share"> </i> View
            </a>
        </td>
    </tr>
    </thead>
</table>

<script>

    function show_report_fnc()
    {
        $(".icon-print").append("<div id='div_loader'><img src='../../system_images/fb_loader.gif' /></div>");
        $(".icon-print").attr('disable', 'disable');
        $('#div_show_rpt').html('');
        $.post('load_show_data.php', $("#frm_area").serialize(), function(result){
            if(result)
            {
                $('#div_loader').remove();
                $(".icon-print").attr('disable', '');
                $('#div_show_rpt').html(result);
            }
        })
    }

</script>