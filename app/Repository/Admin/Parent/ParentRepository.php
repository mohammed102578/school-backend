<?php


namespace App\Repository\Admin\Parent;
use App\Models\Classroom;
use App\Models\Type_Blood;
use App\Traits\GeneralTrait;
use App\Interface\Admin\ParentInterface;
use App\Models\My_parent;
use App\Models\Nationalitie;
use App\Models\Religion;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ParentRepository implements ParentInterface
{
    use GeneralTrait;




  //=================get user profile
  public function get_realation_parent_data($request)
  {
    try{

        $nationality = Nationalitie::orderBy('id','DESC')->get();
        $religion = Religion::orderBy('id','DESC')->get();
        $blood = Type_Blood::orderBy('id','DESC')->get();
        $data=['nationality'=>$nationality,'religion'=>$religion,'blood'=>$blood];
        return $this->returnData('parent', $data,'return all parents');


}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
  }





  public function get_parent($id)
  {
    try{

    $Parent = My_parent::where('id',$id)->first();
    if (!$Parent){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('parent', $Parent,'return parent');

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
    $Parents = My_parent::orderBy('id','DESC')->with('nationality_father')->with('student',function($query){
        return $query->with(['grade','section','classroom'])->get();
    })->with('nationality_mother')->with('religion_father')
    ->with('religion_mother')->with('blood_father')->with('blood_mother')->paginate($paginate);
    if (!$Parents){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('parent', $Parents,'return all parents');

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
        'Name_Father_en'=>'required',
        'Name_Father_ar'=>'required',
        'National_ID_Father'=>'required',
        'email'=>'required|unique:my__parents,email,'.$request->id,
        'password'=>'required',
        'Passport_ID_Father'=>'required',
        'Phone_Father'=>'required|unique:my__parents,Phone_Father,'.$request->id,
        'Job_Father'=>'required',
        'Nationality_Father_id'=>'required',
        'Blood_Type_Father_id'=>'required',
        'Religion_Father_id'=>'required',
        'Address_Father'=>'required',
        'Name_Mother_ar'=>'required',
        'Name_Mother_en'=>'required',
        'National_ID_Mother'=>'required',
        'Passport_ID_Mother'=>'required',
        'Phone_Mother'=>'required',
        'Job_Mother'=>'required',
        'Nationality_Mother_id'=>'required',
        'Blood_Type_Mother_id'=>'required',
        'Religion_Mother_id'=>'required',
        'Address_Mother'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'attachment' => 'mimes:pdf|max:2048',


    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

    $Parent = My_parent::create($request->all());
    if($request->hasfile('attachment'))
    {
    $attachment = $request->file('attachment');
    $attachmentName =$Parent->id.'-'.$request->Name_Father_en . '.' . $attachment->getClientOriginalExtension();
    $attachment->move(public_path('uploads/attachment/parent/documentations'), $attachmentName);
    $userAttachment = ['attachment' => 'uploads/attachment/parent/documentations/'.$attachmentName];
    My_parent::where(['id'=>$Parent->id])->update($userAttachment);
    }
    if($request->hasfile('image'))
    {
        $image = $request->file('image');
        $imageName =$Parent->id.'-'.$request->Name_Father_en . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/attachment/parent/profile'), $imageName);
        $userImage = ['image' => 'uploads/attachment/parent/profile/'.$imageName];
        My_parent::where(['id'=>$Parent->id])->update($userImage);

    }

    DB::commit();


          if ($Parent){
            return $this->returnData('parent', $Parent,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created parent failed');

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
        'Name_Father_en'=>'required',
        'Name_Father_ar'=>'required',
        'National_ID_Father'=>'required',
        'email'=>'required|unique:my__parents,email,'.$request->id,
        'password'=>'min:6',
        'Passport_ID_Father'=>'required',
        'Phone_Father'=>'required|unique:my__parents,Phone_Father,'.$request->id,
        'Job_Father'=>'required',
        'Nationality_Father_id'=>'required',
        'Blood_Type_Father_id'=>'required',
        'Religion_Father_id'=>'required',
        'Address_Father'=>'required',
        'Name_Mother_ar'=>'required',
        'Name_Mother_en'=>'required',
        'National_ID_Mother'=>'required',
        'Passport_ID_Mother'=>'required',
        'Phone_Mother'=>'required',
        'Job_Mother'=>'required',
        'Nationality_Mother_id'=>'required',
        'Blood_Type_Mother_id'=>'required',
        'Religion_Mother_id'=>'required',
        'Address_Mother'=>'required',
        'attachment' => 'mimes:pdf|max:2048',


    ];


    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }

    $Parent = My_parent::find($request->id);

       if ($Parent){
        //attachment
        if($request->hasfile('attachment'))
        {
        $attachment = $request->file('attachment');
        $attachmentName =$Parent->id.'-'.$request->Name_Father_en . '.' . $attachment->getClientOriginalExtension();
        $attachment->move(public_path('uploads/attachment/parent/documentations'), $attachmentName);
        $userAttachment = ['attachment' => 'uploads/attachment/parent/documentations/'.$attachmentName];
        My_parent::where(['id'=>$Parent->id])->update($userAttachment);
        }

        //image
        if($request->hasfile('image'))
        {
            $image = $request->file('image');
            $imageName =$Parent->id.'-'.$request->Name_Father_en . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/attachment/parent/profile'), $imageName);
            $userImage = ['image' => 'uploads/attachment/parent/profile/'.$imageName];
            My_parent::where(['id'=>$Parent->id])->update($userImage);

        }
        if($request->password==""){
           $parent=My_parent::where('id',$request->id)->update($request->except(['password','image','attachment']));

           DB::commit();

           return $this->returnData('parent',$parent ,'updated successfuly');  //return json response

        }else{

            $parent=My_parent::where('id',$request->id)->update($request->except(['image','attachment']));

            DB::commit();

            return $this->returnData('parent', $parent,'updated successfuly');  //return json response

        }

       }else{
        return $this->returnError('E005', 'updated parent failed');
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

      $Student = Student::where('parent_id',$request->id)->pluck('parent_id');

      if($Student->count() == 0){

          $Parents = My_parent::find($request->id);
          if ($Parents){
            My_parent::where('id',$request->id)->delete();

            $parent=  My_parent::first();
            if($parent){
              return $this->returnData('parent',$parent,'Deleted successfuly');  //return json response

            }else{
                $parent=null;
                return $this->returnData('parent',$parent,'Deleted successfuly');  //return json response
              }
          }else{
            return $this->returnError('001', 'this parent not found');

          }


      }

      else{

        return $this->returnError('001', 'this parent not empty');


      }


  } catch(\Exception $e) {
   return $this->returnError('E005', $e->getMessage());
}

}


public function delete_all($request)
{
    try{

       // return $request->all();
    $delete_all_id = $request->parents_id;

    if ($delete_all_id){
      $parent=  My_parent::whereIn('id', $delete_all_id)->Delete();

      $parent=  My_parent::first();
      if($parent){
        return $this->returnData('parent',$parent,'Deleted successfuly');  //return json response

      }else{
          $parent=null;
          return $this->returnData('parent',$parent,'Deleted successfuly');  //return json response
        }


        }else{
         return $this->returnError('E005', 'deleted parent failed');
        }
}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
}



public function search($request)
{
  try{

    if($request->has('search')){
        $Parents=My_parent::orderBy('id','Desc')
        ->with('nationality_father')
        ->with('student',function($query){
            return $query->with(['grade','section','classroom'])->get();
        })
        ->with('nationality_mother')
        ->with('religion_father')
        ->with('religion_mother')
        ->with('blood_father')
        ->with('blood_mother')
        ->where('Name_Father_en','LIKE','%'.$request->search.'%')->orWhere('Name_Father_ar','LIKE','%'.$request->search.'%')->orWhere('Phone_Father','LIKE','%'.$request->search.'%')->paginate(10);


    }
  if (!$Parents){
      return $this->returnError('001', ' not found data');

  }else{
      return $this->returnData('parent', $Parents,'return all parents');

  }

}catch (\Exception $e){
  return $this->returnError('E005', $e->getMessage());

}
}



}
