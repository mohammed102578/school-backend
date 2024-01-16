<?php


namespace App\Http\Controllers\API\Admin\Sections;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Section\SectionRepository;
use Illuminate\Http\Request;

class SectionController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $section;
  public function __construct(SectionRepository $section)
  {

    $this->section=$section;
    $this->middleware('auth:api');

  }


  //=================get user profile
  public function index(Request $request)  {
   return $this->section->index($request);
  }



  public function get_classroom(Request $request)  {
    return $this->section->get_classroom($request);
   }


  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->section->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->section->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->section->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->section->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->section->search($request);

  }
}
