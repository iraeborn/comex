<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrderDocumentOrder;

class OrdersDocumentOrderController extends Controller
{

    protected $model;

    public function __construct(OrderDocumentOrder $model){
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
