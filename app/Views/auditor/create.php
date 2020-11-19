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
                <form action="<?= base_url('auditor/save'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>
                    <h5>Auditor Information</h5>
                    <hr>
                    <?= input_text($field_name = 'nip', $label = 'NIP', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'nama', $label = 'Nama', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_select($field_name = 'groups[]', $label = 'Group/Jabatan', $groups_options, $selected = '', $required = true, $disabled = ''); ?>
                    <h5>Login Information</h5>
                    <hr>
                    <?= input_text($field_name = 'username', $label = 'Username', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_password($field_name = 'password', $label = 'Password', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_password($field_name = 'repeat_password', $label = 'Repeat Password', $value = '', $required = true, $readonly = false, $disabled = false); ?>


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