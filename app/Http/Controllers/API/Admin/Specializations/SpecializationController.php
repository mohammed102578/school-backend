<?php


namespace App\Http\Controllers\API\Admin\Specializations;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Specializations\SpecializationRepository;
use Illuminate\Http\Request;

class SpecializationController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $specialization;
  public function __construct(SpecializationRepository $specialization)
  {

    $this->specialization=$specialization;
    $this->middleware('auth:api');

  }


  //=================get user profile
  public function index(Request $request)  {
   return $this->specialization->index($request);
  }




  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->specialization->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->specialization->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->specialization->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->specialization->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->specialization->search($request);

  }
}
