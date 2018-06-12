<?php

namespace App\Http\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $extra;

    public function __construct(array $extra = [])
    {
        $this->extra = $extra;
    }

    public function transform(User $user)
    {
        return array_merge([
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'avatar' => $user->avatar,
            'last_login' => (string) $user->last_login_at,
            'is_social_account' => !empty($user->facebook_id)
        ], $this->extra);
    }
}
