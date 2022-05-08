<h3>{{$_lang['scommerce_letter_templates']}}</h3>
<div class="table-responsive">
    <table class="table table-hover sectionTrans">
        <thead>
        <tr>
            <th style="text-align:center;">{{$_lang["name"]}}</th>
            <th style="width:70px;text-align:center;">{{$_lang["scommerce_identifier"]}}</th>
            <th id="action-btns">{{$_lang["onlineusers_action"]}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($sCommerce->mailTemplates() as $template)
            @if($template->name != 'meta')
                <tr>
                    <td>{{$template->title}}</td>
                    <td>{{$template->name}}</td>
                    <td style="text-align:center;">
                        <div class="btn-group">
                            <a href="{{$url}}&get=template&i={{$template->name}}" class="btn btn-outline-success">
                                <i class="fa fa-pencil"></i> <span>{{$_lang["edit"]}}</span>
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fa fa-eye"></i> <span>{{$_lang['search_view_docdata']}}</span>
                            </a>
                        </div>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
<div class="split my-1"></div>

@if($modx->hasPermission('settings'))
    <h3>{{$_lang['scommerce_base_configs']}}</h3>
    <div class="row form-row">
        <div class="row-col col-12">
            <div class="row form-row form-row-checkbox">
                <div class="col-auto col-title">
                    <label for="catalog_root" class="warning">{{$_lang['scommerce_catalog_root']}}</label>
                </div>
                <div class="col">
                    <select id="catalog_root" class="form-control" name="catalog_root" onchange="documentDirty=true;">
                        @foreach(\sCommerce\Models\sCategory::whereParent(0)->wherePublished(1)->whereDeleted(0)->get() as $resource)
                            <option value="{{$resource->id}}" @if($resource->id == evo()->getConfig('catalog_root', 1)) selected @endif>{{$resource->pagetitle}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="split my-1"></div>
@endif

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button2" class="btn btn-primary" href="javascript:void(0);">
                <i class="fa fa-plus"></i>
                <span>{{$_lang['scommerce_add_template']}}</span>
            </a>
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#configs');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
            <a id="Button3" class="btn btn-secondary" href="{!!$url!!}">
                <i class="fa fa-times-circle"></i><span>{{$_lang["cancel"]}}</span>
            </a>
            @if($modx->hasPermission('settings'))
                <a id="Button4" class="btn btn-warning" href="{{$url}}&get=template&i=meta">
                    <i class="fa fa-hammer"></i><span>{{$_lang["scommerce_edit_meta"]}}</span>
                </a>
            @endif
        </div>
    </div>
@endpush