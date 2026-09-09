-- =============================================
-- Migration: Tabel Log Aktivitas Admin PMB
-- Jalankan SQL ini di database PMB
-- =============================================

CREATE TABLE IF NOT EXISTS `pmb_log_aktivitas` (
    `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `admin_nik`   VARCHAR(30)     NOT NULL DEFAULT '',
    `admin_nama`  VARCHAR(150)    NOT NULL DEFAULT '',
    `modul`       VARCHAR(60)     NOT NULL DEFAULT '',
    `aksi`        VARCHAR(100)    NOT NULL DEFAULT '',
    `target_kode` VARCHAR(60)     NOT NULL DEFAULT '',
    `keterangan`  TEXT,
    `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_log_admin`      (`admin_nik`),
    INDEX `idx_log_modul`      (`modul`),
    INDEX `idx_log_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Daftarkan menu "Log Aktivitas" ke pmb_modul
-- agar bisa dikontrol via Group User & Privilege
-- =============================================
INSERT IGNORE INTO `pmb_modul` (`modul`, `menu`, `alias`, `MenuAktif`, `created_at`, `created_by`)
VALUES ('Tools', 'Log Aktivitas', 'log-aktivitas', 'Y', NOW(), 'system');

-- =============================================
-- Catatan:
-- Setelah menjalankan SQL ini, masuk ke menu
-- Tools > Group User > Edit Privilege
-- lalu centang "Log Aktivitas" untuk group 
-- yang boleh mengakses menu ini.
-- =============================================
