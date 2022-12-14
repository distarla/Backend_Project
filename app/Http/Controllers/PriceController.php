<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Price;
use App\Models\Event;

class PriceController extends Controller
{
    public function __construct(Price $price)
    {
        $this->middleware('auth.role:1,2')->except(['index', 'show']);
        $this->price=$price;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/prices?attr=id,value,...
        if ($request->has('attr_menu')) {
            $attr_menu=$request->attr_menu;
        }
        if ($request->has('attr_range')) {
            $attr_range=$request->attr_range;
        }
        $menu = $request->has('attr_menu') ? 'menu:id,'.$attr_menu : 'menu';
        $range = $request->has('attr_range') ? 'range:id,'.$attr_range : 'range';
        $prices=$this->price->with($menu, $range);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $prices=$prices->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'menu_id', 'range_id' nos attr caso contrário devolve nulo
            $prices=$prices->selectRaw($request->attr)->get();
        } else {
            $prices=$prices->get();
        }
        return response()->json($prices,200);
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
        $request->validate($this->price->regras(),$this->price->feedback());

        $price= $this->price->create($request->all());
        return response()->json($price,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $price=$this->price->with('menu', 'range')->find($id);
        if ($price===null)
            return response()->json(["erro"=>"O Preço pesquisado não existe!"],404);
        else
            return response()->json($price,200);
    }

    /**
     * Display the events for the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show_events($id)
    {
        $price=$this->price->find($id);
        if ($price===null)
            return response()->json(["erro"=>"O Preço pesquisado não existe!"],404);
        else {
            $events = Event::where('menu_id', $price->menu_id)->where('range_id', $price->range_id)->get();
            return response()->json($events,200);
        }
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
        $price=$this->price->find($id);
        if ($price===null)
            return response()->json(["erro"=>"O Preço pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($price->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->price->feedback());
            }
            else
                $request->validate($this->price->regras($id),$this->price->feedback());

            $price->fill($request->all());
            $price->save();
            return response()->json($price,200);
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
        $price=$this->price->find($id);
        if ($price===null)
            return response()->json(["erro"=>"O Preço pesquisado não existe!"],404);
        else {
            $price->delete();
            return response()->json(["msg"=>"O Preço foi apagado com sucesso!"],200);;
        }
    }
}
