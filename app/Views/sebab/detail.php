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
                <form action="#" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>

                    <?= input_text($field_name = 'position_code', $label = 'Position Code', $value = $data->position_code, $required = true, $readonly = true, $disabled = false); ?>
                    <?= input_text($field_name = 'position_name', $label = 'Position Name', $value = $data->position_name, $required = true, $readonly = true, $disabled = false); ?>
                    <?= input_textarea($field_name = 'description', $label = 'Description', $value = $data->description, $required = false, $readonly = true, $disabled = false); ?>

                    <div class="form-actions no-margin">
                        <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>