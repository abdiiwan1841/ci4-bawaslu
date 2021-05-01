TRUNCATE TABLE incoming;
TRUNCATE TABLE incoming_detail;
TRUNCATE TABLE order_detail;
TRUNCATE TABLE orders;
TRUNCATE TABLE retur_in;
TRUNCATE TABLE retur_in_detail;
TRUNCATE TABLE retur_out;
TRUNCATE TABLE retur_out_detail;
TRUNCATE TABLE stock;
TRUNCATE TABLE transfer;
TRUNCATE TABLE transfer_detail;


SELECT
    a.no_laporan,
    a.nama_laporan,
    b.memo_temuan,
    b.id AS jumlah_temuan,
    b.nilai_temuan,
    d.memo_rekomendasi,
    d.id AS jumlah_rekomendasi,
    d.nilai_rekomendasi,
    '' AS jumlah_sesuai_rekomendasi,
    (
        SELECT SUM(e.nilai_terverifikasi)
        FROM tindak_lanjut e 
        WHERE e.id_rekomendasi=d.id
    ) AS nilai_sesuai_rekomendasi,
    '' AS jumlah_belum_sesuai_rekomendasi,
    (
        (
            SELECT SUM(f.nilai_tindak_lanjut)
            FROM tindak_lanjut f 
            WHERE f.id_rekomendasi=d.id
        )
        -
        (
            SELECT SUM(g.nilai_terverifikasi)
            FROM tindak_lanjut g 
            WHERE g.id_rekomendasi=d.id
        )
    ) AS nilai_yang_belum_sesuai_rekomendasi_dan_dalam_proses_tindak_lanjut,
    '' AS jumlah_belum_ditindaklanjuti,
    (
        d.nilai_rekomendasi
        -
        (
            SELECT SUM(h.nilai_tindak_lanjut)
            FROM tindak_lanjut h 
            WHERE h.id_rekomendasi=d.id
        )
    ) AS nilai_belum_ditindaklanjuti,
    '' AS jumlah_tidak_dapat_ditindaklanjuti,
    (
        IF(d.status='TIDAK_DAPAT_DI_TL',d.nilai_rekomendasi,0)
    ) AS nilai_tidak_dapat_ditindaklanjuti
FROM laporan a 
JOIN temuan b ON b.id_laporan=a.id 
JOIN sebab c ON c.id_temuan=b.id 
JOIN rekomendasi d ON d.id_sebab=c.id 
WHERE a.id_satuan_kerja='a4acf9a8-a709-ac81-e513-2ad247d0e638'
