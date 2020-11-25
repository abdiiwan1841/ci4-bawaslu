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
                        <li role="tab" aria-selected="true" class="active" style="z-index: 6;"><span class="label badge-inverse">2</span><a href="<?= base_url('laporan/list/' . session()->get('id_satuan_kerja')) ?>" class="hidden-phone">Laporan</a></li>
                        <li role="tab" aria-selected="true" class="active" style="z-index: 5;"><span class="label badge-inverse">3</span><a href="<?= base_url('temuan/index/' . session()->get('id_laporan')) ?>" class="hidden-phone">Temuan</a></li>
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
                            <form action="<?= base_url('temuan/update/' . $data->id); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                                <?= csrf_field(); ?>
                                <?= input_text($field_name = 'no_temuan', $label = 'No. Temuan', $value = $data->no_temuan, $required = true, $readonly = false, $disabled = false); ?>
                                <!-- no temuan autonumber/ increment -->
                                <?= input_textarea($field_name = 'memo_temuan', $label = 'Memo Temuan', $value = $data->memo_temuan, $required = true, $readonly = false, $disabled = false); ?>
                                <?= input_select($field_name = 'id_jenis_temuan1', $label = 'Jenis Temuan', $jenis_temuan_options, $selected = $data->id_jenis_temuan1, $required = true, $disabled = ''); ?>
                                <?= input_select($field_name = 'id_jenis_temuan2', $label = '&nbsp;', [], $selected = $data->id_jenis_temuan2, $required = false, $disabled = ''); ?>
                                <?= input_select($field_name = 'id_jenis_temuan3', $label = '&nbsp;', [], $selected = $data->id_jenis_temuan3, $required = false, $disabled = ''); ?>
                                <!-- temuan di buat select box jenis temuan, list nya nanati di kasih -->
                                <?= input_number($field_name = 'nilai_temuan', $label = 'Nilai Temuan', $value = $data->nilai_temuan, $required = true, $readonly = false, $disabled = false); ?>
                                <!-- nilai temuan bergantung jenis temuan -->
                                <?= input_hidden($field_name = 'id_laporan', $value = $data->id_laporan); ?>
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
</div>

<script type="text/javascript">
    $(document).ready(function() {


        $.ajax({
            url: "<?= base_url('temuan/ajaxGetJenisTemuan/' . $data->id_jenis_temuan1); ?>",
            async: false,
            type: "GET",
            dataType: 'json',
            success: function(response) {
                $('select#id_jenis_temuan2').append('<option value="">--Please Select--</option>');
                $.each(response.data, function(i, r) {
                    var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                    if (r.id != undefined) {
                        $(newOption).appendTo("select#id_jenis_temuan2");
                    }
                });
                $('select#id_jenis_temuan2').val('<?= $data->id_jenis_temuan2 ?>');
            }
        });

        $.ajax({
            url: "<?= base_url('temuan/ajaxGetJenisTemuan/' . $data->id_jenis_temuan2); ?>",
            async: false,
            type: "GET",
            dataType: 'json',
            success: function(response) {
                $('select#id_jenis_temuan3').append('<option value="">--Please Select--</option>');
                $.each(response.data, function(i, r) {
                    var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                    if (r.id != undefined) {
                        $(newOption).appendTo("select#id_jenis_temuan3");
                    }
                });
                $('select#id_jenis_temuan3').val('<?= $data->id_jenis_temuan3 ?>');
            }
        });

        $("select#id_jenis_temuan1").change(function() {
            var idJenisTemuan = $(this).val();

            $("select#id_jenis_temuan2 option").remove();
            $.ajax({
                url: "<?= base_url('temuan/ajaxGetJenisTemuan/'); ?>" + "/" + idJenisTemuan,
                async: false,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('select#id_jenis_temuan2').append('<option value="">--Please Select--</option>');
                    $.each(response.data, function(i, r) {
                        var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                        if (r.id != undefined) {
                            $(newOption).appendTo("select#id_jenis_temuan2");
                        }
                    });
                    // $("select#id_jenis_temuan2").trigger('change');
                }
            });
        });

        $("select#id_jenis_temuan2").change(function() {
            var idJenisTemuan2 = $(this).val();

            $("select#id_jenis_temuan3 option").remove();
            $.ajax({
                url: "<?= base_url('temuan/ajaxGetJenisTemuan/'); ?>" + "/" + idJenisTemuan2,
                async: false,
                type: "GET",
                dataType: 'json',
                success: function(response) {
                    $('select#id_jenis_temuan3').append('<option value="">--Please Select--</option>');
                    $.each(response.data, function(i, r) {
                        var newOption = "<option value='" + r.id + "'>" + r.nama + "</option>";
                        if (r.id != undefined) {
                            $(newOption).appendTo("select#id_jenis_temuan3");
                        }
                    });

                }
            });
        });

        // $("select#id_jenis_temuan1").trigger('change');

    });
</script>

<?= $this->endSection(); ?>