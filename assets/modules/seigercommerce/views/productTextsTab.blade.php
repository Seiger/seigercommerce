@if($lang != 'base')<h4><b>{{$_lang['slang_lang_'.$lang]}} {{$_lang['scommerce_lang']}}</b></h4>@endif
<div class="row form-row">
    <div class="row-col col-lg-12 col-12">
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
                <label for="{{$lang}}_introtext" class="warning" data-key="introtext">{{$_lang["resource_summary"]}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang["resource_summary_help"]}}"></i>
            </div>
            <div class="col">
                <textarea id="{{$lang}}_introtext" class="form-control" name="texts[{{$lang}}][introtext]" rows="5" wrap="soft" onchange="documentDirty=true;">{{$texts[$lang]['introtext'] ?? ''}}</textarea>
            </div>
        </div>

        <div class="row form-row form-row-richtext">
            <div class="col-auto col-title">
                <label for="{{$lang}}_content" class="warning" data-key="content">{{$_lang["resource_content"]}}</label>
            </div>
            <div class="col">
                <textarea id="{{$lang}}_content" class="form-control" name="texts[{{$lang}}][content]" cols="40" rows="15" onchange="documentDirty=true;">{{$texts[$lang]['content'] ?? ''}}</textarea>
            </div>
        </div>

        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="{{$lang}}_seotitle" class="warning" data-key="{{$lang}}_seotitle">{{$_lang['scommerce_seotitle']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_seotitle_help']}}"></i>
            </div>
            <div class="col">
                <div class="input-group">
                    <input type="text" id="{{$lang}}_seotitle" class="form-control" name="texts[{{$lang}}][seotitle]" maxlength="255" value="{{$texts[$lang]['seotitle'] ?? ''}}" onchange="documentDirty=true;" spellcheck="true">
                </div>
            </div>
        </div>

        <div class="row form-row">
            <div class="col-auto col-title"><label for="seodescription" class="warning" data-key="{{$lang}}_seodescription">{{$_lang['scommerce_seodescription']}}</label>
                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_seodescription_help']}}"></i>
            </div>
            <div class="col" data-lang="{{$lang}}">
                <div class="input-group">
                    <textarea id="{{$lang}}_seodescription" class="form-control" name="texts[{{$lang}}][seodescription]" rows="3" wrap="soft" onchange="documentDirty=true;">{{$texts[$lang]['seodescription'] ?? ''}}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>