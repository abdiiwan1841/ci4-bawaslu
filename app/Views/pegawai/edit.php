<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>
<?php
// dd($validation);
?>
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
                <form action="<?= base_url('auditee/update/' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>

                    <?= input_hidden($field_name = 'id_user', $value = $data->id_user); ?>
                    <?= input_hidden($field_name = 'old_image', $value = $data->image); ?>

                    <h5>Informasi Auditee</h5>
                    <hr>
                    <?= input_text($field_name = 'nip', $label = 'NIP', $value = $data->nip, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'nama', $label = 'Nama', $value = $data->nama, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'jabatan', $label = 'Jabatan', $value = $data->jabatan, $required = true, $readonly = false, $disabled = false); ?>

                    <h5>Satuan Kerja</h5>
                    <hr>

                    <?= input_select($field_name = 'provinsi', $label = 'Provinsi', $provinsi_options, $selected = $data->id_provinsi, $required = true, $disabled = ''); ?>
                    <?= input_select($field_name = 'kabupaten', $label = 'Kabupaten', $kabupaten_options, $selected = $data->id_kabupaten, $required = false, $disabled = ''); ?>

                    <h5>Informasi Akun</h5>
                    <hr>

                    <?= input_text($field_name = 'username', $label = 'Username', $value = $data->username, $required = true, $readonly = true, $disabled = false); ?>

                    <?= input_password($field_name = 'password', $label = 'Password', $value = '', $required = false, $readonly = false, $disabled = false); ?>

                    <?= input_password($field_name = 'repeat_password', $label = 'Repeat Password', $value = '', $required = false, $readonly = false, $disabled = false); ?>

                    <?= input_text($field_name = 'email', $label = 'Email', $value = $data->email, $required = true, $readonly = false, $disabled = false); ?>

                    <?= input_image($field_name = 'image', $label = 'Image Profile', $file_name = $data->image, $required = false); ?>


                    <div class="form-actions no-margin">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $("select#provinsi").change(function() {
            var idProvinsi = $(this).val();

            $("select#kabupaten option").remove();
            $.ajax({
                url: "<?= base_url('auditee/ajaxGetKabupatenByProvinsiId/'); ?>" + "/" + idProvinsi,
                async: false,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('select#kabupaten').append('<option value="">--Please Select--</option>');
                    $.each(response.data, function(i, r) {
                        var newOption = "<option value='" + r.id + "'>" + r.nama_kabupaten + "</option>";
                        if (r.id != undefined) {
                            $(newOption).appendTo("select#kabupaten");
                        }
                    });

                    // $('select#kabupaten').selectpicker('refresh');
                }
            });
        });

        // $("select#provinsi").trigger('change');
    });
</script>

<?= $this->endSection(); ?>