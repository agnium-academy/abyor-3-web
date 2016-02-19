# abyor-3-web

* Kelompok Abyor Web 3
* Nama Projek: Web Sistem Informasi akademik Sekolah 
* Versi : Beta
* Tanggal Pembuatan : 18 Februari 2016
* Tim: Iqbal, Dhany, Kiki, Trio

# Tujuan

1. Memberikan informasi daftar siswa, daftar guru, daftar mata pelajaran, dan  daftar nilai.
2. Menyederhanakan dan mempermudah administrasi akademik sekolah
3. Mempercepat pelayanan dan pengolahan administrasi akademik sekolah

# User

Pada umumnya user dari sistem informasi akademik sekolah ini adalah Guru TU,
untuk lebih spesifik user yang dimaksud adalah Operator Komputer, dimana pada umumnya tugas dari Operator Komputer Tata Usaha sekolah diantaranya adalah :
* Operator komputer
* Bertanggungjawab  file dan data komputer
* Membantu pengolahan/penyusunan data sekolah
* Membantu pengelolaan/penyusunan data siswa
* Membantu dan melaksanakan tugas lain yang relevan yang diberikan atasan langsung

# Rancangan Web Sistem Informasi Akademik Sekolah 

* Halaman Login
  Pada halaman ini user akan melakukan login untuk masuk menuju halaman utama

* Halaman Utama
  Pada halaman ini menampilkan informasi atau berita berupa teks yang berisi informasi sekolah secara umum, prestasi yang dicapai dan juga visi misi sekolah.
  Konten yang terdapat pada halaman ini antara lain:

	1. Home (Halaman ini berisi Profil, Visi, Misi, dan Prestasi Sekolah)
	2. Daftar User (Menampilkan Daftar User dan memuat fungsi CRUD untuk user)
	3. Daftar Siswa (Menampilkan Daftar Siswa dan memuat fungsi CRUD untuk Siswa)
	4. Daftar Guru (Menampilkan Daftar Guru dan memuat fungsi CRUD untuk Guru)
	5. Daftar Mata Pelajaran (Menampilkan Daftar Mata Pelajaran dan memuat fungsi CRUD untuk Mata Pelajaran)
	6. Daftar Nilai (Menampilkan Daftar Nilai dan memuat fungsi CRUD untuk Nilai)
	7. Logout

* Halaman CRUD
  Selain melihat informasi akademik sekolah, user dapat melakukan aktifitas menambah, merubah dan menghapus data pada setiap konten yang disediakan.
  Konten-konten yang dimaksud adalah daftar user, daftar siswa, daftar guru, daftar Mata Pelajaran, dan daftar nilai.
  Maka dari itu disediakan halaman tersendiri untuk membantu user melakukan aktifitas menambah, merubah dan menghapus data pada konten-konten tersebut.

#Kebutuhan untuk aplikasi
1. OS Windows XP/7/8/Server 2003/Server 2008/Server 2012
2. xampp versi 1.8.1 atau diatasnya.
3. Apache versi 2.4 atau diatasnya
4. mysql versi 5.6.21 atau mariaDb versi 10 
  
# Prasyarat untuk menjalankan aplikasi
1. Instal aplikasi XAMPP atau WAMPP
2. Jalankan service apache 2.4 dan mysql server
  
# Cara Menjalankan Aplikasi
1. Pertama copy folder abyor3 ke dalam folder xampp => htdocs
2. Kemudian import database abyordb.sql ke mysql server
3. Dan yang terkhir jalankan Aplikasi dibrowser dengan perintah https://localhost/abyor3
