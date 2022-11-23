<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Psr\Http\Message\ResponseInterface;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->middleware('auth.role:1')->except(['index', 'show']);
        $this->user=$user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //api/users?attr=id,value,...
        if ($request->has('attr_role')) {
            $attr_role=$request->attr_role;
        }
        $role = $request->has('attr_role') ? 'role:id,'.$attr_role : 'role';
        $users=$this->user->with($role);

        //...&filter=nome:=:5008
        if ($request->has('filter')) {
            $filters=explode(";",$request->filter);

            foreach($filters as $key=>$expression) {
                $conditions=explode(":",$expression);
                $users=$users->where($conditions[0],$conditions[1],$conditions[2]);
            }
        }

        if ($request->has('attr')) {
            //with tem de ter o atributo 'users_id' nos attr caso contrário devolve nulo
            $users=$users->selectRaw($request->attr)->get();
        } else {
            $users=$users->get();
        }
        return response()->json($users,200);
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
        $request->validate($this->user->regras(),$this->user->feedback());

        $user= $this->user->create(array_merge(
            $request->all(), ['password' => bcrypt($request->password)]));
        return response()->json($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=$this->user->with('role')->find($id);
        if ($user===null)
            return response()->json(["erro"=>"O Utilizador pesquisado não existe!"],404);
        else
            return response()->json($user,200);
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
        $user=$this->user->find($id);
        if ($user===null)
            return response()->json(["erro"=>"O Utilizador pesquisado não existe!"],404);
        else {
            if ($request->method() === 'PATCH') {
                $regrasDinamicas=array();

                //Percorrer todas as regras do Model
                foreach($user->regras() as $input=>$regra)  {
                    //adiciona no array regrasdinamicas as regras correspondentes aos campos submetidos
                    if(array_key_exists($input,$request->all()))
                        $regrasDinamicas[$input]=$regra;
                }
                $request->validate($regrasDinamicas,$this->user->feedback());
            } else {
                $request->validate($this->user->regras($id),$this->user->feedback());
            }

            if (array_key_exists('password',$request->all())) {
                $user->fill(array_merge($request->all(), ['password' => bcrypt($request->password)]));
            } else {
                $user->fill($request->all());
            }
            $user->save();
            return response()->json($user,200);
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
        $user=$this->user->find($id);
        if ($user===null)
            return response()->json(["erro"=>"O Utilizador pesquisado não existe!"],404);
        else {
            $user->delete();
            return response()->json(["msg"=>"O Utilizador foi apagado com sucesso!"],200);;
        }
    }
}
