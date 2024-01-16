<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tymon\JWTAuth\Facades\JWTAuth;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//all routes / api here must be api authenticated
Route::group(['middleware' => ['checkPassword'], 'namespace' => 'API', 'prefix' => 'Api'], function () {

    //article route
    Route::post('get_articles', 'ArticleController@index');
    Route::post('article', 'ArticleController@getArticleById');
    Route::post('add_article', 'ArticleController@store');
    Route::post('update_article', 'ArticleController@update');
    Route::post('delete_article', 'ArticleController@destroy');

    //auth route
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::get('logout', 'AuthController@logout');

    //profile route
    Route::post('get_profile', 'Admin\Profile\ProfileController@get_profile');
    Route::post('change_password', 'Admin\Profile\ProfileController@change_password');
    Route::post('change_image', 'Admin\Profile\ProfileController@change_image');
    Route::post('update_profile', 'Admin\Profile\ProfileController@update_profile');

    //grade
    Route::post('get_grades','Admin\Grades\GradeController@index');
    Route::post('add_grade','Admin\Grades\GradeController@store');
    Route::post('search_grade','Admin\Grades\GradeController@search');
    Route::post('update_grade', 'Admin\Grades\GradeController@update');
    Route::delete('delete_grade', 'Admin\Grades\GradeController@destroy');
    Route::post('grade_delete_all', 'Admin\Grades\GradeController@delete_all');

    //grade
    Route::post('get_specializations','Admin\Specializations\SpecializationController@index');
    Route::post('add_specialization','Admin\Specializations\SpecializationController@store');
    Route::post('search_specialization','Admin\Specializations\SpecializationController@search');
    Route::post('update_specialization', 'Admin\Specializations\SpecializationController@update');
    Route::delete('delete_specialization', 'Admin\Specializations\SpecializationController@destroy');
    Route::post('specialization_delete_all', 'Admin\Specializations\SpecializationController@delete_all');




    //classroom
    Route::post('get_classrooms',   'Admin\Classrooms\ClassroomController@index');
    Route::post('add_classroom',    'Admin\Classrooms\ClassroomController@store');
    Route::post('search_classroom', 'Admin\Classrooms\ClassroomController@search');
    Route::post('update_classroom', 'Admin\Classrooms\ClassroomController@update');
    Route::delete('delete_classroom','Admin\Classrooms\ClassroomController@destroy');
    Route::post('classroom_delete_all','Admin\Classrooms\ClassroomController@delete_all');

     //section
     Route::post('get_sections',   'Admin\Sections\SectionController@index');
     Route::post('get_grade_classroom','Admin\Sections\SectionController@get_classroom');
     Route::post('add_section',    'Admin\Sections\SectionController@store');
     Route::post('search_section', 'Admin\Sections\SectionController@search');
     Route::post('update_section', 'Admin\Sections\SectionController@update');
     Route::delete('delete_section','Admin\Sections\SectionController@destroy');
     Route::post('section_delete_all','Admin\Sections\SectionController@delete_all');


      //parent
      Route::post('get_parents',   'Admin\Parents\ParentController@index');
      Route::get('get_parent/{id}',   'Admin\Parents\ParentController@get_parent');
      Route::get('get_realation_parent_data','Admin\Parents\ParentController@get_realation_parent_data');
      Route::post('add_parent',    'Admin\Parents\ParentController@store');
      Route::post('search_parent', 'Admin\Parents\ParentController@search');
      Route::post('update_parent', 'Admin\Parents\ParentController@update');
      Route::delete('delete_parent','Admin\Parents\ParentController@destroy');
      Route::post('parent_delete_all','Admin\Parents\ParentController@delete_all');



       //teacher
       Route::post('get_teachers',   'Admin\Teachers\TeacherController@index');
       Route::get('get_teacher/{id}',   'Admin\Teachers\TeacherController@get_teacher');
       Route::get('get_realation_teacher_data','Admin\Teachers\TeacherController@get_realation_teacher_data');
       Route::post('add_teacher',    'Admin\Teachers\TeacherController@store');
       Route::post('search_teacher', 'Admin\Teachers\TeacherController@search');
       Route::post('update_teacher', 'Admin\Teachers\TeacherController@update');
       Route::delete('delete_teacher','Admin\Teachers\TeacherController@destroy');
       Route::post('teacher_delete_all','Admin\Teachers\TeacherController@delete_all');


       //Student
       Route::post('get_students',   'Admin\Students\StudentController@index');
       Route::get('get_student/{id}',   'Admin\Students\StudentController@get_student');
       Route::get('get_realation_student_data','Admin\Students\StudentController@get_realation_student_data');
       Route::post('get_student_grade_classroom','Admin\Students\StudentController@get_classroom');
       Route::post('get_student_classroom_section','Admin\Students\StudentController@get_section');
       Route::post('get_student_mother','Admin\Students\StudentController@get_student_mother');
       Route::post('add_student',    'Admin\Students\StudentController@store');
       Route::post('search_student', 'Admin\Students\StudentController@search');
       Route::post('update_student', 'Admin\Students\StudentController@update');
       Route::delete('delete_student','Admin\Students\StudentController@destroy');
       Route::post('student_delete_all','Admin\Students\StudentController@delete_all');
});
