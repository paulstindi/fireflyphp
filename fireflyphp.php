<?php
echo("Algoritma Firefly");
echo("<br/>");
echo("Tujuan : Menyelesaikan Michalewiz Benchmark Function");
echo("<br/>");
echo("Diketahui Nilai minimum = -4.687658");
echo("<br/>");
echo("<br/>");

$jumlah_firefly = 40; //typical 15-40
$dimensi = 5;
$max_iterasi = 1000;
$seed = 0;

echo("Jumlah Firefly = ".$jumlah_firefly);
echo("<br/>");
echo("Dimensi Permasalahan = ".$dimensi);
echo("<br/>");
echo("Iterasi Maksimal = ".$max_iterasi);
echo("<br/>");
echo("Inisialisasi Seed (Digunakan Untuk Pembangkit Nilai Random)= ".$seed);
echo("<br/>");
echo("<br/>");

echo("Memulai Algoritma Firefly");
echo("<br/>");
$posisiTerbaik = algoritma_firefly($jumlah_firefly, $dimensi, $seed, $max_iterasi);
echo("<br/>");
echo("Finish");
echo("<br/>");
echo("<br/>");
echo("Solusi Terbaik yang Ditemukan :");
echo("<br/>");
echo("x = ");
menampilkanVector($posisiTerbaik, 4, TRUE);
$z = michaelewicz($posisiTerbaik);
echo("<br/>");
echo("<br/>");
echo("Nilai dari Fungsi pada Posisi Terbaik = ".$z);
echo("<br/>");
echo("<br/>");
$error = fxError($posisiTerbaik);
echo("Error Pada Posisi Terbaik Adalah = ".$error);
echo("<br/>");
echo("Selesai");


function menampilkanVector($v = array(), $dec, $nl){
	for($i = 0; $i < count($v); ++$i){
		echo(number_format((float)$v[$i], $dec,'.',''). " - ");
	}
	if($nl == FALSE){
		echo("");
	}
}
function michaelewicz($xVal = array()){ //Fungsi Error Cost Function
	$hasil = 0.0;
	for($i = 0; $i < count($xVal); ++$i){
		$a = sin($xVal[$i]);
		$b = sin((($i + 1) * $xVal[$i] * $xVal[$i]) / pi());
		$c = pow($b, 20);
		$hasil += $a * $c;
	}
	return(-1.0 * $hasil);
}
function fxError($xval = array()){
	$dim = count($xval);
	$trueMin = 0.0;
	if($dim == 2){
		$trueMin = -1.8013; //Aproksimasi
	} elseif($dim == 5){
		$trueMin = -4.687658; //Aproksimasi
	} elseif($dim == 5){
		$trueMin = -9.66015; //Aproksimasi
	}
	$kalkuasi = michaelewicz($xval);
	return($trueMin - $kalkuasi) * ($trueMin - $kalkuasi);
}
function jarak($posA = array(), $posB = array()){
	$ssd = 0.0;	//Perbeaan total akar kuadrat(Euclidean)
	for($i = 0; $i < count($posA); ++$i){
		$ssd += ($posA[$i] - $posB[$i]) * ($posA[$i] - $posB[$i]);
	}
	return(sqrt($ssd));
}
function algoritma_firefly($jumlah_firefly, $dimensi, $seed, $max_iterasi){
	$rnd = rand(); //srand($seed);
	$minX = 0.0; //Spesifil pada fungsi Michalewicz
	$maxX = 3.2;
	
	$B0 = 1.0; //Beta (Attractiveness base / Dasar Dayatarik)
	
	$g = 1.0; //Gamma(Absorption for Attraction / Penyerapan Daya Tarik)
	$a = 0.20; //Alpha
	
	$intervalTampilan = $max_iterasi / 10;
	
	$errorTerbaik = 340282300000000000000000000000000000000; //Nilai Float Maximal
	$posisiTerbaik = array(); //Terbaik yang ditemukan
	
	$fl = new FireFly();
	$swarm = new ArrayObject($fl); //Keseluruhan dengan nilai Null
	
	//Inisialisasi Swarm pada posisi random
	for($i = 0; $i < $jumlah_firefly; ++$i){
		$swarm[$i] = new FireFly($dimensi);		//Posisi 0, error dan intensitas 0.0
		for ($k = 0; $k < $dimensi; ++$k){		//Posisi Random
			$swarm[$i]->posisi[$k] = ($maxX - $minX) * $rnd + $minX;			
		}
		$swarm[$i]->error = fxError($swarm[$i]->error + 1); // +1 untuk mencegah div 0
		if($swarm[$i]->error < $errorTerbaik){
			$errorTerbaik = $swarm[$i]->error;
			for($k = 0; $k < $dimensi; ++$k){
				$posisiTerbaik[$k] = $swarm[$i]->posisi[$k];
			}
		}
		
	}
	
	$iterasi = 0;
	while($iterasi < $max_iterasi){		//Proses Utama
		if($iterasi % $intervalTampilan == 00 && $iterasi < $max_iterasi){	//Menampilkan progress
			echo(" Iterasi ke - ".$iterasi);
			echo(" - ");
			echo("  Error = ".$errorTerbaik);
			echo("<br/>");
		}
		
		for($i = 0; $i < $jumlah_firefly; ++$i){ //Untuk keseluruhan firefly
			for($j = 0; $j < $jumlah_firefly; ++$j){ //Firefly satu dengan yang lainnya
				if($swarm[$i]->intensitas < $swarm[$j]->intensitas){
					//Firefly i yang kurang intens dipindahkan ke kfirefly j
					$r = jarak($swarm[$i]->posisi, $swarm[$j]->posisi);
					$beta = $B0 * exp(-$g * $r *$r); //Disederhananakan
													//Original Firefly :
													//$beta = ($B0 - $betaMin) * exp(-$g * $r * $r) + $betaMin;
													//$a = $a0 * pow(0.89, $iterasi);
					for($k = 0; $k , $dimensi; ++$k){
						$swarm[$i]->posisi[$k] += $beta * ($swarm[j]->posisi[$k] - $swarm[$i]->posisi[$k]);
						$swarm[$i]->posisi[$k] += $a + ($rnd - 0.5);
						if($swarm[$i]->posisi[$k] < $minX){
							$swarm[$i]->posisi[$k] = ($maxX - $minX) * $rnd + $minX;
						}
						if($swarm[$i]->posisi[$k] > $maxX){
							$swarm[$i]->posisi[$k] = ($maxX - $minX) * $rnd + $minX;
						}
					}
					$swarm[$i]->error = fxError($swarm[$i]->posisi);
					$swarm[$i]->intensitas = 1 / ($swarm[$i]->error + 1);
				}
			}//$j
		} //$i
		$swarm->asort();
		if($swarm[0]->error < $errorTerbaik){//cek error terbaik
			$errorTerbaik = $swarm[0]->error;
			for($k = 0; $k < $dimensi; ++$k){
				$posisiTerbaik[$k] = $swarm[0]->posisi[$k];
			}
		}
		++$iterasi;
	}//while
	return($posisiTerbaik);
	
}
class FireFly{
	public $posisi = array();
	public $error;
	public $intensitas;
	
	function _construct($dimensi){#,,,,,,,,,,,,,,,,,,,,
		$this->posisi = SplFixedArray($dimensi);
		$this->error = 0.0;
		$this->intensitas = 0.0;
	}
	
	public function BandingkanKe($fireflyLain ){
		if($this->error < $fireflyLain->error){
			return(-1);
		} elseif ($this->error > $fireflyLain->error){
			return(+1);
		} else{
			return(0);
		}
	}
}

?>