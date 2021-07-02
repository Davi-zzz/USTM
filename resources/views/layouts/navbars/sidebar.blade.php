<div class="sidebar" data="green">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-normal" style="text-align:center;">
                <!-- <img src="" alt="Logo" width="500"> -->
                NOVA-API
            </a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug=='dashboard' ) class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>Início</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#register" aria-expanded="false" class="collapsed">
                    <i class="tim-icons icon-pencil"></i>
                    <span class="nav-link-text">Cadastros</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="register" style="">
                    <ul class="nav pl-4">
                        @can('cities_view')
                        <li>
                            <a href="{{ route('cities.index') }}">
                                <i class="fas fa-city"></i>
                                <p>Cidades</p>
                            </a>
                        </li>
                        @endcan
                        @can('states_view')
                        <li>
                            <a href="{{ route('states.index') }}">
                                <i class="fas fa-layer-group"></i>
                                <p>Estados</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#collapseGerenciamento" aria-expanded="false" class="collapsed">
                    <i class="fas fa-users-cog"></i>
                    <span class="nav-link-text">Gerenciamento</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="collapseGerenciamento" style="">
                    <ul class="nav pl-4">
                        @can('users_view')
                        <li>
                            <a href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i>
                                <p>Usuários</p>
                            </a>
                        </li>
                        @endcan
                        @can('roles_view')
                        <li>
                            <a href="{{ route('roles.index') }}">
                                <i class="fas fa-user-lock"></i>
                                <p>Atribuições</p>
                            </a>
                        </li>
                        @endcan
                        @can('permissions_view')
                        <li>
                            <a href="{{ route('permissions.index') }}">
                                <i class="fas fa-lock"></i>
                                <p>Permissões</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#configs" aria-expanded="false" class="collapsed">
                    <i class="fas fa-cogs"></i>
                    <span class="nav-link-text">Configurações</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="configs" style="">
                    <ul class="nav pl-4">
                        @can('parameters_view')
                        <li>
                            <a href="{{ route('parameters.index') }}">
                                <i class="fas fa-cog"></i>
                                <p>Parâmetros</p>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
