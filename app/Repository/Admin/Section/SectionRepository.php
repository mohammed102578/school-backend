<?php


namespace App\Repository\Admin\Section;

use App\Interface\Admin\SectionInterface;
use App\Models\Classroom;
use App\Models\Section;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SectionRepository implements SectionInterface
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
      $Section = Section::orderBy('id','DESC')->with('My_classs')->with('Grades')->paginate($paginate);
      if (!$Section){
          return $this->returnError('001', ' not found data');

      }else{
          return $this->returnData('section', $Section,'return all sections');

      }

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

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($request)
    {
   try {
      $rules = [
          'name_section_ar' => 'required',
          'name_section_en' => 'required',
          'grade_id'=>'numeric|required',
          'classroom_id'=>'numeric|required',

      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
          $code = $this->returnCodeAccordingToInput($validator);
          return $this->returnValidationError($code, $validator);
      }

      $find=Section::where('name_section_ar',$request->name_section_ar)->where('name_section_en',$request->name_section_en)->where('grade_id',$request->grade_id)->where('classroom_id',$request->classroom_id)->first();

      if(!$find){
        $Section = Section::create($request->all());
        if ($Section){
            return $this->returnData('section', $Section,'created successfully');  //return json response

          }else{
            return $this->returnError('E005', 'created section failed');

          }
      }else{
        return $this->returnError('E005', 'this section already added');

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
          'name_section_ar' => 'required',
          'name_section_en' => 'required',
          'grade_id'=>'numeric|required',
          'classroom_id'=>'numeric|required',

      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
          $code = $this->returnCodeAccordingToInput($validator);
          return $this->returnValidationError($code, $validator);
      }




      $find=Section::where('id','!=',$request->id)->where('name_section_ar',$request->name_section_ar)->where('name_section_en',$request->name_section_en)->where('grade_id',$request->grade_id)->where('classroom_id',$request->classroom_id)->first();

      if(!$find){
        $Section = Section::find($request->id);

        if ($Section){
         $Section = Section::where('id',$request->id)->update($request->only('name_section_en','name_section_ar','grade_id','classroom_id'));
         return $this->returnData('section', $Section,'updated successfuly');  //return json response

        }else{
         return $this->returnError('E005', 'updated section failed');
        }
      }else{
        return $this->returnError('E005', 'this section already added');

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


            $Section = Section::find($request->id);
            if ($Section){
                Section::where('id',$request->id)->delete();
                $section =Section::first();
                if($section){
                    return $this->returnData('section',$section,'Deleted successfuly');

                }else{
                    $section=null;
                    return $this->returnData('section',$section,'Deleted successfuly');
                }

            }else{
                return $this->returnError('001', 'this section not found');

            }





    } catch(\Exception $e) {
     return $this->returnError('E005', $e->getMessage());
  }

  }


  public function delete_all($request)
  {
      try{

      $delete_all_id = $request->sections_id;

      if ($delete_all_id){
        $section=  Section::whereIn('id', $delete_all_id)->Delete();
        $section=  Section::first();
        if($section){
            return $this->returnData('section',$section,'Deleted successfuly');

        }else{
            $section=null;
            return $this->returnData('section',$section,'Deleted successfuly');
        }
           //return json response

          }else{
           return $this->returnError('E005', 'deleted section failed');
          }
  }catch (\Exception $e){
      return $this->returnError('E005', $e->getMessage());

  }
  }



  public function search($request)
  {
    try{
      if($request->has('search')){
          $Section=Section::with('Grades')->with('My_classs')->where('name_section_ar','LIKE','%'.$request->search.'%')->orWhere('name_section_en','LIKE','%'.$request->search.'%')->paginate(10);
      }
    if (!$Section){
        return $this->returnError('001', ' not found data');

    }else{
        return $this->returnData('section', $Section,'return all sections');

    }

  }catch (\Exception $e){
    return $this->returnError('E005', $e->getMessage());

  }
  }



  }
