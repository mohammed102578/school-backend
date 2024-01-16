<?php


namespace App\Http\Controllers\API\Admin\Classrooms;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Classroom\ClassroomRepository;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $classroom;
  public function __construct(ClassroomRepository $classroom)
  {

    $this->classroom=$classroom;
    $this->middleware('auth:api');

  }


  //=================get user profile
  public function index(Request $request)  {
   return $this->classroom->index($request);
  }




  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->classroom->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->classroom->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->classroom->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->classroom->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->classroom->search($request);

  }
}
