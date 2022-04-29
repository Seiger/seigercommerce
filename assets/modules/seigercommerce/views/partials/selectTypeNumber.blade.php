<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="productFeature{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="input-group col">
            <span class="input-group-text"><i class="fas fa-remove-format"></i></span>
            <input type="text" id="productFeature{{$productFeature->filter}}" name="productFeature[{{$productFeature->filter}}]" class="form-control" value="" onchange="documentDirty=true;">
        </div>
    </div>
</div>