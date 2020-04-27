<?php

namespace Lar\LteAdmin\Controllers;

use Illuminate\Http\Request;
use Lar\Layout\Respond;
use Lar\LteAdmin\Core\ModelSaver;

/**
 * Class HomeController
 *
 * @package Lar\LteAdmin\Controllers
 */
class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        return view('lte::auth.profile', [
            'page_info' => [
                'icon' => 'fas fa-user',
                'title' => \LteAdmin::user()->name,
            ],
            'breadcrumb' => [
                'Администратор',
                'Профиль'
            ],
            'user' => \LteAdmin::user()
        ]);
    }

    /**
     * @param  Request  $request
     * @param  Respond  $respond
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request, Respond $respond)
    {
        $all = request()->all();

        if ($request->has('ch_password')) {

            $validator = \Validator::make($all, [
                'password' => 'required|confirmed|min:4'
            ]);

            if ($validator->fails()) {

                foreach ($validator->errors()->all() as $item) {

                    $respond->toast_error($item);
                }

                return back()->withErrors($validator);
            }

            else {

                admin()->password = bcrypt($all['password']);

                if (admin()->save()) {

                    $respond->toast_success('Пароль успешно изменен!');

                    return back();
                }
            }
        }

        else {

            $validator = \Validator::make($all, [
                'login' => 'required|min:4',
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {

                foreach ($validator->errors()->all() as $item) {

                    $respond->toast_error($item);
                }

                return back()->withErrors($validator);
            }

            else {

                if (ModelSaver::do(admin(), $all)) {

                    $respond->toast_success('Профиль успешно изменен!');

                    return back();
                }
            }
        }
    }

    /**
     * @param  Respond  $respond
     * @return Respond
     */
    public function logout(Respond $respond)
    {
        \Auth::guard('lte')->logout();

        $respond->redirect(route('lte.login'));

        return $respond;
    }
}
