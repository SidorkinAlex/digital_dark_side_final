{literal}
<link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">

<link href="modules/DIGIT_TASK/timelineResours/css/jquery.timespace.light.css?v2.11" rel="stylesheet">
<style>

    #content {
        background: #aaaaaa !important;
    }
    a {
        color: #aaaaaa;
    }
    h1,h2 {
        text-align: center;
    }
    h1 { margin: 150px auto 30px auto; text-align: center; }
    #timeline, #timelineClock {
        box-sizing: border-box;
        padding: 10px;
        width: 100%;
    }
    time{
        text-align: center;
    }
    .jqTimespaceEvent .red{
        background: #d96a6a;
    }
    .jqTimespaceEvent .yelow{
        background: #e0d580;
    }
    .jqTimespaceEvent .blue{
        background: #9da6eb;
    }
</style>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
<script src="modules/DIGIT_TASK/timelineResours/jquery.timespace.js?v2"></script>




<div id="timelineClock"></div>
<div id="timeline"></div>
<script>
    {/literal}
    const dataTask ={$data|@json_encode};
    console.log(dataTask);
    const dataStart ={$dateStart};
    const headingsData ={$headingsData|@json_encode};
    const dataEnd ={$dateEnd};
    {literal}
    $(function () {

        $('#timelineClock').timespace({
            timeType: 'date',
            maxWidth: 2000,
            maxHeight: 28000,
            navigateAmount: 5000,
            dragYMultiplier: 1,
            useTimeSuffix:false,
            shiftOnEventSelect:false,
            scrollToDisplayBox:false,
            use12HourTime:false,
            startTime: dataStart,
            endTime: dataEnd,
            markerWidth: 200,

            //customEventDisplay:null







            // Set the time suffix function for displaying as '12 A.M.'
            selectedEvent: -1,
            data: {
            headings: headingsData,
                events: dataTask
//                    {start: 1, end: 8.5, title: "Задача 0" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.6, title: "Задача 1" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.2, title: "Задача 2" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.6, title: "Задача 3" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.2, title: "Задача 4" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.7, title: "Задача 5" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.3, title: "Задача 6" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.2, title: "Задача 7" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.5, title: "Задача 8" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.6, title: "Задача 9" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.8, title: "Задача 10" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.5, title: "Задача 11" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.4, title: "Задача 12" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.7, title: "Задача 13" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.6, title: "Задача 14" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.8, title: "Задача 15" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.5, title: "Задача 16" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.3, title: "Задача 17" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.5, title: "Задача 18" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.4, title: "Задача 19" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.4, title: "Задача 20" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.2, title: "Задача 21" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.2, title: "Задача 22" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.3, title: "Задача 23" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.3, title: "Задача 24" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.5, title: "Задача 25" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.2, title: "Задача 26" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.6, title: "Задача 27" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.8, title: "Задача 28" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.7, title: "Задача 29" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.6, title: "Задача 30" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.8, title: "Задача 31" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.6, title: "Задача 32" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.5, title: "Задача 33" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.4, title: "Задача 34" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.8, title: "Задача 35" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.5, title: "Задача 36" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.2, title: "Задача 37" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.5, title: "Задача 38" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.2, title: "Задача 39" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.5, title: "Задача 40" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.8, title: "Задача 41" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.3, title: "Задача 42" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.4, title: "Задача 43" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.7, title: "Задача 44" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.7, title: "Задача 45" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.5, title: "Задача 46" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.2, title: "Задача 47" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.6, title: "Задача 48" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.4, title: "Задача 49" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.7, title: "Задача 50" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.3, title: "Задача 51" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.6, title: "Задача 52" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.6, title: "Задача 53" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.3, title: "Задача 54" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.8, title: "Задача 55" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.4, title: "Задача 56" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.3, title: "Задача 57" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.3, title: "Задача 58" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.2, title: "Задача 59" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.5, title: "Задача 60" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.6, title: "Задача 61" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.2, title: "Задача 62" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.2, title: "Задача 63" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.2, title: "Задача 64" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.4, title: "Задача 65" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.4, title: "Задача 66" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.3, title: "Задача 67" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.6, title: "Задача 68" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.8, title: "Задача 69" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.7, title: "Задача 70" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.7, title: "Задача 71" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.8, title: "Задача 72" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.2, title: "Задача 73" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.2, title: "Задача 74" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.2, title: "Задача 75" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.4, title: "Задача 76" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.4, title: "Задача 77" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.2, title: "Задача 78" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.5, title: "Задача 79" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.6, title: "Задача 80" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.6, title: "Задача 81" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 4.2, title: "Задача 82" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.5, title: "Задача 83" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 3.2, title: "Задача 84" ,class : "yelow", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.4, title: "Задача 85" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.6, title: "Задача 86" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 7.4, title: "Задача 87" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.8, title: "Задача 88" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.4, title: "Задача 89" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.2, title: "Задача 90" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.4, title: "Задача 91" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.2, title: "Задача 92" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.7, title: "Задача 93" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.8, title: "Задача 94" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 8.4, title: "Задача 95" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 6.8, title: "Задача 96" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 2.5, title: "Задача 97" ,class : "blue", description: "Meeting with Co-workers."},
//                    {start: 1, end: 1.2, title: "Задача 98" ,class : "red", description: "Meeting with Co-workers."},
//                    {start: 1, end: 5.3, title: "Задача 99" ,class : "blue", description: "Meeting with Co-workers."},
//
//            ]
        },

    });

//        $('#timeline').timespace({
//
//                timeType: 'date',
//                useTimeSuffix: false,
//                startTime: 500,
//                endTime: 2050,
//                markerIncrement: 50,
//                data: {
//                    headings: [
//                        {start: 500, end: 1750, title: 'Dark Ages'},
//                        {start: 1750, end: 1917, title: 'Age of Revolution'},
//                        {start: 1971, title: 'Information Age'},
//                    ],
//                    events: [
//                        {start: 1440, title: 'Gutenberg\'s Printing Press', width: 200},
//                        {start: 1517, end: 1648, title: 'The Reformation',
//                        description: $('<p>The Reformation was a turning point in the history of the world. '
//                        + 'Martin Luther was a key player in this event as he stood up against Papal tyranny '
//                        + 'and church apostasy.</p><p>Many other reformers followed in the steps of Luther '
//                        + 'and followed the convictions of their hearts, even unto death.</p>')},
//                        {start: 1773, title: 'Boston Tea Party'},
//                    {start: 1775, end: 1783, title: 'American Revolution', description: 'Description:', callback: function () {
//
//                    this.container.find('.jqTimespaceDisplay section').append(
//                    '<p>This description was brought to you by the callback function. For information on the American Revolution, '
//                    + '<a target="_blank" href="https://en.wikipedia.org/wiki/American_Revolution">visit the Wikipedia page.</a></p>'
//                    );
//
//                    }},
//                {start: 1789, title: 'French Revolution'},
//                {start: 1914, end: 1918, title: 'World War I', noDetails: true},
//                {start: 1929, end: 1939, title: 'Great Depression',
//                description: 'A period of global economic downturn. Many experienced unemployment and the basest poverty.'
//                },
//        ]
//    },
//
//    }, function () {
//
//        // Edit the navigation amount
//        this.navigateAmount = 500;
//
//    });

    });
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-36251023-1']);
    _gaq.push(['_setDomainName', 'jqueryscript.net']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>


    <script type="text/javascript" src="custom/include/lib/fancybox/jquery.fancybox-1.3.4.js"></script>

<script>
$(document).ready(function () {
    const windowInnerWidth = window.innerWidth-100;
    const windowInnerHeight = window.innerHeight -50;

    $('.iframe').fancybox({
        'width': windowInnerWidth,
        'height': windowInnerHeight,
    });

});

</script>
<link rel="stylesheet" href="custom/include/lib/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
{/literal}