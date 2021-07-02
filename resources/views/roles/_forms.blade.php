<div class="row">
    <div class="col-md-4">
        {!!Form::text('name', 'Nome')
        ->required()
        ->attrs(['maxlength' => 15])!!}
    </div>
    <div class="col-md-5">
        {!!Form::text('description', 'Descrição')
        ->required()
        ->attrs(['maxlength' => 40])!!}
    </div>
    <div class="col-md-3">
        {!!Form::select('type_user', 'Tipo', [null => 'Selecione...'] + \App\Role::types())
        ->required()
        !!}
    </div>
    <div class="col-md-12">
        {!!Form::select('permissions[]', 'Permissões', $permissions->pluck('description', 'id')->all())
        ->attrs(['class' => 'multi-select'])
        ->multiple()
        ->value(isset($item) ? $item->permissions->pluck('id')->all() : [])
        ->required()!!}
    </div>

</div>
<div class="row">
    <div class="col-12">
        <button type="submit" class="btn btn-success float-right mt-4">Salvar</button>
    </div>
</div>
