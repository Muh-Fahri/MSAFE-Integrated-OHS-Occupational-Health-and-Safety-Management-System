You have received an incident notification. 
<br/><br/>
<table border="1" style="border-collapse: collapse;" cellpadding="4">
	<thead style="background-color: yellow;">
		<tr>
			<th align="justify">No</th>
			<th align="justify">Event Type</th>
			<th align="justify">Incident Description</th>
			<th align="justify">Date/Time of Incident</th>
			<th align="justify">Company</th>
			<th align="justify">Department</th>
			<th align="justify">Status</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td align="justify">1</td>
			<td align="justify">{{$data->event_type}}</td>
			<td align="justify">{{$data->incident_description}}</td>
			<td align="justify">{{$data->event_datetime}}</td>
			<td align="justify">{{$data->company_name}}</td>
			<td align="justify">{{$data->department_name}}</td>
			<td align="justify">{{$data->status.' '.(!empty($data->next_user_name) ? ' - '.$data->next_user_name : '') }}</td>
		</tr>
	</tbody>
</table>
<br/>
<a href="{{ env('APP_URL') }}/transaction/incident-notification/show/{{$data->id}}" >Click here for detail.</a>
