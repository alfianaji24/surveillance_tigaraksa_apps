# Clinical Documentation Improvement (CDI) System

## Overview

Sistem CDI (Clinical Documentation Improvement) ini dirancang untuk membantu tenaga kesehatan dalam membuat dokumentasi klinis yang terstruktur dan terintegrasi dengan platform Satu Sehat Kementerian Kesehatan Indonesia.

## Fitur Utama

### 1. Pencarian Kode ICD-10
- **Integrasi Satu Sehat**: Mengambil data ICD-10 langsung dari API Satu Sehat
- **Pencarian Real-time**: Cari kode ICD-10 berdasarkan nama penyakit atau kode
- **Detail Diagnosis**: Tampilkan informasi lengkap termasuk definisi, inklusi, dan eksklusi
- **Fallback Data**: Data mock tersedia untuk development/testing

### 2. Dokumentasi Klinis
- **Form Terstruktur**: Template SOAP (Subjective, Objective, Assessment, Plan)
- **Multi Diagnosis**: Support diagnosis utama dan sekunder
- **Auto-save**: Simpan draft otomatis untuk mencegah kehilangan data
- **Validasi**: Validasi form untuk memastikan kelengkapan data

### 3. Dashboard & Analitik
- **Statistik Real-time**: Monitoring jumlah dokumentasi dan status
- **Top Diagnosis**: Grafik diagnosis yang paling sering digunakan
- **Trend Analysis**: Analisis trend dokumentasi dari waktu ke waktu

## Konfigurasi Satu Sehat

### 1. Environment Variables
Tambahkan konfigurasi berikut ke file `.env`:

```env
# Satu Sehat Configuration
SATU_SEHAT_BASE_URL=https://api.satusehat.kemkes.go.id
SATU_SEHAT_AUTH_URL=https://api.satusehat.kemkes.go.id/oauth2/token
SATU_SEHAT_CLIENT_ID=your_satu_sehat_client_id_here
SATU_SEHAT_CLIENT_SECRET=your_satu_sehat_client_secret_here
```

### 2. Mendapatkan Credentials
1. Daftar di portal developer Satu Sehat: https://satusehat.kemkes.go.id/platform/
2. Buat aplikasi baru dan dapatkan Client ID & Client Secret
3. Konfigurasi permissions untuk akses ICD-10 dan resources FHIR lainnya

## Routes

### CDI Routes
- `GET /cdi` - Dashboard CDI
- `GET /cdi/create` - Form buat dokumentasi baru
- `POST /cdi` - Simpan dokumentasi
- `GET /cdi/{id}` - Lihat detail dokumentasi
- `GET /cdi/{id}/edit` - Edit dokumentasi
- `PUT /cdi/{id}` - Update dokumentasi
- `DELETE /cdi/{id}` - Hapus dokumentasi

### API Routes
- `GET /cdi/search-icd10` - Cari kode ICD-10
- `GET /cdi/icd10-detail/{code}` - Detail kode ICD-10

## Permission System

Sistem menggunakan permission-based access control. Permissions yang diperlukan:

- `read-cdi` - Akses dashboard dan lihat dokumentasi
- `create-cdi` - Buat dokumentasi baru
- `update-cdi` - Edit dokumentasi
- `delete-cdi` - Hapus dokumentasi

## API Endpoints

### Search ICD-10
```http
GET /cdi/search-icd10?query=diabetes&limit=20
```

Response:
```json
[
  {
    "code": "E11",
    "display": "Diabetes mellitus tipe 2",
    "definition": "Gangguan metabolisme karbohidrat dengan resistensi insulin relatif"
  }
]
```

### Get ICD-10 Detail
```http
GET /cdi/icd10-detail/E11
```

Response:
```json
{
  "code": "E11",
  "display": "Diabetes mellitus tipe 2",
  "definition": "Gangguan metabolisme karbohidrat dengan resistensi insulin relatif",
  "hierarchy": "Chapter IV: Endocrine, nutritional and metabolic diseases (E00-E90)",
  "inclusion": ["Diabetes with ketoacidosis", "Diabetes with renal complications"],
  "exclusion": ["Diabetes mellitus type 1 (E10.-)"]
}
```

## Cara Penggunaan

### 1. Mengakses CDI Dashboard
1. Login ke sistem
2. Klik menu "CDI & ICD-10" di dashboard utama
3. Akan tampil halaman dashboard CDI dengan statistik dan quick actions

### 2. Mencari Kode ICD-10
1. Klik tombol "Cari ICD-10" di dashboard
2. Masukkan kata kunci (nama penyakit atau kode)
3. Pilih hasil pencarian untuk melihat detail
4. Klik "Gunakan Kode" untuk memilih kode ICD-10

### 3. Membuat Dokumentasi Klinis
1. Klik "Dokumentasi Baru" di dashboard
2. Isi informasi pasien (ID Pasien dari Satu Sehat)
3. Pilih diagnosis utama menggunakan pencarian ICD-10
4. Tambah diagnosis sekunder jika diperlukan
5. Isi catatan klinis menggunakan template SOAP
6. Simpan sebagai draft atau submit dokumentasi

## Development Notes

### Mock Data
Untuk development tanpa koneksi Satu Sehat, sistem menyediakan mock data:
- Kode ICD-10 sample: A00, A01, A02, I10, E11
- Detail diagnosis dengan definisi dan hierarki

### Caching
- Hasil pencarian ICD-10 di-cache selama 1 jam
- Token Satu Sehat di-cache selama 58 menit
- Detail ICD-10 di-cache selama 1 jam

### Error Handling
- Fallback ke mock data jika API Satu Sehat tidak accessible
- Error logging untuk debugging
- User-friendly error messages

## Troubleshooting

### Common Issues

1. **API Satu Sehat Error**
   - Periksa credentials di .env
   - Pastikan Client ID & Secret valid
   - Cek koneksi internet

2. **Permission Denied**
   - Pastikan user memiliki permission yang diperlukan
   - Cek konfigurasi role dan permission

3. **ICD-10 Not Found**
   - Gunakan mock data untuk development
   - Periksa spelling query pencarian

### Logging
Check log file untuk debugging:
```bash
tail -f storage/logs/laravel.log
```

## Future Enhancements

1. **Integration Features**
   - Sync dengan EMR/EHR existing
   - Export ke PDF/Word
   - Batch documentation

2. **AI Integration**
   - AI-assisted coding
   - Clinical decision support
   - Automated documentation suggestions

3. **Advanced Analytics**
   - DRG coding support
   - Compliance reporting
   - Quality metrics

## Support

Untuk bantuan teknis:
- Documentation: https://satusehat.kemkes.go.id/platform/docs/
- API Reference: https://satusehat.kemkes.go.id/platform/docs/id/api/
- Issue Tracker: Create issue di repository project

---

**Note**: Sistem ini masih dalam pengembangan. Fitur-fitur akan terus ditambahkan dan diperbaiki sesuai kebutuhan.
