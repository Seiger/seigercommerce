@if(in_array($get, ['post', 'postAdd']))
    <form name="post" id="post" class="content" method="post" enctype="multipart/form-data" action="{!!$url!!}&get=postSave" onsubmit="documentDirty=false;">
@endif