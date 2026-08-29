-- =============================================
-- STORED PROCEDURES
-- =============================================

-- Procedure: sp_input_nilai_kelas
-- Input/update nilai siswa per kelas (mengajar_id)
-- Dipakai oleh Guru\NilaiController::store()
-- Menggunakan ON DUPLICATE KEY UPDATE pada unique key (siswa_id, mengajar_id, jenis)
DROP PROCEDURE IF EXISTS `sp_input_nilai_kelas`;
DELIMITER $$
CREATE PROCEDURE `sp_input_nilai_kelas`(
    IN p_mengajar_id BIGINT,
    IN p_jenis ENUM('tugas','uts','uas'),
    IN p_siswa_id BIGINT,
    IN p_nilai DECIMAL(5,2),
    IN p_diinput_oleh BIGINT
)
BEGIN
    INSERT INTO `nilai` (
        `siswa_id`,
        `mengajar_id`,
        `jenis`,
        `nilai`,
        `tanggal_input`,
        `diinput_oleh`,
        `created_at`,
        `updated_at`
    ) VALUES (
        p_siswa_id,
        p_mengajar_id,
        p_jenis,
        p_nilai,
        NOW(),
        p_diinput_oleh,
        NOW(),
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        `nilai` = VALUES(`nilai`),
        `tanggal_input` = VALUES(`tanggal_input`),
        `diinput_oleh` = VALUES(`diinput_oleh`),
        `updated_at` = NOW();
END$$
DELIMITER ;

-- Procedure: sp_rekap_absensi
-- Menghitung/perbarui rekap absensi per siswa per mengajar per semester
-- Dipanggil OTOMATIS oleh trigger trg_absensi_insert
-- JANGAN dipanggil manual dari backend
DROP PROCEDURE IF EXISTS `sp_rekap_absensi`;
DELIMITER $$
CREATE PROCEDURE `sp_rekap_absensi`(
    IN p_siswa_id BIGINT,
    IN p_mengajar_id BIGINT
)
BEGIN
    DECLARE v_semester VARCHAR(20);
    DECLARE v_total_hadir INT DEFAULT 0;
    DECLARE v_total_izin INT DEFAULT 0;
    DECLARE v_total_sakit INT DEFAULT 0;
    DECLARE v_total_alpa INT DEFAULT 0;
    DECLARE v_persentase_hadir DECIMAL(5,2) DEFAULT 0;
    
    -- Ambil semester dari tabel mengajar
    SELECT `semester` INTO v_semester
    FROM `mengajar`
    WHERE `id` = p_mengajar_id;
    
    -- Hitung total per status
    SELECT 
        SUM(CASE WHEN `status` = 'hadir' THEN 1 ELSE 0 END),
        SUM(CASE WHEN `status` = 'izin' THEN 1 ELSE 0 END),
        SUM(CASE WHEN `status` = 'sakit' THEN 1 ELSE 0 END),
        SUM(CASE WHEN `status` = 'alpa' THEN 1 ELSE 0 END)
    INTO v_total_hadir, v_total_izin, v_total_sakit, v_total_alpa
    FROM `absensi`
    WHERE `siswa_id` = p_siswa_id
      AND `mengajar_id` = p_mengajar_id;
    
    -- Hitung persentase hadir
    IF (v_total_hadir + v_total_izin + v_total_sakit + v_total_alpa) > 0 THEN
        SET v_persentase_hadir = (v_total_hadir / (v_total_hadir + v_total_izin + v_total_sakit + v_total_alpa)) * 100;
    END IF;
    
    -- Upsert ke rekap_absensi
    INSERT INTO `rekap_absensi` (
        `siswa_id`,
        `mengajar_id`,
        `semester`,
        `total_hadir`,
        `total_izin`,
        `total_sakit`,
        `total_alpa`,
        `persentase_hadir`,
        `updated_at`
    ) VALUES (
        p_siswa_id,
        p_mengajar_id,
        v_semester,
        v_total_hadir,
        v_total_izin,
        v_total_sakit,
        v_total_alpa,
        v_persentase_hadir,
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        `total_hadir` = VALUES(`total_hadir`),
        `total_izin` = VALUES(`total_izin`),
        `total_sakit` = VALUES(`total_sakit`),
        `total_alpa` = VALUES(`total_alpa`),
        `persentase_hadir` = VALUES(`persentase_hadir`),
        `updated_at` = NOW();
END$$
DELIMITER ;