@php
    // Mendapatkan nama icon, misal: heroicon-o-document-text
    $iconName = $icon ?? 'heroicon-o-document-text';
    $iconParts = explode('-', $iconName, 3);
    $style = $iconParts[1] ?? 'o';
    $name = $iconParts[2] ?? 'document-text';
    $svgUrl = asset("vendor/heroicons/{$style}/{$name}.svg");
@endphp

<img src="{{ $svgUrl }}" alt="{{ $iconName }}" width="24" height="24" style="display:inline-block;vertical-align:middle;">
