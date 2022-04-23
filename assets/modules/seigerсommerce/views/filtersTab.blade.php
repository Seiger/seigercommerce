<div class="table-responsive">
    <table class="table table-condensed table-hover sectionTrans">
        <thead>
        <tr>
            <th style="text-align:center;">{{$_lang["name"]}}</th>
            <th style="text-align:center;">{{$_lang["scommerce_categories"]}}</th>
            <th style="width:70px;text-align:center;">{{$_lang["type"]}}</th>
            <th style="width:260px;text-align:center;">{{$_lang["onlineusers_action"]}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sCommerce->products() as $product)
            <tr>
                <td>
                    <a href="{{$product->link}}" target="_blank"><b>{{$product->pagetitle}}</b> <small>({{$product->product}})</small></a>
                </td>
                <td>
                    @if($product->published)
                        <span class="badge badge-secondary">{{$_lang["page_data_published"]}}</span>
                    @else
                        <span class="badge badge-secondary">{{$_lang["page_data_unpublished"]}}</span>
                    @endif
                    @if($product->availability == 1)
                        <span class="badge badge-secondary">{{$_lang["scommerce_in_stock"]}}</span>
                    @elseif($product->availability == 2)
                        <span class="badge badge-secondary">{{$_lang["scommerce_on_order"]}}</span>
                    @else
                        <span class="badge badge-secondary">{{$_lang["scommerce_not_available"]}}</span>
                    @endif
                </td>
                <td>
                    @if($product->type == 1)
                    <span class="badge badge-warning">{{\sCommerce\Models\sProduct::listType()[$product->type]}}</span>
                    @else
                        <span class="badge badge-primary">{{\sCommerce\Models\sProduct::listStatus()[$product->status]}}</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <a href="{{$url}}&get=product&i={{$product->product}}" class="btn btn-outline-success"><i class="fa fa-pencil"></i>&emsp;{{$_lang['edit']}}</a>
                    <a href="#" data-href="{{$url}}&get=productDelete&i={{$product->product}}" data-toggle="modal" data-target="#confirmDelete" data-id="{{$product->product}}" data-name="{{$product->pagetitle}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i>&emsp;{{$_lang['remove']}}</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a href="{!!$url!!}&get=filter" class="btn btn-success" title="{{$_lang["scommerce_add_help"]}}">
                <i class="fa fa-plus-circle"></i>&emsp;<span>{{$_lang["scommerce_add"]}}</span>
            </a>
        </div>
    </div>
@endpush