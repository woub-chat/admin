<?php

namespace Admin\Controllers;

use Illuminate\Http\Request;
use Admin\Delegates\Card;
use Admin\Delegates\ChartJs;
use Admin\Delegates\Form;
use Admin\Delegates\ModelInfoTable;
use Admin\Delegates\ModelTable;
use Admin\Delegates\SearchForm;
use Admin\Delegates\Tab;
use Admin\Models\AdminRole;
use Admin\Models\AdminUser;
use Admin\Page;

class AdministratorsController extends Controller
{
    /**
     * @var string
     */
    public static $model = AdminUser::class;

    /**
     * @param  AdminUser  $user
     * @return string
     */
    public function show_role(AdminUser $user)
    {
        return '<span class="badge badge-success">'.$user->roles->pluck('name')->implode('</span> <span class="badge badge-success">').'</span>';
    }

    public function defaultTools($type)
    {
        return !($type === 'delete' && $this->model()->id == 1);
    }

    /**
     * @param  Page  $page
     * @param  Card  $card
     * @param  SearchForm  $searchForm
     * @param  ModelTable  $modelTable
     * @return Page
     */
    public function index(Page $page, Card $card, SearchForm $searchForm, ModelTable $modelTable)
    {
        return $page->card(
            $card->title('admin.admin_list'),
            $card->search_form(
                $searchForm->id(),
                $searchForm->email('email', 'admin.email_address'),
                $searchForm->input('login', 'admin.login_name'),
                $searchForm->input('name', 'admin.name'),
                $searchForm->at(),
            ),
            $card->model_table(
                $modelTable->id(),
                $modelTable->col('admin.avatar', 'avatar')->avatar(),
                $modelTable->col('admin.role', [$this, 'show_role']),
                $modelTable->col('admin.email_address', 'email')->sort(),
                $modelTable->col('admin.login_name', 'login')->sort(),
                $modelTable->col('admin.name', 'name')->sort(),
                $modelTable->at(),
                $modelTable->controlDelete(static function (AdminUser $user) {
                    return $user->id !== 1 && admin()->id !== $user->id;
                }),
                $modelTable->disableChecks(),
            )
        );
    }

    /**
     * @param  Page  $page
     * @param  Card  $card
     * @param  Form  $form
     * @param  Tab  $tab
     * @return Page
     */
    public function matrix(Page $page, Card $card, Form $form, Tab $tab)
    {
        return $page
            ->card(
                $card->title(['admin.add_admin', 'admin.edit_admin']),
                $card->form(
                    $form->tab(
                        $tab->ifEdit()->info_id(),
                        $tab->image('avatar', 'admin.avatar')->nullable(),
                        $tab->icon_cogs()->title('admin.common'),
                        $tab->input('login', 'admin.login_name')
                            ->required()
                            ->unique(AdminUser::class, 'login', $this->model()->id),
                        $tab->input('name', 'admin.name')->required(),
                        $tab->email('email', 'admin.email_address')
                            ->required()->unique(AdminUser::class, 'email', $this->model()->id),
                        $tab->multi_select('roles[]', 'admin.role')->icon_user_secret()
                            ->options(AdminRole::all()->pluck('name', 'id')),
                        $tab->ifEdit()->info_updated_at(),
                        $tab->ifEdit()->info_created_at(),
                    ),
                    $form->if(admin()->isRootAdmin())->tab(
                        $tab->ifEdit()->info_id(),
                        $tab->icon_key()->title('admin.password'),
                        $tab->password('password', 'admin.new_password')
                            ->confirm()->required_condition($this->isType('create')),
                        $tab->ifEdit()->info_updated_at(),
                        $tab->ifEdit()->info_created_at(),
                    ),
                ),
                $card->footer_form(),
            );
    }

    /**
     * @param  Request  $request
     * @param  Page  $page
     * @param  Card  $card
     * @param  ModelInfoTable  $modelInfoTable
     * @param  Tab  $tab
     * @param  ChartJs  $chartJs
     * @param  SearchForm  $searchForm
     * @return Page
     */
    public function show(
        Request $request,
        Page $page,
        Card $card,
        ModelInfoTable $modelInfoTable,
        Tab $tab,
        ChartJs $chartJs,
        SearchForm $searchForm
    ) {
        $logTitles = $this->model()->logs()->distinct('title')->pluck('title');

        return $page
            ->card(
                $card->model_info_table(
                    $modelInfoTable->id(),
                    $modelInfoTable->row('admin.avatar', 'avatar')->avatar(150),
                    $modelInfoTable->row('admin.role', [$this, 'show_role']),
                    $modelInfoTable->row('admin.email_address', 'email'),
                    $modelInfoTable->row('admin.login_name', 'login'),
                    $modelInfoTable->row('admin.name', 'name'),
                    $modelInfoTable->at(),
                )
            )
            ->card(
                $card->title('admin.activity')->warningType(),
                $card->tab(
                    $tab->icon_clock()->title('admin.timeline'),
                    $tab->with(fn($content) => UserController::timelineComponent(
                        $content,
                        $this->model()->logs(),
                        $this
                    )),
                ),
                $card->tab(
                    $tab->title('admin.activity')->icon_chart_line(),
                    $tab->active($request->has('q')),
                    $tab->chart_js(
                        $chartJs->model($this->model()->logs())
                            ->hasSearch(
                                $searchForm->date_range('created_at', 'admin.created_at')
                                    ->default(implode(' - ', $this->defaultDateRange()))
                            )
                            ->setDefaultDataBetween('created_at', ...$this->defaultDateRange())
                            ->groupDataByAt('created_at')
                            ->withCollection($logTitles, function ($title) {
                                return $this->chart_js->eachPoint($title, static function ($c) use ($title) {
                                    return $c->where('title', $title)->count();
                                });
                            })->miniChart(),
                    )
                ),
                $card->tab(
                    $tab->title('admin.day_activity')->icon_chart_line(),
                    $tab->chart_js(
                        $chartJs->model($this->model()->logs())
                            ->setDataBetween('created_at', now()->startOfDay(), now()->endOfDay())
                            ->groupDataByAt('created_at', 'H:i')
                            ->withCollection($logTitles, function ($title) {
                                return $this->chart_js->eachPoint($title, function ($c) use ($title) {
                                    return $c->where('title', $title)->count();
                                });
                            })->miniChart(),
                    )
                ),
            );
    }

    public function defaultDateRange()
    {
        return [
            now()->subDay()->startOfDay()->toDateString(),
            now()->endOfDay()->toDateString(),
        ];
    }
}
