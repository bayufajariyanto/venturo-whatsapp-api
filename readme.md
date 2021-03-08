# Dokumentasi WhatsApp API

Hai, repository ini adalah implementasi untuk cek nomor yang terdaftar di WhatsApp menggunakan <a href="https://github.com/pedroslopez/whatsapp-web.js">whatsapp-web.js</a>

### Alat yang dibutuhkan

- Node.js <a href="https://nodejs.org/en/download/">download disini</a>
- Apache <a href="https://www.apachefriends.org/download.html">download disini</a>

### Bagaimana cara menggunakan?

- Clone atau download repository ini
- Pindah project ke dalam htdocs
- Jalankan apache pada xampp
- Masuk ke dalam folder projek
- Buka terminal/gitbash
- Jalankan `npm install` (hanya sekali install, jika pernah skip aja)
- Jalankan `npm start`
- Buka browser dan pergi ke alamat `http://localhost:3000`
- Scan "QR Code" nya
- Pastikan WhatsApp Web telah terhubung
- Klik new tab dan pergi ke alamat `http://localhost/venturo-whatsapp/cek-nomor.php`
- Jika sudah selesai buka gitbash tadi jangan langsung diclose klik `Ctrl+C` dulu terus `Y`
- Enjoy!

### Cek nomor menggunakan api

**Method:**

- `post`

**Endpoint:**

- `/cek-nomor`

**Paramater Body:**

- `nomor`: nomor telepon (gunakan 62 (kode negara tanpa +) atau 0 sebagai awalan nomor)