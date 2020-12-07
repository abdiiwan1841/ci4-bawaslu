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
                                                <td valign="top">Memo Rekomendasi</td>
                                                <td valign="top">:</td>
                                                <td><?= $data->memo_rekomendasi; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nilai Rekomendasi</td>
                                                <td>:</td>
                                                <td><?= format_number($data->nilai_rekomendasi, true); ?></td>
                                            </tr>
                                            <tr>
                                                <td valign="top">Penanggung Jawab</td>
                                                <td valign="top">:</td>
                                                <td>
                                                    <?php if ($data->nama_penanggung_jawab != "") : ?>
                                                        <ul>
                                                            <?php $pj = explode(",", $data->nama_penanggung_jawab); ?>
                                                            <?php foreach ($pj as $j) : ?>
                                                                <?= '<li>' . $j . '</li>'; ?>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                </td>
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

                <?php if (session()->getFlashData('messages')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashData('messages') ?>
                    </div>
                <?php endif; ?>

                <?php if ($data->status != 'SESUAI') : ?>
                    <a href="<?= base_url('laporanauditee/createtindaklanjut/' . $data->id) ?>" class="btn btn-info">Tambah Data</a>
                <?php endif; ?>
                <a href="<?= base_url('laporanauditee/detail/' . $data->id_laporan) ?>" class="btn btn-default">Kembali</a>
                <div class="clearfix" style="margin-bottom: 5px;"></div>
                <table class="table table-condensed table-striped table-bordered table-hover no-margin">
                    <thead>
                        <tr>
                            <th style="width:10%; text-align:center;">No.</th>
                            <th style="width:10%; text-align:center;">Tanggal</th>
                            <th style="width:10%; text-align:center;">No.Tindal Lanjut</th>
                            <th style="width:10%; text-align:center;">Nilai Tindal Lanjut</th>
                            <th style="width:10%; text-align:center;">Nilai Terverifikasi</th>
                            <th style="width:20%; text-align:center;">Remark Auditee</th>
                            <th style="width:10%; text-align:center;">Remark Auditor</th>
                            <th style="width:15%; text-align:center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php $totalNilaiTidakLanjut = 0; ?>
                        <?php $totalNilaiTerverifikasi = 0; ?>
                        <?php foreach ($data->tindak_lanjut as $r) : ?>
                            <tr>
                                <td style="text-align:center;"><?= '#' . $no++; ?></td>
                                <td style="text-align:left;"><?= $r->created_at; ?></td>
                                <td style="text-align:left;"><?= $r->no_tindak_lanjut; ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_tindak_lanjut, true); ?></td>
                                <td style="text-align:right;"><?= format_number($r->nilai_terverifikasi, true); ?></td>
                                <td style="text-align:left;"><?= $r->remark_auditee; ?></td>
                                <td style="text-align:left;"><?= $r->remark_auditor; ?></td>
                                <td style="text-align:center;">
                                    <?php if (($r->nilai_terverifikasi == 0 || $r->nilai_terverifikasi == '')) : ?>
                                        <a href="<?= base_url('laporanauditee/edittindaklanjut/' . $r->id); ?>" class="btn btn-default">Edit</a>
                                    <?php endif; ?>
                                    <a href="<?= base_url('laporanauditee/bukti/' . $r->id); ?>" class="btn btn-success">Bukti</a>
                                </td>
                            </tr>
                            <?php $totalNilaiTidakLanjut += $r->nilai_tindak_lanjut; ?>
                            <?php $totalNilaiTerverifikasi += $r->nilai_terverifikasi; ?>
                        <?php endforeach; ?>
                        <?php if ($no == 1) : ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Belum ada tindak lanjut</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align:right;">Total</th>
                            <th style="text-align:right;"><?= format_number($totalNilaiTidakLanjut, true); ?></th>
                            <th style="text-align:right;"><?= format_number($totalNilaiTerverifikasi, true); ?></th>
                            <th colspan="3">&nbsp;</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align:right;">Nilai Rekomendasi</th>
                            <th style="text-align:right;">&nbsp;</th>
                            <th style="text-align:right;"><?= format_number($data->nilai_rekomendasi, true); ?></th>
                            <th colspan="3">&nbsp;</th>
                        </tr>
                        <tr>
                            <th colspan="3" style="text-align:right;">Sisa Nilai Rekomendasi</th>
                            <th style="text-align:right;">&nbsp;</th>
                            <th style="text-align:right;"><?= format_number($data->nilai_rekomendasi - $totalNilaiTerverifikasi, true); ?></th>
                            <th colspan="3">&nbsp;</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>