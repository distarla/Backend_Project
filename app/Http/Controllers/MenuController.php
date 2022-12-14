<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Menu;

class MenuController extends Controller
{
    public function __construct(Menu $menu)
    {
        $this->middleware('auth.role:1,2')->except(['index', 'show']);
        $this->menu=$menu;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/menus?attr=id,value,...

        if ($request->has('attr_prices')) {
            $attr_prices=$request->attr_prices;
        }
        if ($request->has('attr_events')) {
            $attr_events=$request->attr_events;
        }
        $price = $request->has('attr_prices') ? 'prices:id,'.$attr_prices : 'prices';
        $event = $request->has('attr_events') ? 'events:id,'.$attr_events : 'events';
        $menus=$this->menu->with($price, $event);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $menus=$menus->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'price_id', 'events_id' nos attr caso contrário devolve nulo
            $menus=$menus->selectRaw($request->attr)->get();
        } else {
            $menus=$menus->get();
        }
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
        $menu=$this->menu->with('prices', 'events')->find($id);
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

            $menu->fill($request->all());
            $menu->save();
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
