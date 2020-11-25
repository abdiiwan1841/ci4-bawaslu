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
                                        <table>
                                            <tr>
                                                <td>No.Temuan</td>
                                                <td>:</td>
                                                <td><?= $data->no_temuan; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Temuan</td>
                                                <td>:</td>
                                                <td><?= $data->jenis_temuan; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Memo Temuan</td>
                                                <td>:</td>
                                                <td><?= $data->memo_temuan; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nilai Temuan</td>
                                                <td>:</td>
                                                <td><?= format_number($data->nilai_temuan, true); ?></td>
                                            </tr>
                                        </table>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="message-wrapper">
                            <h4 class="message-heading">Sebab</h4>
                            <ul>
                                <li>
                                    <blockquote class="message">
                                        <table>
                                            <tr>
                                                <td>No.Sebab</td>
                                                <td>:</td>
                                                <td><?= $data->no_sebab; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Memo Sebab</td>
                                                <td>:</td>
                                                <td><?= $data->memo_sebab; ?></td>
                                            </tr>
                                        </table>
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
                                        <table>
                                            <tr>
                                                <td>No.Rekomendasi</td>
                                                <td>:</td>
                                                <td><?= $data->no_rekomendasi; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jenis Rekomendasi</td>
                                                <td>:</td>
                                                <td><?= $data->jenis_rekomendasi; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Memo Rekomendasi</td>
                                                <td>:</td>
                                                <td><?= $data->memo_rekomendasi; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nilai Rekomendasi</td>
                                                <td>:</td>
                                                <td><?= format_number($data->nilai_rekomendasi, true); ?></td>
                                            </tr>
                                        </table>
                                    </blockquote>
                                    <br>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>

                <h5>List Tindak Lanjut</h5>
                <hr />

                <a href="<?= base_url('laporanauditee/createtindaklanjut/' . $data->id) ?>" class="btn btn-info">Tambah Data</a>
                <a href="<?= base_url('laporanauditee/detail/' . $data->id_laporan) ?>" class="btn btn-default">Kembali</a>
                <div class="clearfix" style="margin-bottom: 5px;"></div>
                <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                    <thead>
                        <tr>
                            <th style="width:10%; text-align:center;">No.</th>
                            <th style="width:15%; text-align:center;">Tanggal</th>
                            <th style="width:20%; text-align:center;">Nilai Rekomendasi</th>
                            <th style="width:20%; text-align:center;">Nilai Sisa Rekomendasi</th>
                            <th style="width:20%; text-align:center;">Nilai Akhir Rekomendasi</th>
                            <th style="width:15%; text-align:center;">Action</th>
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
                                <td style="text-align:center;">
                                    <a href="<?= base_url('laporanauditee/edittindaklanjut/' . $r->id); ?>" class="btn btn-default">Edit</a>
                                    <a href="<?= base_url('laporanauditee/bukti/' . $r->id); ?>" class="btn btn-success">Bukti</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if ($no == 1) : ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Belum ada tindak lanjut</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>