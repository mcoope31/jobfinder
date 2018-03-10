<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\User;
use App\Seeker;
use App\Company;
use App\Skill;
use App\SeekerSkill;
use App\SeekerExperience;
use App\SeekerEducation;
use App\JobOpening;
use App\Education;

class ProfileController extends Controller
{
    /*
     * CONSTRUCTOR: ALL PAGES RESTRICTED EXCEPT SHOW PROFILE
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => 'show']);
    }
    
    /*
     * AUTH: GUEST
     * SHOW SEEKER OR COMPANY PROFILE PAGE
     */
    public function show($user_id)
    {
        $user = User::find($user_id);

        //Check if User was found
        if(!isset($user))
            return back();
        
        if($user->user_type == 1)
        {
            $info = Seeker::find($user_id);
            $skills = $info->seeker_skills;
            $experiences = SeekerExperience::where('seeker_id',$user_id)->orderBy('ended', 'desc')->get();
            $educations = SeekerEducation::where('seeker_id',$user_id)->orderBy('achieved', 'desc')->get();
            return view('seeker.profile', compact('info', 'skills', 'experiences', 'educations'));
        }
        else if($user->user_type == 2)
        {
            $info = Company::find($user_id);
            $jobs = JobOpening::where('company_id',$user_id)->where('status',0)->orderBy('created_at','desc')->get();
            return view('company.profile', compact('info', 'jobs'));
        }
    }
    
    /*
     * AUTH: SEEKER/COMPANY
     * SHOW SEEKER OR COMPANY EDIT PROFILE PAGE
     */
    public function edit($user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            return view('seeker.profile_edit');
        }
        else if(auth()->user()->user_type == 2)
        {
            return view('company.profile_edit');
        }
    }
    
    /*
     * AUTH: SEEKER
     * SHOW SEEKER EDIT SKILLS LIST PAGE
     */
    public function edit_skills($user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            $skills_list = Skill::all();
            $seeker_skills = SeekerSkill::where('seeker_id',$user_id)->orderBy('rating','asc')->get();
            
            return view('seeker.skills_edit', compact('skills_list','seeker_skills'));
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER/COMPANY
     * UPDATE SEEKER OR COMPANY EDIT PROFILE: PATCH
     */
    public function update(Request $request, $user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            //VALIDATE
            $request->validate([
                'name' => 'required|regex:/^[\pL\s\-]+$/u|min:1|max:100',
                'age' => 'required|numeric|min:16',
                'city' => 'required|regex:/^[\pL\s\-]+$/u|min:3|max:100',
                'phone' => 'regex:/^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/u',
                'state' => 'required|alpha|min:3|max:100',
                'zip' => 'required|numeric|digits:5'
            ]);
            
            //STORE IMAGE
            if($request->hasFile('image_file'))
            {
                $fileNameToStore = 'seeker'.$user_id.'.jpg';
                $path = $request->file('image_file')->storeAs('public/seeker_images',$fileNameToStore);
            }
            
            $seeker = Seeker::find($user_id);
            $seeker->name = $request->name;
            if(isset($request->age)) $seeker->age = $request->age;
            if(isset($request->phone))
            {
                $phone = localize_us_number($request->phone);
                $seeker->phone = $phone;
            }
            if(isset($request->city)) $seeker->city = $request->city;
            if(isset($request->state)) $seeker->state = $request->state;
            if(isset($request->zip)) $seeker->zipcode = $request->zip;
            $seeker->save();
        }
        else if(auth()->user()->user_type == 2)
        {
            //VALIDATE
            $request->validate([
                'name' => 'required|min:1|max:100',
                'industry' => 'required',
                'description' => 'required|min:1|max:1000',
                'phone' => 'required|regex:/^\(?\d{3}\)?[- ]?\d{3}[- ]?\d{4}$/u',
                'contact_email' => 'nullable|email|unique:email',
                'founded' => 'required|digits:4',
                'company_size' => 'required|numeric|min:1',
                'city' => 'required|regex:/^[\pL\s\-]+$/u|min:1|max:100',
                'state' => 'required|alpha|min:1|max:100',
                'zip' => 'required|digits:5',
                'website' => 'nullable|URL|max:100'
            ]);
            
            //STORE IMAGE
            if($request->hasFile('image_file'))
            {
                $fileNameToStore = 'company'.$user_id.'.jpg';
                $path = $request->file('image_file')->storeAs('public/company_images',$fileNameToStore);
            }
            
            $company = Company::find($user_id);
            $company->name = $request->name;
            if(isset($request->industry)) $company->industry = $request->industry;
            if(isset($request->description)) $company->description = $request->description;
            if(isset($request->phone))
            {
                $phone = localize_us_number($request->phone);
                $company->phone = $phone;
            }
            if(isset($request->contact_email)) $company->contact_email = $request->contact_email;
            $company->founded = $request->founded;
            $company->size = $request->company_size;
            if(isset($request->city)) $company->city = $request->city;
            if(isset($request->state)) $company->state = $request->state;
            if(isset($request->zipcode)) $company->zipcode = $request->zip;
            if(isset($request->website)) $company->website = $request->website;
            $company->save();
        }
        
        return redirect('/profile/'.$user_id);
    }
    
    /*
     * AUTH: SEEKER
     * UPDATE SEEKER SKILLS LIST
     */
    public function update_skills(Request $request, $user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            //Loop to verify all 10 skills were added, and load Array of skill id's
            $skills = array();
            for($i = 0; $i < 10; $i++)
            {
                $val = request('skill'.$i);
                if(is_numeric($val))
                    array_push($skills,(int)$val);
                else
                    return back()->withErrors(['field_name' => ['You must have 10 skills']]);
            }
            
            //Loop to update or insert skills into seekerskills table
            for($i = 0; $i < 10; $i++)
            {
                //Get seekerskill for specific rating
                $row = SeekerSkill::where('seeker_id',$user_id)->where('rating',$i+1);
                
                if(count($row->get())>0) //this rating already exists, update it
                {
                    $row->update(['skill_id'=>$skills[$i]]);
                }
                else //this rating doesnt exist, insert new row
                {

                    $row = new SeekerSkill;
                    $row->seeker_id = auth()->user()->id;
                    $row->skill_id = $skills[$i];
                    $row->rating = ($i+1);
                    $row->save();
                }
            }
            
            return redirect('/profile/'.$user_id);
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * SHOW SEEKER EDIT EXPERIENCE PAGE
     */
    public function edit_experience($user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            $experiences = SeekerExperience::where('seeker_id',auth()->user()->id)->orderBy('ended','asc')->get();
            return view('seeker.experience_edit', compact('experiences'));
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * ADD SEEKER EXPERIENCE
     */
    public function add_experience(Request $request, $user_id)
    {
        
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            $this->validate(request(), [
                'company' => 'required',
                'title' => 'required',
                'date_start' => 'required|date',
                'date_end' => 'nullable|date',
                'description' => 'required'
            ]);
            
            //Check if End-Date or Present was selected
            if(!$request->has('present') && request('date_end') == null)
            {
                return back()->withErrors(['field_name' => ['Must have end date or present checked.']]);
            }
            
            if(request('date_end')==null)
                $date_end = now();
            else 
                $date_end = new \DateTime($request->date_end);

            $date_start = new \DateTime($request->date_start);
            
            //Check if start date is before end date
            if($date_start > $date_end)
            {
                return back()->withErrors(['field_name' => ['End Date cannot be before Start Date.']]);
            }
            
            //Get Experience in days
            $interval = $date_start->diff($date_end);
            $days_experience = $interval->format('%a');
            
            //Store in DB
            $experience = new SeekerExperience;
            $experience->seeker_id = $user_id;
            $experience->company = $request->company;
            $experience->job_title = $request->title;
            $experience->started = date('Y-m-d', strtotime($request->date_start));

            if(request('date_end')!=null)
                $experience->ended = date('Y-m-d', strtotime($request->date_end));
            else
                $experience->present = 1;
            
            $experience->description = $request->description;
            $experience->days_experience = $days_experience;
            
            $experience->save();
            
            return redirect('/profile/'.$user_id.'/edit_experience');
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * DELETE SEEKER EXPERIENCE
     */
    public function delete_experience($user_id, $experience_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            SeekerExperience::where('id',$experience_id)->delete();
        
            return redirect('/profile/'.$user_id.'/edit_experience');
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * SHOW EDIT SEEKER EDUCATION
     */
    public function edit_education($user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            $degrees = Education::all();
            $educations = SeekerEducation::where('seeker_id',$user_id)->orderBy('achieved', 'desc')->get();
            
            return view('seeker.education_edit', compact('educations', 'degrees'));
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * ADD SEEKER EDUCATION
     */
    public function add_education(Request $request, $user_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type == 1)
        {
            $this->validate(request(), [
                'university' => 'required',
                'degree' => 'required',
                'achieved' => 'required|date',
                'title' => 'required'
            ]);
            
            $education = new SeekerEducation;
            $education->seeker_id = $user_id;
            $education->university = $request->university;
            $education->education_id = $request->degree;
            $education->title = $request->title;
            $education->achieved = date('Y-m-d', strtotime($request->achieved));
            $education->save();
            
            return redirect('/profile/'.$user_id.'/edit_education');
        }
        else //Not a Seeker
        {
            return back();
        }
    }
    
    /*
     * AUTH: SEEKER
     * DELETE SEEKER EDUCATION
     */
    public function delete_education($user_id, $education_id)
    {
        if(auth()->user()->id != $user_id)
            return back();
        
        if(auth()->user()->user_type != 1)
            return back();
        
        SeekerEducation::where('id',$education_id)->delete();
        
        return redirect('/profile/'.$user_id.'/edit_education');
    }

    public function account($user_id)
    {
        if(auth()->user()->id != $user_id || auth()->user()->user_type > 2)
            return back();

        return view('pages.account', compact('user_id'));
    }

    public function edit_account($user_id)
    {
        if(auth()->user()->id != $user_id || auth()->user()->user_type > 2)
            return back();

        return view('pages.account_edit', compact( 'user_id'));
    }

    public function update_account_email(Request $request, $user_id)
    {
        if(auth()->user()->id != $user_id || auth()->user()->user_type > 2)
            return back();

        //VALIDATE
        $request->validate([
            'email' => 'nullable|email',
        ]);

        $user = User::find($user_id);
        if(isset($request->email)) $user->email = $request->email;
        $user->save();

        return redirect('/profile/'.$user_id.'/account');
    }

    public function update_account_password(Request $request, $user_id)
    {
        if(auth()->user()->id != $user_id || auth()->user()->user_type > 2)
            return back();

        //VALIDATE
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find($user_id);

        //AUTHENTICATE
        if(!auth()->attempt(['email' => $request->email, 'password' => $request->current_password]))
        {
            return back()->withErrors([
                'message' => 'Please enter your current password and try again.'
            ]);
        } elseif(isset($request->new_password)) $user->password = bcrypt($request->new_password);

        $user->save();

        return redirect('/profile/'.$user_id.'/account');
    }
}
