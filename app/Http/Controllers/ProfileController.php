<?php
namespace App\Http\Controllers;

use app\libraries\user\UserMeta;
use App\Models\Revision;
use Auth;
use Date;
use Hash;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Redirect;
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
		$view->user = Auth::user();
		$revisions = Revision::where('user_id', '=', Auth::user()->id)->get();
		$history = [];
		foreach($revisions as $revision)
		{
			$subject = $revision->revisionable_type;
			if(str_contains($subject, "\\"))
				$subject = substr($subject, strrpos($subject, "\\") + 1, strlen($subject) - strrpos($subject, "\\") - 1);
			$action = "changed";
			if($revision->key == 'name')
				$action = "renamed";
			else if($revision->value == 'name')
				$action = "edited";
			$historyItem = new \stdClass();
			$historyItem->subject = $subject;
			$historyItem->action = $action;
			$historyItem->oldValue = $revision->old_value;
			$historyItem->newValue = $revision->new_value;
			$historyItem->time = $revision->updated_at;
			array_push($history, $historyItem);
		}
		$view->history = $history;
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
			return UserMeta::save_file('profile_pic');
		else
			UserMeta::save($name, $value);

		return '';
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
     */
	public function savePassword()
	{
		$user = Auth::user();
		$passwordOnFile = $user->password;
		$oldPassword = \Input::get('old-password');
		$newPassword = \Input::get('password');
		$passwordCheck = \Input::get('password2');
		if($newPassword !== $passwordCheck)
			return Redirect::route('view_profile')->with('message_title' , 'Failed!')->with("message_type", "failure")
				->with('message', 'The new password did not match the repeated password');
		if(!Hash::check($oldPassword, $passwordOnFile))
			return Redirect::route('view_profile')->with('message_title' , 'Failed!')->with("message_type", "failure")
				->with('message', 'The old password typed did not match the actual old password');
		$user->password = Hash::make($newPassword);
		$user->save();
		return Redirect::route('view_profile')->with('message_title' , 'Successfully Saved!')->with("message_type", "success");

	}


}