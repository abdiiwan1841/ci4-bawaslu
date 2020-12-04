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
                <form action="<?= base_url('auditor/update/' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>

                    <?= input_hidden($field_name = 'id_user', $value = $data->id_user); ?>
                    <?= input_hidden($field_name = 'old_image', $value = $data->image); ?>

                    <h5>Informasi Auditor</h5>
                    <hr>
                    <?= input_text($field_name = 'nip', $label = 'NIP', $value = $data->nip, $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'nama', $label = 'Nama', $value = $data->nama, $required = true, $readonly = false, $disabled = false); ?>

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

        $('select#eselon2').append('<option value="">--Please Select--</option>');
        $("select#eselon2 option").remove();

        $('select#eselon3').append('<option value="">--Please Select--</option>');
        $("select#eselon3 option").remove();

        $("select#eselon1").change(function() {
            var idEselon1 = $(this).val();

            $("select#eselon2 option").remove();
            $.ajax({
                url: "<?= base_url('laporan/ajaxGetEselon2/'); ?>" + "/" + idEselon1,
                async: false,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('select#eselon2').append('<option value="">--Please Select--</option>');
                    $.each(response.data, function(i, r) {
                        var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                        if (r.id != undefined) {
                            $(newOption).appendTo("select#eselon2");
                        }
                    });
                    $("select#eselon2").trigger('change');
                }
            });
        });

        $("select#eselon2").change(function() {
            var idEselon2 = $(this).val();

            $("select#eselon3 option").remove();
            $.ajax({
                url: "<?= base_url('laporan/ajaxGetEselon3/'); ?>" + "/" + idEselon2,
                async: false,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('select#eselon3').append('<option value="">--Please Select--</option>');
                    $.each(response.data, function(i, r) {
                        var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                        if (r.id != undefined) {
                            $(newOption).appendTo("select#eselon3");
                        }
                    });

                }
            });
        });

        $("select#eselon1").trigger('change');

    });
</script>

<?= $this->endSection(); ?>