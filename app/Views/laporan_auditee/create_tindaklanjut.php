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
                            <?= input_number($field_name = 'nilai_rekomendasi', $label = 'Nilai Rekomendasi', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                            <?= input_number($field_name = 'nilai_akhir_rekomendasi', $label = 'Nilai Akhir Rekomendasi', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                            <?= input_number($field_name = 'nilai_sisa_rekomendasi', $label = 'Nilai Sisa Rekomendasi', $value = '', $required = true, $readonly = false, $disabled = false); ?>
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

<?= $this->endSection(); ?>