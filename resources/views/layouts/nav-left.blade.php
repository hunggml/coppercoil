<aside class="main-sidebar sidebar-light-Success elevation-4">
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('dist/img/sti.png') }}" alt="AdminLTE Logo" class="brand-image  " style="opacity: .8">
        <span class="brand-text font-weight-light">STI-MES</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-4 pb-4 mb-4 d-flex">
            <div class="image">
                <img src="{{ asset('dist/img/') }}/{{ Auth::user()->avatar }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block id-user"
                    style="text-transform: capitalize; font-weight: bold;">{{ Auth::user()->name }}</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item has-treeview">
                    <a href="{{ route('home') }}" class="nav-link home">
                        <i class="nav-icon fas fa-home"></i>
                        <p>
                            {{ __('Dashboard') }}
                            <i class="right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link warehouse-system">
                        <i class="nav-icon fas fa-pallet"></i>
                        <p>
                            {{ __('Warehouse System') }}
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item has-treeview">
                            <a href="{{ route('warehousesystem.import.print_label') }}" class="nav-link print_label">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Print') }} {{ __('Label') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="{{ route('warehousesystem.update_location') }}" class="nav-link Update-Location">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Update') }} {{ __('Location') }}</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link import || Transfer || retype">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Import') }} {{ __('Warehouse') }}</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item has-treeview">
                                    <a href="{{ route('warehousesystem.import') }}" class="nav-link import">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Import') }} {{ __('Of') }} Packing List</p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{ route('warehousesystem.transfer') }}" class="nav-link Transfer">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Transfer') }} {{ __('Warehouse') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{ route('warehousesystem.retype') }}" class="nav-link retype">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Retype') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('warehousesystem.export') }}" class="nav-link export">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Export') }} {{ __('Warehouse') }}</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('warehousesystem.productivity') }}" class="nav-link productivity">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Report') }} {{ __('Productivity') }}</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('warehousesystem.inventory') }}" class="nav-link inventory">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Inventory') }}</p>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a href="#" class="nav-link Stock">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Stock') }}</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item has-treeview">
                                    <a href="{{ route('warehousesystem.import.location', ['Format' => 1]) }}"
                                        class="nav-link import">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Stock') }} {{ __('Save') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{ route('warehousesystem.import.location', ['Format' => 2]) }}"
                                        class="nav-link Transfer">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Stock') }} {{ __('Machine') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item ">
                                    <a href="{{ route('warehousesystem.import.location', ['Format' => 3]) }}"
                                        class="nav-link retype">
                                        <i class="far fa-dot-circle nav-icon"></i>
                                        <p>{{ __('Stock') }} {{ __('NG') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item ">
                            <a href="{{ route('warehousesystem.report') }}" class="nav-link report">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Import-Export-Inventory') }}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if (Auth::user()->level == 9999 || Auth::user()->checkRole('view_master'))
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link setting ">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                {{ __('Setting') }}
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('masterData.unit') }}" class="nav-link setting-unit">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Unit') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.supplier') }}" class="nav-link setting-supplier">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Supplier') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.product') }}" class="nav-link setting-product">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Product') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.groupMaterials') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Group Materials') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.materials') }}" class="nav-link setting-materials">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Materials') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.machine') }}" class="nav-link setting-machine">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Machine') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('masterData.error') }}" class="nav-link setting-materials">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Error') }} NG</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
              <a href="#" class="nav-link setting-materials">
                <i class="far fa-circle nav-icon"></i>
                <p>{{ __('Handle') }} NG</p>
              </a>
            </li> -->
                            <li class="nav-item">
                                <a href="{{ route('masterData.warehouses') }}" class="nav-link setting-warehouse">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Setting') }} {{ __('Warehouse') }}</p>
                                </a>
                            </li>
                @endif
                @if (Auth::user()->level == 9999 ||
                    Auth::user()->checkRole('create_master') ||
                    Auth::user()->checkRole('update_master'))
                @endif
            </ul>
            </li>
            @if (Auth::user()->level == 9999)
                <li class="nav-item has-treeview">
                    <a href="{{ route('account') }}" class="nav-link setting-account">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            {{ __('Account') }}
                        </p>
                    </a>
                </li>
            @endif
            </ul>
        </nav>
    </div>
</aside>
