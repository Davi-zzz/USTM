<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Parameter;
use Illuminate\Validation\Rule;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParameterController extends BaseController
{
    public function index(Request $request)
    {
        $query = Parameter::distinct()
            ->when($request->has('name'), function ($query) use($request) {
                return $query->where('name', $request->name);
            });

        ($request->has('page'))  ? $data = $query->paginate(10) : $data = $query->get();

        return $this->sendResponse($data, '');

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules($request));

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }

        $inputs = $request->all();

        $data = Parameter::create($inputs);

        return $this->sendResponse($data, "Registro criado com sucesso", 201);

    }


    public function show($id)
    {
        $item = Parameter::findOrFail($id);

        return $this->sendResponse($item, '');

    }


    public function update(Request $request, $id)
    {
        $item = Parameter::findOrFail($id);

        $validator = Validator::make($request->all(), $this->rules($request, $id));

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }

        $inputs = $request->all();
        $item->fill($inputs)->save();

        return $this->sendResponse($item, "Registro atualizado com sucesso", 200);
    }

    public function destroy($id)
    {
        $item = Parameter::findOrFail($id);

        try {
            $item->delete();
            return $this->sendResponse([], 'Registro deletado com sucesso.');
        } catch (Exception $e) {
            return $this->sendError('Você não tem permissão para excluir esse registro.', [], 403);
        }
    }

    private function rules(Request $request, $primaryKey = null, bool $changeMessages = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'type' => ['required', 'integer', Rule::in(array_keys(Parameter::opTypes()))],
            'value' => ['required', 'integer'],
            'description' => ['nullable', 'string'],
        ];

        $messages = [];

        return !$changeMessages ? $rules : $messages;
    }
}
