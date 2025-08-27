<?php 

include_once 'fungsi.php';

define('_CEKFORMATNIK', 'NIK yang benar terdiri dari 16 angka dengan format tertentu. Mohon periksa kembali NIK yang Anda isikan.');
define('_CEKNIK', 'NIK tidak sinkron dengan data pendukung lainnya. Mohon periksa kembali NIK, jenis kelamin, dan tanggal lahir yang Anda isikan.');
define('_NIKEXISTS', 'NIK sudah ada.');
define('_NIK','NIK');
define('_NAME','Nama atlet');

if ($action == "save") {
    if (isset($_POST['submit'])) {
        $notificationbuilder = "";
        $notificationbuilder .= validation($nik, _NIK, false);
        $notificationbuilder .= validation($nama, _NAME, false);

		if ($nik != '') {
			if (!ceknikformat($nik)) {
				$notificationbuilder .= _CEKFORMATNIK;
			} else if (!ceknik($nik, $jenis_kelamin, $tanggal_lahir)) {
				$notificationbuilder .= _CEKNIK;
			} else if (getNIKExists($nik)) {
				$notificationbuilder .= _NIKEXISTS;
			}
		}
		
        if ($notificationbuilder != "") {
            $action = createmessage($notificationbuilder, _ERROR, "error", "add");
        } else {

			$columns = implode(', ', $fields);
			$placeholders = ':' . implode(', :', $fields);

			$query = "INSERT INTO $table_name ($columns) VALUES ($placeholders)";

			$data = [
				// 'kartu_anggota' => '123456',
				'nik' => $nik,
				'nama' => $nama,
				'jenis_kelamin' => $jenis_kelamin,
				'tanggal_lahir' => $tanggal_lahir,
				'kab_kota' => $kab_kota,
				'klub_id' => $klub_id
			];

			$escaped_values = array_map(function($f) use ($data, $mysql) {
				return "'" . $mysql->real_escape_string($data[$f]) . "'";
			}, $fields);

			$columns = implode(', ', $fields);
			$values = implode(', ', $escaped_values);

			$sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
            $result = $mysql->query($sql);
			$newId = $mysql->insert_id();
			
			// if (!empty($cropped_image)) {
				// if (preg_match('/^data:image\/(\w+);base64,/', $cropped_image, $type)) {
					// $cropped_image = substr($cropped_image, strpos($cropped_image, ',') + 1);
					// $type = strtolower($type[1]); // jpg, png, etc

					// $cropped_image = base64_decode($cropped_image);
					// $filename = $newId . '_' . uniqid('foto_') . '.' . $type;
					// $filepath = $cfg_fullsizepics_path . '/' . $filename;
					// file_put_contents($filepath, $cropped_image);

					// $sql = "UPDATE $table_name SET filename='$filename' WHERE id='$newId'";
					// $result = $mysql->query($sql);
				// }
			// }
			
			if ($img_large != '' && $img_small != '') {
				$tempFile = uniqid();
				$filename = $newId.'_'.$tempFile .''.'.png';
				$largePath = "$cfg_fullsizepics_path/$filename";
				$smallPath = "$cfg_thumb_path/$filename";
				
				saveBase64Image($img_large, $largePath);
				saveBase64Image($img_small, $smallPath);
				
				$sql = "UPDATE $table_name SET filename='$filename' WHERE id='$newId'";
				$result = $mysql->query($sql);
			}
			
            if ($result) {
                $action = createmessage(_ADDSUCCESS, _SUCCESS, "success", "");
            } else {
                $action = createmessage(_DBERROR, _ERROR, "error", "add");
            }
        }
    } else {
        $action = "add";
    }
}