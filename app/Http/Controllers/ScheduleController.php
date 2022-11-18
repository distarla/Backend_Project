<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    public function __construct(Schedule $schedule)
    {
        $this->schedule=$schedule;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->schedule->regras(),$this->schedule->feedback());

        $schedule = $this->schedule->firstOrCreate(['event_id' => $request->event_id, 'client_id' => $request->client_id], ['event_id' => $request->event_id, 'client_id' => $request->client_id]);
        return response()->json($schedule,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($event_id, $client_id)
    {
        $schedule=$this->schedule->where('event_id', $event_id)->where('client_id', $client_id);
        if (!$schedule->exists())
            return response()->json(["erro"=>"A marcação pesquisada não existe!"],404);
        else {
            $schedule->delete();
            return response()->json(["msg"=>"A marcação foi apagada com sucesso!"],200);;
        }
    }
}
