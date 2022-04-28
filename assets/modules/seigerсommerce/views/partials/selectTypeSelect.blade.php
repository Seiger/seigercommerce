<div class="row-col col-12">
    <div class="row form-row">
        <div class="col-auto col-title">
            <label for="productFeature{{$productFeature->filter}}" class="warning">{{$productFeature->pagetitle}}</label>
            @if(trim($productFeature->introtext))
                <i class="fa fa-question-circle" data-tooltip="{{$productFeature->introtext}}"></i>
            @endif
        </div>
        <div class="col">
            <select id="productFeature{{$productFeature->filter}}" class="form-control" name="productFeature[{{$productFeature->filter}}]" onchange="documentDirty=true;">
                @foreach($sCommerce->listCategories() as $key => $value)
                    <option value="{{$key}}" @if($key == ($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))) selected @endif>{{$value}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
{!! dd($productFeature->values) !!}