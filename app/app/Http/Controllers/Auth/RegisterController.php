<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // ログイン済みのユーザーのみアクセスできる
        $this->middleware('auth');
    }

    // 管理者以外のアクセスを拒否する
    private function checkAdmin()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'この操作は管理者のみ利用できます。');
        }
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm()
    {
        $this->checkAdmin();

        return view('auth.signup');
    }

    /**
     * Handle the signup confirmation screen.
     */
    public function confirm(Request $request)
    {
        $this->checkAdmin();

        $data = $request->validate([
            'role' => ['required', 'in:teacher,reception'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password_confirmation'] = $request->input('password_confirmation');

        return view('auth.signup_conf', compact('data'));
    }

    /**
     * Handle a registration request for the application without auto-login.
     */
    public function register(Request $request)
    {
        $this->checkAdmin();

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        // 登録後はお問い合わせ一覧へ（管理者はすでにログイン済みのため）
        return redirect()->route('inquiry.index')->with('success', $user->name . ' を登録しました。');
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
            'role' => ['required', 'in:teacher,reception'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);
    }
}
