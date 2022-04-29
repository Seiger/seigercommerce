<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="features{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            <select id="features{{$productFeature->filter}}" class="form-control" name="features[{{$productFeature->filter}}]" onchange="documentDirty=true;">
                <option value=""></option>
                @foreach($productFeature->values as $value)
                    <option value="{{$value->vid}}">{{ $value->{$sCommerce->langDefault()} }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>