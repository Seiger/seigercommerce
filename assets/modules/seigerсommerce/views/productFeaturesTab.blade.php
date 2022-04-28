<div class="row form-row">
    @foreach($sCommerce->getProductFeatures((int)request()->i) as $productFeature)
        @switch($productFeature->type_select)
            @case(\sCommerce\Models\sFilter::STYPE_SELECT)
                @include('partials.selectTypeSelect')
                @break
        @endswitch
    @endforeach
</div>