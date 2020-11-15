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
                    <ol class="bwizard-steps clearfix clickable" role="tablist">
                        <li role="tab" aria-selected="true" class="active" style="z-index: 6;"><span class="label badge-inverse">1</span><a href="<?= base_url('laporan') ?>" class="hidden-phone">Satuan Kerja</a></li>
                        <li role="tab" aria-selected="false" style="z-index: 5;" class=""><span class="label">2</span>Laporan</li>
                        <li role="tab" aria-selected="false" style="z-index: 4;" class=""><span class="label">3</span>Temuan</li>
                        <li role="tab" aria-selected="false" style="z-index: 3;" class=""><span class="label">4</span>Rekomendasi</li>
                        <li role="tab" aria-selected="false" style="z-index: 2;" class=""><span class="label">5</span>Tindak Lanjut</li>
                        <li role="tab" aria-selected="false" style="z-index: 1;" class=""><span class="label">6</span>Bukti</li>
                    </ol>

                    <div class="well">
                        <div class="widget-body">

                            <?php if (session()->getFlashData('messages')) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= session()->getFlashData('messages') ?>
                                </div>
                            <?php endif; ?>
                            <form action="<?= base_url('laporan/list'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                                <?= csrf_field(); ?>
                                <?= input_select($field_name = 'provinsi', $label = 'Provinsi', $provinsi_options, $selected = '', $required = true, $disabled = ''); ?>
                                <?= input_select($field_name = 'kabupaten', $label = 'Kabupaten', [], $selected = '', $required = false, $disabled = ''); ?>
                                <div class="form-actions no-margin">
                                    <span id="submit" class="btn btn-primary">Tampilkan Laporan</span>
                                    <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('select#kabupaten').append('<option value="">--Please Select--</option>');
        $("select#kabupaten option").remove();

        $("select#provinsi").change(function() {
            var idProvinsi = $(this).val();

            $("select#kabupaten option").remove();
            $.ajax({
                url: "<?= base_url('laporan/ajaxGetKabupatenByProvinsiId/'); ?>" + "/" + idProvinsi,
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

        $("select#provinsi").trigger('change');

        $('#submit').click(function() {
            var idKabupaten = $('select#kabupaten').val();
            var idProvinsi = $("select#provinsi").val();

            var idWilayah = (idKabupaten != null && idKabupaten != "" && idKabupaten != undefined) ? idKabupaten : idProvinsi;
            window.location.href = "<?= base_url('/laporan/list'); ?>" + "/" + idWilayah;
        });
    });
</script>

<?= $this->endSection(); ?>