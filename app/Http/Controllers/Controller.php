<?php

namespace App\Http\Controllers;

abstract class Controller extends \Illuminate\Routing\Controller
{
    //

    protected string $module = '';

    protected $pageData = [];


    protected function view(string $view, array $data = [])
    {

        if ($this->module) {
            $view = 'pages.' . $this->module . '.' . $view;
        }

        return view($view, $data);
    }
}
