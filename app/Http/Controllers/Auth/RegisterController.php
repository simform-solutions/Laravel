<?php

namespace App\Http\Controllers\Auth;

use App\Http\Transformers\UserTransformer;
use App\Rules\ValidateImage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * @var Response
     */
    protected $response;

    /**
     * Create a new controller instance.
     * @param Response $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile_number' => 'required|string|max:20|phone|unique:users',
            'mobile_number_country' => 'required_with:mobile_number',
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:30',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required_without:facebook_id|string|min:6|max:20',
            'avatar' => [
                'required',
                new ValidateImage,
                'unique:users'
            ],
            'facebook_id' => 'numeric|unique:users'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create($data);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  mixed $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        if ('api' === $request->route()->getPrefix()) {
            auth()->logout();
            return $this->response->withItem($user, new UserTransformer, null, [], ['X-Session-Token' => encrypt(time())]);
        }
        return redirect()->intended($this->redirectTo);
    }
}
