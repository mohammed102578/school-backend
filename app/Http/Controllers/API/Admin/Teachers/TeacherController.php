<?php


namespace App\Http\Controllers\API\Admin\Teachers;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Teacher\TeacherRepository;
use Illuminate\Http\Request;

class TeacherController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $teacher;
  public function __construct(TeacherRepository $teacher)
  {

    $this->teacher=$teacher;
    $this->middleware('auth:api');

  }


  //=================get all teacher
  public function index(Request $request)  {
   return $this->teacher->index($request);
  }

  public function get_teacher($id)  {
    return $this->teacher->get_teacher($id);
   }
  public function get_realation_teacher_data(Request $request)  {
    return $this->teacher->get_realation_teacher_data($request);
   }




  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->teacher->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->teacher->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->teacher->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->teacher->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->teacher->search($request);

  }
}
