<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;

class ServiceController extends Controller
{

    public function index(){
      $services = Service::orderBy('id', 'ASC')->paginate(1);
      dd($services);
    }

    public function create(){
      //retornaría la vista para formulario de creación
    }

    public function store(Request $request){
      $service = new Service($request->all());
      $service->save();
      dd('Servicio creado');
    }

    public function show($id){

    }

    // public function edit($id){
    //   $service = Service::find($id);
    //   //Envía usuario a la vista, no se utilizará en entrega de primer proyecto
    // }

    public function update(Request $request, $id){
      dd($request);
      $service = Service::find($id);
      $service->name = $request->name;
      $service->description = $request->description;
      $service->save();
      dd('Servicio editado');
    }

    public function destroy($id){
      $service = Service::find($id);
      $service->delete();
      dd('Servicio eliminado');
    }
}
