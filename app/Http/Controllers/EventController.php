<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Event;
use App\Models\Price;

class EventController extends Controller
{
    public function __construct(Event $event)
    {
        $this->event=$event;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/events?attr=id,value,...
        if ($request->has('attr_price')) {
            $attr_price=$request->attr_price;
        }
        if ($request->has('attr_menu')) {
            $attr_menu=$request->attr_menu;
        }
        if ($request->has('attr_range')) {
            $attr_range=$request->attr_range;
        }
        if ($request->has('attr_clients')) {
            $attr_clients=$request->attr_clients;
        }
        $price = $request->has('attr_price') ? 'price:id,'.$attr_price : 'price';
        $menu = $request->has('attr_menu') ? 'menu:id,'.$attr_menu : 'menu';
        $range = $request->has('attr_range') ? 'range:id,'.$attr_range : 'range';
        $client = $request->has('attr_clients') ? 'clients:id,'.$attr_clients : 'clients';
        $events=$this->event->with($price, $menu, $range, $client);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $events=$events->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'price_id', 'menu_id', 'range_id', 'clients_id' nos attr caso contrário devolve nulo
            $events=$events->selectRaw($request->attr)->get();
        } else {
            $events=$events->get();
        }
        return response()->json($events,200);
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
        $request->validate($this->event->regras(),$this->event->feedback());

        $price = Price::where('menu_id', $request->menu_id)->where('range_id', $request->range_id)->get();
        $event_att = array_merge($request->all(), array('price_id'=>$price[0]->id));
        $event= $this->event->create($event_att);

        return response()->json($event,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event=$this->event->with('price', 'menu', 'range', 'clients')->find($id);
        if ($event===null)
            return response()->json(["erro"=>"O Evento pesquisado não existe!"],404);
        else
            return response()->json($event,200);
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

        $event=$this->event->find($id);
        if ($event===null)
            return response()->json(["erro"=>"O Evento pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($event->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->event->feedback());
            }
            else {
                $request->validate($this->event->regras($id),$this->event->feedback());
            }

            $menu_id = $request->has('menu_id') ? $request->menu_id : $event->menu_id;
            $range_id = $request->has('range_id') ? $request->range_id : $event->range_id;
            $price = Price::where('menu_id', $menu_id)->where('range_id', $range_id)->get();
            $event_att = array_merge($request->all(), array('price_id'=>$price[0]->id));
            $event->fill($event_att);
            $event->save();
            return response()->json($event,200);
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
        $event=$this->event->find($id);
        if ($event===null)
            return response()->json(["erro"=>"O Evento pesquisado não existe!"],404);
        else {
            $event->delete();
            return response()->json(["msg"=>"O Evento foi apagado com sucesso!"],200);;
        }
    }
}
