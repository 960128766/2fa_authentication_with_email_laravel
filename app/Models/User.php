<?php

namespace App\Models;

use App\Mail\SendCodeMail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use mysql_xdevapi\Exception;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generatecode()
    {
        $code = rand(1000, 9999);
        UserCode::updateOrCreate(
            ['user_id' => auth()->user()->id],
            ['code' => $code]
        );
        try {
            $details = [
                'title' =>'mail from hoseinbayati',
                'code'=>$code
            ];
            Mail::to(auth()->user()->email)->send(new SendCodeMail($details));
        } catch (Exception $exception) {
            info('Error:' . $exception->getMessage());
        }
    }
}
