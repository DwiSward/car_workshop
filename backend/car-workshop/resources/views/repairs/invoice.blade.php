<style type="text/css">
	* {
		font-size: 12px;
	}
</style>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td align="center" colspan="5"><strong>INVOICE</strong></td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td width="5%"><strong>Customer</strong></td>
		<td><strong>: {{ @$repair->car->customer->user->name }}</strong></td>
		<td></td>
		<td><strong></strong></td>
		<td></td>
	</tr>
	<tr>
		<td><strong></strong></td>
		<td><strong></strong></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="5">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5">
			
		</td>
	</tr>
</table>
<table border="1" cellspacing="0" cellpadding="4" width="100%">
	<tr align="center">
		<th width="50">No.</th>
		<th>Service</th>
		<th width="100">Price</th>
	</tr>
	@if (isset($repair->repairServices))	
		@foreach ($repair->repairServices as $key => $repairService)
			<tr>
				<td align="center">{{ $key + 1 }}</td>
				<td>
					{{ @$repairService->service->name }}
				</td>
				<td align="right">{{ number_format($repairService->price, 2, ',', '.') }}</td>
			</tr>
		@endforeach
	@endif
</table>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td width="550"></td>
		<td>
			<table border="1" cellspacing="0" cellpadding="4" width="100%">
				<tr>
					<td width="101" style="border-left-style: hidden;"><strong>Total</strong></td>
					<td width="101" style="border-left-style: hidden;"><strong></strong></td>
					<td align="right"><strong>{{ number_format($repairService->total, 2, ',', '.') }}</strong></td>
				</tr>
				
			</table>
		</td>
	</tr>
</table>