@extends('manager::template.page')

@section('content')
    <div class="notifier">
        <div class="notifier-txt"></div>
    </div>
    <h1><i class="fa fa-store"></i> {{$_lang['scommerce_title']}}</h1>
    <p style="margin-left:15px;">{!!$_lang['scommerce_description']!!}</p>

    @include('partials.formStart')
    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script>tpResources = new WebFXTabPane(document.getElementById('resourcesPane'), false);</script>

            <div class="tab-page productsTab" id="productsTab">
                <h2 class="tab">
                    <a href="{!!$url!!}&get=products">
                        <span><i class="fa fa-store"></i> {{$_lang['scommerce_products']}}</span>
                    </a>
                </h2>
                <script>tpResources.addTabPage(document.getElementById('productsTab'));</script>
                @if($get == 'products')
                    @include('productsTab')
                @endif
            </div>

            @if(in_array($get, ['product']))
                <div class="tab-page productTab" id="productTab">
                    <h2 class="tab">
                        <a href="{!!$url!!}&get=product&i={{$product->product}}">
                            <span><i class="fa fa-meteor"></i> {{$_lang['scommerce_product']}}</span>
                        </a>
                    </h2>
                    <script>tpResources.addTabPage(document.getElementById('productTab'));</script>
                    @if($get == 'product')
                        @include('productTab')
                    @endif
                </div>

                @foreach($sCommerce->langTabs() as $lang => $tabName)
                    <div class="tab-page productTexts{{$lang}}Tab" id="productTexts{{$lang}}Tab">
                        <h2 class="tab"><i class="fa fa-flag"></i> {{$tabName}}</h2>
                        <script>tpResources.addTabPage(document.getElementById('productTexts{{$lang}}Tab'));</script>
                        @include('productTextsTab')
                    </div>
                @endforeach
            @endif

            <script>tpResources.setSelectedTab('{{$get}}Tab');</script>
        </div>
    </div>
    @include('partials.formEnd')
    <img src="{{evo()->getConfig('site_url', '/')}}assets/snippets/phpthumb/noimage.png" id="img-preview"
         style="display: none;" class="post-thumbnail">

    <div id="copyright">
        {!!$_lang['scommerce_copyright']!!} <strong><a href="https://seigerit.com/" target="_blank">Seiger IT</a></strong>
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

            $('.js_add_tag').on('click', function () {
                var _value = $(document).find('[name="add_tag"]').val();
                jQuery.ajax({
                    url: '{!!$url!!}&get=addTag',
                    type: 'POST',
                    data: 'value=' + _value,
                });
                $('#addTag').modal('hide');
            });

            $(document).on("click", ".js_translate", function () {
                var _this = $(this).parents('td');
                var source = _this.data('id');
                var target = _this.data('lang');
                $.ajax({
                    url: '{!!$url!!}&get=translate',
                    type: 'POST',
                    data: 'source=' + source + '&target=' + target,
                    success: function (ajax) {
                        _this.find('input').val(ajax);
                    }
                });
            });

            jQuery(".sectionTrans").on("blur", "input", function () {
                var _this = $(this).parents('td');
                var source = _this.data('id');
                var target = _this.data('lang');
                var _value = _this.find('input').val();
                jQuery.ajax({
                    url: '{!!$url!!}&get=update',
                    type: 'POST',
                    data: 'source=' + source + '&target=' + target + '&value=' + _value,
                });
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