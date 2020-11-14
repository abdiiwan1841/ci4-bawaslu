<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>
<style>
    @media (max-width: 480px) {
        .dashboard-wrapper .main-container .widget .widget-header .tools {
            display: block !important;
        }

        select {
            width: unset !important;
        }
    }

    @media (max-width: 767px) {
        .dashboard-wrapper .main-container .widget .widget-header .tools {
            display: block !important;
        }

        select {
            width: unset !important;
        }
    }
</style>


<!-- Use any element to open/show the overlay navigation menu -->
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/jquery.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/responsive.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/css/fullcalendar/fullcalendar.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/fullcalendar/fullcalendar.print.css' ?>">

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe09f;"></span> Quick Access
                </div>
            </div>
            <div class="widget-body">
                <a class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe053;"></span>
                    <p class="no-margin">Apply Leave</p>
                </a>
                <a class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe048;"></span>
                    <p class="no-margin">Apply Overtime </p>
                </a>
                <a class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe038;"></span>
                    <p class="no-margin">Payroll Management</p>
                </a>
                <a class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span>
                    <p class="no-margin">Absence Management</p>
                </a>
                <a href="<?= base_url('/employee'); ?>" class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe075;"></span>
                    <p class="no-margin">Employees</p>
                </a>
                <a href="<?= base_url('/org'); ?>" class="quick-action-btn span2 input-bottom-margin" data-original-title="">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b9;"></span>
                    <p class="no-margin">Organization Cart</p>
                </a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<!-- <div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe097;"></span> Dashboard
                </div>
            </div>
            <div class="widget-body">
                <ul class="stats-overview">
                    <li>
                        <span class="name">
                        </span>
                        <span class="value text-success">
                        </span>
                    </li>
                    <li>
                        <span class="name">
                        </span>
                        <span class="value text-info">
                        </span>
                    </li>
                    <li>
                        <span class="name">
                        </span>
                        <span class="value text-error">
                        </span>
                    </li>
                </ul>
            </div>
            <div class="widget-body">
                <div id="gold" style="height:350px;"></div>
            </div>
        </div>
    </div>
</div> -->

<div class="row-fluid">

    <div class="span4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span> Leave Calendar
                </div>
            </div>
            <div class="widget-body">
                <div id="calendar" style=""></div>
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span> Inbox
                </div>
            </div>
            <div class="widget-body">
                <div id="dt_example" style="height:515px;">
                    <table id="datatables" class="table table-condensed table-bordered no-margin">
                        <thead>
                            <tr>
                                <th>
                                    Request Type
                                </th>
                                <th>
                                    Empoyee Name
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="success">
                                <td>
                                    Leave
                                </td>
                                <td>
                                    Tarkiman
                                </td>
                                <td style="text-align: center;">
                                    <span class="label label-success">Approval</span>
                                </td>
                            </tr>
                            <tr class="success">
                                <td>
                                    Overtime
                                </td>
                                <td>
                                    Fulan
                                </td>
                                <td style="text-align: center;">
                                    <span class="label label-success">Approval</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="span4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span> Absence Summary
                </div>
            </div>
            <div class="widget-body">
                <div id="absence" style="height:515px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= '/assets/js/echart/echarts.min.js'; ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/responsive.bootstrap.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/js/fullcalendar/fullcalendar.min.js' ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#datatables').DataTable();

    });

    // Calendar
    $(document).ready(function() {

        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                var title = prompt('Event Title:');
                if (title) {
                    calendar.fullCalendar('renderEvent', {
                            title: title,
                            start: start,
                            end: end,
                            allDay: allDay
                        },
                        true // make the event "stick"
                    );
                }
                calendar.fullCalendar('unselect');
            },
            editable: true,
            events: [{
                    title: 'All Day Event',
                    start: new Date(y, m, 1)
                },
                {
                    title: 'Long Event',
                    start: new Date(y, m, d - 5),
                    end: new Date(y, m, d - 2)
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d - 3, 16, 0),
                    allDay: false
                },
                {
                    id: 999,
                    title: 'Repeating Event',
                    start: new Date(y, m, d + 4, 16, 0),
                    allDay: false
                },
                {
                    title: 'Meeting',
                    start: new Date(y, m, d, 10, 30),
                    allDay: false
                },
                {
                    title: 'Lunch',
                    start: new Date(y, m, d, 12, 0),
                    end: new Date(y, m, d, 14, 0),
                    allDay: false
                },
                {
                    title: 'Birthday Party',
                    start: new Date(y, m, d + 1, 19, 0),
                    end: new Date(y, m, d + 1, 22, 30),
                    allDay: false
                },
                {
                    title: 'Click for Google',
                    start: new Date(y, m, 28),
                    end: new Date(y, m, 29),
                    url: 'http://google.com/'
                }
            ]
        });
    });
</script>

<script>
    var stockCart = echarts.init(document.getElementById('absence'));

    var res = {
        "colors": ["#6f7390", "#6ac280", "#ff6600", "#ab7136", "#a8a9b8"],
        "series": [{
            "value": "4",
            "name": "Mangkir",
            "color_code": "#6f7390"
        }, {
            "value": "141",
            "name": "Hadir",
            "color_code": "#6ac280"
        }, {
            "value": "11",
            "name": "Cuti",
            "color_code": "#ff6600"
        }, {
            "value": "3",
            "name": "Sakit",
            "color_code": "#ab7136"
        }, {
            "value": "1",
            "name": "Ijin",
            "color_code": "#a8a9b8"
        }]
    };


    var itemStyle = {
        normal: {
            opacity: 0.7,
            borderWidth: 0.5,
            borderColor: '#000',
            shadowBlur: 10,
            shadowColor: 'rgba(120, 36, 50, 0.5)',
            shadowOffsetY: 5
        }
    };

    option2 = {
        backgroundColor: '#fff',
        title: {
            text: 'ABSENCE SUMMARY',
            subtext: 'TARKIMAN',
            left: 'center',
            textStyle: {
                color: '#000'
            }
        },
        tooltip: {
            trigger: 'item',
            formatter: '{a} <br/>{b} : {c} ({d}%)'
        },
        series: [{
            name: 'Stock Product',
            type: 'pie',
            radius: '55%',
            center: ['50%', '60%'],
            color: res.colors,
            data: res.series,
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },
            itemStyle: itemStyle
        }]
    };

    stockCart.setOption(option2);
</script>


<?= $this->endSection(); ?>