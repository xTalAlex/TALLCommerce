@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === config('app.name'))
<img src="{{ asset('img/logo.png') }}" class="logo" alt="ColomboFood">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
