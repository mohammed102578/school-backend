<?php


namespace App\Interface\Admin;


interface SectionInterface
{
    public function index($request);

    public function get_classroom( $request);

    public function store($request);

    public function update($request);

    public function destroy($request);

    public function delete_all($request);

    public function search($request);

}
