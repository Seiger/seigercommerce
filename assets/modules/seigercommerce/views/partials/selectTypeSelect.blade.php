<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="features{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            @php($select = (isset($features[$productFeature->filter]) && is_array($features[$productFeature->filter])) ? reset($features[$productFeature->filter]) : ['vid' => 0])
            <select id="features{{$productFeature->filter}}" class="form-control" name="features[{{$productFeature->filter}}]" onchange="documentDirty=true;">
                <option value=""></option>
                @foreach($productFeature->values as $value)
                    {!! $selected = $value->vid == $select['vid'] ? 'selected' : '' !!}
                    <option value="{{$value->vid}}" {{$selected}}>{{ $value->{$sCommerce->langDefault()} }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>