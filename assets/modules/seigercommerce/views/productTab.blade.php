<h3>{{$product->pagetitle ?? $_lang['scommerce_new_product']}}</h3>
<div class="split my-3"></div>
<div class="row form-row">
    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row form-row-checkbox">
            <div class="col-auto col-title">
                <label for="publishedcheck" class="warning" data-key="published">{{$_lang['resource_opt_published']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_published_help']}}"></i>
            </div>
            <div class="col">
                <input type="checkbox" id="publishedcheck" class="form-checkbox form-control " name="publishedcheck" maxlength="255" value="" onchange="documentDirty=true;" onclick="changestate(document.product.published);" @if(isset($product->published) && $product->published) checked @endif>
                <input type="hidden" id="published" class="form-control" name="published" maxlength="255" value="{{$product->published ?? 0}}" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="availability" class="warning" data-key="availability">{{$_lang["scommerce_availability"]}}</label>
            </div>
            <div class="col">
                <select id="availability" class="form-control" name="availability" onchange="documentDirty=true;">
                    @foreach(\sCommerce\Models\sProduct::listAvailability() as $key => $title)
                        <option value="{{$key}}" @if($key == ($product->availability ?? 0)) selected @endif>{{$title}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="status" class="warning" data-key="status">{{$_lang["scommerce_status"]}}</label>
            </div>
            <div class="col">
                <select id="status" class="form-control" name="status" onchange="documentDirty=true;">
                    @foreach(\sCommerce\Models\sProduct::listStatus() as $key => $title)
                        <option value="{{$key}}" @if($key == ($product->status ?? '')) selected @endif>{{$title}}</option>
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
                    <span class="btn btn-secondary" onclick="var elm = document.product.position;var v=parseInt(elm.value+'')-1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;" style="cursor: pointer;"><i class="fa fa-angle-left"></i></span>
                    <span class="btn btn-secondary" onclick="var elm = document.product.position;var v=parseInt(elm.value+'')+1;elm.value=v>0? v:0;elm.focus();documentDirty=true;return false;" style="cursor: pointer;"><i class="fa fa-angle-right"></i></span>
                </div>
                <input type="text" id="position" name="position" class="form-control" value="{{$product->position ?? 0}}" maxlength="11" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="price" class="warning" data-key="price">{{$_lang["scommerce_price"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_price_help']}}"></i>
            </div>
            <div class="input-group col">
                <span class="input-group-text">₴</span>
                <input type="text" id="price" name="price" class="form-control" value="{{$product->price ?? 0.00}}" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="price_old" class="warning" data-key="price_old">{{$_lang["scommerce_price_old"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_price_old_help']}}"></i>
            </div>
            <div class="input-group col">
                <span class="input-group-text">₴</span>
                <input type="text" id="price_old" name="price_old" class="form-control" value="{{$product->price_old ?? 0.00}}" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="code" class="warning" data-key="code">{{$_lang["scommerce_code"]}}</label>
            </div>
            <div class="col">
                <input type="text" id="code" name="code" class="form-control" value="{{$product->code ?? ''}}" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang["type"]}}</label>
            </div>
            <div class="col">
                <select id="type" class="form-control" name="type" onchange="documentDirty=true;">
                    @foreach(\sCommerce\Models\sProduct::listType() as $key => $title)
                        <option value="{{$key}}" @if($key == ($product->type ?? 0)) selected @endif>{{$title}}</option>
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
            <div class="input-group col">
                <input type="text" id="alias" class="form-control" name="alias" maxlength="255" value="{{$product->alias ?? ''}}" onchange="documentDirty=true;" spellcheck="true">
                <a id="preview" href="{{$product->link ?? '/'}}" class="btn btn-outline-secondary form-control" type="button" target="_blank">{{$_lang["preview"]}}</a>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="category" class="warning" data-key="category">{{$_lang["scommerce_category"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_category_help']}}"></i>
            </div>
            <div class="col">
                <select id="category" class="form-control" name="category" onchange="documentDirty=true;">
                    @foreach($sCommerce->listCategories() as $key => $value)
                        <option value="{{$key}}" @if($key == ($product->category ?? evo()->getConfig('catalog_root', evo()->getConfig('site_start', 1)))) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row form-row-image">
            <div class="col-auto col-title">
                <label for="cover" class="warning" data-key="cover">{{$_lang['scommerce_image']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["scommerce_image_help"]}}"></i>
            </div>
            <div class="col">
                <input type="text" id="cover" class="form-control" name="cover" value="{{$product->cover ?? ''}}" onchange="documentDirty=true;">
                <input class="form-control" type="button" value="{{$_lang["insert"]}}" onclick="BrowseServer('cover')">
                <div class="col-12">
                    <div id="image_for_cover" class="image_for_field" data-image="{{$product->coverSrc ?? ''}}" onclick="BrowseServer('cover')" style="background-image: url('{{$product->coverSrc ?? ''}}');"></div>
                    <script>document.getElementById('cover').addEventListener('change', evoRenderImageCheck, false);</script>
                </div>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="categories" class="warning" data-key="categories">{{$_lang["scommerce_categories"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["scommerce_categories_help"]}}"></i>
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

    @if($product->product)
        <div class="row-col col-12">
            <div class="row form-row">
                <div class="col-auto col-title">
                    <label for="categories" class="warning" data-key="categories">{{$_lang["scommerce_gallery"]}}</label>
                    <i class="fa fa-question-circle" data-tooltip="{{$_lang["scommerce_gallery_help"]}}"></i>
                </div>
                <div class="col">
                    {!! sGallery::initialise('section', 'product', 'i') !!}
                </div>
            </div>
        </div>
    @endif

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="categories" class="warning">{{$_lang["scommerce_recommend"]}}</label>
            </div>
            <div class="col">
                @php($recommends = Str::of($product->recommend)->explode(','))
                <select id="recommends" class="form-control select2" name="recommends[]" multiple onchange="documentDirty=true;">
                    @foreach($sCommerce->productsAll()->where('product', '!=', request()->i) as $value)
                        <option value="{{$value->product}}" @if(Arr::has($recommends, $value->product)) selected @endif>{{$value->pagetitle}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="categories" class="warning">{{$_lang["scommerce_similar"]}}</label>
            </div>
            <div class="col">
                @php($similars = Str::of($product->similar)->explode(','))
                <select id="similars" class="form-control select2" name="similars[]" multiple onchange="documentDirty=true;">
                    @foreach($sCommerce->productsAll()->where('product', '!=', request()->i) as $value)
                        <option value="{{$value->product}}" @if(Arr::has($similars, $value->product)) selected @endif>{{$value->pagetitle}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="categories" class="warning">{{$_lang["scommerce_rating"]}}</label>
            </div>
            <div class="col">
                <select id="rating" class="form-control select2" name="rating" onchange="documentDirty=true;">
                    @foreach([0, 1, 2, 3, 4, 5] as $value)
                        <option value="{{$value}}" @if($product->rating == $value) selected @endif>{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="split my-3"></div>

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#product');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
            <a id="Button3" class="btn btn-secondary" href="{!!$url!!}">
                <i class="fa fa-times-circle"></i><span>{{$_lang["cancel"]}}</span>
            </a>
        </div>
    </div>
@endpush