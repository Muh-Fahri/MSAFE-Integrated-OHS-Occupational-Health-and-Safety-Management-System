<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="utf-8">
    	<meta charset="utf-8">
    	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>MDA Safe</title>
    	<link rel="shortcut icon" type="img/png" href="{{ url('/img/favicon.png') }}"/>
  	</head>
  	<body>
  		<center>
  			<span style="font-weight: bold; font-size: 18px;">APLIKASI M-SAFE</span><br/>
  			<span style="font-size: 15px;">PT MASMINDO DWI AREA</span><br/><br/>
  			Menyatakan bahwa : <br/><br/>
  			<table style="margin-bottom: 20px;">
  				<tr>
  					<td>Nomor Kartu KIMPER/KIMPAK</td>
  					<td>:</td>
  					<td>{{ $license->no }}</td>
  				</tr>
  				<tr>
  					<td>Dari</td>
  					<td>:</td>
  					<td>PT Masmindo Dwi Area</td>
  				</tr>
  				<tr>
  					<td>Perihal</td>
  					<td>:</td>
  					<td>Penerbitan KIMPER/KIMPAK</td>
  				</tr>
  				<tr>
  					<td>Tanggal Penerbitan Kartu</td>
  					<td>:</td>
  					<td>{{ $license->completed_date }}</td>
  				</tr>
  			</table>
        <span style="font-size: 15px;">Telah Mendapatkan Persetujuan dari Bapak Mustafa selaku Kepala Teknik Tambang PT Masmindo Dwi Area.</span><br/>
        <span style="font-size: 15px;">Untuk memastikan keabsahan kartu tersebut. Silahkan menghubungi Departemen OHS atau mengakses Aplikasi M-Safe.</span><br/>
  		</center>
	</body>
</html>
