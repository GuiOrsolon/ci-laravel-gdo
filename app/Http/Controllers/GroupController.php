<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index(){
        return Group::all(); 
    }

    public function store(Request $request){

        $data = $this->filterData($request->all());
        $validator = $this->validator($data);
        if($validator->fails()){
            return response()->json($validator->errors())->setStatusCode(400);
        }

        return Group::create($data);

    }

    private function validator(array $data){

        $rules    = $this->getRules();
        $messages = $this->getMessages();

        return Validator::make($data, $rules, $messages);
    }

    private function getRules(){

        return [
            'name' => 'required|max:50',
            'cnpj' => 'required|cnpj|unique:groups'
        ];
        
    }

    private function getMessages(){

        return [
            'required' => 'O campo é obrigatório!',
            'cnpj'     => 'CNPJ é inválido!',
            'max'      => 'Máximo de :max caracteres!',
            'unique'   => ':input já em uso!'
        ];

    }

    private function filterData($values){
        $data = [];

        if(!$values){
            return $data;
        }

        foreach($values as $key => $value){

            if (is_string($value) && trim(strip_tags($value))){
                $data[$key] = trim(strip_tags($value));
            }

            if (is_array($value) && !empty($value)){
                $data[$key] = $this->filterData($value);
            }

            if (is_int($value) || is_float($value) || is_bool($value)){
                $data[$key] = $value;
            }

        }

        if(isset($data['cnpj'])){
            $data['cnpj'] = preg_replace('/\D/','',$data['cnpj']);
        }

        return $data;
    }

}