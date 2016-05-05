<?php

namespace App\Http\Controllers\Auth;

use app\libraries\user\UserMeta;
use App\Models\User;
use App\Models\User_Meta;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    protected $loginPath = '/login';

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     * GET /auth/login
     *
     * @return \Response
     */
    public function getLogin()
    {
        //
        return \View::make("auth.login");
    }

    /**
     * Show the form for creating a new resource.
     * POST /auth/login
     *
     * @return \Response
     */
    public function postLogin()
    {
        //

        $rules = array(
            'username' => 'required|alphaNum',
            //
            'password' => 'required|alphaNum|min:3'
            // password can only be alphanumeric and has to be greater than 3 characters
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(\Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return \Redirect::to('login')
                ->withErrors($validator)// send back all errors to the login form
                ->withInput(\Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'username' => \Input::get('username'),
                'password' => \Input::get('password')
            );

            // attempt to do the login
            if (\Auth::attempt($userdata)) {
                $row = User_Meta::where('user_id', '=', \Auth::user()->id)->where('key', '=', 'last_logged_in')->first();
                $last_logged_in = UserMeta::get('last_logged_in');

                if ($last_logged_in == '') {
                    $date = new \DateTime();
                    $row = new User_Meta();
                    $row->user_id = \Auth::user()->id;
                    $row->key = 'last_logged_in';
                    $row->value = $date->format('Y-m-d h:i:s');
                    $row->save();
                } else {
                    $date = new \DateTime();
                    $row->user_id = \Auth::user()->id;
                    $row->key = 'last_logged_in';
                    $row->value = $date->format('Y-m-d h:i:s');
                    $row->save();
                }

                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                //echo 'SUCCESS!';
                return \Redirect::to('/');
            } else {

                // validation not successful, send back to form
                return \Redirect::to('login')->with('message', "login attempt failed!");
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     * GET /auth/lougout
     *
     * @return \Response
     */
    public function getLogout()
    {
        \Auth::logout(); // log the user out of our application
        return \Redirect::to('login'); // redirect the user to the login screen
    }

    /**
     * Display the specified resource.
     * GET /auth/register
     *
     *
     * @return \Response
     */
    public function getRegister()
    {
        //
    }

    /**
     * Posts the registration.
     * POST /auth/register
     *
     *
     * @return \Response
     */
    public function postRegister()
    {
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
