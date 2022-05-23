<h4>{{$product->pagetitle}} - {{\sCommerce\Models\sProduct::listType()[$product->type]}} {{\Illuminate\Support\Str::lower($_lang['scommerce_product'])}}.</h4>
<div class="split my-3"></div>
<div class="row form-row">
    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="grouping_parameters" class="warning">{{$_lang["scommerce_grouping_parameter"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_grouping_parameter_help']}}"></i>
            </div>
            <div class="col">
                <select id="grouping_parameters" class="form-control" name="grouping_parameters[]" onchange="changeVariations();">
                    @foreach($sCommerce->getProductOptionalsSelect((int)request()->i) as $optionalsSelect)
                        <option value="{{$optionalsSelect->id}}" @if(in_array($optionalsSelect->id, $product->grouping_parameters_array)) selected @endif>{{$optionalsSelect->pagetitle}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="split my-3"></div>
@if (!count($product->variations_array))<b style="color:red">{{$_lang['scommerce_variations_save_help']}}</b>@endif
<p>{{$_lang['scommerce_variations_price_help']}}</p>
<div class="table-responsive">
    <table id="variations" class="table table-condensed table-hover sectionTrans">
        <thead>
        <tr>
            <th style="text-align:center;">{{$_lang["scommerce_variation"]}}</th>
            <th style="text-align:center;">{{$_lang["scommerce_price"]}}</th>
            <th style="text-align:center;">{{$_lang["resource_opt_published"]}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($product->grouping_parameters_array as $filterId)
            @foreach($sCommerce->filterValues($filterId) as $value)
                <tr>
                    <td>{{$value->base}}</td>
                    <td>
                        <div class="input-group">
                            <span class="input-group-text">₴</span>
                            <input type="text" name="variations[{{$value->vid}}][price]" class="form-control" value="{{$product->variations_array[$value->vid]['price'] ?? $product->price}}" onchange="documentDirty=true;">
                        </div>
                    </td>
                    <td>
                        <input type="hidden" name="variations[{{$value->vid}}][published]" class="form-control" value="0">
                        <input type="checkbox" name="variations[{{$value->vid}}][published]" class="form-checkbox form-control" value="1" @if(isset($product->variations_array[$value->vid]['published']) && $product->variations_array[$value->vid]['published']) checked @endif>
                    </td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>
<script>
    function changeVariations() {
        document.getElementById('variations').remove();
    }
</script>