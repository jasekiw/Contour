<?php
namespace App\Http\Controllers;

use app\libraries\user\UserMeta;
use Auth;
use Date;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Response;

/**
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller {

	/**
	 * Display a listing of the resource.
	 * GET /profile
	 *
	 * @return Response
	 */
	public function show()
	{

		$view = \View::make('user.profile');
		$view->title = "Profile";
		$view->birthday = UserMeta::get('birthday');
		$view->gender = UserMeta::get('gender');
		$last_logged_in = UserMeta::get('last_logged_in');;
		$view->last_logged_in = Date::get_time_dif($last_logged_in);
		$view->email = UserMeta::get('email');
		$view->date_joined = UserMeta::get('date_joined');
		$view->phone = UserMeta::get('phone');
		$view->first_name = UserMeta::get('first_name');
		$view->last_name = UserMeta::get('last_name');
		$view->company = UserMeta::get('company');
		$view->about = UserMeta::get('about');
		return $view;
	}


	/**
	 * Store a newly created resource in storage.
	 * POST /profile
	 *
	 * @return Response
	 */
	public function save()
	{


		$name = Input::get('name');

		$value = Input::get('value');
		if($name == "email")
		{
			Auth::user()->email = $value;
			Auth::user()->save;
		}
		elseif($name == "profile_pic")
		{
			return UserMeta::save_file('profile_pic');

		}
		else
		{
			UserMeta::save($name, $value);
		}
		return '';
	}


}