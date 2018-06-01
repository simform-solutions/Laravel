<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagersController extends Controller
{
    public function index()
    {
        return view('admin.managers.list');
    }

    public function anyData()
    {
        return \DataTables::of(User::withRole('restaurant_manager'))
            ->editColumn('avatar', function (User $user) {
                return \Html::link(
                            $user->avatar,
                            \Html::image(
                                $user->avatar,
                                'Avatar',
                                [
                                    'class' => 'img-thumbnail img-circle',
                                    'style' => 'height: 50px; width: 50px;'
                                ]
                            ),
                            [
                                'data-lightbox' => 'user-avatars',
                                'data-title' => $user->fullName()
                            ], null, false);
            })->editColumn('is_active', function (User $user) {
                return '<div class="switch panel-switch-btn">
                                    <label>Deactive<input type="checkbox" ' . ($user->is_active ? 'checked' : '') . '><span class="lever switch-col-teal"></span>Active</label>
                                </div>';
            })->addColumn('editAction', function(User $user) {
                return '<a href="' . route('admin.managers.edit', ['manager' => $user->id]) . '" data-toggle="modal" data-target="#myModal" class="btn btn-primary waves-effect btn-xs"><i class="material-icons">edit</i></a>';
            })->addColumn('deleteAction', function(User $user) {
                return '<a href="' . route('admin.managers.destroy', ['manager' => $user->id]) . '" data-success-callback="userDeletedSuccess" class="btn btn-danger waves-effect btn-xs confirm-delete"><i class="material-icons">delete</i></a>';
            })->rawColumns([
                'avatar', 'is_active', 'editAction', 'deleteAction'
            ])->make(true);
    }
}
