# Employee Task Management

## 📌 Arsitektur Solusi

### 🏗 Alur Data dari Backend ke Frontend
1. **Frontend (Vue)** mengirim request ke API Laravel.
2. **Backend (Laravel)** memproses request:
   - Menyimpan dan mengambil data dari database (MySQL).
   - Menghitung remunerasi berdasarkan pekerjaan dan jam kerja pegawai.
3. **Database** menyimpan data pegawai, pekerjaan, dan hubungan antar keduanya.
4. **Frontend menampilkan data** setelah menerima response dari API.

---

## 🎨 Penjelasan Desain

### 🔹 Pemilihan Pendekatan
- **RESTful API:** Memisahkan frontend dan backend untuk fleksibilitas dan skalabilitas.
- **Model Relasional:** Satu pekerjaan bisa memiliki banyak pegawai dengan alokasi jam kerja berbeda.
- **Resource API & Request Validation:** Memastikan data yang dikirimkan valid dan sesuai kebutuhan.

### 🔹 Integrasi Perhitungan Remunerasi
- Remunerasi dihitung per pegawai berdasarkan rumus:
  ```
  remuneration = (hours_spent * hourly_rate) - (additional_charges / banyaknya pegawai)
  ```
- Total remunerasi pekerjaan dihitung dengan:
  ```
  total_remuneration = SUM(remuneration)
  ```
- Data ini diintegrasikan langsung ke dalam response API.

---

## 🚀 Setup & Deploy

### 📦 Instalasi & Konfigurasi
#### 1️⃣ Clone Repository
```sh
git clone https://github.com/MRafliRamadhani28/task-manager.git
cd task-manager
```

#### 2️⃣ Install Dependencies Backend
```sh
composer install
cp .env.example .env
php artisan key:generate
```
#### 2️⃣ Install Dependencies Frontend
```sh
cd frontend
npm install
```

#### 3️⃣ Konfigurasi Database di `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

#### 4️⃣ Migrasi Database
```sh
php artisan migrate
```

#### 5️⃣ Jalankan Server
```sh
php artisan serve
```
```sh
npm run dev
```
Akses API di: `http://127.0.0.1:8000/api`

---

## 🔥 Tantangan & Solusi

### 🛑 Tantangan: Mengelola Select Option Pegawai
**Masalah:** Saat memilih pegawai untuk tugas, pegawai yang sudah dipilih harus dihilangkan dari opsi.
**Solusi:**
- Menggunakan `filter` di frontend untuk menghapus pegawai yang sudah dipilih dari daftar select.
- Data pegawai yang tersedia diperbarui setiap kali pengguna menambahkan pegawai baru ke tugas.

### 🛑 Tantangan: Mencegah Duplikasi Pegawai dalam Tugas
**Masalah:** Pegawai yang sama bisa ditambahkan berkali-kali dalam tugas.
**Solusi:** 
- Validasi di backend menggunakan `unique` di pivot table.
- Validasi frontend dengan menyembunyikan pegawai yang sudah dipilih dari dropdown menggunakan `filter`.

### 🛑 Tantangan: Perhitungan Remunerasi yang Akurat
**Masalah:** Perhitungan Remunerasi yang Akurat
**Solusi:**
- Menggunakan **Eloquent Mutator** untuk menghitung total langsung di model.
- Menyertakan perhitungan dalam response API agar bisa langsung digunakan oleh frontend.

---