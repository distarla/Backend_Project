<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Range;

class RangeController extends Controller
{
    public function __construct(Range $range)
    {
        $this->middleware('auth.role:1,2')->except(['index', 'show']);
        $this->range=$range;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/ranges?attr=id,value,...

        if ($request->has('attr_prices')) {
            $attr_prices=$request->attr_prices;
        }
        if ($request->has('attr_events')) {
            $attr_events=$request->attr_events;
        }
        $price = $request->has('attr_prices') ? 'prices:id,'.$attr_prices : 'prices';
        $event = $request->has('attr_events') ? 'events:id,'.$attr_events : 'events';
        $ranges=$this->range->with($price, $event);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $ranges=$ranges->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'price_id', 'events_id' nos attr caso contrário devolve nulo
            $ranges=$ranges->selectRaw($request->attr)->get();
        } else {
            $ranges=$ranges->get();
        }
        return response()->json($ranges,200);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->range->regras(),$this->range->feedback());

        $range= $this->range->create($request->all());
        return response()->json($range,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $range=$this->range->with('prices', 'events')->find($id);
        if ($range===null)
            return response()->json(["erro"=>"O Escalão pesquisado não existe!"],404);
        else
            return response()->json($range,200);
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
     * @param  \Illuminate\Http\Request $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $range=$this->range->find($id);
        if ($range===null)
            return response()->json(["erro"=>"O Escalão pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($range->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->range->feedback());
            }
            else
                $request->validate($this->range->regras($id),$this->range->feedback());

            $range->fill($request->all());
            $range->save();
            return response()->json($range,200);
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
        $range=$this->range->find($id);
        if ($range===null)
            return response()->json(["erro"=>"O Escalão pesquisado não existe!"],404);
        else {
            $range->delete();
            return response()->json(["msg"=>"O Escalão foi apagado com sucesso!"],200);;
        }
    }
}
