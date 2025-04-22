@php
    $marca_agua = app_path('CoreFacturalo'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'grupopereda'.DIRECTORY_SEPARATOR.'header.jpg');
@endphp
<div style="position: relative;text-align: center; padding-top: -33; padding-left: -60; padding-right: -70; left:0px; broder: #000;">
    <img style="width: 200%; height: 150px;" src="data:{{mime_content_type($marca_agua)}};base64, {{base64_encode(file_get_contents($marca_agua))}}">
</div>