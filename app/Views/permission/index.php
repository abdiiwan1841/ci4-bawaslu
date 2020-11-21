<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/jquery.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/buttons.dataTables.min.css' ?>">
<link rel="stylesheet" type="text/css" href="<?= '/assets/datatables/css/select.dataTables.min.css' ?>">
<style>
    table.dataTable tr th.select-checkbox.selected::after {
        content: "✔";
        margin-top: -11px;
        margin-left: -4px;
        text-align: center;
        text-shadow: rgb(176, 190, 217) 1px 1px, rgb(176, 190, 217) -1px -1px, rgb(176, 190, 217) 1px -1px, rgb(176, 190, 217) -1px 1px;
    }
</style>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe14a;"></span> <?= $title; ?>
                </div>
            </div>
            <div class="widget-body">
                <?php if (session()->getFlashData('messages')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashData('messages') ?>
                    </div>
                <?php endif; ?>
                <table id="datatables" class="table table-condensed table-bordered no-margin">
                    <thead>
                        <tr>
                            <th style="width:5%" class="hidden-phone">

                            </th>
                            <th style="width:35%">
                                Permission Name
                            </th>
                            <th style="width:35%" class="hidden-phone">
                                Uri
                            </th>
                            <th style="width:20%">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= '/assets/datatables/js/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.buttons.min.js' ?>"></script>
<script type="text/javascript" src="<?= '/assets/datatables/js/dataTables.select.min.js' ?>"></script>
<script type="text/javascript">
    $(document).ready(function() {

        var table = $('#datatables').DataTable({
            dom: 'Bfrtip',
            buttons: [
                <?php if (in_array('permission/delete', session()->get('user_permissions'))) : ?> {
                        text: 'Delete Selected',
                        action: function(e, dt, node, config) {
                            deletedSelected();
                        }
                    }
                <?php endif; ?>
                <?php if (in_array('permission/create', session()->get('user_permissions'))) : ?>, {
                        text: 'Create New',
                        action: function(e, dt, node, config) {
                            window.location.href = "<?= base_url('/permission/create'); ?>";
                        }
                    }
                <?php endif; ?>
            ],
            processing: true,
            serverSide: true,
            order: [
                [1, "asc"]
            ],
            search: {
                caseInsensitive: false
            },
            ajax: "<?= base_url('permission/datatables'); ?>",
            columns: [{
                    data: "DT_RowId"
                },
                {
                    data: "name"
                },
                {
                    data: "uri"
                },
                {
                    data: "id"
                }
            ],
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }],
            stateSave: false,
            select: {
                // style: 'os',
                style: 'multi',
                selector: 'td:first-child'
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                var info = table.page.info();
                var page = info.page;
                var length = info.length;
                var index = (page * length + (iDisplayIndex + 1));
                // $('td:first', nRow).html(index);
                $('td:first', nRow).html('');
                $('td:eq(1)', nRow).css("text-align", "left");
                $('td:eq(2)', nRow).css("text-align", "left");
                $('td:eq(3)', nRow).css("text-align", "center");
                return nRow;
            },
        });

        table.on("click", "th.select-checkbox", function() {
            if ($("th.select-checkbox").hasClass("selected")) {
                table.rows().deselect();
                $("th.select-checkbox").removeClass("selected");
            } else {
                table.rows().select();
                $("th.select-checkbox").addClass("selected");
            }
        }).on("select deselect", function() {
            ("Some selection or deselection going on")
            if (table.rows({
                    selected: true
                }).count() !== table.rows().count()) {
                $("th.select-checkbox").removeClass("selected");
            } else {
                $("th.select-checkbox").addClass("selected");
            }
        });

        function deletedSelected() {

            var rows = table.rows({
                selected: true
            }).ids(true).toArray();

            console.log(rows);

            $.ajax({
                url: "<?= '/permission/deletedSelected' ?>",
                data: {
                    'id': JSON.stringify(rows)
                },
                type: "POST",
                dataType: 'json',
                cache: false,
                success: function(res) {
                    console.log(res);
                    if (res.status) {
                        $("th.select-checkbox").removeClass("selected");
                        table.ajax.reload();
                    } else {
                        alert(res.messages);
                    }
                }
            });

        };

    });
</script>

<?= $this->endSection(); ?>