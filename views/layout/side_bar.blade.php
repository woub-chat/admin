<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-inner" @live(
        'sidebar')>
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-1 pb-1 mb-3 d-flex">
            <a class="image" href="{{route('admin.profile')}}">
                <img src="{{asset(admin_user()->avatar)}}" class="img-circle elevation-2" alt="{{admin_user()->name}}">
            </a>
            <div class="info">
                <div class="d-block text-light">
                    <span class="d-inline">{{admin_user()->name}}</span> &nbsp;
                    {{--                        <a href="{{route('admin.profile')}}" title="{{__('admin.edit_profile')}}">--}}
                    {{--                            <i class="fas fa-edit" style="font-size: 12px;"></i>--}}
                    {{--                        </a>--}}
                    <small class="d-inline text-light">
                            <span class="badge badge-success">
                                {!! Admin::user()->roles->pluck('name')->implode('</span>&nbsp;<span class="badge badge-success">') !!}
                            </span>
                    </small>
                </div>
                <small class="d-block text-light">
                    {{ admin_user()->email }}
                </small>
                {{--                    <small class="d-block text-light">--}}
                {{--                        <span--}}
                {{--                            class="badge badge-success">{!! Admin::user()->roles->pluck('name')->implode('</span>&nbsp;<span class="badge badge-success">') !!}</span>--}}
                {{--                    </small>--}}
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-sidebar nav-child-indent flex-column" data-widget="treeview" role="menu"
                data-accordion="true">
                @include('admin::layout.side_bar_items')
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    </div>
    <!-- /.sidebar -->
    <div class="sidebar-custom navbar navbar-expand-lg justify-content-center">
        <div class="row" @live(
        'sidebar-custom')>
        {{--            <div class="col-auto">--}}
        {{--                <a href="{{ route('admin.settings') }}" class="btn btn-link" data-turbolinks="false" title="Setting">--}}
        {{--                    <i class="fas fa-cog"></i>--}}
        {{--                </a>--}}
        {{--            </div>--}}

        <div class="col-4">
            <a href="{{route('admin.profile')}}" class="btn btn-link" data-turbolinks="false" title="Profile">
                <i class="fas fa-user-cog"></i>
            </a>
        </div>

        <div class="col-4">
            <a class="btn btn-link" href="javascript:void(0)" role="button" data-turbolinks="false"
               data-click="jax.lte_admin.toggle_dark" title="{{ admin_repo()->isDarkMode ? 'Light' : 'Dark' }} mode">
                @if(admin_repo()->isDarkMode)
                    <i class="fas fa-sun"></i>
                @else
                    <i class="fas fa-adjust"></i>
                @endif
            </a>
        </div>

        <div class="col-4">
            <a class="btn btn-link" href="javascript:void(0)" data-click="alert::confirm" data-turbolinks="false"
               data-params="{{__('admin.logout')}}, {{admin()->name}}? && {{route('admin.profile.logout')}} >> $jax.get"
               title="{{__('admin.logout')}}"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
    </div>
</aside>
