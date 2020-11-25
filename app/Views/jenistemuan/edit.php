<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
    <div class="span6">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe023;"></span><?= $title; ?>
                </div>
            </div>
            <div class="widget-body">
                <?php if (session()->getFlashData('messages')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashData('messages') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url('jenistemuan/update/' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>

                    <?= input_text($field_name = 'kode', $label = 'Kode', $value = $data->kode, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'deskripsi', $label = 'Deskripsi', $value = $data->deskripsi, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_select($field_name = 'id_parent', $label = 'Parent', $jenis_temuan_options, $selected = $data->id_parent, $required = false, $disabled = ''); ?>
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