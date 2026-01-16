-- =====================================================
-- RunRace Database Schema
-- MySQL Database for Running Event Registration App
-- =====================================================

-- Create database
CREATE DATABASE IF NOT EXISTS runrace_db;
USE runrace_db;

-- =====================================================
-- Users Table
-- =====================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    photo_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- Events Table
-- =====================================================
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_event VARCHAR(200) NOT NULL,
    lokasi VARCHAR(300) NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    tanggal DATE NOT NULL,
    status ENUM('ongoing', 'upcoming', 'completed') DEFAULT 'upcoming',
    banner_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- Registrations Table
-- =====================================================
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (user_id, event_id)
);

-- =====================================================
-- News Table
-- =====================================================
CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(500) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- =====================================================
-- Seed Data
-- =====================================================

-- Admin user (password: admin123)
INSERT INTO users (name, email, password, role, photo_url) VALUES
('Admin RunRace', 'admin@runrace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'https://ui-avatars.com/api/?name=Admin&background=FF5722&color=fff'),
('John Doe', 'john@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'https://ui-avatars.com/api/?name=John+Doe&background=00BCD4&color=fff'),
('Jane Smith', 'jane@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'https://ui-avatars.com/api/?name=Jane+Smith&background=9C27B0&color=fff');

-- Events
INSERT INTO events (nama_event, lokasi, kategori, tanggal, status, banner_url) VALUES
('Jakarta Marathon 2026', 'Monas, Jakarta', '42K Marathon', '2026-03-15', 'upcoming', 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=800'),
('Bali Beach Run', 'Kuta Beach, Bali', '10K Fun Run', '2026-01-20', 'ongoing', 'https://images.unsplash.com/photo-1571008887538-b36bb32f4571?w=800'),
('Bandung Trail Run', 'Dago Pakar, Bandung', '21K Half Marathon', '2026-02-28', 'upcoming', 'https://images.unsplash.com/photo-1486218119243-13883505764c?w=800'),
('Surabaya City Run', 'Tugu Pahlawan, Surabaya', '5K Fun Run', '2025-12-01', 'completed', 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?w=800'),
('Yogyakarta Heritage Run', 'Malioboro, Yogyakarta', '10K', '2026-04-10', 'upcoming', 'https://images.unsplash.com/photo-1461896836934- voices8c06e4?w=800');

-- News
INSERT INTO news (title, content, image_url) VALUES
('Tips Persiapan Marathon untuk Pemula', 'Persiapan marathon membutuhkan latihan bertahap minimal 4 bulan sebelum hari H. Mulailah dengan jarak pendek dan tingkatkan secara bertahap.', 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=400'),
('Komunitas Lari Jakarta Gelar Latihan Bersama', 'Setiap akhir pekan, komunitas lari Jakarta mengadakan latihan bersama di area Car Free Day Sudirman. Bergabunglah!', 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?w=400'),
('Nutrisi Penting Sebelum dan Sesudah Lari', 'Konsumsi karbohidrat kompleks sebelum lari dan protein setelah lari untuk pemulihan otot yang optimal.', 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=400'),
('Event Lari Terbesar 2026 di Indonesia', 'Tahun 2026 akan ada lebih dari 50 event lari di Indonesia. Simak kalender lengkapnya di sini.', 'https://images.unsplash.com/photo-1513593771513-7b58b6c4af38?w=400');

-- Sample registrations
INSERT INTO registrations (user_id, event_id) VALUES
(2, 1),
(2, 2),
(3, 1),
(3, 3);
