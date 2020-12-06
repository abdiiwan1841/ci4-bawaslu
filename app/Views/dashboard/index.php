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

<!-- <div class="row-fluid">
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
</div> -->

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

    <div class="span8">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span> Tindak Lanjut Baru
                </div>
            </div>
            <div class="widget-body">
                <div id="dt_example" style="height:515px;">
                    <table id="datatables" class="table table-condensed table-bordered no-margin">
                        <thead>
                            <tr>
                                <th>Tanggal TL</th>
                                <th>Satuan Kerja</th>
                                <th>Laporan</th>
                                <th>Memo Temuan</th>
                                <th>Memo Rekomendasi</th>
                                <th>Nilai Rekomendasi</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
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
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0b3;"></span> Summary Tindak Lanjut
                </div>
            </div>
            <div class="widget-body">
                <div id="tindakLanjutPieChart" style="height:515px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?= '/assets/js/echart/echarts.min.js'; ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/responsive.bootstrap.min.js' ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#datatables').DataTable({
            paging: true,
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{
                responsivePriority: 1,
                targets: 3
            }],
            order: [
                [1, "asc"]
            ],
            search: {
                "caseInsensitive": false
            },
            ajax: "<?= base_url('dashboard/datatables'); ?>",
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = table.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                // $('td:first', nRow).html(index);
                $('tr:eq(0) th').css("text-align", "center");
                $('td:eq(5)', nRow).css("text-align", "right");
                $('td:eq(6)', nRow).css("text-align", "center");
                return nRow;
            },
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        $.ajax({
            url: "<?= '/dashboard/getDataPieChart' ?>",
            // data: {
            //     'stock_type': stock_type
            // },
            type: "POST",
            dataType: 'json',
            cache: false,
            success: function(res) {
                console.log(res);
                callStock(res);
            }
        });

    });
</script>

<script>
    var stockCart = echarts.init(document.getElementById('tindakLanjutPieChart'));

    function callStock(res) {

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
                text: 'SUMMARY TINDAK LANJUT',
                subtext: 'BAWASLU - SITINJU',
                left: 'center',
                textStyle: {
                    color: '#000'
                }
            },
            tooltip: {
                trigger: 'item',
                formatter: '{a} <br/>{b} : {c} ({d}%)'
            },
            legend: {
                // orient: 'vertical',
                // left: 'left',
                bottom: 10,
                data: ['Sesuai', 'Belum Sesuai', 'Belum ditindaklanjuti', 'Tidak Dapat ditindaklanjuti']
            },
            series: [{
                name: 'Status Tindak Lanjut',
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
    }

    // var res = {
    //     "colors": ["#6f7390", "#6ac280", "#ff6600", "#ab7136", "#a8a9b8"],
    //     "series": [{
    //         "value": "4",
    //         "name": "Mangkir",
    //         "color_code": "#6f7390"
    //     }, {
    //         "value": "141",
    //         "name": "Hadir",
    //         "color_code": "#6ac280"
    //     }, {
    //         "value": "11",
    //         "name": "Cuti",
    //         "color_code": "#ff6600"
    //     }, {
    //         "value": "3",
    //         "name": "Sakit",
    //         "color_code": "#ab7136"
    //     }, {
    //         "value": "1",
    //         "name": "Ijin",
    //         "color_code": "#a8a9b8"
    //     }]
    // };


    // var itemStyle = {
    //     normal: {
    //         opacity: 0.7,
    //         borderWidth: 0.5,
    //         borderColor: '#000',
    //         shadowBlur: 10,
    //         shadowColor: 'rgba(120, 36, 50, 0.5)',
    //         shadowOffsetY: 5
    //     }
    // };

    // option2 = {
    //     backgroundColor: '#fff',
    //     title: {
    //         text: 'ABSENCE SUMMARY',
    //         subtext: 'TARKIMAN',
    //         left: 'center',
    //         textStyle: {
    //             color: '#000'
    //         }
    //     },
    //     tooltip: {
    //         trigger: 'item',
    //         formatter: '{a} <br/>{b} : {c} ({d}%)'
    //     },
    //     series: [{
    //         name: 'Stock Product',
    //         type: 'pie',
    //         radius: '55%',
    //         center: ['50%', '60%'],
    //         color: res.colors,
    //         data: res.series,
    //         emphasis: {
    //             itemStyle: {
    //                 shadowBlur: 10,
    //                 shadowOffsetX: 0,
    //                 shadowColor: 'rgba(0, 0, 0, 0.5)'
    //             }
    //         },
    //         itemStyle: itemStyle
    //     }]
    // };

    // stockCart.setOption(option2);
</script>


<?= $this->endSection(); ?>