<h3>{{$product->pagetitle ?? $_lang['scommerce_new_product']}}</h3>
<div class="row form-row">
    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row form-row-checkbox">
            <div class="col-auto col-title">
                <label for="publishedcheck" class="warning" data-key="published">{{$_lang['resource_opt_published']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_published_help']}}"></i>
            </div>
            <div class="col">
                <input type="checkbox" id="publishedcheck" class="form-checkbox form-control " name="publishedcheck" maxlength="255" value="" onchange="documentDirty=true;" onclick="changestate(document.post.published);" @if(isset($product->published) && $product->published) checked @endif>
                <input type="hidden" id="published" class="form-control" name="published" maxlength="255" value="{{$product->published ?? 0}}" onchange="documentDirty=true;">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang["scommerce_availability"]}}</label>
                {{--<i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_availability_help']}}"></i>--}}
            </div>
            <div class="col">
                <select id="type" class="form-control" name="type" onchange="documentDirty=true;">
                    {{--@foreach(\sPost\Models\sPostContent::listTypes() as $key => $type)
                        <option value="{{$key}}" @if($key == ($post->type ?? '')) selected @endif>{{$type}} ({{$key}})</option>
                    @endforeach--}}
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang['spost_author']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['spost_author_help']}}"></i>
            </div>
            <div class="col">
                <select id="author" class="form-control" name="author" onchange="documentDirty=true;">
                    @foreach($authors as $author)
                        <option value="{{$author['author']}}" @if($author['author'] == ($post->author ?? '')) selected @endif>{{$author['author_name']}} ({{$author['author']}})</option>
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
                <input type="text" id="alias" class="form-control " name="alias" maxlength="255" value="{{$post->alias ?? ''}}" onchange="documentDirty=true;" spellcheck="true">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="type" class="warning" data-key="type">{{$_lang["type"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['spost_type_help']}}"></i>
            </div>
            <div class="col">
                <select id="type" class="form-control" name="type" onchange="documentDirty=true;">
                    {{--@foreach(\sPost\Models\sPostContent::listTypes() as $key => $type)
                        <option value="{{$key}}" @if($key == ($post->type ?? '')) selected @endif>{{$type}} ({{$key}})</option>
                    @endforeach--}}
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="alias" class="warning" data-key="alias">{{$_lang["spost_tags_list"]}}</label>
            <!--<i class="fa fa-question-circle" data-tooltip="{{$_lang["resource_alias_help"]}}"></i>-->
            </div>
            <div class="col">
                <select id="type" class="form-control select2" name="tags[]" multiple onchange="documentDirty=true;">
                    {{--@foreach(\sPost\Models\sPostTag::all() as $tag)
                        <option value="{{$tag->id}}" @if(in_array($tag->id, $tags)) selected @endif>{!! $tag->{$sPost->langDefault()} !!} ({{$tag->alias}})</option>
                    @endforeach--}}
                </select>
            </div>
        </div>
    </div>

    <div class="row-col col-lg-6 col-md-6 col-12">
        <div class="row form-row form-row-image">
            <div class="col-auto col-title">
                <label for="cover" class="warning" data-key="cover">{{$_lang['spost_image']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["spost_image_help"]}}"></i>
            </div>
            <div class="col">
                <input type="text" id="cover" class="form-control" name="cover" value="{{$post->cover ?? ''}}" onchange="documentDirty=true;">
                <input class="form-control" type="button" value="{{$_lang["insert"]}}" onclick="BrowseServer('cover')">
                <div class="col-12">
                    <div id="image_for_cover" class="image_for_field" data-image="{{evo()->getConfig('site_url', '/')}}{{$post->cover ?? ''}}" onclick="BrowseServer('cover')" style="background-image: url('{{evo()->getConfig('site_url', '/')}}{{$post->cover ?? ''}}');"></div>
                    <script>document.getElementById('cover').addEventListener('change', evoRenderImageCheck, false);</script>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts.bot')
    @include('partials.actionButtons')
@endpush