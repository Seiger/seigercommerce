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
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#configs');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
            <a id="Button3" class="btn btn-secondary" href="{!!$url!!}">
                <i class="fa fa-times-circle"></i><span>{{$_lang["cancel"]}}</span>
            </a>
        </div>
    </div>
@endpush