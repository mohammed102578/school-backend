<?php


namespace App\Repository\Admin\Teacher;
use App\Models\Type_Blood;
use App\Traits\GeneralTrait;
use App\Interface\Admin\TeacherInterface;
use App\Models\Gender;
use App\Models\Teacher;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\Specialization;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeacherRepository implements TeacherInterface
{
    use GeneralTrait;




  //=================get user profile
  public function get_realation_teacher_data($request)
  {
    try{

        $nationality = Nationalitie::orderBy('id','DESC')->get();
        $religion = Religion::orderBy('id','DESC')->get();
        $blood = Type_Blood::orderBy('id','DESC')->get();
        $specialization = Specialization::orderBy('id','DESC')->get();
        $gender = Gender::orderBy('id','DESC')->get();

        $data=['nationality'=>$nationality,'religion'=>$religion,'blood'=>$blood
        ,'specialization'=>$specialization,'gender'=>$gender];
        return $this->returnData('teacher', $data,'return all teachers data');


}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }





  public function get_teacher($id)
  {
    try{

    $Teacher = Teacher::where('id',$id)->first();
    if (!$Teacher){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('teacher', $Teacher,'return teacher');

    }

}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }






  public function index($request)
  {
    try{
        if($request->Items_show_number != null){
            $paginate= $request->Items_show_number;

        }else{
            $paginate=10;
        }
    $Teachers = Teacher::orderBy('id','DESC')
    ->with('specialization')
    ->with('gender')
    ->with('blood')
    ->with('religion')
    ->with('nationality')
    ->paginate($paginate);
    if (!$Teachers){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('teacher', $Teachers,'return all teachers');

    }

}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }



  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store($request)
  {
    DB::beginTransaction();

 try {

    $rules = [
        'name_en'=>'required',
        'name_ar'=>'required',
        'National_ID'=>'required',
        'email'=>'required|unique:teachers,email,'.$request->id,
        'password'=>'required',
        'phone'=>'required|unique:teachers,phone,'.$request->id,
        'Nationality_id'=>'required',
        'Blood_Type_id'=>'required',
        'Religion_id'=>'required',
        'address'=>'required',
        'bank_account'=>'required|max:10|min:3',
        'Joining_Date'=>'required|date',
        'gender_id'=>'required',
        'specialization_id'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'attachment' => 'mimes:pdf|max:2048',


    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

    $Teacher = Teacher::create($request->all());
    if($request->hasfile('attachment'))
    {
    $attachment = $request->file('attachment');
    $attachmentName =$Teacher->id.'-'.$request->Name_Father_en . '.' . $attachment->getClientOriginalExtension();
    $attachment->move(public_path('uploads/attachment/teacher/documentations'), $attachmentName);
    $userAttachment = ['attachment' => 'uploads/attachment/teacher/documentations/'.$attachmentName];
    Teacher::where(['id'=>$Teacher->id])->update($userAttachment);
    }
    if($request->hasfile('image'))
    {
        $image = $request->file('image');
        $imageName =$Teacher->id.'-'.$request->Name_Father_en . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/attachment/teacher/profile'), $imageName);
        $userImage = ['image' => 'uploads/attachment/teacher/profile/'.$imageName];
        Teacher::where(['id'=>$Teacher->id])->update($userImage);

    }

    DB::commit();


          if ($Teacher){
            return $this->returnData('teacher', $Teacher,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created teacher failed');

          }


        }catch (\Exception $e){
            DB::rollBack();

          return $this->returnError('E005', $e->getMessage());

      }


  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update($request)
 {
   try {
    DB::beginTransaction();

    $rules = [
        'name_en'=>'required',
        'name_ar'=>'required',
        'National_ID'=>'required',
        'email'=>'required|unique:teachers,email,'.$request->id,
        'password'=>'min:6',
        'phone'=>'required|unique:teachers,phone,'.$request->id,
        'Nationality_id'=>'required',
        'Blood_Type_id'=>'required',
        'Religion_id'=>'required',
        'address'=>'required',
        'bank_account'=>'required|max:10|min:3',
        'Joining_Date'=>'required|date',
        'gender_id'=>'required',
        'specialization_id'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'attachment' => 'mimes:pdf|max:2048',


    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

    $Teacher = Teacher::find($request->id);

       if ($Teacher){
        //attachment
        if($request->hasfile('attachment'))
        {
        $attachment = $request->file('attachment');
        $attachmentName =$Teacher->id.'-'.$request->Name_Father_en . '.' . $attachment->getClientOriginalExtension();
        $attachment->move(public_path('uploads/attachment/teacher/documentations'), $attachmentName);
        $userAttachment = ['attachment' => 'uploads/attachment/teacher/documentations/'.$attachmentName];
        Teacher::where(['id'=>$Teacher->id])->update($userAttachment);
        }

        //image
        if($request->hasfile('image'))
        {
            $image = $request->file('image');
            $imageName =$Teacher->id.'-'.$request->Name_Father_en . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/attachment/teacher/profile'), $imageName);
            $userImage = ['image' => 'uploads/attachment/teacher/profile/'.$imageName];
            Teacher::where(['id'=>$Teacher->id])->update($userImage);

        }
        if($request->password==""){
           $teacher=Teacher::where('id',$request->id)->update($request->except(['password','image','attachment']));

           DB::commit();

           return $this->returnData('teacher',$teacher ,'updated successfuly');  //return json response

        }else{

            $teacher=Teacher::where('id',$request->id)->update($request->except(['image','attachment']));

            DB::commit();

            return $this->returnData('teacher', $teacher,'updated successfuly');  //return json response

        }

       }else{
        return $this->returnError('E005', 'updated teacher failed');
       }


   }
   catch(\Exception $e) {
    DB::rollBack();

    return $this->returnError('E005', $e->getMessage());
}
 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($request)
  {
    try{

          $Teachers = Teacher::find($request->id);
          if ($Teachers){
            Teacher::where('id',$request->id)->delete();

            $teacher=  Teacher::first();
            if($teacher){
              return $this->returnData('teacher',$teacher,'Deleted successfuly');  //return json response

            }else{
                $teacher=null;
                return $this->returnData('teacher',$teacher,'Deleted successfuly');  //return json response
              }
          }else{
            return $this->returnError('001', 'this teacher not found');

          }





  } catch(\Exception $e) {
   return $this->returnError('E005', $e->getMessage());
}

}


public function delete_all($request)
{
    try{

       // return $request->all();
    $delete_all_id = $request->teachers_id;

    if ($delete_all_id){
      $teacher=  Teacher::whereIn('id', $delete_all_id)->Delete();

      $teacher=  Teacher::first();
      if($teacher){
        return $this->returnData('teacher',$teacher,'Deleted successfuly');  //return json response

      }else{
          $teacher=null;
          return $this->returnData('teacher',$teacher,'Deleted successfuly');  //return json response
        }


        }else{
         return $this->returnError('E005', 'deleted teacher failed');
        }
}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
}



public function search($request)
{
  try{

    if($request->has('search')){
        $Teachers=Teacher::orderBy('id','Desc')
        ->with('specialization')
        ->with('gender')
        ->with('blood')
        ->with('religion')
        ->with('nationality')
        ->where('name_en','LIKE','%'.$request->search.'%')->orWhere('name_ar','LIKE','%'.$request->search.'%')->orWhere('phone','LIKE','%'.$request->search.'%')->paginate(10);


    }
  if (!$Teachers){
      return $this->returnError('001', ' not found data');

  }else{
      return $this->returnData('teacher', $Teachers,'return all teachers');

  }

}catch (\Exception $e){
  return $this->returnError('E005', $e->getMessage());

}
}



}
