<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mutable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, Mutable;
    use Eloquence {
        Eloquence::save insteadof EntrustUserTrait;
    }

    protected $getterMutators = [
        'first_name' => 'ucfirst',
        'last_name' => 'ucfirst'
    ];

    protected $setterMutators = [
        'first_name' => 'strtolower',
        'last_name' => 'strtolower'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'mobile_number', 'email', 'password', 'avatar', 'facebook_id', 'device_type', 'push_token', 'time_zone', 'last_login_at', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'last_login_at'
    ];

    public function getAvatarAttribute($value)
    {
        return $value ?: \asset(\env('USER_DEFAULT_AVATAR'));
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

    public function doFileUpload($fileKey, $dbColumn, &$modelObj, $saveIt = false, $fileSystem = 's3', $additionalCondition = true)
    {
        if ($additionalCondition && \request()->hasFile($fileKey)) {
            $file = \request()->file($fileKey);
            if ($file->isValid()) {
                $fileName = \env('USER_AVATAR_PREFIX') . \time() . '.' . $file->getClientOriginalExtension();

                if ($fileSystem === 's3') {
                    $s3 = \Storage::disk('s3');
                    $filePath = \env('AWS_USER_BUCKET_NAME') . $fileName;
                    !$s3->put($filePath, \file_get_contents($file), 'public') || $modelObj->$dbColumn = \env('AWS_URL') . \env('AWS_BUCKET') . $filePath;
                } else {
                    $userDir = $this->getMyUploadDirFor();
                    $file->storeAs($userDir, $fileName);
                    !@$modelObj->getAttributes()[$dbColumn] || \Storage::delete($userDir . \pathinfo($modelObj->getAttributes()[$dbColumn])['basename']);
                    $modelObj->$dbColumn = \asset($this->getMyUploadDirFor(true) . $fileName);
                }

                !$saveIt || $modelObj->save();
            }
        }
    }

    public function getMyUploadDirFor($toDisplay = false, $dir = ''): string
    {
        return \env($toDisplay ? 'USER_UPLOAD_DISPLAY_PATH' : 'USER_UPLOAD_PATH') . $this->id . $dir . '/';
    }

    public static function checkField($field = 'email', $id = null)
    {
        $user = self::where($field, \request()->get($field));
        !$id || $user = $user->where('id', '!=', $id);
        return $user->first() ? 'false' : 'true';
    }
}
