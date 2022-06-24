<div class="table-responsive">
    <table class="table table-condensed table-hover sectionTrans">
        <thead>
        <tr>
            <th style="text-align:center;">{{$_lang['scommerce_promo_code']}}</th>
            <th style="width:70px;text-align:center;">{{$_lang['scommerce_discount']}}</th>
            <th style="text-align:center;">{{$_lang['scommerce_validity']}}</th>
            <th style="width:70px;text-align:center;">{{$_lang['scommerce_applied']}}</th>
            <th style="width:70px;text-align:center;">{{$_lang['scommerce_status']}}</th>
            <th id="action-btns">{{$_lang["onlineusers_action"]}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach(\sCommerce\Models\sPromoCode::all() as $promoCode)
            <tr>
                <td>
                    <b>{{$promoCode->code}}</b> <small>({{$promoCode->id}})</small>
                </td>
                <td style="text-align:center;">
                    {{$promoCode->discount}}
                    @if($promoCode->type == \sCommerce\Models\sPromoCode::PTYPE_FIXED)
                        <span class="badge badge-dark">₴</span>
                    @else
                        <span class="badge badge-dark">%</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    {{$_lang['scommerce_from']}} {{\Carbon\Carbon::parse($promoCode->validity_from)->toDateString()}}
                    @if($promoCode->validity_to)
                        {{$_lang['scommerce_to']}} {{\Carbon\Carbon::parse($promoCode->validity_to)->toDateString()}}
                    @endif
                </td>
                <td style="text-align:center;">
                    {{$promoCode->applieds}}
                </td>
                <td>
                    @if($promoCode->published)
                        <span class="badge badge-success">{{$_lang["scommerce_active"]}}</span>
                    @else
                        <span class="badge badge-secondary">{{$_lang["scommerce_inactive"]}}</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    <div class="btn-group">
                        <button href="#" class="btn btn-outline-success js_edit" data-item="{{$promoCode->toJson()}}">
                            <i class="fa fa-pencil"></i> <span>{{$_lang['edit']}}</span>
                        </button>
                        <a href="#" data-href="{{$url}}&get=filterDelete&i={{$promoCode->filter}}" data-toggle="modal" data-target="#confirmDelete" data-id="{{$promoCode->filter}}" data-name="{{$promoCode->pagetitle}}" class="btn btn-outline-danger">
                            <i class="fa fa-trash"></i> <span>{{$_lang['remove']}}</span>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <form name="promoCode" id="promoCode" class="content" method="post" action="{!!$url!!}&get=promoCodeSave" onsubmit="documentDirty=false;">
            <div class="modal-content">
                <div class="modal-header">{{$_lang['scommerce_new_promo_code']}}</div>
                <div class="modal-body">
                    <div class="row-col col-12">
                        <div class="row form-row form-row-checkbox">
                            <div class="col-auto col-title-6">
                                <label for="code" class="warning">{{$_lang['scommerce_promo_code']}}</label>
                            </div>
                            <div class="col">
                                <input type="text" id="code" class="form-control" name="code" maxlength="255" value="" onchange="documentDirty=true;">
                            </div>
                        </div>
                    </div>

                    <div class="row-col col-12">
                        <div class="row form-row form-row-checkbox">
                            <div class="col-auto col-title-6">
                                <label for="discount" class="warning">{{$_lang['scommerce_discount']}}</label>
                                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_discount_help']}}"></i>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <input type="text" id="discount" name="discount" class="form-control">
                                    <span class="input-group-append">
                                        <select class="custom-select" id="type" name="type">
                                            <option value="{{\sCommerce\Models\sPromoCode::PTYPE_PERCENT}}" selected>%</option>
                                            <option value="{{\sCommerce\Models\sPromoCode::PTYPE_FIXED}}">₴</option>
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-col col-12">
                        <div class="row form-row form-row-date">
                            <div class="col-auto col-title-6">
                                <label class="warning">{{$_lang['scommerce_validity']}}</label>
                                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_validity_help']}}"></i>
                            </div>
                            <div class="col">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{$_lang['scommerce_from']}}</span>
                                    </div>
                                    <input type="text" id="validity_from" name="validity_from" class="form-control DatePicker" data-format="dd-mm-YYYY">
                                    <span class="input-group-append">
                                        <a class="btn text-danger" href="javascript:(0);" onclick="documentDirty=true; return true;">
                                            <i class="fa fa-calendar-times-o" title="{{$_lang["remove_date"]}}"></i>
                                        </a>
                                    </span>
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{{$_lang['scommerce_to']}}</span>
                                    </div>
                                    <input type="text" id="validity_to" name="validity_to" class="form-control DatePicker" data-format="dd-mm-YYYY">
                                    <span class="input-group-append">
                                        <a class="btn text-danger" href="javascript:(0);" onclick="documentDirty=true; return true;">
                                            <i class="fa fa-calendar-times-o" title="{{$_lang["remove_date"]}}"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row-col col-12">
                        <div class="row form-row form-row-checkbox">
                            <div class="col-auto col-title">
                                <label for="publishedcheck" class="warning" data-key="published">{{$_lang['resource_opt_published']}}</label>
                                <i class="fa fa-question-circle" data-tooltip="{{$_lang['scommerce_active_help']}}"></i>
                            </div>
                            <div class="col">
                                <input type="checkbox" id="published" class="form-control" name="published" maxlength="255" value="1" onchange="documentDirty=true;"> <small>({{$_lang['scommerce_active']}})</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{$_lang["cancel"]}}</button>
                    <button type="submit" class="btn btn-success">{{$_lang['save']}}</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts.bot')
    <div id="actions">
        <div class="btn-group">
            <a id="Button2" class="btn btn-primary" href="#" data-toggle="modal" data-target="#edit" title="{{$_lang["scommerce_add_help"]}}">
                <i class="fa fa-plus-circle"></i> <span>{{$_lang["scommerce_add"]}}</span>
            </a>
        </div>
    </div>

    <script>
        $(document).on("click", ".js_edit", function () {
            var item = JSON.parse($(this).attr('data-item'));
            $('#code').val(item.code);
            $('#discount').val(item.discount);
            $("#type").val(item.type).change();
            $('#validity_from').val(item.validity_from);
            $('#validity_to').val(item.validity_to);
            if (item.published == 1) {
                $('#published').prop('checked', true);
            } else {
                $('#published').prop('checked', false);
            }
            $('#Button2').trigger('click');
            console.dir(item);
        });
    </script>
@endpush