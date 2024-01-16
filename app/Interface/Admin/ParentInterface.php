<?php


namespace App\Interface\Admin;


interface ParentInterface
{
    public function get_realation_parent_data($request);

    public function index($request);

    public function get_parent($request);

    public function store($request);

    public function update($request);

    public function destroy($request);

    public function delete_all($request);

    public function search($request);

}
