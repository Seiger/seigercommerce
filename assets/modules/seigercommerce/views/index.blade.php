@extends('manager::template.page')
@section('content')
    <div class="notifier">
        <div class="notifier-txt"></div>
    </div>
    <h1><i class="fa fa-store" data-tooltip="{{$_lang["scommerce_description"]}}"></i> {{$_lang['scommerce_title']}}</h1>

    @include('partials.formStart')
    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script>tpResources = new WebFXTabPane(document.getElementById('resourcesPane'), false);</script>

            @if(!in_array($get, ['filter', 'filterValues']))
                <div class="tab-page productsTab" id="productsTab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get=products">
                            <span><i class="fa fa-store" data-tooltip="{{$_lang["scommerce_products_help"]}}"></i> {{$_lang['scommerce_products']}}</span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('productsTab'));</script>
                    @if($get == 'products')
                        @include('productsTab')
                    @endif
                </div>
            @endif

            @if(in_array($get, ['product']))
                <div class="tab-page productTab" id="productTab">
                    <h2 class="tab">
                        <span><i class="fa fa-meteor"></i> {{$_lang['scommerce_product']}}</span>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('productTab'));</script>
                    @if($get == 'product')
                        @include('productTab')
                    @endif
                </div>

                @if(
                    in_array($product->type, [
                        \sCommerce\Models\sProduct::TYPE_OPTIONAL
                    ])
                    )
                    <div class="tab-page productVariationsTab" id="productVariationsTab">
                        <h2 class="tab">
                            <span><i class="fas fa-code-branch"></i> {{$_lang['scommerce_variations']}}</span>
                        </h2>
                        <script>tpResources.addTabPage(document.getElementById('productVariationsTab'));</script>
                        @include('productVariationsTab')
                    </div>
                @endif

                <div class="tab-page productFeaturesTab" id="productFeaturesTab">
                    <h2 class="tab"><i class="fa fa-puzzle-piece"></i> {{$_lang['scommerce_features']}}</h2>
                    <script>tpResources.addTabPage(document.getElementById('productFeaturesTab'));</script>
                    @include('productFeaturesTab')
                </div>

                @foreach($sCommerce->langTabs() as $lang => $tabName)
                    <div class="tab-page productTexts{{$lang}}Tab" id="productTexts{{$lang}}Tab">
                        <h2 class="tab"><i class="fa fa-flag"></i> {!! $tabName !!}</h2>
                        <script>tpResources.addTabPage(document.getElementById('productTexts{{$lang}}Tab'));</script>
                        @include('productTextsTab')
                    </div>
                @endforeach
            @endif

            @if(!in_array($get, ['product']))
                <div class="tab-page filtersTab" id="filtersTab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get=filters">
                            <span><i class="fa fa-filter" data-tooltip="{{$_lang["scommerce_filters_help"]}}"></i> {{$_lang['scommerce_filters']}}</span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('filtersTab'));</script>
                    @if($get == 'filters')
                        @include('filtersTab')
                    @endif
                </div>
            @endif

            @if(in_array($get, ['filter', 'filterValues']))
                <div class="tab-page filterTab" id="filterTab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get=filter&i={{$filter->filter}}">
                            <span><i class="fa fa-tools"></i> {{$_lang['scommerce_filter']}}</span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('filterTab'));</script>
                    @if($get == 'filter')
                        @include('filterTab')
                    @endif
                </div>

                @if(
                    in_array($filter->type_select, [
                        \sCommerce\Models\sFilter::STYPE_SELECT,
                        \sCommerce\Models\sFilter::STYPE_MULTISELECT
                        ])
                    )
                    <div class="tab-page filterValuesTab" id="filterValuesTab">
                        <h2 class="tab">
                            <a href="{!!$url!!}&get=filterValues&i={{$filter->filter}}">
                                <span><i class="fa fa-cloud"></i> {{$_lang['tmplvars_elements']}}</span>
                            </a>
                        </h2>
                        <script>tpResources.addTabPage(document.getElementById('filterValuesTab'));</script>
                        @if($get == 'filterValues')
                            @include('filterValuesTab')
                        @endif
                    </div>
                @endif
            @endif

            @if(!in_array($get, ['product', 'filter', 'filterValues']))
                <div class="tab-page configsTab" id="configsTab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get=configs">
                            <span><i class="fa fa-cog" data-tooltip="{{$_lang["scommerce_configs_help"]}}"></i> {{$_lang['scommerce_configs']}}</span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('configsTab'));</script>
                    @if($get == 'configs')
                        @include('configsTab')
                    @endif
                </div>

                @if(in_array($get, ['template']))
                    <div class="tab-page templateTab" id="templateTab">
                        <h2 class="tab">
                            <a href="{{$url}}&get=template&i={{$template['base']->name}}">
                                <span><i class="fab fa-mailchimp"></i> {{$template['base']->title}}</span>
                            </a>
                        </h2>
                        <script>tpResources.addTabPage(document.getElementById('templateTab'));</script>
                        @include('templateTab')
                    </div>
                @endif
            @endif

            <script>tpResources.setSelectedTab('{{$get}}Tab');</script>
        </div>
    </div>
    @include('partials.formEnd')
    <img src="{{evo()->getConfig('site_url', '/')}}assets/modules/seigercommerce/images/noimage.png" id="img-preview"
         style="display: none;" class="post-thumbnail">

    <div id="copyright">
        <span class="badge bg-seigerit">
        {!!$_lang['scommerce_copyright']!!} <strong><a href="https://seigerit.com/" target="_blank">Seiger IT</a></strong>
        </span>
    </div>
@endsection

@push('scripts.top')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @include('partials.style')
    <script>
        function evoRenderImageCheck(a) {
            console.log(a);
            var b = document.getElementById('image_for_' + a.target.id),
                c = new Image;
            a.target.value ? (c.src = "<?php echo evo()->getConfig('site_url')?>" + a.target.value, c.onerror = function () {
                b.style.backgroundImage = '', b.setAttribute('data-image', '');
            }, c.onload = function () {
                b.style.backgroundImage = 'url(\'' + this.src + '\')', b.setAttribute('data-image', this.src);
            }) : (b.style.backgroundImage = '', b.setAttribute('data-image', ''));
        }
    </script>
@endpush

@push('scripts.bot')
    {!!$editor!!}
    <script src="media/script/jquery.quicksearch.js"></script>
    <script src="media/script/jquery.nucontextmenu.js"></script>
    <script src="media/script/bootstrap/js/bootstrap.min.js"></script>
    <script src="actions/resources/functions.js"></script>
    <script type="text/javascript" src="media/calendar/datepicker.js"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();

            $(document).on('click', '[data-target="#productTab"], [data-target="#productFeaturesTab"]', function () {
                $('.select2').select2();
            });

            $("table img").on("mouseenter", function () {
                var alt = $(this).attr("alt");
                if (alt.length > 0) {
                    $("#img-preview").attr("src", alt).show();
                }
            });

            $("table img").on("mouseleave", function () {
                $("#img-preview").hide();
            });

            $('#confirmDelete').on('show.bs.modal', function (e) {
                $(this).find('#confirm-id').text($(e.relatedTarget).data('id'));
                $(this).find('#confirm-name').text($(e.relatedTarget).data('name'));
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });

        // Form Validation and Saving
        function saveForm(selector) {
            var errors = 0;
            var messages = "";
            var validates = $(selector + " [data-validate]");
            validates.each(function (k, v) {
                var rule = $(v).attr("data-validate").split(":");
                switch (rule[0]) {
                    case "textNoEmpty": // Not an empty field
                        if ($(v).val().length < 1) {
                            messages = messages + $(v).parent().find(".error-text").text() + "<br/>";
                            $(v).parent().removeClass("is-valid").addClass("is-invalid");
                            errors = errors + 1;
                        } else {
                            $(v).parent().removeClass("is-invalid").addClass("is-valid");
                        }
                        break;
                    case "textMustContainDefault": // Must contain the value of the default language
                        var _default = $(v).parents('tbody').find('[name^="s_lang_default"]').val();
                        _index = $(v).val().indexOf(_default);
                        if (_index >= $(v).val().length || _index < 0 || isNaN(_index)) {
                            messages = messages + $(v).parent().find(".error-text").text() + "<br/>";
                            $(v).parent().removeClass("is-valid").addClass("is-invalid");
                            errors = errors + 1;
                        } else {
                            $(v).parent().removeClass("is-invalid").addClass("is-valid");
                        }
                        break;
                    case "textMustContainSiteLang": // Must contain site language list values
                        var _default = $(v).parents('tbody').find('[name^="s_lang_default"]').val();
                        var _config = $(v).parents('tbody').find('[name^="s_lang_config"]').val();
                        var _valid = 1;
                        _index = $(v).val().indexOf(_default);
                        $(v).val().forEach(function (val) {
                            if (_config.indexOf(val) < 0) {
                                return _valid = 0;
                            }
                        });
                        if (_index >= $(v).val().length || _index < 0 || isNaN(_index) || _valid < 1) {
                            messages = messages + $(v).parent().find(".error-text").text() + "<br/>";
                            $(v).parent().removeClass("is-valid").addClass("is-invalid");
                            errors = errors + 1;
                        } else {
                            $(v).parent().removeClass("is-invalid").addClass("is-valid");
                        }
                        break;
                }
            });
            if (errors == 0) {
                $(selector).submit();
            } else {
                $('.notifier').addClass("notifier-error");
                $('.notifier').fadeIn(500);
                $('.notifier').find('.notifier-txt').html(messages);
                setTimeout(function () {
                    $('.notifier').fadeOut(5000);
                }, 2000);
                setTimeout(function () {
                    $('.notifier').removeClass("notifier-error");
                }, 5000);
            }
        }

        var dpOffset = -10;
        var dpformat = 'YYYY-mm-dd hh:mm:00';
        var dpdayNames = {!!$_lang["dp_dayNames"]!!};
        var dpmonthNames = {!!$_lang["dp_monthNames"]!!};
        var dpstartDay = 1;
        var DatePickers = document.querySelectorAll('input.DatePicker');
        if (DatePickers) {
            for (var i = 0; i < DatePickers.length; i++) {
                let format = DatePickers[i].getAttribute("data-format");
                new DatePicker(DatePickers[i], {
                    yearOffset: dpOffset,
                    format: format !== null ? format : dpformat,
                    dayNames: dpdayNames,
                    monthNames: dpmonthNames,
                    startDay: dpstartDay
                });
            }
        }

        function changestate(el) {
            if (parseInt(el.value) === 1) {
                el.value = 0;
            } else {
                el.value = 1;
            }
            documentDirty = true;
        }
    </script>
@endpush