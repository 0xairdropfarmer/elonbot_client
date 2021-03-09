<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use EnvEditor;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;
use Illuminate\Support\Str;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        $allowed_register = Setting::where('allowed_register','yes')->first();
        $getClientIp = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
        if($allowed_register){
          
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        ])->validate();
         $sign_message = Str::random(11);
         $result = Http::post(env('command_center_address').'/store_client_ip',[
            'email'=>$input['email'],
            'ip_address'=> $getClientIp,
            'sign_message'=> $sign_message
         ]);
         info($result);
         DB::table('settings')->update(['allowed_register' => 'nope']);
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
             'ip_address'=> $getClientIp,
            'sign_message'=>$sign_message
        ]);
        }else{
             return abort('403');

        }
    }
}
