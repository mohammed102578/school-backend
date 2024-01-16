<?php


namespace App\Repository\Admin\Profile;

use App\Interface\Admin\ProfileInterface;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ProfileRepository implements ProfileInterface
{
    use GeneralTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //=================get user profile
    public function get_profile($request)
    {
        $user = User::find($request->id);
        if (!$user)
            return $this->returnError('001', 'this user not found');

        return $this->returnData('user', $user);

    }


    //=======================update_profile
    public function update_profile($request)
    {


        try{
        $rules = [
            "id" => "required",
            "name" => "required|alpha",
            'email' => 'required|unique:users,email,'.$request->id

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user = User::find($request->id);

        if (!$user)
            return $this->returnError('001', 'this user not found');



        //update user

        $credentials = $request->only(['name', 'email']);

          $update=  $user->update($credentials);


        if (!$update){
            return $this->returnError('E010', 'updated failed');

        }else{
            return $this->returnData('user', $user,"updated successfully");  //return json response

        }




    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }




    //================change password
    public function change_password($request)
    {


        try{
        $rules = [
            "id" => "required",
            "password_current" => "required",
            'password'=>'required|min:6',
            'password_confirmation'=>'required|same:password',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $user = User::find($request->id);

        if (!$user)
            return $this->returnError('001', 'this user not found');


          //check if request password_current equal current password
            if(!Hash::check($request->password_current,$user->password)){
                return $this->returnError('001', "Current Password Doesn't match!");

            }


            #Update the new Password
            $update= $user->update([
                'password' => Hash::make($request->password)
            ]);


        if (!$update){
            return $this->returnError('E010', 'updated failed');

        }else{
            return $this->returnData('user', $update,"updated successfuly");  //return json response

        }




    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }


    public function change_image($request)

    {
       try{
        $rules = [
            "id" => "required",
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }
        $user = User::find($request->id);

        if (!$user){
            return $this->returnError('001', 'this user not found');

        }else{
            if ($request->hasFile('image')) {

                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/profile'), $imageName);

                $userImage = ['image' => 'uploads/profile/'.$imageName];
                $update = User::where(['id'=>$request->id])->update($userImage);


            if (!$update){
                 return $this->returnError('E010', 'updated failed');
             }else{
                 return $this->returnData('user', $user,"updated successfuly");  //return json response
             }

            }else{
                return response()->json(['success' => false, 'message' => 'No image found']);

            }


        }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

    }




}
