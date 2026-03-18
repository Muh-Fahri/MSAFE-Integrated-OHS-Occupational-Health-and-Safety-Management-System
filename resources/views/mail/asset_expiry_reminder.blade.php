@if(!empty($data[7]))
7 Hari : Lakukan komisioning ulang pada sarana/peralatan karena masa berlaku uji kelayakan/komisioning akan berakhir pada 7 hari kedepan. 
<table border="1" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Sarana/Peralatan</th>
			<th>Expired Tanggal</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data[7] as $k=>$v)
		<tr>
			<td>{{$k+1}}</td>
			<td>{{$v->name}}</td>
			<td>{{$v->expiry_date}}</td>
			<td><a href="{{ env('APP_URL') }}/transaction/asset/show/{{$v->id}}" >Lihat Detail</a></td>
		</tr>
		@endforeach
	</tbody>
</table>
<br/><br/>
@endif
@if(!empty($data[3]))
3 Hari : Lakukan komisioning ulang pada sarana/peralatan karena masa berlaku uji kelayakan/komisioning akan berakhir pada 3 hari kedepan
<br/>
<table border="1" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Sarana/Peralatan</th>
			<th>Expired Tanggal</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@if(!empty($data[3]))
		@foreach($data[3] as $k=>$v)
		<tr>
			<td>{{$k+1}}</td>
			<td>{{$v->name}}</td>
			<td>{{$v->expiry_date}}</td>
			<td><a href="{{ env('APP_URL') }}/transaction/asset/show/{{$v->id}}" >Lihat Detail</a></td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>
@endif