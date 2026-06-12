-- Query untuk membuat tabel users
CREATE TABLE users (
    id_user SERIAL PRIMARY KEY,
    username VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) CHECK (role IN ('mahasiswa', 'satpam', 'admin')) NOT NULL
);

-- Query untuk membuat tabel laporan_kerusakan
CREATE TABLE laporan_kerusakan (
    id_laporan SERIAL PRIMARY KEY,
    id_user INT REFERENCES users(id_user) ON DELETE CASCADE,
    lokasi_gedung VARCHAR(255) NOT NULL,
    deskripsi_fasilitas TEXT NOT NULL,
    foto_kondisi VARCHAR(255),
    tingkat_urgensi VARCHAR(50),
    status_penanganan VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
