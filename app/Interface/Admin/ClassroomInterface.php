<?php


namespace App\Interface\Admin;


interface ClassroomInterface
{

    public function index($request);

    public function store($request);

    public function update($request);

    public function destroy($request);

    public function delete_all($request);

    public function search($request);

}
