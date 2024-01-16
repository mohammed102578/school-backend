<?php


namespace App\Http\Controllers\API\Admin\Students;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Student\StudentRepository;
use Illuminate\Http\Request;

class StudentController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $student;
  public function __construct(StudentRepository $student)
  {

    $this->student=$student;

    $this->middleware('auth:api');

  }


  //=================get all student
  public function index(Request $request)  {
   return $this->student->index($request);
  }

  public function get_student($id)  {
    return $this->student->get_student($id);
   }


   public function get_classroom(Request $request)  {
    return $this->student->get_classroom($request);
   }


   public function get_student_mother(Request $request)  {
    return $this->student->get_student_mother($request);
   }


   public function get_section(Request $request)  {
    return $this->student->get_section($request);
   }


  public function get_realation_student_data(Request $request)  {
    return $this->student->get_realation_student_data($request);
   }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->student->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->student->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->student->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->student->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->student->search($request);

  }

  public function Upload_attachment(Request $request)
  {
      return $this->student->Upload_attachment($request);
  }

  public function Download_attachment($studentsname,$filename)
  {
      return $this->student->Download_attachment($studentsname,$filename);
  }

  public function Delete_attachment(Request $request)
  {
      return $this->student->Delete_attachment($request);

  }
}
