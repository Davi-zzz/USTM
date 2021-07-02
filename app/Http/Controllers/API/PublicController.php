<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Validator;
use App\Expedition;
use App\Pointing;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

class PublicController extends BaseController
{
    public function expeditionShow($id)
    {
        $data = Expedition::with(['activity' => function($query) {
            $query->orderByRaw('day(date) asc');
        }])->findOrFail($id);

        return $this->sendResponse($data, '');

    }

    public function pointingStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_finished' => 'required',
            'date' => 'required',
            'note' => 'required',
            'activity_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }

        $inputs = $request->all();

        $item = Pointing::create($inputs);

    }

    public function pointingUpdate(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'is_finished' => 'required',
            'date' => 'required',
            'note' => 'required',
            'activity_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Erro de validação', $validator->errors()->all(), 422);
        }

        $item = Pointing::findOrFail($id);

        $inputs = $request->all();
        $item->fill($inputs)->save();

    }
}
