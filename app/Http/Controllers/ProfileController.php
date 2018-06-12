<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Transformers\UserTransformer;
use EllipseSynergie\ApiResponse\Laravel\Response;

class ProfileController extends Controller
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function update(ProfileRequest $request)
    {
        $data = $request->all();
        !array_key_exists('password', $data) || !$data['password'] || $data['password'] = bcrypt($data['password']);
        \auth()->user()->fill($data)->save();
        return $this->response->withItem(\auth()->user(), new UserTransformer);
    }
}
