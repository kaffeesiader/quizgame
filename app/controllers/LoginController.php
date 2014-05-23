<?php
 
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\HTML;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class LoginController extends BaseController {
	
	public function doLogin() {
		// validate the info, create rules for the inputs
		$rules = array(
			'email' => 'required|email', // make sure the email is an actual email
		);
		
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			Log::error("Authentication failed!");
			return Response::create("No valid email address provided", 401);
		} else {
		
			$email = Input::get('email');
			$user = User::where('email', '=', $email)->first();
			// create a new user if it does not exists...
			if(!$user) {
				$user = new User();
				$user->setEmail($email);
				$user->setName('');
				$user->save();
			}
			
			Auth::login($user);
			Log::info('User '.$user->getEmail().' logged in!');
			
			return $user->toJson();
			
		}
	}
	
	public function logout() {
		Auth::logout();
		Log::info("User logged out!");
	}
	
	public function getCurrentUser() {
		$user = Auth::user();
		
		if(!$user) {
			return Response::create('No user assoziated to current session!', 401);
		} else {
			return $user->toJson();
		}
	}
}