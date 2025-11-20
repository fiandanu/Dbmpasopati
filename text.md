=============================================================
📋 REVISI SETTING ALAT UPT - TRACKING LOG
=============================================================
Project: DBMPasopati - Setting Alat Module
Started: [Tanggal Mulai]
Last Update: [Tanggal Update]

=============================================================
🧪 TESTING
=============================================================
⏸️ Testing akan dilakukan setelah development selesai

Filter & Search:
❌ Test filter per kolom
❌ Test date range filter
❌ Test kombinasi multiple filters
❌ Test clear all filters

Pagination:
❌ Test pagination (10, 15, 20, Semua)
❌ Test Previous/Next navigation
❌ Test pagination dengan filter aktif

Export:
❌ Test export CSV tanpa filter
❌ Test export CSV dengan filter
❌ Test export PDF tanpa filter
❌ Test export PDF dengan filter

CRUD Operations:
❌ Test Add data
❌ Test Edit data
❌ Test Delete data
❌ Test validation errors

Auto Functions:
❌ Test auto-calculate durasi hari
❌ Test validation tanggal_selesai >= tanggal_terlapor

=============================================================
➕ PENAMBAHAN FITUR BARU
=============================================================
🔄 [Search By Nama Kolom] - [ Data Bisa Didownload bertipe PDF dan CSV, Diterapkan di semua halaman kecuali di halaman add data Provider/Vpn dan Kendala/Pic]

=============================================================
🐛 ERROR FOUND
=============================================================

❌ [Tanggal] - [Deskripsi bug] - [Lokasi: Controller/View/Route]

❌ [Tanggal] - [Deskripsi bug] - [Lokasi: ]

=============================================================
📝 KODE NULL SAFETY
=============================================================

Kode yang perlu dirapihin lagi di bagian

- app\Http\Controllers\user\ponpes\vtren\VtrenController.php [di method exportPonpesCsv $rows]

Kode yang perlu di cek ulang di Mclient UPT


CASE SENSITIF NAMA WILAYAH

data@data:/var/www/Dbmpasopati/app/Models/user$ cd namaWilayah
data@data:/var/www/Dbmpasopati/app/Models/user/namaWilayah$ ls
namaWilayah.php
data@data:/var/www/Dbmpasopati/app/Models/user/namaWilayah$

NAMA WILAYAH MENGGUNAKAN HURUF BESAR DIAWAL
-NamaWilayah
-NamaWilayah.php

CASE SENSITIF MIKROTIK CONTROLLER

data@data:/var/www/Dbmpasopati/app/Http/Controllers/tutorial/upt$ ls
MikrotikController.php ServerController.php
RegullerController.php VpasController.php

data@data:/var/www/Dbmpasopati/app/Http/Controllers/tutorial/upt$ mv MikrotikController.php mikrotikController.php
mv: cannot move 'MikrotikController.php' to 'mikrotikController.php': Permission denied

data@data:/var/www/Dbmpasopati/app/Http/Controllers/tutorial/upt$ sudo mv Mi
krotikController.php mikrotikController.php

data@data:/var/www/Dbmpasopati/app/Http/Controllers/tutorial/upt$ htop
data@data:/var/www/Dbmpasopati/app/Http/Controllers/tutorial/upt$ ls
mikrotikController.php ServerController.php
RegullerController.php VpasController.php

TUTORIAL MIKROTIK CONTROLLER MENGGUNAKAN HURUF BESAR DIAWAL, HARUSNYA HURUF KECIL

- mikrotikController.php

PADA DATABASE SEEDER

data@data:/var/www/Dbmpasopati/database/factories$ ls
DataOpsionalPonpesFactory.php PonpesFactory.php upt
DataOpsionalUptFactory.php providers user
mclient tutorial UserFactory.php
data@data:/var/www/Dbmpasopati/database/factories$ mv upt Upt
mv: cannot move 'upt' to 'Upt': Permission denied
data@data:/var/www/Dbmpasopati/database/factories$ sudo mv upt Upt
[sudo] password for data:
data@data:/var/www/Dbmpasopati/database/factories$ ls
DataOpsionalPonpesFactory.php PonpesFactory.php Upt
DataOpsionalUptFactory.php providers user
mclient tutorial UserFactory.php

NAMA UPT SEBELUMYA KECIL

- upt menjadi Upt

HALAMAN GRAFIK UPT DAN PONPES DIBUAT CETAK PDF

DATA YANG NGAMBIL DARI SERVER ITU UPT DAN PONPES YAITU DARI NAMA UPT DAN KANWIL DAN NAMA WILAYAH

=============================================================
📝 CATATAN REVISI
=============================================================

- list data UPT VPASREG itu dijadiin 1 aja di datamanajemen


- HALAMAN DATA MANAJEMEN TAMBAHIN SEARCH BY TANGGAL
- DI TUTORIAL UPT DAN PONPES COUNTNYA DIUBAT MENGIKUTI DATA YANG ADA


- KHUSUS ROLE USER ✅
  Super Admin,Teknisi, Marketing

kolom di role

- username
- nama
- password
- keterangan
- status kalo aktif role bisa login, dan sebaliknya
- level akses / rolenya
- history login
- tanggal pembuatan

- Rule Role Marketing
- Marketing hanya bisa melihat dan edit
  -(tidak bisa menghapus data)
- di halaman data manajemen di hide untuk role ini
- hanya bisa tambah edit di Halaman Db pks dan spp saja

- Rule Teknisi
- (tidak bisa menghapus data)
- bisa membuat data di semua halaman Edit Dan tambah

catatan
yang bisa membuat user dan role itu hanya super admin

- dibuat kolom untuk di halaman home usernya itu
  terakhir login dan tanggal pembuatan user

- Di semua keterangan dibuat limit 100 kata

🔄 menampilkan keseluruhan total data di bawah perncarian kartu gsm vpas vtren
(Pembenaran Layout)

- di mclient dashboard di munculkan data yang paling awal itu yang paling lama belum selesai dan yang baru dipaling bawah
  yang sudah selesai urutannya dipaling bawah
    -

<!-- - menu user di limit 1000 data -->

- Di Halaman Db Upt Dan Ponpes Dibuat Download PDF saja Dashboard Topnya saja

=============================================================
✅ REVISI SELESAI
=============================================================

-- Check Ulang
11/14/2025

-- Komplain Vpas Dibuat jumlah presentase dari jenis kendala dibuat perbulan, intinya diambil dari jenis kendala ✅
- di catatan kartu vpas nama Upt masih hilang di Pdfnya pas download ✅
- terpakai disebelah whatsaap terpakai, pic dibuat ke paling kanan aja ✅
- tanggal disebelum nama Upt ✅
- Grafik Upt dan Grafik Ponpes Dibuat halaman baru ✅
- Di pencatatan kartu Vpas di munculin total berdasarkan
  hari, bulan, tahun, ✅

- Db Upt di munculin status Pks yang upload, Status Spp juga , Vpas/reguler dimunculin status wartel belum aktif dan aktif jumlahnya ✅

- di Db Upt di pks Nama Ponpes Diubah Menjadi Nama UPT ✅
- di bagian Pencatatan Kartu Vpas di buat jumlah totalya otomatis, dihitung dari kolom kartu baru, kartu bekas, kartu goip otomatis di jumlah otomatis ✅
- kartu terpakai diubah ke jumlah kartu terpakai
- total kartu dibuat perbulan grafiknya ✅
- kartu baru sampe wa terpakai dibuat perhari ✅
  dibuat line chart / bar chart disetiap bulan otomatis ke arsip dan masuk kebulan baru secara realtime
  grafik dibuat secara realtime berjalan terus, ✅

-- Check Ulang
20/10/2025
✅ Kanwil dan Nama Wilayah dibuat menjadi dropdown saja ------- done
✅ pada saat menentukan tanggal terlapor maka otomatis hari akan berjalan
✅ Nerapin Durasi Hari ke halaman yang pake ini di mclient Ponpes/UPT
✅ pemindahan layout pencarian by tanggal keseluruhan 100%
✅ perbaikan spasi bagian add data di user ponpes
✅ Di kolom Tanggal menjadi 1 flex column ----- done
✅ provider kendala sama kanwil/nama wilayah dibuat export public all data Csv dan PDF
✅ sidebar untuk memilih kategori tidak ketutup
✅ Di Page DB UPT Dan Ponpes di Pks dan spp ditambahkan data mirip seperti di Mclient reguller
✅ dibuatkan tombol edit penambahan tanggal kontrak dan tanggal selesai kontrak di bagian list data pks upt dan ponpes dan menghapus tipe vpas reg di bagian nama
✅ perbaikan pencarian status upload pdf di spp di bagian database upt dan ponpes

    di bagian add modal dan edit belum

✅ pic 1 menjadi pic dan pic 2 menjadi penerima di bagian pengiriman alat ponpes

- (Nama Db Belum Diubah)
  ✅ tanggal selesai di ganti menjadi tnaggal diterima di bagian pengiriman alat ponpes
  ✅ Tanggal Kontrak dan Jatuh Tempo

✅

- Jumlah SST Reguller Diambil dari jumlah extension
- Jumlah SST Vpas Diambil dari jumlah extension

✅

- di halaman DB UPT PONPES dibuat untuk halaman Export PDF dan CSV
- menu dibuat dropdown di mclient upt dan vpas
- yang dibuat dropdown itu menu,status, tipe
- di halaman pengiriman alat di kolom penerima diketik sendiri DI HALAMAN PONPES DAN UPT MCLIENT

=============================================================
📊 PROGRESS SUMMARY
=============================================================
Total Tasks: 0
✅ Completed: 0
🔄 In Progress: 0
❌ Todo: 0
Progress: 0%

=============================================================
📌 KETERANGAN EMOJI
=============================================================
❌ = Belum dikerjakan
✅ = Sudah selesai
🔄 = Sedang dikerjakan
⚠️ = Ada masalah/perlu perhatian
🐛 = Bug ditemukan
📝 = Catatan penting
➕ = Fitur tambahan baru
=============================================================
