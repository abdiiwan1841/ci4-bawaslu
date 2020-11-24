<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>
<!-- https://formbuilder.online/docs/ -->
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
                <div id="build-wrap"></div>

                <div class="form-actions no-margin">
                    <form action="<?= base_url('generatemodule/save') ?>" method="post">
                        <?= input_text('module', 'Module Name', '', true); ?>
                        <?= input_hidden('json_generated', ''); ?>
                        <button id="saveData" value="" class="btn btn-success"><span class="fa fa-floppy-o fa-right"></span> Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script type="text/javascript">
    jQuery(($) => {
        const fbEditor = document.getElementById("build-wrap");
        const formBuilder = $(fbEditor).formBuilder();

        document.getElementById("saveData").addEventListener("click", () => {
            document.getElementById('json_generated').value = formBuilder.actions.getData('json', true);
        });
    });
</script>

<?= $this->endSection(); ?>