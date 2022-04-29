<div class="row form-row">
    @foreach($sCommerce->getProductFeatures((int)request()->i) as $productFeature)
        @switch($productFeature->type_select)
            @case(\sCommerce\Models\sFilter::STYPE_NUMBER)
                @include('partials.selectTypeNumber')
                @break
            @case(\sCommerce\Models\sFilter::STYPE_TEXT)
                @include('partials.selectTypeText')
                @break
            @case(\sCommerce\Models\sFilter::STYPE_SELECT)
                @include('partials.selectTypeSelect')
                @break
            @case(\sCommerce\Models\sFilter::STYPE_MULTISELECT)
                @include('partials.selectTypeMultielect')
                @break
        @endswitch
    @endforeach
</div>