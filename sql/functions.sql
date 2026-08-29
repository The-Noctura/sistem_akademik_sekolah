-- =============================================
-- FUNCTIONS
-- =============================================

-- Function: fn_rata_rata_nilai
-- Menghitung rata-rata nilai per siswa per mengajar
-- Dipakai oleh trigger nilai dan fallback tampilan
DROP FUNCTION IF EXISTS `fn_rata_rata_nilai`;
DELIMITER $$
CREATE FUNCTION `fn_rata_rata_nilai`(
    p_siswa_id BIGINT,
    p_mengajar_id BIGINT
) RETURNS DECIMAL(5,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_rata_rata DECIMAL(5,2);
    
    SELECT COALESCE(AVG(`nilai`), 0)
    INTO v_rata_rata
    FROM `nilai`
    WHERE `siswa_id` = p_siswa_id
      AND `mengajar_id` = p_mengajar_id;
    
    RETURN v_rata_rata;
END$$
DELIMITER ;

-- Function: fn_persentase_hadir
-- Menghitung persentase kehadiran per siswa per mengajar
-- Dipakai oleh trigger absensi dan fallback tampilan
DROP FUNCTION IF EXISTS `fn_persentase_hadir`;
DELIMITER $$
CREATE FUNCTION `fn_persentase_hadir`(
    p_siswa_id BIGINT,
    p_mengajar_id BIGINT
) RETURNS DECIMAL(5,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_total INT DEFAULT 0;
    DECLARE v_hadir INT DEFAULT 0;
    DECLARE v_persentase DECIMAL(5,2) DEFAULT 0;
    
    SELECT COUNT(*), SUM(CASE WHEN `status` = 'hadir' THEN 1 ELSE 0 END)
    INTO v_total, v_hadir
    FROM `absensi`
    WHERE `siswa_id` = p_siswa_id
      AND `mengajar_id` = p_mengajar_id;
    
    IF v_total > 0 THEN
        SET v_persentase = (v_hadir / v_total) * 100;
    END IF;
    
    RETURN v_persentase;
END$$
DELIMITER ;