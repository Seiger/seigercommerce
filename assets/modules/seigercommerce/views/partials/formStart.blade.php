@if(in_array($get, ['product']))
    <form name="product" id="product" class="content" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=productSave" onsubmit="documentDirty=false;">
@endif

@if(in_array($get, ['filter']))
    <form name="filter" id="filter" class="content" method="post" action="{!!$url!!}&get=filterSave" onsubmit="documentDirty=false;">
@endif

@if(in_array($get, ['filterValues']))
    <form name="filterValues" id="filterValues" class="content" method="post" action="{!!$url!!}&get=filterValuesSave" onsubmit="documentDirty=false;">
@endif

@if(in_array($get, ['configs']))
    <form name="configs" id="configs" class="content" method="post" action="{!!$url!!}&get=configs" onsubmit="documentDirty=false;">
@endif

@if(in_array($get, ['template']))
    <form name="template" id="template" class="content" method="post" action="{!!$url!!}&get=templateSave" onsubmit="documentDirty=false;">
        <input type="hidden" name="name" value="{{$template['base']->name ?? ''}}">
@endif