@if(!empty($data[7]))
7 Hari : Lakukan tindaklanjut pada temuan karena tenggang waktu tindaklanjut temuan akan berakhir pada 7 hari kedepan.
<table border="1" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Temuan</th>
			<th>Tanggal Jatuh Tempo</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data[7] as $k=>$v)
		<tr>
			<td>{{$k+1}}</td>
			<td>{{$v->risk_description}}</td>
			<td>{{$v->due_date}}</td>
			<td><a href="{{ env('APP_URL') }}/transaction/corrective-actions/show/{{$v->id}}" >Lihat Detail</a></td>
		</tr>
		@endforeach
	</tbody>
</table>
<br/><br/>
@endif
@if(!empty($data[3]))
3 Hari : Lakukan tindaklanjut pada temuan karena tenggang waktu tindaklanjut temuan akan berakhir pada 3 hari kedepan.
<br/>
<table border="1" width="100%">
	<thead>
		<tr>
			<th>No</th>
			<th>Nama Temuan</th>
			<th>Tanggal Jatuh Tempo</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
		@foreach($data[3] as $k=>$v)
		<tr>
			<td>{{$k+1}}</td>
			<td>{{$v->risk_description}}</td>
			<td>{{$v->due_date}}</td>
			<td><a href="{{ env('APP_URL') }}/transaction/corrective-actions/show/{{$v->id}}" >Lihat Detail</a></td>
		</tr>
		@endforeach
	</tbody>
</table>
@endif