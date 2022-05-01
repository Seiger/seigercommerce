<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="features{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="input-group col">
            @php($select = (isset($features[$productFeature->filter]) && is_array($features[$productFeature->filter])) ? reset($features[$productFeature->filter]) : ['base' => ''])
            <span class="input-group-text"><i class="fas fa-remove-format"></i></span>
            <input type="text" id="features{{$productFeature->filter}}" name="features[{{$productFeature->filter}}]" class="form-control" value="{{$select['base']}}" onchange="documentDirty=true;">
        </div>
    </div>
</div>