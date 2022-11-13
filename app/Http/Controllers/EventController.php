<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Event;

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

        if ($request->has('attr')) {
            //with tem de ter o atributo 'price_id', 'menu_id', 'range_id', 'clients_id' nos attr caso contrário devolve nulo
            $events=$this->event->selectRaw($request->attr)->with('price', 'menu', 'range', 'clients')->get();
        } else {
            $events=$this->event->with('price', 'menu', 'range', 'clients')->get();
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

        $event= $this->event->create($request->all());
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
            else
                $request->validate($this->event->regras($id),$this->event->feedback());

            $event->fill($request->all());
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
