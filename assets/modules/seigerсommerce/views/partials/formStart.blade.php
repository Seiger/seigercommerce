@if(in_array($get, ['product']))
    <form name="product" id="product" class="content" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=productSave" onsubmit="documentDirty=false;">
@endif