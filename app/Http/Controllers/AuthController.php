<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Seeker;
use App\Company;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }
    
    public function index()
    {
        return view('login.login');
    }
    
    public function login()
    {
        $user = User::where('email',request()->email)->first();
        if(count($user)==1 && $user->status==1)
            return back()->withErrors(['message' => 'Your account is currently frozen.']);
        
        if(!auth()->attempt(request(['email','password'])))
        {
            return back()->withErrors([
               'message' => 'Please check your credentials and try again.' 
            ]);
        }

        if(auth()->user()->user_type == 1)
        {
            return redirect()->route('dashboard');
        }
        else if(auth()->user()->user_type == 2)
        {
            return redirect()->route('dashboard');
        }
        else if(auth()->user()->user_type == 3)
        {
            return redirect()->route('dashboard');
        }
        
        return redirect()->home();
    }
    
    public function register()
    {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);
        
        $usertype = (request('type')=='seeker') ? 1 : 2;
        
        $user = User::create([
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'user_type' => $usertype
        ]);
        
        auth()->login($user);
        
        if($usertype == 1)
        {
            $seeker = new Seeker;
            $seeker->user_id = auth()->user()->id;
            $seeker->name = request('name');
            $seeker->save();

            //Redirect to profile page
            return redirect('/profile/'.$user->id.'/edit');
        }
        else if($usertype == 2)
        {
            $company = new Company;
            $company->user_id = auth()->user()->id;
            $company->name = request('name');
            $company->save();
            
            //Redirect to profile page
            return redirect('/profile/'.$user->id.'/edit');
        }
        
        return redirect()->home();
    }
    
    public function logout()
    {
        auth()->logout();
        
        return redirect()->route('login');
    }
}
