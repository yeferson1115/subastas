
<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">
              <div class="container-xxl d-flex h-100">
                <ul class="menu-inner">
                  <!-- Dashboards -->
                  <li class="menu-item">
                    <a href="/dashboard" class="menu-link">
                      <i class="menu-icon icon-base ti tabler-smart-home"></i>
                      <div data-i18n="Inicio">Inicio</div>
                    </a>
                  </li>


                   
                  @can('Administracion')
                  <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                      <i class="menu-icon fa-solid fa-sliders"></i>
                      <div data-i18n="Administración">Administración</div>
                    </a>

                    <ul class="menu-sub">
                       @can('Ver Usuarios')
                      <li class="menu-item">
                        <a href="/users" class="menu-link">
                          <i class="menu-icon fa-solid fa-users"></i>
                          <div data-i18n="Usuarios">Usuarios</div>
                        </a>
                      </li>
                      @endcan
                      @can('Ver Subastadores')
                      <li class="menu-item">
                        <a href="{{ route('admin.auctioneer-clients.index') }}" class="menu-link">
                          <i class="menu-icon fa-solid fa-gavel"></i>
                          <div data-i18n="Clientes subastadores">Clientes subastadores</div>
                        </a>
                      </li>
                      @endcan                    



                      @can('Editar Permisos')
                      <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                          <i class="menu-icon fa-solid fa-user-shield"></i>
                          <div data-i18n="Permisos">Permisos</div>
                        </a>
                        <ul class="menu-sub">
                          <input type="hidden" value="{{$roles = Spatie\Permission\Models\Role::get()}}">
                          @foreach ($roles as $role)
                          <li class="menu-item">
                            <a href="/roles/{{ $role->id }}/permissions/edit" class="menu-link">
                              <div data-i18n="{{ $role->name }}">{{ $role->name }}</div>
                            </a>
                          </li>
                          @endforeach

                        </ul>
                      </li>
                      @endcan



                    </ul>
                  </li>
                  @endcan

                  @can('Gestionar Configuracion')
                  <!-- Layouts -->
                  <li class="menu-item">
                    <a href="javascript:void(0)" class="menu-link menu-toggle">
                      <i class="menu-icon fa-solid fa-gears"></i>
                      <div data-i18n="Configuración">Configuración</div>
                    </a>

                    <ul class="menu-sub">
                      @can('Ver planes')
                      <li class="menu-item">
                        <a href="{{ route('admin.plans.index') }}" class="menu-link">
                          <i class="menu-icon fa-solid fa-tags"></i>
                          <div data-i18n="Planes">Planes</div>
                        </a>
                      </li>
                      @endcan

                      @can('Ver Categorias')
                      <li class="menu-item">
                        <a href="{{ route('categories.index') }}" class="menu-link">
                          <i class="menu-icon fa-solid fa-layer-group"></i>
                          <div data-i18n="Categorías">Categorías</div>
                        </a>
                      </li>
                      @endcan
                      @can('Ver SubCategorias')
                      <li class="menu-item">
                        <a href="{{ route('subcategories.index') }}" class="menu-link">
                          <i class="menu-icon fa-solid fa-sitemap"></i>
                          <div data-i18n="Subcategorías">Subcategorías</div>
                        </a>
                      </li>
                    @endcan
                    </ul>
                  </li>
                  @endcan
                  @can('Ver Productos')
                  <li class="menu-item">
                    <a href="{{ route('auction-products.index') }}" class="menu-link">
                      <i class="menu-icon fa-solid fa-box-open"></i>
                      <div data-i18n="Productos a subastar">Productos a subastar</div>
                    </a>
                  </li>
                  <li class="menu-item">
                    <a href="{{ route('admin.auctions.index') }}" class="menu-link">
                      <i class="menu-icon fa-solid fa-gavel"></i>
                      <div data-i18n="Subastas">Subastas</div>
                    </a>
                  </li>
                  @endcan
                </ul>
              </div>
            </aside>


