<?php


namespace App\Interface\Admin;


interface StudentInterface
{

    public function get_classroom( $request);

    public function get_section( $request);

    public function get_student_mother( $request);

    public function get_realation_student_data($request);

    public function index($request);

    public function get_student($request);

    public function store($request);

    public function update($request);

    public function destroy($request);

    public function delete_all($request);

    public function search($request);

    public function Upload_attachment($request);

    public function  Download_attachment($studentsname, $filename);

    public function Delete_attachment($request);

}
