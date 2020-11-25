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
                        <li role="tab" aria-selected="true" class="active" style="z-index: 7;"><span class="label badge-inverse">1</span><a href="<?= base_url('laporan') ?>" class="hidden-phone">Satuan Kerja</a></li>
                        <li role="tab" aria-selected="false" style="z-index: 6;" class=""><span class="label">2</span>Laporan</li>
                        <li role="tab" aria-selected="false" style="z-index: 5;" class=""><span class="label">3</span>Temuan</li>
                        <li role="tab" aria-selected="false" style="z-index: 4;" class=""><span class="label">4</span>Sebab</li>
                        <li role="tab" aria-selected="false" style="z-index: 3;" class=""><span class="label">5</span>Rekomendasi</li>
                        <li role="tab" aria-selected="false" style="z-index: 2;" class=""><span class="label">6</span>Tindak Lanjut</li>
                        <li role="tab" aria-selected="false" style="z-index: 1;" class=""><span class="label">7</span>Bukti</li>
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
                                <?= input_select($field_name = 'eselon1', $label = 'Eselon 1', $eselon1_options, $selected = '', $required = true, $disabled = ''); ?>
                                <?= input_select($field_name = 'eselon2', $label = 'Eselon 2', [], $selected = '', $required = false, $disabled = ''); ?>
                                <?= input_select($field_name = 'eselon3', $label = 'Eselon 3', [], $selected = '', $required = false, $disabled = ''); ?>
                                <div class="form-actions no-margin">
                                    <button id="submit" class="btn btn-primary">Tampilkan Laporan</button>
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