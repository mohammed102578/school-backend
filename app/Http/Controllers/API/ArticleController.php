<?php

namespace App\Http\Controllers\API;

use App\Models\Article;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use GeneralTrait;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    //get all articles
    public function index()
    {
        $article = Article::all();
        return $this->returnData('article', $article);  //return json response

    }


    //get one article
    public function getArticleById(Request $request)
    {

        $article = Article::find($request->id);
        if (!$article)
            return $this->returnError('001', 'this article not found');

        return $this->returnData('article', $article);
    }


    //create article
    public function store(Request $request)
    {

        try{
        $rules = [
            "title" => "required",
            "body" => "required",
            "author" => "required"


        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        //add article

        $credentials = $request->only(['title', 'body','author']);

            $article = Article::create($credentials);

        if (!$article)
            return $this->returnError('E005', 'created article failed');

        return $this->returnData('article', $article);  //return json response

    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }




    //update article
    public function update(Request $request)
    {

        try{
        $rules = [
            "title" => "required",
            "body" => "required",
            "author" => "required"


        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $code = $this->returnCodeAccordingToInput($validator);
            return $this->returnValidationError($code, $validator);
        }

        $article = Article::find($request->id);
        if (!$article)
            return $this->returnError('001', 'this article not found');



        //update article

        $credentials = $request->only(['title', 'body','author']);

          $update=  $article->update($credentials);


        if (!$update){
            return $this->returnError('E010', 'updated failed');

        }else{
            return $this->returnData('article', $update,"updated successfully");  //return json response

        }




    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }
    }





//delete article
    public function destroy(Request $request)
    {

        $article = Article::find($request->id);
        if (!$article)
            return $this->returnError('001', 'this article not found');


            $article->delete();
        return $this->returnData('article', $article,'article deleted successfully');
    }

}
