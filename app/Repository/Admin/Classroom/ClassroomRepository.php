<?php


namespace App\Repository\Admin\Classroom;

use App\Interface\Admin\ClassroomInterface;
use App\Models\Classroom;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;

class ClassroomRepository implements ClassroomInterface
{
    use GeneralTrait;

    //=================get  classroom
    public function index($request)
    {
      try{
          if($request->Items_show_number != null){
              $paginate= $request->Items_show_number;

          }else{
              $paginate=10;
          }
      $Classroom = Classroom::orderBy('id','DESC')->with('Grades')->paginate($paginate);
      if (!$Classroom){
          return $this->returnError('001', ' not found data');

      }else{
          return $this->returnData('classroom', $Classroom,'return all classrooms');

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
          'name_ar' => 'required',
          'name_en' => 'required',
          'grade_id'=>'numeric|required',

      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
          $code = $this->returnCodeAccordingToInput($validator);
          return $this->returnValidationError($code, $validator);
      }
$find=Classroom::where('name_ar',$request->name_ar)->where('name_en',$request->name_en)->where('grade_id',$request->grade_id)->first();
  if(!$find){
    $Classroom = Classroom::create($request->all());
    if ($Classroom){
        return $this->returnData('classroom', $Classroom,'created successfully');  //return json response

      }else{
        return $this->returnError('E005', 'created classroom failed');

      }
  }else{
    return $this->returnError('E005', 'this classroom already added');

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
          'name_ar' => 'required',
          'name_en' => 'required',
          'grade_id'=>'numeric|required',

      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
          $code = $this->returnCodeAccordingToInput($validator);
          return $this->returnValidationError($code, $validator);
      }

       $find=Classroom::where('id',"!=",$request->id)->where('name_ar',$request->name_ar)->where('name_en',$request->name_en)->where('grade_id',$request->grade_id)->first();


       if(!$find){
        $Classroom = Classroom::find($request->id);

        if ($Classroom){
            $Classroom = Classroom::where('id',$request->id)->update($request->only('name_en','name_ar','grade_id'));
            return $this->returnData('classroom', $Classroom,'updated successfuly');  //return json response

        }else{
         return $this->returnError('E005', 'updated classroom failed');
        }

      }else{
        return $this->returnError('E005', 'this classroom already added');

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


            $Classroom = Classroom::find($request->id);
            if ($Classroom){
                Classroom::where('id',$request->id)->delete();

                $classroom=  Classroom::first();

                if($classroom){
                  return $this->returnData('classroom',$classroom,'Deleted successfuly');  //return json response
                }else{
                    $classroom=null;
                    return $this->returnData('classroom',$classroom,'Deleted successfuly');  //return json response
                  }

            }else{
                return $this->returnError('001', 'this classroom not found');

            }





    } catch(\Exception $e) {
     return $this->returnError('E005', $e->getMessage());
  }

  }


  public function delete_all($request)
  {
      try{

      $delete_all_id = $request->classrooms_id;

      if ($delete_all_id){
        $classroom=  Classroom::whereIn('id', $delete_all_id)->Delete();
        $classroom=  Classroom::first();

        if($classroom){
          return $this->returnData('classroom',$classroom,'Deleted successfuly');  //return json response
        }else{
            $classroom=null;
            return $this->returnData('classroom',$classroom,'Deleted successfuly');  //return json response
          }
          }else{
           return $this->returnError('E005', 'deleted classroom failed');
          }
  }catch (\Exception $e){
      return $this->returnError('E005', $e->getMessage());

  }
  }



  public function search($request)
  {
    try{


      if($request->has('search')){
          $Classroom=Classroom::with('Grades')->where('name_ar','LIKE','%'.$request->search.'%')->orWhere('name_en','LIKE','%'.$request->search.'%')->paginate(10);
      }
    if (!$Classroom){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('classroom', $Classroom,'return all classrooms');

    }

  }catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

  }
  }



  }
