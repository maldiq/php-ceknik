<?php 

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

function ceknik($nik,$jkentry,$tlentry) {
	if (preg_match('/^\d{16}$/', $nik)) {
		$dmynik = substr($nik,6,6);
		if ($dmynik > '400000') {
			$tlnik = sprintf('%06d',intval($dmynik)-400000);
			$jknik = "Perempuan";
		} else {
			$tlnik = $dmynik;
			$jknik = "Laki-laki";
		}
		$tlentry = date('dmy',strtotime($tlentry));
		if ($tlnik==$tlentry && $jknik==$jkentry) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}