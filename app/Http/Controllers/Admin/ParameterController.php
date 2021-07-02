<?php

namespace App\Http\Controllers\Admin;

use App\Parameter;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public function index(Request $request)
    {
        $data =  Parameter::orderBy('name')->paginate(10);

        return view('parameters.index', compact('data'));
    }

    public function create()
    {
        return view('parameters.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'name' => 'required|max:6|unique:parameters',
                'type' => 'required',
                'value' => 'required',
                'description' => 'required|max:100'
            ]
        );

        Parameter::create($request->all());

        return redirect()->route('parameters.index')
            ->withStatus('Registro adicionado com sucesso.');
    }

    public function show($id)
    {
        $item = Parameter::findOrFail($id);

        return view('parameters.show', compact('item'));
    }

    public function edit($id)
    {
        $item = Parameter::findOrFail($id);

        return view('parameters.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Parameter::findOrFail($id);

        $this->validate(
            $request,
            [
                'name' => 'required|max:6|unique:parameters,name,'. $item->id,
                'type' => 'required',
                'value' => 'required',
                'description' => 'required|max:100'
            ]
        );



        $inputs = $request->all();

        $item->fill($inputs)->save();

        return redirect()->route('parameters.index')
            ->withStatus('Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $item = Parameter::findOrFail($id);

        try {
            $item->delete();
            return redirect()->route('parameters.index')
                ->withStatus('Registro deletado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('parameters.index')
                ->withError('Registro vinculado á outra tabela, somente poderá ser excluído se retirar o vinculo.');
        }
    }

}
