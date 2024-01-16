<?php

namespace App\Http\Controllers\API\Admin\Profile;

use App\Repository\Admin\Profile\ProfileRepository;
use App\Http\Controllers\API\Controller;
use Illuminate\Http\Request;
class ProfileController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $profile;
    public function __construct(ProfileRepository $profile)
    {
        $this->profile=$profile;
        $this->middleware('auth:api');
    }


    //=================get user profile
    public function get_profile(Request $request)
    {
   return $this->profile->get_profile($request);
    }
    //=======================update_profile
    public function update_profile(Request $request)
    {
        return $this->profile->update_profile($request);
    }

    //================change password
    public function change_password(Request $request)
    {
        return $this->profile->change_password($request);
    }


    public function change_image(Request $request)
    {
        return $this->profile->change_image($request);
    }




}
