<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationAttempt;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index(){
        return view('index');
    }

    public function verify(Request $request){
        $phone = $request->session()->get("phone");
        if(!$phone){
            return redirect()->route('login');
        }
        return view('verify');
    }

    public function generateOTP(){
        $otp = mt_rand(1000,9999);
        return $otp;
    }

    public function submitForm(Request $request){
        //Phone validation
        $countryCode = "359";
        $phoneRaw = $request->get("phone");
        $phone = (int)preg_replace("/[^0-9]/", "", $phoneRaw);
        if(substr($phone,0, strlen($countryCode)) !== $countryCode){
            $phone = $countryCode.$phone;
        }
        $request->offsetSet("phone", $phone);

        $data = $request->validate([
            'email' => "required|email",
            'phone' => "required|unique:users",
            'password' => "required|string|min:6|confirmed"
        ]);

        $apiKey =  env('API_KEY',"");

        $otp = $this->generateOTP();
        $message = 'Your validation code is '.$otp;

        $messageEncoded = urlencode($message);
        $url ="https://api.smsbump.com/send/$apiKey.json?to=$phone&message=$messageEncoded";
        $responce = file_get_contents($url);

        $result = json_decode($responce);

        if(true/*$result["status"] !== "error"*/){
            $user = User::create([
                'email' => $data['email'],
                'phone' => $data['phone'],
                'otp' => $otp,
                'password' => Hash::make($data['password']),
            ]);
        }else{
            return redirect()->route('register');
        }

        return redirect()->route('verify')->with(['phone' => $data['phone'], 'otp' => $otp]);

    }

    public function submitOtp(Request $request){
        $data = $request->validate([
            'verification_code' => ['required', 'numeric'],
            'phone' => ['required', 'string'],
        ]);

        $user = User::where('phone', $data["phone"])->first();
        $lockedUntil = $user->locked_until;

        if($lockedUntil){
            if($lockedUntil > date("Y-m-d H:i:s")){
                return back()->with(['otp' => $user->otp, 'phone' => $data['phone'], 'error' => 'Too many verification attempts! Account is locked until '.date("d.m.Y H:i:s", strtotime($lockedUntil))]);
            }else{
                $user = tap($user)->update(["locked_until" => null, "verification_attempts" => 0]);
            }
        }
        if($user){
            $verificationCodeSent = (int)$user->otp;
            $verificationCodeInput = (int)$data["verification_code"];

            $verificationAttempt = new VerificationAttempt();
            $verificationAttempt->user_id = $user->id;
            $verificationAttempt->otp = $verificationCodeInput;
            $verificationAttempt->created_at = date("Y-m-d H:i:s");

            if($verificationCodeInput === $verificationCodeSent){
                $user = tap($user)->update(['isVerified' => true]);
                $verificationAttempt->success = 1;
                $verificationAttempt->save();
                /* Authenticate user */
                Auth::login($user);
                return redirect()->route('home')->with(['message' => 'Phone number verified']);
            }else{
                $verificationAttempt->success = 0;
                $verificationAttempt->save();

                $user = tap($user)->update([
                    'verification_attempts'=> DB::raw('verification_attempts + 1'),
                    'updated_at' => Carbon::now(),
                    'locked_until' => DB::raw("IF(verification_attempts >= 3, NOW() + INTERVAL 1 MINUTE, NULL)")
                ]);

                return back()->with(['otp' => $user->otp, 'phone' => $data['phone'], 'error' => 'Invalid verification code entered!']);
            }
        }else{
            return redirect()->route('register');
        }
    }

    public function login(Request $request){
        $rules = [
            'phone' => "required|numeric|exists:users", // make sure the phone is an actual phone
            'password' => 'required|alphaNum|min:6'
        ];
        $validator = Validator::make($request->all() , $rules);

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator) // send back all errors to the login form
                ->withInput($request->except(["password"])); // send back the input (not the password) so that we can repopulate the form
        }else{
            $userdata = array(
                'phone' => $request->get('phone') ,
                'password' => $request->get('password'),
                //'isVerified' => 1
            );

            if (Auth::attempt($userdata)){
                if(Auth::user()->isVerified){
                    return redirect()->route('home');
                }else{
                    $userPhone = Auth::user()->phone;
                    $otp = Auth::user()->otp;
                    Auth::logout();
                    return redirect()->route('verify')->with(['phone' => $userPhone, 'otp' => $otp]);
                }
            }else{

                return redirect()->route('login');
            }
        }
    }

    public function home(Request $request){
        if(Auth::check()){
            $message = $request->get("message");
            return view('home', ["message" => $message]);
        }else{
            return redirect()->route('register');
        }
    }

    public function loginForm(Request $request){
        if(Auth::check()){
            return redirect()->route('home');
        }else{
            return view('login');
        }
    }

    public function logout () {
        Auth::logout();
        return redirect('/');
    }

}
