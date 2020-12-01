<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

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
                    <div class="widget-body">

                        <?php if (session()->getFlashData('messages')) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= session()->getFlashData('messages') ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?= base_url('laporanauditee/savetindaklanjut'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                            <?= csrf_field(); ?>
                            <?= input_text($field_name = 'no_tindak_lanjut', $label = 'No Tindak Lanjut', $value = $no_tindak_lanjut, $required = true, $readonly = true, $disabled = false); ?>
                            <?= input_number($field_name = 'nilai_rekomendasi', $label = 'Nilai Rekomendasi', $value = $data->nilai_rekomendasi, $required = true, $readonly = true, $disabled = false); ?>
                            <?= input_number($field_name = 'total_nilai_terverifikasi', $label = 'Total Nilai Terverifikasi', $value = $total_terverifikasi, $required = true, $readonly = true, $disabled = false); ?>
                            <?= input_number($field_name = 'nilai_tindak_lanjut', $label = 'Nilai Tindak Lanjut', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                            <?= input_number($field_name = 'nilai_sisa_rekomendasi', $label = 'Nilai Sisa Rekomendasi', $value = '', $required = true, $readonly = true, $disabled = false); ?>
                            <?= input_textarea($field_name = 'remark_auditee', $label = 'Remark Auditee', $value = '', $required = false, $readonly = false, $disabled = false); ?>
                            <?= input_hidden($field_name = 'id_rekomendasi', $value = $id_rekomendasi); ?>
                            <div class="form-actions no-margin">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#nilai_tindak_lanjut").keyup(function() {
            var nilai_tindak_lanjut = $(this).val();
            var nilai_rekomendasi = $("#nilai_rekomendasi").val();
            var total_nilai_terverifikasi = $("#total_nilai_terverifikasi").val();

            nilai_rekomendasi = untarkiman(nilai_rekomendasi);
            nilai_tindak_lanjut = untarkiman(nilai_tindak_lanjut);
            total_nilai_terverifikasi = untarkiman(total_nilai_terverifikasi);

            nilai_sisa_rekomendasi = nilai_rekomendasi - (total_nilai_terverifikasi + nilai_tindak_lanjut);
            $("#nilai_sisa_rekomendasi").val(nilai_sisa_rekomendasi.toLocaleString('en-US'));
        });
    });
</script>


<?= $this->endSection(); ?>