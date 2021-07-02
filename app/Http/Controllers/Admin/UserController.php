<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Person;
use App\Client;
use App\Broker;
use App\Farm;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:users_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:users_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:users_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data =  User::person()
            ->where('users.id', '!=', auth()->id())
            ->orderBy('name')
            ->paginate(10);

        return view('users.index', compact('data'));
    }

    public function create()
    {
        $roles = Role::orderBy('description')->get(['id', 'description']);

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();


        DB::transaction(function () use ($request) {
            $inputs = $request->all();

            $person = Person::updateOrCreate(
                ['nif' => $request->nif],
                $inputs
            );

            $inputs['person_id'] = $person->id;
            $inputs['password'] = bcrypt($request->input('password'));

            $user = User::updateOrCreate(
                ['person_id' => $inputs['person_id']],
                $inputs
            );

            if ($request->roles <> '') {
                $user->assignRole($request->roles);
            }

        });

        return redirect()->route('users.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = User::person()->with('roles')->findOrFail($id);

        return view('users.show', compact('item'));
    }

    public function edit($id)
    {
        $roles = Role::all();

        $item = User::person()->with('roles')->findOrFail($id);

        return view('users.edit', compact('item', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $item = User::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->id)
        )->validate();

        DB::transaction(function () use ($request, $item) {
            $inputs = $request->except('roles');

            $item->fill($inputs)->save();

            $people = Person::find($item->person_id);
            $people->fill($inputs)->save();

            if ($request->roles <> '') {
                $item->syncRoles($request->roles);
            }
        });

        return redirect()->route('users.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = User::findOrFail($id);

        if (auth()->id() != $item->id) {
            try {
                $item->delete();
                $item->people()->delete();
                return redirect()->route('users.index')
                    ->withStatus('Registro deletado com sucesso.');
            } catch (\Exception $e) {
                return redirect()->route('user.index')
                    ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
            }
        } else {
            return redirect()->route('users.index')
                ->withError('Você não tem permissão para excluir esse usuário.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:120'],
            'nif' => ['required', 'max:14'],
            'city_id' => ['required'],
            'email' => ['required', 'max:89'],
            'phone' => ['max:15'],
            'address' => ['required', 'max:120'],
            'roles' => ['required'],
        ];

        if (!empty($primaryKey)) {
            $rules['email'][] = Rule::unique('users')->ignore($primaryKey);
        } else {
            $rules['email'][] = Rule::unique('users');
        }

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
