<?php


namespace App\Interface\Admin;


interface TeacherInterface
{
    public function get_realation_teacher_data($request);

    public function index($request);

    public function get_teacher($request);

    public function store($request);

    public function update($request);

    public function destroy($request);

    public function delete_all($request);

    public function search($request);

}
