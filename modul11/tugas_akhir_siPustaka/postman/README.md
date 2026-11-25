# Panduan Pengujian Postman - SiPustaka API

## Persiapan

1. Pastikan database `db_sipustaka2` sudah dibuat di MySQL.
2. Jalankan migrasi:

   ```sh
   php artisan migrate
   ```

3. Jalankan server:

   ```sh
   php artisan serve
   ```

4. Import file `SiPustaka.postman_collection.json` ke Postman.
5. Jalankan request **Register** sekali, lalu **Login**. Token akan otomatis
   tersimpan ke variabel koleksi `token`.

> Untuk request yang memakai form-data (tambah/ubah buku + cover), pilih file
> gambar melalui kolom `cover` di tab **Body > form-data** sebelum menekan Send.

## Urutan pengujian yang disarankan

1. **Register** - berhasil (201) dan token muncul.
2. **Login** - berhasil (200), token tersimpan otomatis.
3. **Daftar Buku** dan **Detail Buku** tanpa token - berhasil (200).
4. **Tambah Buku + Cover** dengan gambar jpg/png < 2 MB - berhasil (201).
5. **Buka Cover** - gambar tampil (200).
6. **Ubah Buku + Cover** dengan gambar baru - berhasil (200), cover lama
   terhapus otomatis.
7. **Validasi - Stok Huruf** - gagal (422).
8. **Validasi - Cover Terlalu Besar** (file > 2 MB) - gagal (422).
9. **Validasi - Format Cover Salah** (mis. PDF) - gagal (422).
10. **Tambah Peminjaman** - berhasil (201), status `Dipinjam`.
11. **Ubah Peminjaman** menjadi `Dikembalikan` - berhasil (200).
12. **Login sebagai user kedua**, lalu akses peminjaman milik user pertama -
    gagal (403).
13. **Hapus Buku** - berhasil (200), file cover ikut terhapus.
14. **Logout** - berhasil (200); token lama tidak bisa dipakai lagi (401).

## Lokasi penyimpanan cover

File cover disimpan di:

```text
storage/app/private/covers
```

Folder tersebut tidak dapat diakses langsung dari browser. Sampul hanya bisa
dibuka melalui endpoint publik:

```text
GET /api/books/{id}/cover
```
