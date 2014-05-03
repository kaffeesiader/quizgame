<?php
 
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\HTML;

class LoginController extends BaseController {
	
	public function showLogin() {
		return View::make('login');
	}
	
	public function doLogin() {
		// validate the info, create rules for the inputs
		$rules = array(
			'email' => 'required|email', // make sure the email is an actual email
		);
		
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		
		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			return Redirect::to('login')
			->withErrors($validator) // send back all errors to the login form
			->withInput(); // send back the input so that we can repopulate the form
		} else {
		
			$email = Input::get('email');
			$user = User::where('email', '=', $email)->first();
			
			if(!$user) {
				$user = new User();
				$user->setEmail($email);
				$user->setName('');
				$user->save();
				Auth::login($user);
				// redirect to new user form to enter username.
				return Redirect::to('user/new')->with('email', $email);
			} else {
				Auth::login($user);
				return Redirect::intended('/');
			}
		}
	}
	
	public function showCreateUser() {
		return View::make('newUser');
	}
	
	public function createUser() {
		// validate the info, create rules for the inputs
		$rules = array(
			'username' => 'required|min:4', // make sure the name is present
		);
		
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);
		if($validator->fails()) {
			
			return Redirect::to('user/new')
					->withInput()
					->withErrors($validator);
			
		} else {
			$user = Auth::user();
			$name = e(Input::get('username'));
			$user->setName($name);
			$user->save();
			
			return Redirect::intended('/');
		}
		
	}
	
	public function logout() {
		Auth::logout();
		return Redirect::to("/");
	}
	
}