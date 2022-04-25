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
        @foreach($sCommerce->filters() as $filter)
            <tr>
                <td>
                    <b>{{$filter->pagetitle}}</b> <small>({{$filter->filter}})</small>
                </td>
                <td>
                    @foreach($filter->categories as $category)
                        <span class="badge badge-secondary">{{$category->pagetitle}}</span>
                    @endforeach
                </td>
                <td>
                    @if($filter->type == \sCommerce\Models\sFilter::FTYPE_FILTER)
                        <span class="badge badge-primary">{{\sCommerce\Models\sFilter::listType()[$filter->type]}}</span>
                    @else
                        <span class="badge badge-info">{{\sCommerce\Models\sFilter::listType()[$filter->type]}}</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <a href="{{$url}}&get=filter&i={{$filter->filter}}" class="btn btn-outline-success"><i class="fa fa-pencil"></i>&emsp;{{$_lang['edit']}}</a>
                    <a href="#" data-href="{{$url}}&get=filterDelete&i={{$filter->filter}}" data-toggle="modal" data-target="#confirmDelete" data-id="{{$filter->filter}}" data-name="{{$filter->pagetitle}}" class="btn btn-outline-danger"><i class="fa fa-trash"></i>&emsp;{{$_lang['remove']}}</a>
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