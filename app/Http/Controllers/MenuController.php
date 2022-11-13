<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Menu;

class MenuController extends Controller
{
    public function __construct(Menu $menu)
    {
        $this->menu=$menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menus=$this->menu->all();
        return response()->json($menus,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->menu->regras(),$this->menu->feedback());

        $menu= $this->menu->create($request->all());
        return response()->json($menu,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menu=$this->menu->find($id);
        if ($menu===null)
            return response()->json(["erro"=>"O Menu pesquisado não existe!"],404);
        else
            return response()->json($menu,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $menu=$this->menu->find($id);
        if ($menu===null)
            return response()->json(["erro"=>"O Menu pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($menu->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->menu->feedback());
            }
            else
                $request->validate($this->menu->regras($id),$this->menu->feedback());

            $menu->update($request->all());
            return response()->json($menu,200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu=$this->menu->find($id);
        if ($menu===null)
            return response()->json(["erro"=>"O Menu pesquisado não existe!"],404);
        else {
            $menu->delete();
            return response()->json(["msg"=>"O Menu foi apagado com sucesso!"],200);;
        }
    }
}
