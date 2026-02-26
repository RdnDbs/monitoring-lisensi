/* =====================================================
   DATABASE : MONITORING LISENSI
   AUTHOR   : siap pakai (PHP + MySQL)
   ===================================================== */

DROP DATABASE IF EXISTS monitoring_lisensi;
CREATE DATABASE monitoring_lisensi
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

USE monitoring_lisensi;

-- =====================================================
-- TABLE USERS (LOGIN SYSTEM)
-- =====================================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama VARCHAR(100),
  role ENUM('admin','viewer') DEFAULT 'admin',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password, nama, role) VALUES
('admin', MD5('admin123'), 'Administrator', 'admin');

-- =====================================================
-- TABLE LISENSI (INTI MONITORING)
-- =====================================================
CREATE TABLE lisensi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_layanan VARCHAR(150) NOT NULL,
  tanggal_mulai DATE NOT NULL,
  tanggal_berakhir DATE NOT NULL,
  keterangan TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================
-- INDEX (OPTIMASI QUERY DASHBOARD)
-- =====================================================
CREATE INDEX idx_lisensi_tgl_akhir ON lisensi (tanggal_berakhir);
CREATE INDEX idx_lisensi_nama ON lisensi (nama_layanan);

-- =====================================================
-- DATA CONTOH (BIAR DASHBOARD LANGSUNG HIDUP)
-- =====================================================
INSERT INTO lisensi (nama_layanan, tanggal_mulai, tanggal_berakhir, keterangan) VALUES
('PROXMOXC JAKARTA', '2026-01-01', '2026-10-24', 'Cluster Proxmox Jakarta'),
('MEDBAR 1', '2025-09-16', '2025-12-22', 'Media Bar'),
('MEDBAR 2', '2025-09-16', '2025-12-23', 'Media Bar'),
('MEDBAR 3', '2024-11-19', '2025-12-24', 'Media Bar'),
('MEDBAR 4', '2025-09-16', '2025-12-25', 'Media Bar'),
('MEDBAR 5', '2025-01-21', '2025-12-26', 'Media Bar'),
('MEDBAR 6', '2025-09-16', '2025-12-27', 'Media Bar'),
('MEDBAR 7', '2025-05-12', '2025-12-28', 'Media Bar'),
('MEDBAR 8', '2025-01-21', '2025-12-29', 'Media Bar'),
('FORTIGATE KTR-PUSAT', '2025-01-14', '2026-12-01', 'Firewall Kantor Pusat'),
('FORTIGATE DC-JKT', '2025-01-14', '2027-12-01', 'Firewall Data Center Jakarta');