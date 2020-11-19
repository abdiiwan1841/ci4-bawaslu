<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/jquery.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/buttons.dataTables.min.css' ?>">

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
                <form action="<?= base_url('satuankerja/save'); ?>" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">

                    <?= csrf_field(); ?>
                    <?= input_text($field_name = 'kode_satuan_kerja', $label = 'Kode Satuan Kerja', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <?= input_text($field_name = 'nama_satuan_kerja', $label = 'Nama Satuan Kerja', $value = '', $required = true, $readonly = false, $disabled = false); ?>
                    <h5>Wilayah</h5>
                    <hr>
                    <?= input_select($field_name = 'provinsi', $label = 'Provinsi', $provinsi_options, $selected = '', $required = true, $disabled = ''); ?>
                    <?= input_select($field_name = 'kabupaten', $label = 'Kabupaten', [], $selected = '', $required = false, $disabled = ''); ?>

                    <div class="control-group">
                        <label class="control-label" for="">Pimpinan<font color="red"> * </font></label>
                        <div class="controls controls-row">
                            <div class="input-append">
                                <input type="hidden" name="id_pimpinan" id="id_pimpinan" value="">
                                <input type="text" name="nama_pimpinan" id="nama_pimpinan" value="" class="input-large" autocomplete="off" placeholder="Nama Pimpinan">
                                <a href="#auditiModal" data-toggle="modal" class="add-on" id="search" style="cursor: pointer;">Search</a>
                            </div>
                            <?= $validation->hasError('nama_pimpinan') ? '<span class="help-inline ">' . $validation->getError('nama_pimpinan') . '</span>' : ''; ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>

                    <?= input_text($field_name = 'nip', $label = 'NIP Pimpinan', $value = '', $required = true, $readonly = true, $disabled = false); ?>
                    <?= input_text($field_name = 'jabatan', $label = 'Jabatan', $value = '', $required = true, $readonly = true, $disabled = false); ?>

                    <div class="form-actions no-margin">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn" onclick="window.history.back();">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal List Auditi -->
<div id="auditiModal" style="width: 1000px;margin-left: -500px;" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="formColor" method="POST" enctype="multipart/form-data" class="form-horizontal no-margin">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                ×
            </button>
            <h4 id="myModalLabel">
                List Auditi
            </h4>
        </div>
        <div class="modal-body">

            <table id="auditiDatatables" class="table table-condensed table-striped table-hover table-bordered pull-left" width="100%" ;>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>NIP</th>
                        <th>Nama </th>
                        <th>Jabatan</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">
                Close
            </button>
        </div>
    </form>
</div>

<script type="text/javascript" src="<?= '/assets/datatables/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.buttons.min.js' ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('select#kabupaten').append('<option value="">--Please Select--</option>');
        $("select#kabupaten option").remove();

        $("select#provinsi").change(function() {
            var idProvinsi = $(this).val();

            $("select#kabupaten option").remove();
            $.ajax({
                url: "<?= base_url('satuankerja/ajaxGetKabupatenByProvinsiId/'); ?>" + "/" + idProvinsi,
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

        var auditiDatatables = $('#auditiDatatables').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [
                [1, "asc"]
            ],
            "search": {
                "caseInsensitive": false
            },
            "ajax": "<?= base_url('/satuankerja/auditi_datatables'); ?>",
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = auditiDatatables.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                $('td:first', nRow).html(index);
                $('td:eq(1)', nRow).css("text-align", "left");
                $('td:eq(5)', nRow).css("text-align", "center");
                return nRow;
            },
        });
    });

    function displayResult(frm, id, nip, name, jabatan) {
        $("#id_user").val(id);
        $("#nip").val(nip);
        $("#nama_pimpinan").val(name);
        $("#jabatan").val(jabatan);
    }
</script>
<?= $this->endSection(); ?>