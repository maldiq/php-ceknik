# 🔍 Cek Format NIK Indonesia – PHP Validator

Repositori ini berisi fungsi PHP untuk memvalidasi **Nomor Induk Kependudukan (NIK)** berdasarkan format standar Indonesia, khususnya dengan memperhatikan **logika penambahan angka 40 pada tanggal lahir untuk perempuan**.

---

## 🧠 Latar Belakang

Dalam sistem kependudukan Indonesia, NIK terdiri dari 16 digit. Salah satu bagian penting dari NIK adalah representasi tanggal lahir, yang terletak pada digit ke-7 hingga ke-12. Untuk **perempuan**, terdapat penyesuaian berupa **penambahan angka 40 pada tanggal lahir**, untuk membedakan dari laki-laki.

| Jenis Kelamin | Tanggal Lahir Asli | Kode Tanggal di NIK |
|---------------|--------------------|---------------------|
| Laki-laki     | 01                 | 01                  |
| Perempuan     | 01                 | 41                  |
| Laki-laki     | 15                 | 15                  |
| Perempuan     | 15                 | 55                  |
| Laki-laki     | 30                 | 30                  |
| Perempuan     | 30                 | 70                  |

---

## ✅ Fungsi Validasi NIK

Fungsi berikut memeriksa:
1. Apakah NIK berjumlah 16 digit numerik.
2. Apakah bagian tanggal lahir valid.
3. Apakah tanggal valid setelah dikonversi dari format perempuan (jika > 40).

### 📄 Kode PHP

```php
function ceknikformat($nik) {
	if (preg_match('/^\d{16}$/', $nik)) {
		$tgl = substr($nik,6,2);
		if ($tgl>'40') $tgl = sprintf('%02d',intval($tgl)-40);
		$bln = substr($nik,8,2);
		$thn = '20' . substr($nik,10,2);
		if (checkdate($bln,$tgl,$thn)) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
