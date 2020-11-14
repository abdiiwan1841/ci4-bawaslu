<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<link href="<?= '/assets/css/select2.css'; ?>" rel="stylesheet">

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
                <form action="<?= base_url('group/save'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>

                    <?= input_text($field_name = 'name', $label = 'Group Name', $value = '', $required = true, $readonly = false, $disabled = false); ?>

                    <?= input_textarea($field_name = 'description', $label = 'Description', $value = '', $required = false, $readonly = false, $disabled = false); ?>

                    <?= input_select($field_name = 'landing_page', $label = 'Landing Page', $permission_options, $selected = '', $required = true, $disabled = false); ?>

                    <?= input_multiselect('permissions[]', 'Permissions', $options = $permission_options, $selected = array(), $required = true, $readonly = false); ?>

                    <div class="form-actions no-margin">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?= '/assets/js/select2.js'; ?>"></script>
<script type="text/javascript">
    $(".select2-container").select2();
</script>

<?= $this->endSection(); ?>