<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderSpecification;

class OrdersSpecificationsController extends Controller
{

    protected $model;

    public function __construct(OrderSpecification $model){
        $this->model = $model;
    }

    public function index(){
    }
    public function show(){
    }
    public function store(){
    }
    public function edit(){
    }
    public function update(){
    }
    public function destroy(){
    }

}
