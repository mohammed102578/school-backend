<?php


namespace App\Repository\Admin\Grades;
use App\Models\Classroom;
use App\Traits\GeneralTrait;
use App\Interface\Admin\GradeInterface;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradeRepository implements GradeInterface
{
    use GeneralTrait;




  //=================get user profile
  public function index($request)
  {
    try{
        if($request->Items_show_number != null){
            $paginate= $request->Items_show_number;

        }else{
            $paginate=10;
        }
    $Grades = Grade::orderBy('id','DESC')->paginate($paginate);
    if (!$Grades){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('grade', $Grades,'return all grades');

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
    $rules = [
        'name_ar' => 'required|unique:grades,name_ar,'.$request->id,
        'name_en' => 'required|unique:grades,name_en,'.$request->id,
        'notes' => 'required',

    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $Grade = Grade::create($request->all());


          if ($Grade){
            return $this->returnData('grade', $Grade,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created grade failed');

          }


        }catch (\Exception $e){
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

    $rules = [
        'name_ar' => 'required|unique:grades,name_ar,'.$request->id,
        'name_en' => 'required|unique:grades,name_en,'.$request->id,
        'notes' => 'required',
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $Grade = Grade::find($request->id);

       if ($Grade){
        $Grade = Grade::where('id',$request->id)->update($request->only('name_en','name_ar','notes'));
        return $this->returnData('grade', $Grade,'updated successfuly');  //return json response

       }else{
        return $this->returnError('E005', 'updated grade failed');
       }


   }
   catch(\Exception $e) {
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

      $MyClass_id = Classroom::where('Grade_id',$request->id)->pluck('Grade_id');

      if($MyClass_id->count() == 0){

          $Grades = Grade::find($request->id);
          if ($Grades){
            Grade::where('id',$request->id)->delete();

            $grade=  Grade::first();
            if($grade){
              return $this->returnData('grade',$grade,'Deleted successfuly');  //return json response

            }else{
                $grade=null;
                return $this->returnData('grade',$grade,'Deleted successfuly');  //return json response
              }
          }else{
            return $this->returnError('001', 'this grade not found');

          }

      return $this->returnData('grade', $Grades,'grade deleted successfully');

      }

      else{

        return $this->returnError('001', 'this grade not empty');


      }


  } catch(\Exception $e) {
   return $this->returnError('E005', $e->getMessage());
}

}


public function delete_all($request)
{
    try{

       // return $request->all();
    $delete_all_id = $request->grades_id;

    if ($delete_all_id){
      $grade=  Grade::whereIn('id', $delete_all_id)->Delete();

      $grade=  Grade::first();
      if($grade){
        return $this->returnData('grade',$grade,'Deleted successfuly');  //return json response

      }else{
          $grade=null;
          return $this->returnData('grade',$grade,'Deleted successfuly');  //return json response
        }


        }else{
         return $this->returnError('E005', 'deleted grade failed');
        }
}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
}



public function search($request)
{
  try{

    if($request->has('search')){
        $Grades=DB::table('grades')->where('name_ar','LIKE','%'.$request->search.'%')->orWhere('name_en','LIKE','%'.$request->search.'%')->paginate(10);
    }
  if (!$Grades){
      return $this->returnError('001', ' not found data');

  }else{
      return $this->returnData('grade', $Grades,'return all grades');

  }

}catch (\Exception $e){
  return $this->returnError('E005', $e->getMessage());

}
}



}
