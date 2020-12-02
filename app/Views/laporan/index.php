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
                    <span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span> <?= $title; ?>
                </div>
            </div>
            <div class="widget-body">
                <ol class="bwizard-steps clearfix clickable" role="tablist">
                    <li role="tab" aria-selected="true" class="active" style="z-index: 7;"><span class="label badge-inverse">1</span><a href="<?= base_url('laporan') ?>" class="hidden-phone">Satuan Kerja</a></li>
                    <li role="tab" aria-selected="true" class="active" style="z-index: 6;"><span class="label badge-inverse">2</span><a href="<?= base_url('laporan/list/' . session()->get('id_wilayah')) ?>" class="hidden-phone">Laporan</a></li>
                    <li role="tab" aria-selected="false" style="z-index: 5;" class=""><span class="label">3</span>Temuan</li>
                    <li role="tab" aria-selected="false" style="z-index: 4;" class=""><span class="label">4</span>Sebab</li>
                    <li role="tab" aria-selected="false" style="z-index: 3;" class=""><span class="label">5</span>Rekomendasi</li>
                    <li role="tab" aria-selected="false" style="z-index: 2;" class=""><span class="label">6</span>Tindak Lanjut</li>
                    <li role="tab" aria-selected="false" style="z-index: 1;" class=""><span class="label">7</span>Bukti</li>
                </ol>

                <div class="well">

                    <?php if (session()->getFlashData('messages')) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= session()->getFlashData('messages') ?>
                        </div>
                    <?php endif; ?>
                    <table id="datatables" class="table table-condensed table-bordered no-margin">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>No Laporan</th>
                                <th>Tanggal Laporan</th>
                                <th>Nama Laporan</th>
                                <th>No Surat Tugas</th>
                                <th>Tanggal Surat Tugas</th>
                                <th>Unit Pelaksana</th>
                                <th>NIP Pimpinan</th>
                                <th>Pimpinan Satuan Kerja</th>
                                <th>Nama Satuan Kerja</th>
                                <th>Tahun Anggaran</th>
                                <th>Nilai Anggaran</th>
                                <th>Realisasi Anggaran</th>
                                <th>Audit Anggaran</th>
                                <th>Jenis Anggaran</th>
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
            buttons: [
                <?php if (in_array('laporan/create', session()->get('user_permissions'))) : ?> {
                        text: 'Create New',
                        action: function(e, dt, node, config) {
                            window.location.href = "<?= base_url('/laporan/create'); ?>";
                        }
                    }
                <?php endif; ?>, {
                    text: 'Report PDF',
                    action: function(e, dt, node, config) {
                        window.location.href = "<?= base_url('/laporan/report'); ?>";
                    }
                }
            ],
            processing: true,
            serverSide: true,
            responsive: true,
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 15
                },
                {
                    width: '100px',
                    targets: 15
                }
            ],
            order: [
                [1, "asc"]
            ],
            search: {
                "caseInsensitive": false
            },
            ajax: "<?= base_url('laporan/datatables'); ?>",
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = table.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                $('td:first', nRow).html(index);
                $('td:eq(1)', nRow).css("text-align", "left");
                $('td:eq(11)', nRow).css("text-align", "right");
                $('td:eq(12)', nRow).css("text-align", "right");
                $('td:eq(13)', nRow).css("text-align", "right");
                $('td:eq(15)', nRow).css("text-align", "center");
                return nRow;
            },
        });

    });
</script>

<?= $this->endSection(); ?>