@if(!request()->has('show_deleted'))
<div class="card-title dropdown dropdown-inline" style="margin: -5px 10px -4px -.625rem;">
    <button type="button" class="btn btn-outline-primary btn-sm" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-left" aria-labelledby="dropdownMenuButton">
        @if($hasHidden)
            @foreach($all_columns as $column)
                @if($column['key'])
                <button class="dropdown-item" type="button" data-click="doc::location" data-param="{{urlWithGet([$column['key'] => (int)$column['hide']])}}">
                    <i class="fas fa-border-{{!$column['hide'] ? 'all':'none'}}"></i> {{__(!$column['hide']? 'lte.hide':'lte.show')}} "{{$column['label']}}"
                </button>
                @endif
            @endforeach
                @if(count(\Lar\LteAdmin\Core\PrepareExport::$columns) || count($actions) || $hasDelete)
                    <hr class="dropdown-divider" />
                @endif
        @endif
        @if(count(\Lar\LteAdmin\Core\PrepareExport::$columns))
            <button class="dropdown-item"
                    type="button"
                    data-table="{{$table_id}}"
                    data-object="{{$object}}"
                    data-order="{{$order_field}}"
                    data-order-type="{{$select_type}}"
                    data-click="table_action::exportToExcel"
                    name="select_{{$table_id}}">
                <i class="fas fa-file-excel"></i> Export to Excel
            </button>
            <button
                class="dropdown-item"
                type="button"
                data-table="{{$table_id}}"
                data-object="{{$object}}"
                data-order="{{$order_field}}"
                data-order-type="{{$select_type}}"
                data-click="table_action::exportToCsv"
                name="select_{{$table_id}}">
                <i class="fas fa-file-csv"></i> Export to CSV
            </button>
            @if(count($actions) || $hasDelete)
                <hr class="dropdown-divider" />
            @endif
        @endif

        @if(count($actions))
            @foreach($actions as $action)
                <button class="dropdown-item"
                        data-click="table_action"
                        data-table="{{$table_id}}"
                        data-object="{{$object}}"
                        data-columns="{{json_encode($columns, JSON_UNESCAPED_UNICODE)}}"
                        @if(isset($action['warning']) && $action['warning']) data-warning="@lang($action['warning'])" @endif
                        @if(isset($action['confirm']) && $action['confirm']) data-confirm="@lang($action['confirm'])" @endif
                        data-jax="{{$action['jax']}}"
                        type="button"
                >
                    @if(isset($action['icon']) && $action['icon']) <i class="{{$action['icon']}}"></i>&nbsp;@endif
                    @if(isset($action['title']) && $action['title']) @lang($action['title']) @endif
                </button>
            @endforeach
            <hr class="dropdown-divider" />
        @endif
        @if($hasDelete)
            <button class="dropdown-item"
                    data-click="table_action"
                    data-table="{{$table_id}}"
                    data-object="{{$object}}"
                    data-order="{{$order_field}}"
                    data-order-type="{{$select_type}}"
                    data-columns="{{json_encode($all_columns, JSON_UNESCAPED_UNICODE)}}"
                    data-confirm="@lang('lte.delete_selected_rows')"
                    data-warning="@lang('lte.before_need_to_select')"
                    data-jax="lte_admin.mass_delete"
                    type="button"
            ><i class="fas fa-trash"></i>&nbsp; @lang('lte.delete')</button>
            <hr class="dropdown-divider" />
        @endif
        @if($hasDelete || count($actions))
            <button class="dropdown-item" type="button" data-click="table_list::invertChildCheckboxes" name="select_{{$table_id}}">
                <i class="fas fa-vote-yea"></i> Invert selections
            </button>
            <button class="dropdown-item" type="button" data-click="table_list::checkChildCheckboxes" name="select_{{$table_id}}">
                <i class="far fa-check-square icon"></i> Select all
            </button>
            <button class="dropdown-item" type="button" data-click="table_list::uncheckChildCheckboxes" name="select_{{$table_id}}">
                <i class="far fa-square"></i> Unselect all
            </button>
        @endif
    </div>
</div>
@endif