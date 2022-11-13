<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Client;

class ClientController extends Controller
{
    public function __construct(Client $client)
    {
        $this->client=$client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients=$this->client->all();
        return response()->json($clients,200);
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
        $request->validate($this->client->regras(),$this->client->feedback());

        $client= $this->client->create($request->all());
        return response()->json($client,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client=$this->client->find($id);
        if ($client===null)
            return response()->json(["erro"=>"O Cliente pesquisado não existe!"],404);
        else
            return response()->json($client,200);
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
        $client=$this->client->find($id);
        if ($client===null)
            return response()->json(["erro"=>"O Cliente pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($client->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->client->feedback());
            }
            else
                $request->validate($this->client->regras($id),$this->client->feedback());

            $client->update($request->all());
            return response()->json($client,200);
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
        $client=$this->client->find($id);
        if ($client===null)
            return response()->json(["erro"=>"O Cliente pesquisado não existe!"],404);
        else {
            $client->delete();
            return response()->json(["msg"=>"O Cliente foi apagado com sucesso!"],200);;
        }
    }
}
