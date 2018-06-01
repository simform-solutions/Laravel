<?php

namespace App\Http\Controllers\Admin;

use App\User;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManagersController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

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
                return '<a href="' . route('admin.managers.edit', ['manager' => $user->id]) . '" data-toggle="modal" data-target="#defaultModal" class="btn btn-primary waves-effect btn-xs"><i class="material-icons">edit</i></a>';
            })->addColumn('deleteAction', function(User $user) {
                return '<a href="' . route('admin.managers.destroy', ['manager' => $user->id]) . '" data-success-callback="userDeletedSuccess" class="btn btn-danger waves-effect btn-xs confirm-delete"><i class="material-icons">delete</i></a>';
            })->rawColumns([
                'avatar', 'is_active', 'editAction', 'deleteAction'
            ])->make(true);
    }

    public function create()
    {
        return view('admin.managers.form', ['user' => new User()]);
    }

    public function edit($manager)
    {
        return view('admin.managers.form', ['user' => User::find($manager)]);
    }

    public function checkEmail($manager = null)
    {
        return User::checkField('email', $manager);
    }

    public function checkMobile($manager = null)
    {
        return User::checkField('mobile_number', $manager);
    }

    /**
     * @param Request $request
     * @param null $manager
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function store(Request $request, $manager = null)
    {
        $user = $manager ? User::find($manager) : new User();
        $user->fill($request->all());
        if ($isSaved = $user->save()) {
            $manager || $user->attachRole(2);
            $user->doFileUpload('profile_picture', 'avatar', $user, true);
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $manager
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Symfony\Component\HttpFoundation\File\Exception\FileException
     * @throws \Illuminate\Database\Eloquent\MassAssignmentException
     */
    public function update(Request $request, $manager)
    {
        return $this->store($request, $manager);
    }

    public function destroy($manager)
    {
        User::find($manager)->delete();
        return $this->response->withArray([], [], JSON_FORCE_OBJECT);
    }
}
