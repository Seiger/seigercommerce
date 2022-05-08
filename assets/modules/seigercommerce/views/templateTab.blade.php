@php($disabled = (isset($template['base']) && $template['base']->name == 'meta') ? 'disabled' : '')
<h3>{{$template['base']->title ?? $_lang['scommerce_new_template']}} <small>({{$template['base']->name ?? ''}})</small></h3>
<div class="row form-row">
    <div class="row-col col-lg-9 col-md-9 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="alias" class="warning" data-key="alias">{{$_lang["name"]}}</label>
            </div>
            <div class="col">
                <input type="text" id="title" class="form-control" name="title" value="{{$template['base']->title ?? ''}}" {{$disabled}} maxlength="255" onchange="documentDirty=true;" spellcheck="true">
            </div>
        </div>
    </div>

    <div class="row-col col-lg-3 col-md-3 col-12">
        <div class="row form-row">
            <div class="col-auto col-title">
                <label for="alias" class="warning" data-key="alias">{{$_lang["scommerce_identifier"]}}</label>
            </div>
            <div class="col">
                <input type="text" id="alias" class="form-control" name="alias" value="{{$template['base']->name ?? ''}}" {{$disabled}} {{trim($template['base']->name) ? 'disabled' : ''}} maxlength="255" onchange="documentDirty=true;" spellcheck="true">
            </div>
        </div>
    </div>
</div>
<div class="split my-2"></div>

@foreach($sCommerce->langTabs() as $lang => $tabName)
    <h4>{!! $tabName !!}</h4>
    <div class="row form-row">
        <div class="row-col col-12">
            <div class="row form-row">
                <div class="col-auto col-title">
                    <label for="{{$lang}}_subject" class="warning">{{$_lang['scommerce_subject']}}</label>
                    <i class="fa fa-question-circle" data-tooltip="{{$_lang["scommerce_subject_help"]}}"></i>
                </div>
                <div class="col">
                    <input type="text" id="{{$lang}}_subject" class="form-control" name="{{$lang}}_subject" value="{{$template[$lang]->subject ?? ($template['base']->subject ?? '')}}" {{$disabled}} maxlength="255" onchange="documentDirty=true;" spellcheck="true">
                </div>
            </div>
        </div>
    </div>
    <div class="section-editor clearfix">
        @include('manager::form.textareaElement', [
            'name' => $lang.'_template',
            'value' => ($template[$lang]->template ?? ($template['base']->template ?? '')),
            'class' => 'phptextarea',
            'rows' => 20,
            'attributes' => 'onChange="documentDirty=true;" wrap="soft"'
        ])
    </div>
    <div class="split my-2"></div>
@endforeach

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button2" class="btn btn-primary" href="javascript:void(0);">
                <i class="fa fa-plus"></i>
                <span>{{$_lang['scommerce_add_template']}}</span>
            </a>
            <a id="Button1" class="btn btn-success" href="javascript:void(0);" onclick="saveForm('#template');">
                <i class="fa fa-floppy-o"></i>
                <span>{{$_lang['save']}}</span>
            </a>
            <a id="Button3" class="btn btn-secondary" href="{!!$url!!}">
                <i class="fa fa-times-circle"></i><span>{{$_lang["cancel"]}}</span>
            </a>
        </div>
    </div>
@endpush