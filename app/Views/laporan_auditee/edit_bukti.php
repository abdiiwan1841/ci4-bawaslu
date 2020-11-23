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

                <?php if (session()->getFlashData('messages')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashData('messages') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('laporanauditee/updatebukti/' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>
                    <?= input_text($field_name = 'no_bukti', $label = 'No. Bukti', $value = $data->no_bukti, $required = true, $readonly = true, $disabled = false); ?>
                    <?= input_text($field_name = 'nama_bukti', $label = 'Nama Bukti', $value = $data->nama_bukti, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_number($field_name = 'nilai_bukti', $label = 'Nilai Bukti', $value = $data->nilai_bukti, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_file($field_name = 'lampiran', $label = 'Lampiran', $file_name = $data->lampiran, $required = false, $readonly = false, $path = 'uploads', $tips = '*.jpg | .png |.pdf |.xls | .xlsx |.doc | .docx'); ?>
                    <?= input_hidden($field_name = 'old_lampiran', $value = $data->lampiran); ?>
                    <?= input_hidden($field_name = 'id_tindak_lanjut', $value = $data->id_tindak_lanjut); ?>
                    <div class="form-actions no-margin">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>