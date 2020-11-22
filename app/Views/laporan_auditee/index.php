<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/jquery.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/buttons.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/responsive.dataTables.min.css' ?>">
<style>
    table.dataTable>tbody>tr.child span.dtr-title {
        min-width: 170px;
    }
</style>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span> <?= $title . ' - ' . session()->get('wilayah'); ?>
                </div>
            </div>
            <div class="widget-body">
                <?php if (session()->getFlashData('messages')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashData('messages') ?>
                    </div>
                <?php endif; ?>
                <table id="datatables" class="table table-condensed table-bordered no-margin">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>No Laporan</th>
                            <th>Tanggal Laporan</th>
                            <th>Nama Laporan</th>
                            <!-- <th>Pelakasana Audit</th> BPK/IRWIL -->
                            <th>Sesuai</th>
                            <th>Belum Sesuai</th>
                            <th>Belum Ditindak Lanjuti</th>
                            <th>Tidak Dapat Ditindaklanjuti</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</div>
</div>

<script type="text/javascript" src="<?= '/assets/datatables/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.buttons.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/responsive.bootstrap.min.js' ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#datatables').DataTable({
            paging: true,
            dom: 'Bfrtip',
            buttons: [],
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 8
                },
                {
                    width: '120px',
                    targets: 1
                },
                {
                    width: '100px',
                    targets: 8
                }
            ],
            order: [
                [1, "asc"]
            ],
            search: {
                "caseInsensitive": false
            },
            ajax: "<?= base_url('laporanauditee/datatables'); ?>",
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = table.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                $('td:first', nRow).html(index);
                $('td:eq(1)', nRow).css("text-align", "left");
                $('td:eq(8)', nRow).css("text-align", "center");
                return nRow;
            },
        });

    });
</script>

<?= $this->endSection(); ?>