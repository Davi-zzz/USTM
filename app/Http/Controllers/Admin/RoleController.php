<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles_view', ['only' => ['show', 'index']]);
        $this->middleware('permission:roles_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $data =  Role::orderBy('description')->paginate(10);

        return view('roles.index', compact('data'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('description')->get();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        Validator::make(
            $request->all(),
            $this->rules($request)
        )->validate();


        $item = Role::create($request->except('permissions'));

        $item->permissions()->attach($request->permissions);

        return redirect()->route('roles.index')
            ->withStatus('Registro criado com sucesso.');
    }

    public function show($id)
    {
        $item = Role::with('permissions')->findOrFail($id);

        return view('roles.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Role::findOrFail($id);
        $permissions = Permission::orderBy('description')->get();

        return view('roles.edit', compact('item', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $item = Role::findOrFail($id);

        Validator::make(
            $request->all(),
            $this->rules($request, $item->getKey())
        )->validate();


        $item->fill($request->except('permissions'))->save();

        $item->permissions()->sync($request->permissions);

        return redirect()->route('roles.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Role::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('roles.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('roles.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:40'],
            'type_user' => ['required'],
            'description' => ['required', 'max:40'],
            'permissions' => ['required']
        ];

        if (empty($primaryKey)) {
            $rules['name'][] = Rule::unique('roles');
        } else {
            $rules['name'][] = Rule::unique('roles')->ignore($primaryKey);
        }

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
