<?php

namespace ImguBox;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, Authorizable, CanResetPassword, SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'password', 'imgur_username'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return    Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * @return    Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    /**
     * @return    Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dropboxToken()
    {
        return $this->hasOne(Token::class)->isDropboxToken();
    }

    /**
     * @return    Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function imgurToken()
    {
        return $this->hasOne(Token::class)->isImgurToken();
    }

    public function scopeHasDropboxToken($query)
    {
        return $query->whereHas('tokens', function ($q) {

            return $q->isDropboxToken();

        });
    }

    public function scopeHasImgurToken($query)
    {
        return $query->whereHas('tokens', function ($q) {

            return $q->isImgurToken();

        });
    }

    /**
     * Boolean shorthand to determine if a user has Tokens he needs
     * @return boolean
     */
    public function canFetchFavorites()
    {
        return ($this->imgurToken && $this->dropboxToken);
    }
}
