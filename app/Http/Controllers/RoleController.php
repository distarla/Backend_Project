<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\Role;

class RoleController extends Controller
{
    public function __construct(Role $role)
    {
        $this->middleware('auth.role:1')->except(['index', 'show']);
        $this->role=$role;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/roles?attr=id,value,...
        if ($request->has('attr_users')) {
            $attr_users=$request->attr_users;
        }
        $user = $request->has('attr_users') ? 'users:id,'.$attr_users : 'users';
        $roles=$this->role->with($user);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $roles=$roles->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'users_id' nos attr caso contrário devolve nulo
            $roles=$roles->selectRaw($request->attr)->get();
        } else {
            $roles=$roles->get();
        }
        return response()->json($roles,200);
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
        $request->validate($this->role->regras(),$this->role->feedback());

        $role= $this->role->create($request->all());
        return response()->json($role,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role=$this->role->with('users')->find($id);
        if ($role===null)
            return response()->json(["erro"=>"A Função pesquisada não existe!"],404);
        else
            return response()->json($role,200);
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
        $role=$this->role->find($id);
        if ($role===null)
            return response()->json(["erro"=>"A Função pesquisada não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($role->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->role->feedback());
            }
            else
                $request->validate($this->role->regras($id),$this->role->feedback());

            $role->fill($request->all());
            $role->save();
            return response()->json($role,200);
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
        $role=$this->role->find($id);
        if ($role===null)
            return response()->json(["erro"=>"A Função pesquisada não existe!"],404);
        else {
            $role->delete();
            return response()->json(["msg"=>"A Função foi apagada com sucesso!"],200);;
        }
    }
}
