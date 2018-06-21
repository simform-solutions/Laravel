<?php

namespace App\Http\Controllers\Admin;

use App\Http\Transformers\UserTransformer;
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
                            'class' => 'img-circle',
                            'style' => 'height: 50px !important; width: 50px !important;'
                        ]
                    ),
                    [
                        'data-lightbox' => 'user-avatars',
                        'data-title' => $user->fullName()
                    ], null, false);
            })->editColumn('email', function (User $user) {
                return '<a href="mailto:' . $user->email . '">' . $user->email . '</a>';
            })->editColumn('is_active', function (User $user) {
                return '<div class="switch panel-switch-btn">
                            <label>De-active<input data-url="' . route('admin.managers.update', ['manager' => $user->id]) . '" type="checkbox" ' . ($user->is_active ? 'checked' : '') . ' name="is_active" /><span class="lever switch-col-teal"></span>Active</label>
                        </div>';
            })->addColumn('editAction', function (User $user) {
                return '<a href="' . route('admin.managers.edit', ['manager' => $user->id]) . '" data-toggle="modal" data-target="#defaultModal" class="btn btn-primary waves-effect btn-xs"><i class="material-icons">edit</i></a>';
            })->addColumn('deleteAction', function (User $user) {
                return '<a href="' . route('admin.managers.destroy', ['manager' => $user->id]) . '" data-success-callback="userDeletedSuccess" class="btn btn-danger waves-effect btn-xs confirm-delete"><i class="material-icons">delete</i></a>';
            })->rawColumns([
                'email', 'avatar', 'is_active', 'editAction', 'deleteAction'
            ])->make(true);
    }

    public function create()
    {
        return view('admin.managers.form', ['user' => new User()]);
    }

    public function edit($manager)
    {
        $user = User::find($manager);
        $user->phone_number = substr($user->mobile_number, -10);
        $user->country_code = str_replace($user->phone_number, '', $user->mobile_number);
        return view('admin.managers.form', ['user' => $user]);
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
        return $this->response->withItem($user, new UserTransformer);
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
