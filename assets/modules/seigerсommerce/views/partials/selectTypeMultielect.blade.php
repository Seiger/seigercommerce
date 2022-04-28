<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="productFeature{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            <select id="productFeature{{$productFeature->filter}}" class="form-control select2" name="productFeature[{{$productFeature->filter}}][]" multiple onchange="documentDirty=true;">
                @foreach($productFeature->values as $value)
                    <option value="{{$value->vid}}">{{ $value->{$sCommerce->langDefault()} }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>