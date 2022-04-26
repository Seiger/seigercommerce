<h3>{{$filter->pagetitle ?? $_lang['scommerce_new_filter']}} <small>({{$filter->filter ?? 0}})</small></h3>
<div class="row form-row widgets">

</div>

@push('scripts.bot')
    <style>
        .close-icon {cursor:pointer;position:absolute;top:0;right:0;z-index:2;padding:0.6rem 1rem;}
        .draft-value {display:none;}
    </style>
    <script>
        $(document).on("click", ".close-icon", function () {
            $(this).closest('.card').fadeOut();
            documentDirty=true;
        });
        function addFilterValue() {
            $(".widgets").append($('.draft-value').html());
        }
    </script>
    <div id="actions">
        <div class="btn-group">
            <a id="Button2" class="btn btn-primary" href="javascript:void(0);" onclick="addFilterValue();">
                <i class="fa fa-plus"></i>
                <span>{{$_lang['add']}}</span>
            </a>
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#filter');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
        </div>
    </div>
    <div class="draft-value">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    {{$_lang["scommerce_value"]}}
                    <span class="close-icon"><i class="fa fa-times"></i></span>
                </div>
                <div class="card-block">
                    <div class="userstable">
                        <div class="card-body">
                            @foreach($sCommerce->langTabs() as $lang => $tabName)
                                <div class="row form-row">
                                    <div class="col-auto col-title">
                                        <label class="warning">@if($lang != 'base') {{$_lang['slang_lang_'.$lang]}} {{$_lang['scommerce_lang']}}@endif</label>
                                    </div>
                                    <div class="col">
                                        <input type="text" id="{{$lang}}_pagetitle" class="form-control" name="values[{{$lang}}][pagetitle]" maxlength="255" value="" onchange="documentDirty=true;" spellcheck="true">
                                    </div>
                                </div>
                            @endforeach
                            <div class="row form-row">
                                <div class="col-auto col-title">
                                    <label class="warning">{{$_lang["resource_alias"]}}</label>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="values[{{$lang}}][pagetitle]" maxlength="255" value="" onchange="documentDirty=true;" spellcheck="true">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush