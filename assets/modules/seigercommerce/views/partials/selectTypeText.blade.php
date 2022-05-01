<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="features{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            @php($select = (isset($features[$productFeature->filter]) && is_array($features[$productFeature->filter])) ? reset($features[$productFeature->filter]) : ['base' => ''])
            @foreach($sCommerce->langTabs() as $lang => $tabName)
                <div class="input-group">
                    <span class="input-group-text">{{mb_strtoupper($lang)}}</span>
                    <input type="text" id="features{{$productFeature->filter}}" name="features[{{$productFeature->filter}}][{{$lang}}]" class="form-control" value="{{$select[$lang] ?? ''}}" onchange="documentDirty=true;">
                </div>
            @endforeach
        </div>
    </div>
</div>