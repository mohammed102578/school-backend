<?php


namespace App\Http\Controllers\API\Admin\Grades;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Grades\GradeRepository;
use Illuminate\Http\Request;

class GradeController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $grade;
  public function __construct(GradeRepository $grade)
  {

    $this->grade=$grade;
    $this->middleware('auth:api');

  }


  //=================get user profile
  public function index(Request $request)  {
   return $this->grade->index($request);
  }




  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->grade->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->grade->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->grade->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->grade->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->grade->search($request);

  }
}
