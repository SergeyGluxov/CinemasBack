<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class YearsFilter
{
    //todo: Пренести в норм модель фильтра
    public $id;
    public $title;

    public function __construct($id, $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

}
