@php
    $icon = $icon ?? null;
@endphp
@if($icon)
    <div style="margin-top:8px;">
        <img src="{{ asset('vendor/heroicons/o/' . str_replace('heroicon-o-', '', $icon) . '.svg') }}" width="32" height="32" style="vertical-align:middle;">
        <span style="margin-left:8px;">{{ $icon }}</span>
    </div>
@else
    <div style="margin-top:8px; color: #888; font-size: 12px;">Pilih icon lalu klik simpan untuk melihat preview icon di sini.</div>
@endif
