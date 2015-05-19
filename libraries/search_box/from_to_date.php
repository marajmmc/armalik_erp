<table class="table table-condensed table-striped table-hover table-bordered pull-left" id="data-table"  style="width: 50%; float: left;">
    <thead>
    <tr>
        <th style="width:10%">
            From Date
        </th>
        <th style="width:10%">
            To Date
        </th>
    </tr>
    <tr>
        <td>
            <div class="input-append">
                <input type="text" name="from_date" id="from_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())  ?>"  />
                <span class="add-on" id="calcbtn_from_date">
                    <i class="icon-calendar"></i>
                </span>
            </div>
        </td>
        <td>
            <div class="input-append">
                <input type="text" name="to_date" id="to_date" class="span9" placeholder="From Date" value="<?php // echo $db->date_formate($db->ToDayDate())  ?>"  />
                <span class="add-on" id="calcbtn_to_date">
                    <i class="icon-calendar"></i>
                </span>
            </div>
        </td>
    </tr>
    </thead>
</table>
<script>
    var cal = Calendar.setup({
        onSelect: function(cal) { cal.hide() },
        fdow :0,
        minuteStep:1
    });
    cal.manageFields("calcbtn_from_date", "from_date", "%d-%m-%Y");
    cal.manageFields("calcbtn_to_date", "to_date", "%d-%m-%Y");

</script>