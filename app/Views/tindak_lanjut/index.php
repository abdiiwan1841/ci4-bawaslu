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
                <div id="wizard" class="bwizard clearfix">
                    <ol class="bwizard-steps clearfix clickable" role="tablist">
                        <li role="tab" aria-selected="true" class="active" style="z-index: 7;"><span class="label badge-inverse">1</span><a href="<?= base_url('laporan') ?>" class="hidden-phone">Satuan Kerja</a></li>
                        <li role="tab" aria-selected="true" class="active" style="z-index: 6;"><span class="label badge-inverse">2</span><a href="<?= base_url('laporan/list/' . session()->get('id_satuan_kerja')) ?>" class="hidden-phone">Laporan</a></li>
                        <li role="tab" aria-selected="true" class="active" style="z-index: 5;"><span class="label badge-inverse">3</span><a href="<?= base_url('temuan/index/' . session()->get('id_laporan')) ?>" class="hidden-phone">Temuan</a></li>
                        <li role="tab" aria-selected="false" class="active" style="z-index: 4;"><span class="label badge-inverse">4</span><a href="<?= base_url('sebab/index/' . session()->get('id_temuan')) ?>" class="hidden-phone">Sebab</a></li>
                        <li role="tab" aria-selected="false" class="active" style="z-index: 3;"><span class="label badge-inverse">5</span><a href="<?= base_url('rekomendasi/index/' . session()->get('id_sebab')) ?>" class="hidden-phone">Rekomendasi</a></li>
                        <li role="tab" aria-selected="true" class="active" style="z-index: 2;"><span class="label badge-inverse">6</span><a href="<?= base_url('tindaklanjut/index/' . session()->get('id_rekomendasi')) ?>" class="hidden-phone">Tindak Lanjut</a></li>
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
                                    <th>No.</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Nilai Tindak Lanjut</th>
                                    <th>Nilai Verifikasi Auditor</th>
                                    <th>Remark Auditor</th>
                                    <th>Remark Auditee</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" style="text-align:right;">Total</th>
                                    <th style="text-align:right;"><?= format_number($summary->total_nilai_tindak_lanjut, true); ?></th>
                                    <th style="text-align:right;"><?= format_number($summary->total_nilai_terverifikasi, true); ?></th>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:right;">Nilai Rekomendasi</th>
                                    <th style="text-align:right;">&nbsp;</th>
                                    <th style="text-align:right;"><?= format_number($summary->nilai_rekomendasi, true); ?></th>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align:right;">Sisa Nilai Rekomendasi</th>
                                    <th style="text-align:right;">&nbsp;</th>
                                    <th style="text-align:right;"><?= format_number($summary->sisa_nilai_rekomendasi, true); ?></th>
                                    <th colspan="3">&nbsp;</th>
                                </tr>
                            </tfoot>
                            <tbody>
                        </table>
                        <div class="form-actions no-margin">
                            <?php if (($summary->sisa_nilai_rekomendasi == 0) && (session()->get('ketua_tim') == session()->get('id_pegawai')) && ($summary->status != 'SESUAI')) : ?>
                                <a href="<?= base_url('rekomendasi/updateStatusRekomendasiSesuai/' . session()->get('id_rekomendasi')); ?>" onclick="return confirm('Yakin TL sudah sesuai rekomendasi ini ?');" class="btn btn-success">TL Sudah Sesuai Rekomendasi</a>
                            <?php endif; ?>

                            <?php if ($show_button_tidak_dapat_di_tl && (session()->get('ketua_tim') == session()->get('id_pegawai'))) : ?>
                                <a href="<?= base_url('rekomendasi/tidakDapatDiTL/' . session()->get('id_rekomendasi')); ?>" class="btn btn-danger">Tindak Dapat di TL</a>
                            <?php endif; ?>
                        </div>

                        <!--
                            -1-BELUM TL
                            -2-BELUM SESUAI
                            -3-SESUAI
                            -4-TIDAK DAPAT TL
                          -->

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
                <?php if (in_array('tindaklanjut/create', session()->get('user_permissions')) && session()->get('ketua_tim') == session()->get('id_pegawai')) : ?>
                    // {
                    //         text: 'Create New',
                    //         action: function(e, dt, node, config) {
                    //             window.location.href = "<?= base_url('/tindaklanjut/create/' . $id_rekomendasi); ?>";
                    //         }
                    //     }
                <?php endif; ?>
            ],
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
            ajax: "<?= base_url('tindaklanjut/datatables/' . $id_rekomendasi); ?>",
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = table.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                $('td:first', nRow).html(index);
                $('tr:eq(0) th').css("text-align", "center");
                $('td:eq(0)', nRow).css("text-align", "center");
                $('td:eq(1)', nRow).css("text-align", "left");
                $('td:eq(2)', nRow).css("text-align", "right");
                $('td:eq(3)', nRow).css("text-align", "right");
                $('td:eq(4)', nRow).css("text-align", "center");
                return nRow;
            },
        });

    });
</script>

<?= $this->endSection(); ?>