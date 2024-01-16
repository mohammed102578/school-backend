<?php


namespace App\Repository\Admin\Specializations;
use App\Models\Classroom;
use App\Traits\GeneralTrait;
use App\Interface\Admin\SpecializationInterface;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SpecializationRepository implements SpecializationInterface
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
    $Specializations = Specialization::orderBy('id','DESC')->paginate($paginate);
    if (!$Specializations){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('specialization', $Specializations,'return all specializations');

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
        'name_ar' => 'required|unique:specializations,name_ar,'.$request->id,
        'name_en' => 'required|unique:specializations,name_en,'.$request->id,

    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $Specialization = Specialization::create($request->all());


          if ($Specialization){
            return $this->returnData('specialization', $Specialization,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created specialization failed');

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
        'name_ar' => 'required|unique:specializations,name_ar,'.$request->id,
        'name_en' => 'required|unique:specializations,name_en,'.$request->id,
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        $code = $this->returnCodeAccordingToInput($validator);
        return $this->returnValidationError($code, $validator);
    }
    $Specialization = Specialization::find($request->id);

       if ($Specialization){
        $Specialization = Specialization::where('id',$request->id)->update($request->only('name_en','name_ar','notes'));
        return $this->returnData('specialization', $Specialization,'updated successfuly');  //return json response

       }else{
        return $this->returnError('E005', 'updated specialization failed');
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

          $Specializations = Specialization::find($request->id);
          if ($Specializations){
            Specialization::where('id',$request->id)->delete();

            $specialization=  Specialization::first();
            if($specialization){
              return $this->returnData('specialization',$specialization,'Deleted successfuly');  //return json response

            }else{
                $specialization=null;
                return $this->returnData('specialization',$specialization,'Deleted successfuly');  //return json response
              }
          }else{
            return $this->returnError('001', 'this specialization not found');

          }

      return $this->returnData('specialization', $Specializations,'specialization deleted successfully');




  } catch(\Exception $e) {
   return $this->returnError('E005', $e->getMessage());
}

}


public function delete_all($request)
{
    try{

       // return $request->all();
    $delete_all_id = $request->specializations_id;

    if ($delete_all_id){
      $specialization=  Specialization::whereIn('id', $delete_all_id)->Delete();

      $specialization=  Specialization::first();
      if($specialization){
        return $this->returnData('specialization',$specialization,'Deleted successfuly');  //return json response

      }else{
          $specialization=null;
          return $this->returnData('specialization',$specialization,'Deleted successfuly');  //return json response
        }


        }else{
         return $this->returnError('E005', 'deleted specialization failed');
        }
}catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

}
}



public function search($request)
{
  try{

    if($request->has('search')){
        $Specializations=DB::table('specializations')->where('name_ar','LIKE','%'.$request->search.'%')->orWhere('name_en','LIKE','%'.$request->search.'%')->paginate(10);
    }
  if (!$Specializations){
      return $this->returnError('001', ' not found data');

  }else{
      return $this->returnData('specialization', $Specializations,'return all specializations');

  }

}catch (\Exception $e){
  return $this->returnError('E005', $e->getMessage());

}
}



}
