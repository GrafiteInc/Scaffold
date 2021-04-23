<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (config('general.email.image_for_header'))
<img src="{{ config('general.email.image') }}" class="logo" alt="{{ $slot }}">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
