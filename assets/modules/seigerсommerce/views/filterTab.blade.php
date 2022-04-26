<h3>{{$filter->pagetitle ?? $_lang['scommerce_new_filter']}}</h3>
<div class="row form-row">
    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang["type"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_type_help']}}"></i>
            </div>
            <div class="col">
                <select id="type" class="form-control" name="type" onchange="documentDirty=true;">
                    @foreach(\sCommerce\Models\sFilter::listType() as $key => $title)
                        <option value="{{$key}}" @if($key == ($filter->type ?? 0)) selected @endif>{{$title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="position" class="warning" data-key="position">{{$_lang["scommerce_sorting"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_sorting_help']}}"></i>
            </div>
            <div class="input-group col">
                <div class="input-group-prepend">
                    <span class="btn btn-secondary" onclick="var elm = document.filter.position;var v=parseInt(elm.value+'')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;" style="cursor: pointer;"><i class="fa fa-angle-left"></i></span>
                    <span class="btn btn-secondary" onclick="var elm = document.filter.position;var v=parseInt(elm.value+'')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;" style="cursor: pointer;"><i class="fa fa-angle-right"></i></span>
                </div>
                <input type="text" id="position" name="position" class="form-control" value="{{$filter->position ?? 0}}" maxlength="11" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang["scommerce_type_select"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_type_select_help']}}"></i>
            </div>
            <div class="col">
                <select id="type" class="form-control" name="type_select" onchange="documentDirty=true;">
                    @foreach(\sCommerce\Models\sFilter::listTypeSelect() as $key => $title)
                        <option value="{{$key}}" @if($key == ($filter->type_select ?? 0)) selected @endif>{{$title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="alias" class="warning" data-key="alias">{{$_lang["resource_alias"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["resource_alias_help"]}}"></i>
            </div>
            <div class="col">
                <input type="text" id="alias" class="form-control" name="alias" maxlength="255" value="{{$filter->alias ?? ''}}" onchange="documentDirty=true;" spellcheck="true">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="categories" class="warning" data-key="categories">{{$_lang["scommerce_categories"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["scommerce_filter_categories_help"]}}"></i>
            </div>
            <div class="col">
                <select id="categories" class="form-control select2" name="categories[]" multiple onchange="documentDirty=true;">
                    @foreach($sCommerce->listCategories() as $key => $value)
                        <option value="{{$key}}" @if(in_array($key, $categories)) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="split my-2"></div>
<div class="row form-row">
    @foreach($sCommerce->langTabs() as $lang => $tabName)
        <div class="row-col col-lg-12 col-12">
            @if($lang != 'base')<h4>{{$_lang['slang_lang_'.$lang]}} {{$_lang['scommerce_lang']}}</h4>@endif

            <div class="row form-row">
                <div class="col-auto col-title">
                    <label for="{{$lang}}_pagetitle" class="warning" data-key="pagetitle">{{$_lang["resource_title"]}}</label>
                    <i class="fa fa-question-circle" data-tooltip="{{$_lang["resource_title_help"]}}"></i>
                </div>
                <div class="col">
                    <input type="text" id="{{$lang}}_pagetitle" class="form-control" name="texts[{{$lang}}][pagetitle]" maxlength="255" value="{{$texts[$lang]['pagetitle'] ?? ''}}" onchange="documentDirty=true;" spellcheck="true">
                </div>
            </div>

            <div class="row form-row">
                <div class="col-auto col-title">
                    <label for="{{$lang}}_introtext" class="warning" data-key="introtext">{{$_lang["description"]}}</label>
                    <i class="fa fa-question-circle" data-tooltip="{{$_lang["resource_summary_help"]}}"></i>
                </div>
                <div class="col">
                    <textarea id="{{$lang}}_introtext" class="form-control" name="texts[{{$lang}}][introtext]" rows="5" wrap="soft" onchange="documentDirty=true;">{{$texts[$lang]['introtext'] ?? ''}}</textarea>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#filter');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
        </div>
    </div>
@endpush