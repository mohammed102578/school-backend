<?php


namespace App\Interface\Admin;


interface ProfileInterface
{
    public function get_profile($request);

    public function update_profile($request);

    public function change_password($request);

    public function change_image($request);


}
