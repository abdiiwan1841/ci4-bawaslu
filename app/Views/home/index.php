<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe098;"></span> <?= $title; ?>
                </div>
            </div>
            <div class="widget-body" style="height: 300px;">

                <div class="x_title">
                    <h2><small></small></h2>

                    <div class="clearfix"></div>
                </div>
                <div class="x_content">

                    <div class="col-md-8 col-lg-8 col-sm-7">
                        <blockquote>
                            <h3>Welcome, <?= session()->get('name'); ?></h3>
                            <footer>SISTEM INFORMASI PEMANTAUAN TINDAK LANJUT - BAWASLU<cite title="Source Title"></cite>
                            </footer>
                        </blockquote>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-md-12">

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection(); ?>