<?php


namespace App\Repository\Admin\Student;
use App\Models\Type_Blood;
use App\Traits\GeneralTrait;
use App\Interface\Admin\StudentInterface;
use App\Models\Student;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\Gender;
use App\Models\Classroom;
use App\Models\Grade;
use App\Models\My_Parent;
use App\Models\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class StudentRepository implements StudentInterface
{
    use GeneralTrait;




  //=================get user profile
  public function get_realation_student_data($request)
  {
    try{


        $nationality = Nationalitie::orderBy('id','DESC')->get();
        $religion = Religion::orderBy('id','DESC')->get();
        $blood = Type_Blood::orderBy('id','DESC')->get();
        $gender = Gender::orderBy('id','DESC')->get();
        $parent = My_Parent::orderBy('id','DESC')->get();
        $grade = Grade::orderBy('id','DESC')->get();
        $section =Section::orderBy('id','DESC')->with('My_classs')->get();



        $data=['nationality'=>$nationality,'religion'=>$religion,'grade'=>$grade,'parent'=>$parent,
        'gender'=>$gender,'blood'=>$blood,'section'=>$section];
        return $this->returnData('student', $data,'return all students');


}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }


  public function get_classroom($request)
  {
    try{
      $id= $request->id;

        if($request->id != null){
          $Classroom = Classroom::orderBy('id','DESC')->where('grade_id',$id)->get();

        }else{
          $Classroom = Classroom::orderBy('id','DESC')->where('grade_id','!=',0)->get();

        }

      return $this->returnData('classroom', $Classroom,'return all classrooms');



}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }



  public function get_student_mother($request)
  {
    try{
      $id= $request->id;

        if($request->id != null){
          $Mother = My_Parent::orderBy('id','DESC')->where('id',$id)->get();

        }else{
          $Mother = My_Parent::orderBy('id','DESC')->where('id','!=',0)->get();

        }

      return $this->returnData('mother', $Mother,'return all mothers');



}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }



  public function get_section($request)
  {
    try{
      $id= $request->id;

        if($request->id != null){
          $Section = Section::orderBy('id','DESC')->where('classroom_id',$id)->get();

        }else{
          $Section = Section::orderBy('id','DESC')->where('classroom_id','!=',0)->get();

        }

      return $this->returnData('section', $Section,'return all sections');



}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }


  public function get_student($id)
  {
    try{

    $Student = Student::where('id',$id)->first();
    if (!$Student){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('student', $Student,'return student');

    }

}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }






  public function index($request)
  {
    try{
        //return student by section id
        if($request->section_id !=null){
            $Students = Student::where('section_id',$request->section_id)->orderBy('id','DESC')
            ->with('parent')->with('grade')->with('section')
            ->with('classroom')->with('blood')->with('gender')->with('nationality')->paginate(200);

            if (!$Students){
                return $this->returnError('001', ' not found data');

            }else{
                return $this->returnData('student', $Students,'return all students');

            }
        }

        //return student by items
        if($request->Items_show_number != null){
            $paginate= $request->Items_show_number;

        }else{
            $paginate=10;
        }
    $Students = Student::orderBy('id','DESC')->with('parent')->with('grade')->with('section')
    ->with('classroom')->with('blood')->with('gender')->with('nationality')->paginate($paginate);
    if (!$Students){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('student', $Students,'return all students');

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
 try {
    DB::beginTransaction();

    $rules = [
            'name_ar' => 'required',
            'name_en' => 'required',
            'email' => 'required|email|unique:students,email,',
            'password' => 'required|string|min:6|max:15',
            'gender_id' => 'required',
            'nationalitie_id' => 'required',
            'blood_id' => 'required',
            'Date_Birth' => 'required|date|date_format:Y-m-d',
            'Grade_id' => 'required',
            'Classroom_id' => 'required',
            'section_id' => 'required',
            'parent_id' => 'required',
            'academic_year' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'mimes:pdf|max:2048',


    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

    $Student = Student::create($request->all());

    if($request->hasfile('attachment'))
    {
    $attachment = $request->file('attachment');
    $attachmentName =$Student->id.'-'.$request->name_en . '.' . $attachment->getClientOriginalExtension();
    $attachment->move(public_path('uploads/attachment/student/documentations'), $attachmentName);
    $userAttachment = ['attachment' => 'uploads/attachment/student/documentations/'.$attachmentName];
     Student::where(['id'=>$Student->id])->update($userAttachment);
}
    if($request->hasfile('image'))
    {
        $image = $request->file('image');
        $imageName =$Student->id.'-'.$request->name_en . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/attachment/student/profile'), $imageName);
        $userImage = ['image' => 'uploads/attachment/student/profile/'.$imageName];
         Student::where(['id'=>$Student->id])->update($userImage);

    }

    DB::commit();

          if ($Student){
            return $this->returnData('student', $Student,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created student failed');

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
            'name_ar' => 'required',
            'name_en' => 'required',
            'email' => 'required|email|unique:students,email,'.$request->id,
            'password' => 'string|min:6|max:10',
            'gender_id' => 'required',
            'nationalitie_id' => 'required',
            'blood_id' => 'required',
            'Date_Birth' => 'required|date|date_format:Y-m-d',
            'Grade_id' => 'required',
            'Classroom_id' => 'required',
            'section_id' => 'required',
            'parent_id' => 'required',
            'academic_year' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'mimes:pdf|max:2048',


    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

  $Student = Student::find($request->id);

       if ($Student){
        //attachment
        if($request->hasfile('attachment'))
        {
            $file=$Student->attachment;
            if(file_exists($file)){
                unlink($file);
            }
            $attachment = $request->file('attachment');
            $attachmentName =$Student->id.'-'.$request->name_en . '.' . $attachment->getClientOriginalExtension();
            $attachment->move(public_path('uploads/attachment/student/documentations'), $attachmentName);
            $userAttachment = ['attachment' => 'uploads/attachment/student/documentations/'.$attachmentName];
             Student::where(['id'=>$Student->id])->update($userAttachment);

        }
        //update image
        if($request->hasfile('image'))
        {
            $file=$Student->image;
            if(file_exists($file)){
                unlink($file);
            }
            $image = $request->file('image');
            $imageName =$Student->id.'-'.$request->name_en . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/attachment/student/profile'), $imageName);
            $userImage = ['image' => 'uploads/attachment/student/profile/'.$imageName];
             Student::where(['id'=>$Student->id])->update($userImage);

        }
        //update password
        if($request->password==""){
           $student=Student::where('id',$request->id)->update($request->except(['password','image','attachment']));

           DB::commit();

           return $this->returnData('student',$student ,'updated successfuly');  //return json response

        }else{

            $student=Student::where('id',$request->id)->update($request->except(['image','attachment']));

            DB::commit();

            return $this->returnData('student', $student,'updated successfuly');  //return json response

        }



            //update image

       }else{
        return $this->returnError('E005', 'updated student failed');
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



          $Students = Student::find($request->id);
          if ($Students){
            Student::where('id',$request->id)->delete();

            $student=  Student::first();
            if($student){
              return $this->returnData('student',$student,'Deleted successfuly');  //return json response

            }else{
                $student=null;
                return $this->returnData('student',$student,'Deleted successfuly');  //return json response
              }
          }else{
            return $this->returnError('001', 'this student not found');

          }

      return $this->returnData('student', $Students,'student deleted successfully');




  } catch(\Exception $e) {
   return $this->returnError('E005', $e->getMessage());
}

}


public function delete_all($request)
{
    try{

    $delete_all_id = $request->students_id;

    if ($delete_all_id){
      $student=  Student::whereIn('id', $delete_all_id)->Delete();

      $student=  Student::first();
      if($student){
        return $this->returnData('student',$student,'Deleted successfuly');  //return json response

      }else{
          $student=null;
          return $this->returnData('student',$student,'Deleted successfuly');  //return json response
        }


        }else{
         return $this->returnError('E005', 'deleted student failed');
        }
}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
}



public function search($request)
{
  try{

    if($request->has('search')){
        $Students=Student::orderBy('id','DESC')->with('parent')->with('grade')->with('section')
        ->with('classroom')->with('blood')->with('gender')->with('nationality')->where('name_en','LIKE','%'.$request->search.'%')->orWhere('name_ar','LIKE','%'.$request->search.'%')->paginate(10);
    }
  if (!$Students){
      return $this->returnError('001', ' not found data');

  }else{
      return $this->returnData('student', $Students,'return all students');

  }

}catch (\Exception $e){
  return $this->returnError('E005', $e->getMessage());

}
}

public function Upload_attachment($request)
{
    // foreach($request->file('photos') as $file)
    // {
    //     $name = $file->getClientOriginalName();
    //     $file->storeAs('attachments/students/'.$request->student_name, $file->getClientOriginalName(),'upload_attachments');

    //     // insert in image_table
    //     $images= new image();
    //     $images->filename=$name;
    //     $images->imageable_id = $request->student_id;
    //     $images->imageable_type = 'App\Models\Student';
    //     $images->save();
    // }

}

public function Download_attachment($studentsname, $filename)
{
   // return response()->download(public_path('attachments/students/'.$studentsname.'/'.$filename));
}

public function Delete_attachment($request)
{
    // Delete img in server disk
    // Storage::disk('upload_attachments')->delete('attachments/students/'.$request->student_name.'/'.$request->filename);

    // // Delete in data
    // image::where('id',$request->id)->where('filename',$request->filename)->delete();
    // toastr()->error(trans('messages.Delete'));
    // return redirect()->route('Students.show',$request->student_id);
}



}
