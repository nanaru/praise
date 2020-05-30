<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
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
            'user_id' => ['required', 'string', 'max:30','unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:60','confirmed'],
            'name' => ['required', 'string', 'max:30'],
            'profile' => ['min:0','max:100'],
            'email' => ['email','max:60']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(Request $data)
    {
        $image=$data->file('image');
        if(empty($image)){
            $image =null;
        }else{
            $image = base64_encode(Image::make($image)->fit(400,400)->stream('png', 50));          
        }

        return User::create([
            'user_id' => $data->user_id,
            'password' => Hash::make($data->password),
            'name' => $data->name,
            'profile' => $data->profile,
            'email' => $data->email,
            'image'=>$image
        ]);
    }
}
