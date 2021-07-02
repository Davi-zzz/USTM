<div class="row">
    <div class="col-md-6">
        {!!Form::text('name', 'Nome')
        ->required()
        ->attrs(['maxlength' => 20])!!}
    </div>
    <div class="col-md-6">
    {!!Form::text('value', 'Valor')
        ->required(true)
        ->attrs(['maxlength' => 20])!!}
    </div>
    <div class="col-md-4">
        {!!Form::select('type', 'Tipo', [null => 'Selecione...']+ \App\Parameter::opTypes())
        ->required()
        !!}
    </div>
    <div class="col-md-8">
        {!!Form::text('description', 'Descrição')
        ->required(false)
        ->attrs(['maxlength' => 20])!!}
    </div>
</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>
