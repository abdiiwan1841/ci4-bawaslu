<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
    <div class="span12">

        <div class="widget no-margin">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0f7;"></span> Riwayat Tindak Lanjut
                </div>
            </div>
            <div class="widget-body">
                <ul class="imp-messages">
                    <li>
                        <div class="message-wrapper">
                            <h4 class="message-heading">Laporan</h4>
                            <ul>
                                <li>
                                    <blockquote class="message">
                                        No.Laporan : <?= $data->no_laporan; ?>
                                        <br />
                                        <?= $data->memo_temuan; ?>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="message-wrapper">
                            <h4 class="message-heading">Temuan</h4>
                            <ul>
                                <li>
                                    <blockquote class="message">
                                        No.Temuan : <?= $data->no_temuan; ?>
                                        <br />
                                        <?= $data->memo_temuan; ?>
                                        <br /><br />
                                        Nilai Temuan :
                                        <?= format_number($data->nilai_temuan, true); ?>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="message-wrapper">
                            <h4 class="message-heading">Rekomendasi</h4>
                            <ul>
                                <li>
                                    <blockquote class="message">
                                        No.Rekomendasi : <?= $data->no_rekomendasi; ?>
                                        <br />
                                        <?= $data->memo_rekomendasi; ?>
                                        <br /><br />
                                        Nilai Rekomendasi :
                                        <?= format_number($data->nilai_rekomendasi, true); ?>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>

                <h5>Tindak Lanjut</h5>
                <hr />

                <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                    <thead>
                        <tr>
                            <th style="width:10%">No.</th>
                            <th style="width:20%">Tanggal</th>
                            <th style="width:20%">Nilai Rekomendasi</th>
                            <th style="width:20%">Nilai Sisa Rekomendasi</th>
                            <th style="width:20%">Nilai Akhir Rekomendasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($data->tindak_lanjut as $r) : ?>
                            <tr>
                                <td><?= '#' . $no++; ?></td>
                                <td><?= $r->created_at; ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_rekomendasi, true); ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_sisa_rekomendasi, true); ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_akhir_rekomendasi, true); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>