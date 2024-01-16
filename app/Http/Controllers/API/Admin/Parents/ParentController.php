<?php


namespace App\Http\Controllers\API\Admin\Parents;
use App\Http\Controllers\API\Controller;
use App\Repository\Admin\Parent\ParentRepository;
use Illuminate\Http\Request;

class ParentController extends Controller
{


  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public $parent;
  public function __construct(ParentRepository $parent)
  {

    $this->parent=$parent;
    $this->middleware('auth:api');

  }


  //=================get all parent
  public function index(Request $request)  {
   return $this->parent->index($request);
  }

  public function get_parent($id)  {
    return $this->parent->get_parent($id);
   }
  public function get_realation_parent_data(Request $request)  {
    return $this->parent->get_realation_parent_data($request);
   }




  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    return $this->parent->store($request);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
   public function update(Request $request)
 {
    return $this->parent->update($request);

 }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy(Request $request)
  {
    return $this->parent->destroy($request);

  }


  public function delete_all(Request $request)
  {
    return $this->parent->delete_all($request);

  }


  public function search(Request $request)
  {
    return $this->parent->search($request);

  }
}
