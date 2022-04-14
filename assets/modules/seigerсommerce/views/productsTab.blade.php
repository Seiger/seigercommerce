<div class="table-responsive">
    <table class="table table-condensed table-hover sectionTrans">
        <thead>
        <tr>
            <th style="width:70px;text-align:center;">ID</th>
            <th style="text-align:center;">{{$_lang["name"]}}</th>
            <th style="width:70px;text-align:center;">{{$_lang["scommerce_availability"]}}</th>
            <th style="width:150px;text-align:center;">{{$_lang["scommerce_status"]}}</th>
            <th style="width:260px;text-align:center;">{{$_lang["onlineusers_action"]}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sCommerce->products() as $product)
            <tr>
                <td><b>{{$product->product}}</b></td>
                <td>
                    <img src="{{$product->coverSrc}}" alt="{{$product->coverSrc}}" class="post-thumbnail">
                    <a href="{{$product->link}}" target="_blank"><b>{{$product->pagetitle}}</b></a>
                </td>
                <td>
                    {{--@if($post['published'])
                        <span class="badge badge-success">{{$_lang["page_data_published"]}}</span>
                    @else
                        <span class="badge badge-secondary">{{$_lang["page_data_unpublished"]}}</span>
                    @endif--}}
                </td>
                <td>
                    {{--@if($post['type'] == 0)<span class="badge badge-info">{{$_lang['spost_'.$post['type']]}}</span>@endif
                    @if($post['type'] == 1)<span class="badge badge-dark">{{$_lang['spost_'.$post['type']]}}</span>@endif--}}
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
            <a href="{!!$url!!}&get=productAdd" class="btn btn-success" title="{{$_lang["scommerce_add_help"]}}">
                <i class="fa fa-plus-circle"></i>&emsp;<span>{{$_lang["scommerce_add"]}}</span>
            </a>
        </div>
    </div>
@endpush