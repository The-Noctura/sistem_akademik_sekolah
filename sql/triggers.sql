-- =============================================
-- TRIGGERS
-- =============================================

-- Trigger: trg_rekap_nilai_insert
-- AFTER INSERT ON nilai
-- Otomatis update/insert rekap_nilai saat nilai baru ditambahkan
DROP TRIGGER IF EXISTS `trg_rekap_nilai_insert`;
DELIMITER $$
CREATE TRIGGER `trg_rekap_nilai_insert`
AFTER INSERT ON `nilai`
FOR EACH ROW
BEGIN
    DECLARE v_semester VARCHAR(20);
    DECLARE v_rata_rata DECIMAL(5,2);
    DECLARE v_nilai_akhir DECIMAL(5,2);
    DECLARE v_predikat VARCHAR(10);
    
    -- Ambil semester dari mengajar
    SELECT `semester` INTO v_semester
    FROM `mengajar`
    WHERE `id` = NEW.`mengajar_id`;
    
    -- Hitung rata-rata nilai untuk siswa & mengajar ini
    SELECT fn_rata_rata_nilai(NEW.`siswa_id`, NEW.`mengajar_id`) INTO v_rata_rata;
    
    -- Nilai akhir = rata-rata (bisa diperluang logika bobot nanti)
    SET v_nilai_akhir = v_rata_rata;
    
    -- Tentukan predikat
    IF v_nilai_akhir >= 85 THEN SET v_predikat = 'A';
    ELSEIF v_nilai_akhir >= 75 THEN SET v_predikat = 'B';
    ELSEIF v_nilai_akhir >= 65 THEN SET v_predikat = 'C';
    ELSEIF v_nilai_akhir >= 55 THEN SET v_predikat = 'D';
    ELSE SET v_predikat = 'E';
    END IF;
    
    -- Upsert ke rekap_nilai
    INSERT INTO `rekap_nilai` (
        `siswa_id`,
        `mengajar_id`,
        `semester`,
        `rata_rata`,
        `nilai_akhir`,
        `predikat`,
        `updated_at`
    ) VALUES (
        NEW.`siswa_id`,
        NEW.`mengajar_id`,
        v_semester,
        v_rata_rata,
        v_nilai_akhir,
        v_predikat,
        NOW()
    )
    ON DUPLICATE KEY UPDATE
        `rata_rata` = VALUES(`rata_rata`),
        `nilai_akhir` = VALUES(`nilai_akhir`),
        `predikat` = VALUES(`predikat`),
        `updated_at` = NOW();
END$$
DELIMITER ;

-- Trigger: trg_rekap_nilai_update
-- AFTER UPDATE ON nilai
-- Otomatis update rekap_nilai saat nilai diubah
-- PALING SERING LUPA DIBUAT - CEK MANUAL
DROP TRIGGER IF EXISTS `trg_rekap_nilai_update`;
DELIMITER $$
CREATE TRIGGER `trg_rekap_nilai_update`
AFTER UPDATE ON `nilai`
FOR EACH ROW
BEGIN
    DECLARE v_semester VARCHAR(20);
    DECLARE v_rata_rata DECIMAL(5,2);
    DECLARE v_nilai_akhir DECIMAL(5,2);
    DECLARE v_predikat VARCHAR(10);
    
    -- Ambil semester dari mengajar
    SELECT `semester` INTO v_semester
    FROM `mengajar`
    WHERE `id` = NEW.`mengajar_id`;
    
    -- Hitung rata-rata nilai untuk siswa & mengajar ini
    SELECT fn_rata_rata_nilai(NEW.`siswa_id`, NEW.`mengajar_id`) INTO v_rata_rata;
    
    -- Nilai akhir = rata-rata
    SET v_nilai_akhir = v_rata_rata;
    
    -- Tentukan predikat
    IF v_nilai_akhir >= 85 THEN SET v_predikat = 'A';
    ELSEIF v_nilai_akhir >= 75 THEN SET v_predikat = 'B';
    ELSEIF v_nilai_akhir >= 65 THEN SET v_predikat = 'C';
    ELSEIF v_nilai_akhir >= 55 THEN SET v_predikat = 'D';
    ELSE SET v_predikat = 'E';
    END IF;
    
    -- Update rekap_nilai
    UPDATE `rekap_nilai`
    SET `rata_rata` = v_rata_rata,
        `nilai_akhir` = v_nilai_akhir,
        `predikat` = v_predikat,
        `updated_at` = NOW()
    WHERE `siswa_id` = NEW.`siswa_id`
      AND `mengajar_id` = NEW.`mengajar_id`
      AND `semester` = v_semester;
END$$
DELIMITER ;

-- Trigger: trg_log_nilai_update
-- AFTER UPDATE ON nilai
-- Catat audit trail ke log_perubahan saat nilai diubah
DROP TRIGGER IF EXISTS `trg_log_nilai_update`;
DELIMITER $$
CREATE TRIGGER `trg_log_nilai_update`
AFTER UPDATE ON `nilai`
FOR EACH ROW
BEGIN
    IF OLD.`nilai` <> NEW.`nilai` OR OLD.`jenis` <> NEW.`jenis` THEN
        INSERT INTO `log_perubahan` (
            `tabel`,
            `record_id`,
            `aksi`,
            `user_id`,
            `waktu`,
            `data_lama`,
            `data_baru`
        ) VALUES (
            'nilai',
            NEW.`id`,
            'UPDATE',
            NEW.`diinput_oleh`,
            NOW(),
            JSON_OBJECT(
                'siswa_id', OLD.`siswa_id`,
                'mengajar_id', OLD.`mengajar_id`,
                'jenis', OLD.`jenis`,
                'nilai', OLD.`nilai`,
                'tanggal_input', OLD.`tanggal_input`
            ),
            JSON_OBJECT(
                'siswa_id', NEW.`siswa_id`,
                'mengajar_id', NEW.`mengajar_id`,
                'jenis', NEW.`jenis`,
                'nilai', NEW.`nilai`,
                'tanggal_input', NEW.`tanggal_input`
            )
        );
    END IF;
END$$
DELIMITER ;

-- Trigger: trg_absensi_insert
-- AFTER INSERT ON absensi
-- Otomatis panggil sp_rekap_absensi saat absensi baru ditambahkan
DROP TRIGGER IF EXISTS `trg_absensi_insert`;
DELIMITER $$
CREATE TRIGGER `trg_absensi_insert`
AFTER INSERT ON `absensi`
FOR EACH ROW
BEGIN
    CALL sp_rekap_absensi(NEW.`siswa_id`, NEW.`mengajar_id`);
END$$
DELIMITER ;