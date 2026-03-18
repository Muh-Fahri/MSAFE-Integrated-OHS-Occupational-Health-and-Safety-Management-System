<!DOCTYPE html>
<html>
  	<head>
    	<meta charset="utf-8">
    	<title>{{$license->no}}</title>
    	<style>
    		
    	</style>
  	</head>
  	<body>
  		<div style=" width: 10.5cm; ">
  			<div style="float:left;  width: 5cm; height: 8cm; border: 1px solid black; padding: 2px;">
			   	<div style="height: 1.5cm; text-align: center; margin-bottom: 2px;">
			   		<img src="img/logo2.PNG" height="57px;">
			   	</div>
				<div style="background-color: #D59801; padding: 3px;">
					<div style="text-align: center; font-weight: bold; font-size: 13px;">KIMPER</div>
					<div style="text-align: center; font-weight: bold; font-size: 9px;">Kartu Izin Mengoperasikan Kendaraan</div>
				</div>
				<div style="margin-top: 5px; height: 1.9cm;">
					<table style="font-size: 8px; margin-top: 5px;">
			  			<tr>
			  				<td style="font-weight: bold;">Nama</td>
			  				<td>:</td>
			  				<td>{{ $license->name }}</td>
			  			</tr>
			  			<tr>
			  				<td style="font-weight: bold;">Perusahaan</td>
			  				<td>:</td>
			  				<td>{{ $license->company_name }}</td>
			  			</tr>
			  			<tr>
			  				<td style="font-weight: bold;">Departemen</td>
			  				<td>:</td>
			  				<td>{{ $license->department_name }}</td>
			  			</tr>
			  			<tr>
			  				<td style="font-weight: bold;">Masa Berlaku</td>
			  				<td>:</td>
			  				<td>{{ $license->expiry_date }}</td>
			  			</tr>
			  			<tr>
			  				<td style="font-weight: bold;">No Register</td>
			  				<td>:</td>
			  				<td>{{ $license->no }}</td>
			  			</tr>
			  		</table>
			  	</div>
		  		<table style="width: 5cm; margin-top: 5px;">
		  			<tr>
				  		<td colspan="{{ count($license->zone) }}" style="background-color: #D59801; height: 0.4cm;text-align: center; font-weight: bold; font-size: 13px;">Zona KIMPER</td>
		  			</tr>
		  			<tr>
				  		@foreach($license->zone as $v)
		  					<td style="background-color: #{{$v->color_code}}; height: 0.4cm;"></td>
				  		@endforeach
		  			</tr>
		  		</table>
		  		<table style="width: 5cm; font-size: 7px; " cellpadding="0" cellspacing="0">
		  			<tr>
				  		<td rowspan="3" style="width: 45%; text-align: center;">
				  			<img src="{{ $license->photo_base64 }}" style="height: 2cm; width: 1.5cm;" />
				  		</td>
				  		<td style="width: 55%; text-align: center;">
				  			Rante Balla, {{ $license->completed_date }}
				  		</td>
		  			</tr>
		  			<tr>
		  				<td style="height: 0.7cm; text-align: center;">
		  					<img src="data:image/png;base64, {!! base64_encode(QrCode::size(40)->generate(env('APP_URL').'/transaction/license/detail_qrcode/'.$license->id)) !!} ">
		  				</td>
		  			</tr>
		  			<tr>
				  		<td style="text-align: center; font-weight: bold;">
				  		<span style="text-decoration: underline;">Mustafa Ibrahim</span><br/>
				  		Kepala Teknik Tambang
				  		</td>
		  			</tr>
		  		</table>
			</div>

			<div style="float:right;  width: 5cm; height: 8cm; border: 1px solid black; padding: 2px;">
			   	<div style="height: 1.5cm; text-align: center; margin-bottom: 2px;">
			   		<img src="img/logo2.PNG" height="57px;">
			   	</div>
				<div style="background-color: #D59801; padding: 3px;">
					<div style="text-align: center; font-weight: bold; font-size: 13px;">KIMPER</div>
					<div style="text-align: center; font-weight: bold; font-size: 9px;">Kartu Izin Mengoperasikan Kendaraan</div>
				</div>
				<div style="height: 3.25cm; ">
					<table style="background-color: #D5D3D3; width: 5cm; margin-top: 2px; font-size: 8px;" cellpadding="0" cellspacing="1">
						<tr>
					  		<td colspan="3" style="background-color: #D59801; text-align: center; font-weight: bold; font-size: 9px;">Jenis KIMPER</td>
			  			</tr>
			  			<tr>
					  		<td style="background-color: #D59801; font-weight: bold; text-align: center;">Klasifikasi</td>
					  		<td style="background-color: #D59801; font-weight: bold; text-align: center;">Kode</td>
					  		<td style="background-color: #D59801; font-weight: bold; text-align: center;">Keterangan</td>
			  			</tr>
			  			@foreach($license->item as $v)
			  			<tr>
					  		<td style="background-color: #FFFFFF; text-align: center;">{{$v->value2}}</td>
					  		<td style="background-color: #FFFFFF; text-align: center;">{{$v->value1}}</td>
					  		<td style="background-color: #FFFFFF; text-align: center;">{{$v->remarks}}</td>
			  			</tr>
			  			@endforeach
			  		</table>
			  	</div>
		  		<div style="background-color: #D59801; width: 4.88cm; margin-top: 4px; font-size: 8px; color: #FFFFFF;  padding: 2px;">
		  			<span style="font-size: 11px; font-weight: bold;">No Kontak Darurat Masmindo</span><br/>
		  			<span style="font-weight: bold;">Kedaruratan &nbsp;&nbsp;: 0877-9890-8911</span><br/>
		  			<span style="font-weight: bold;">Medis &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 0821-8791-0019</span>
		  		</div>
		  		<div style="background-color: #D59801; width: 4.88cm; margin-top: 4px; font-size: 6.75px; color: #FFFFFF;  padding: 2px;">
		  			Catatan:<br/>
		  			Perbarui KIMPER sebelum masa berlaku habis<br/>
		  			Apabila kartu hilang agar segera melaporkan ke atasan<br/>
		  			Apabila menemukan kartu ini agar dikembalikan ke Security MDA
		  		</div>
			</div>
  		</div>
	</body>
</html>
