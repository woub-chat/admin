<?php

namespace Admin\Controllers;

use Admin\Delegates\Alert;
use Illuminate\Support\Str;
use Lang;
use Admin\Delegates\Card;
use Admin\Delegates\Form;
use Admin\Delegates\ModelInfoTable;
use Admin\Delegates\ModelTable;
use Admin\Delegates\SearchForm;
use Admin\Models\AdminPermission;
use Admin\Models\AdminRole;
use Admin\Page;

class PermissionController extends Controller
{
    /**
     * @var string
     */
    public static $model = AdminPermission::class;

    /**
     * @var string[]
     */
    public $method_colors = [
        '*' => 'dark',
        'GET' => 'info',
        'POST' => 'primary',
        'PUT' => 'warning',
        'DELETE' => 'danger',
    ];

    /**
     * @param  Page  $page
     * @param  Card  $card
     * @param  SearchForm  $searchForm
     * @param  ModelTable  $modelTable
     * @return Page
     */
    public function index(Page $page, Card $card, SearchForm $searchForm, ModelTable $modelTable)
    {
        return $page
            ->card(
                $card->search_form(
                    $searchForm->id(),
                    $searchForm->input('path', 'admin.path'),
                    $searchForm->select('admin_role_id', 'admin.role')
                        ->options(AdminRole::all()->pluck('name', 'id'))->nullable(),
                    $searchForm->at(),
                ),
                $card->model_table(
                    $modelTable->id(),
                    $modelTable->col('admin.path', 'path')->badge('success'),
                    $modelTable->col('admin.methods', [$this, 'show_methods'])->sort('method'),
                    $modelTable->col('admin.state', [$this, 'show_state'])->sort('state'),
                    $modelTable->col('admin.role', 'role.name')->sort('role_id'),
                    $modelTable->col('admin.description', 'description')->str_limit(50)->to_hide(),
                    $modelTable->active_switcher(),
                    $modelTable->updated_at()->to_hide(),
                    $modelTable->created_at(),
                ),
            );
    }

    /**
     * @param  Page  $page
     * @param  Card  $card
     * @param  Form  $form
     * @return Page
     */
    public function matrix(Page $page, Card $card, Form $form)
    {
        return $page
            ->card(
                $card->form(
                    $form->ifEdit()->info_id(),
                    $form->input('path', 'admin.path')
                        ->required(),
                    $form->multi_select('method[]', 'admin.methods')
                        ->options(collect($this->method_colors)->map(function ($i, $k) {
                            return __("admin.method_$k");
                        })->toArray())
                        ->required(),
                    $form->radios('state', 'admin.state')
                        ->options(['close' => __('admin.close'), 'open' => __('admin.open')], true),
                    $form->radios('admin_role_id', 'admin.role')
                        ->options(AdminRole::all()->pluck('name', 'id'), true),
                    $form->input('description', 'admin.description'),
                    $form->switcher('active', 'admin.active')->switchSize('mini')
                        ->default(1),
                    $form->ifEdit()->info_updated_at(),
                    $form->ifEdit()->info_created_at(),


                    $form->dual_select('test0', 'test0')->options(['1' => '1', '2' => '2']),
                    $form->checks('test1', 'test1')->options(['1' => '1', '2' => '2']),
                    $form->select_tags('test10', 'test10')->options(['1' => '1', '2' => '2']),
                    $form->ckeditor('test2', 'test2'),
                    $form->divider(),
                    $form->divider('1'),
                    $form->divider(null, '2'),
                    $form->divider(null, null, '3'),
                    $form->codemirror('test3', 'test3'),
                    $form->color('test4', 'test4'),
                    $form->date('test5', 'test5'),
                    $form->date_range('test6', 'test6'),
                    $form->hidden('test7', 'test7')->value(123),
                    $form->mdeditor('test8', 'test8'),
                    $form->number('test9', 'test9')->min(0),
                    $form->slider('test11', 'test11')->min(0),
                    $form->textarea('test12', 'test12')->rows(10),
                    $form->time('test13', 'test13'),
                    $form->password('password', 'password')->confirm(),
                ),
                $card->card_body()->p(
                    Str::markdown(__('admin.permission_instruction'))
                ),
                $card->footer_form(),
            );
    }

    /**
     * @param  Page  $page
     * @param  Card  $card
     * @param  ModelInfoTable  $modelInfoTable
     * @return Page
     */
    public function show(Page $page, Card $card, ModelInfoTable $modelInfoTable, Alert $alert)
    {
        return $page
            ->card(
                $card->model_info_table(
                    $modelInfoTable->id(),
                    $modelInfoTable->row('admin.path', 'path')->badge('success'),
                    $modelInfoTable->row('admin.methods', [$this, 'show_methods']),
                    $modelInfoTable->row('admin.state', [$this, 'show_state']),
                    $modelInfoTable->row('admin.role', 'role.name')->info('test'),
                    $modelInfoTable->row('admin.active', 'active')->yes_no(),
                    $modelInfoTable->at(),
                )
            )->alert(
                $alert->title('admin.error')
                    ->body('admin.access_denied')
                    ->dangerType()
                    ->icon_exclamation_triangle()
                    ->mt3()
                    ->w100(),
            );
    }

    /**
     * @param  AdminPermission  $permission
     * @return string
     */
    public function show_methods(AdminPermission $permission)
    {
        return collect($permission->method)->map(function ($i) {
            return "<span class=\"badge badge-".($this->method_colors[$i] ?? 'light')."\">".
                (Lang::has("admin.method_$i") ? __("admin.method_$i") : $i).'</span>';
        })->implode(' ');
    }

    /**
     * @param  AdminPermission  $permission
     * @return string
     */
    public function show_state(AdminPermission $permission)
    {
        return '<span class="badge badge-'.($permission->state === 'open' ? 'success' : 'danger').'">'.($permission->state === 'open' ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>').' '.__("admin.{$permission->state}").'</span>';
    }

    /**
     * @return array
     */
    public function permissionInfo(): array
    {
        $card = new Card;

        return [
            $card->title('Instructions')->successType(),
            $card->card_body()->p(Str::markdown(__('admin.permission_instruction')))
        ];
    }
}
