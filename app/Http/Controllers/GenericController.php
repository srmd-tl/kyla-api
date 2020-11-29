<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Yaml\Yaml;

class GenericController extends Controller
{
    public function races()
    {
        $filePath = (public_path() . '/race.yml');
        $races = Yaml::parse(file_get_contents($filePath));

        return $races ? response()->success($races) : response()->error("Nohting Found!");
    }

    public function genders()
    {
        $filePath = public_path() . '/gender.yml';
        $genders = Yaml::parse(file_get_contents($filePath));

        return $genders ? response()->success($genders) : response()->error("Nothing Found");
    }

    public function states()
    {
        $filePath = public_path() . '/states.yml';
        $states = Yaml::parse(file_get_contents($filePath));
        return $states ? response()->success($states) : response()->error("Nothing Found!");
    }

    public function laws()
    {
        $filePath = public_path().'laws.yml';
        $laws = Yaml::parse(file_get_contents($filePath));
        return $laws?response()->success($laws):response()->error("Nothing Found!");
    }

}

