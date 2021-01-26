<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index(){
        return Group::all(); 
    }

    public function store(Request $request){

        $data = $request->all();
        $data['cnpj'] = preg_replace('/\D/','',$data['cnpj']);
        return Group::create($data);

    }

}