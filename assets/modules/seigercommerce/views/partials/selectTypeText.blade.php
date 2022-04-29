<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="productFeature{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            @foreach($sCommerce->langTabs() as $lang => $tabName)
                <div class="input-group">
                    <span class="input-group-text">{{mb_strtoupper($lang)}}</span>
                    <input type="text" id="productFeature{{$productFeature->filter}}" name="productFeature[{{$productFeature->filter}}]" class="form-control" value="" onchange="documentDirty=true;">
                </div>
            @endforeach
        </div>
    </div>
</div>