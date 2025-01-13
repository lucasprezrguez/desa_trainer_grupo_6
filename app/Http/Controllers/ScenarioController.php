<?php

namespace App\Http\Controllers;

use App\Models\Scenario;

class ScenarioController extends Controller
{
    public function index(){
        $scenarios = Scenario::all();
        return view('admin.scenarios.index', compact('scenarios'));
    }

    public function create(){
        return view('admin.scenarios.create');
    }

    public function store(){
        //Validation & store
    }

    public function show($id){
        return view('admin.scenarios.show', ['id' => $id]);
    }

    public function edit($id){
        return view('admin.scenarios.edit', ['id' => $id]);
    }

    public function update($id){
        //Validation & update
    }

    public function destroy($id){
        //Delete logic
    }


}
