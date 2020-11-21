<?= $this->extend('layout/backend_template'); ?>

<?= $this->section('backend_content'); ?>

<div class="row-fluid">
    <div class="span12">

        <div class="widget no-margin">
            <div class="widget-header">
                <div class="title">
                    <span class="fs1" aria-hidden="true" data-icon="&#xe0f7;"></span> <?= $title; ?>
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
                    <li>
                        <div class="message-wrapper">
                            <h4 class="message-heading">Tindak Lanjut</h4>
                            <ul>
                                <li>
                                    <blockquote class="message">
                                        <table>
                                            <tr>
                                                <td>Nilai Rekomendasi</td>
                                                <td>:</td>
                                                <td style="text-align:right;"><?= format_number($data->nilai_rekomendasi, true); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nilai Sisa Rekomendasi</td>
                                                <td>:</td>
                                                <td style="text-align:right;"><?= format_number($data->nilai_sisa_rekomendasi, true); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nilai Akhir Rekomendasi</td>
                                                <td>:</td>
                                                <td style="text-align:right;"><?= format_number($data->nilai_akhir_rekomendasi, true); ?></td>
                                            </tr>
                                        </table>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>

                <h5>List Bukti</h5>
                <hr />

                <a href="<?= base_url('laporanauditee/createbukti/' . $data->id) ?>" class="btn btn-info">Tambah Bukti</a>
                <a href="<?= base_url('laporanauditee/tindaklanjut/' . $data->id_rekomendasi) ?>" class="btn btn-default">Kembali</a>
                <div class="clearfix" style="margin-bottom: 5px;"></div>
                <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                    <thead>
                        <tr>
                            <th style="text-align:center; width:5%">No.</th>
                            <th style="text-align:center; width:15%">Tanggal Dibuat</th>
                            <th style="text-align:center; width:15%">No.Bukti</th>
                            <th style="text-align:center; width:20%">Nama Bukti</th>
                            <th style="text-align:center; width:15%">Nilai Bukti</th>
                            <th style="text-align:center; width:20%">Lampiran</th>
                            <th style="text-align:center; width:10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php foreach ($data->bukti as $r) : ?>
                            <tr>
                                <td><?= '#' . $no++; ?></td>
                                <td><?= $r->created_at; ?></td>
                                <td style="text-align:left;"><?= $r->no_bukti; ?></td>
                                <td style="text-align:left;"><?= $r->nama_bukti; ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_bukti, true); ?></td>
                                <td style="text-align:left;">
                                    <?php if ($r->lampiran) : ?>
                                        <p class="url">
                                            <a href="<?= base_url('/attachments/' . $r->lampiran); ?>" target="_blank" style="color:#3a86c8;">
                                                <span class="fs1 text-info" aria-hidden="true" data-icon="&#xe0c5;"></span>
                                                <?= $r->nama_bukti; ?>
                                            </a>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:center;">
                                    <a href="<?= base_url('laporanauditee/editbukti/' . $r->id); ?>" class="btn btn-default">Edit</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($no == 1) : ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Belum ada bukti</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>