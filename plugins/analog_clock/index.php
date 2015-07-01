<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<style type="text/css">
    * {
        margin: 0;
        padding: 0;
    }
    #clock {
        position: relative;
        width: 600px;
        height: 600px;
        margin: 0px auto 0 auto;
        background: url(images/clockface.jpg);
        list-style: none;
    }
    #sec, #min, #hour {
        position: absolute;
        width: 30px;
        height: 600px;
        top: 0px;
        left: 285px;
    }
    #sec {
        background: url(images/sechand.png);
        z-index: 3;
    }
    #min {
        background: url(images/minhand.png);
        z-index: 2;
    }
    #hour {
        background: url(images/hourhand.png);
        z-index: 1;
    }
    p {
        text-align: center;
        padding: 10px 0 0 0;
    }
</style>

<ul id="clock">
    <li id="sec"></li>
    <li id="hour"></li>
    <li id="min"></li>
</ul>

<script type="text/javascript">
    jQuery(document).ready(function() {
        setInterval( function() {
            var seconds = new Date().getSeconds();
            var sdegree = seconds * 6;
            var srotate = "rotate(" + sdegree + "deg)";
            jQuery("#sec").css({"-moz-transform" : srotate, "-webkit-transform" : srotate});
        }, 1000 );
        setInterval( function() {
            var hours = new Date().getHours();
            var mins = new Date().getMinutes();
            var hdegree = hours * 30 + (mins / 2);
            var hrotate = "rotate(" + hdegree + "deg)";
            jQuery("#hour").css({"-moz-transform" : hrotate, "-webkit-transform" : hrotate});
        }, 1000 );
        setInterval( function() {
            var mins = new Date().getMinutes();
            var mdegree = mins * 6;
            var mrotate = "rotate(" + mdegree + "deg)";
            jQuery("#min").css({"-moz-transform" : mrotate, "-webkit-transform" : mrotate});
        }, 1000 );
    });
</script>