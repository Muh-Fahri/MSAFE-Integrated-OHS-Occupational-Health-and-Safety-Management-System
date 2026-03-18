Hi {{ $data->delegatee_name }},
<br/><br/>
@if($data->status=='GRANTED')
{{ $data->delegator_name }} has granted you access (Delegation) for {{ $data->type }} OHS Approval from {{ $data->delegation_start_date }} to {{ $data->delegation_end_date }}.
@else
{{ $data->delegator_name }} has removed your access (Delegation) for {{ $data->type }} OHS Approval.
@endif
<br/><br/>
Kind Regards,<br/>
Intranet Alert System
