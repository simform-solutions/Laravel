<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile_number', 'email', 'password', 'avatar', 'facebook_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getAvatarAttribute($value)
    {
        return $value ?: asset(env('USER_DEFAULT_AVATAR'));
    }

    public function getEmailForPasswordReset()
    {
        return ('api' === \request()->route()->getPrefix()) ? $this->mobile_number : $this->email;
    }

    public function fullName()
    {
        return ucwords($this->first_name . ' ' . $this->last_name);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime((string)$value))->diffForHumans();
    }

    public function doFileUpload($fileKey, $dbColumn, &$modelObj, $saveIt = false, $additionalCondition = true)
    {
        if ($additionalCondition && request()->hasFile($fileKey)) {
            $file = request()->file($fileKey);
            if ($file->isValid()) {
                $userDir = $this->getMyUploadDirFor();
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs($userDir, $fileName);
                !@$modelObj->getAttributes()[$dbColumn] || \Storage::delete($userDir . $modelObj->getAttributes()[$dbColumn]);
                $modelObj->$dbColumn = asset($this->getMyUploadDirFor(true) . $fileName);
                !$saveIt || $modelObj->save();
            }
        }
    }

    public function getMyUploadDirFor($toDisplay = false, $dir = ''): string
    {
        return env($toDisplay ? 'USER_UPLOAD_DISPLAY_PATH' : 'USER_UPLOAD_PATH') . $this->id . $dir . '/';
    }

    public static function checkField($field = 'email', $id = null)
    {
        $user = self::where($field, \request()->get($field));
        !$id || $user = $user->where('id', '!=', $id);
        return $user->first() ? 'false' : 'true';
    }
}
