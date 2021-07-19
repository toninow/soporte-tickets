<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img class="logo" alt="Laravel Logo" src="{{ asset('img/logo.png') }}"  alt="" >
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
