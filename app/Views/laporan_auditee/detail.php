<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>
<style>
    .imp-messages li .message-wrapper,
    .tasks li .message-wrapper {
        margin-left: 20px !important;
    }
</style>

<div class="row-fluid">
    <div class="span12">

        <div class="alert alert-block alert-info fade in no-margin">
            <h4 class="alert-heading">
                <?= $data->no_laporan; ?>
            </h4>
            <br>
            <p>
                <?= $data->nama_laporan; ?>
            </p>
        </div>
        <br>
        <div class="widget no-margin">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0f7;"></span> Temuan
                </div>
            </div>
            <div class="widget-body">
                <ul class="imp-messages">
                    <?php $i = 1; ?>
                    <?php foreach ($data->temuan as $r) : ?>
                        <?php $i++; ?>
                        <li>
                            <div class="message-wrapper">
                                <h4 class="message-heading"><?= $r->memo_temuan; ?></h4>
                                <ul style="padding-left: 30px">
                                    <h5 class="message-heading">Sebab</h5>
                                    <li>
                                        <?php $no = 1; ?>
                                        <?php foreach ($r->sebab as $d) : ?>
                                            <?php $no++; ?>
                                            <blockquote class="message">
                                                <?= $d->memo_sebab; ?>
                                            </blockquote>
                                            <br>
                                            <ul style="padding-left: 30px">
                                                <h5 class="message-heading">Rekomendasi</h5>
                                                <li>
                                                    <?php $n = 1; ?>
                                                    <?php foreach ($d->rekomendasi as $e) : ?>
                                                        <?php $n++; ?>
                                                        <blockquote class="message">
                                                            <?= $e->memo_rekomendasi; ?>
                                                            <p class="url">
                                                                <span class="fs1 text-info" aria-hidden="true" data-icon="&#xe132;"></span>
                                                                <a href="<?= base_url('laporanauditee/tindaklanjut/' . $e->id) ?>">Riwayat Tindak Lanjut</a>
                                                            </p>
                                                        </blockquote>
                                                        <br>
                                                    <?php endforeach; ?>
                                                </li>
                                                <?php if ($n == 1) : ?>
                                                    <blockquote class="message">
                                                        <span style="color:red;">Belum ada rekomendasi</span>
                                                    </blockquote>
                                                <?php endif; ?>
                                            </ul>
                                        <?php endforeach; ?>
                                        <?php if ($no == 1) : ?>
                                            <blockquote class="message">
                                                <span style="color:red;">Belum ada sebab</span>
                                            </blockquote>
                                        <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    <?php endforeach; ?>
                    <?php if ($i == 1) : ?>
                        <blockquote class="message">
                            <span style="color:red;">Belum ada temuan</span>
                        </blockquote>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="form-actions no-margin">

                <a href="<?= base_url('laporanauditee') ?>" class="btn btn-default">Kembali</a>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>