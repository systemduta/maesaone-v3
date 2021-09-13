<div class="main-wrapper">
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">
				<ul>
					<li class="{{ Route::currentRouteName() ==  'AdminControllerGetIndex' ? 'active' : ''}}">
						<a  href="{{ route('AdminControllerGetIndex') }}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
					</li>	
					@if (file_exists(resource_path('views/mixtra/sidemenu.blade.php')))
            			@include('mixtra.sidemenu')
        			@endif
                    @if(\Mixtra\Helpers\MITBooster::isSuperadmin())
					<li class="menu-title"> 
						<span>SUPERADMIN</span>
					</li>
					<li class="submenu">
						<a href="#"><i class="fa fa-user-lock"></i> <span> Priviledge</span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
						<ul style="display: none;">
							<li><a class="{{ Route::currentRouteName() ==  'RoleControllerGetIndex' ? 'active' : ''}}" href="{{ route('RoleControllerGetIndex') }}">Roles</a></li>
							<li><a class="{{ Route::currentRouteName() ==  'UserControllerGetIndex' ? 'active' : ''}}" href="{{ route('UserControllerGetIndex') }}">User List</a></li>
						</ul>
					</li>
					<li class="{{ Route::currentRouteName() ==  'MenuControllerGetIndex' ? 'active' : ''}}">
						<a  href="{{ route('MenuControllerGetIndex') }}"><i class="fa fa-layer-group"></i> <span>Menu</span></a>
					</li>	
					<li class="{{ Route::currentRouteName() ==  'SettingControllerGetIndex' ? 'active' : ''}}">
						<a  href="{{ route('SettingControllerGetShow') }}"><i class="fa fa-sliders-h"></i> <span>Settings</span></a>
					</li>	
					<li class="{{ Route::currentRouteName() ==  'LogControllerGetIndex' ? 'active' : ''}}">
						<a  href="{{ route('LogControllerGetIndex') }}"><i class="fa fa-history"></i> <span>Logs</span></a>
					</li>
                    @endif
				</ul>
			</div>
		</div>
	</div>
</div>


			
