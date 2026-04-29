<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getUser($request)
    {
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->where('users.level','Admin')
        ->orderBy('users.name','ASC')
        ->get();
        return $data;
    }
    public static function getEdit($id)
    {
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->where('users.id',$id)
        ->get();
        return $data;
    }
    public static function getMyProfil()
    {
        $data = User::join('biodata','biodata.id_user','=','users.id')
        ->where('users.id',Auth::user()->id)
        ->get();
        return $data;
    }
}
