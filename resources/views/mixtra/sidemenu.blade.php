<li class="submenu">
    <a href="#"><i class="la la-users"></i> <span> Administration</span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Route::currentRouteName() ==  'CompanyControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('CompanyControllerGetIndex') }}"><span>Company</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'DepartmentControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('DepartmentControllerGetIndex') }}"><span>Departments</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'TitleControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('TitleControllerGetIndex') }}"><span>Job Positions</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'HolidayControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('HolidayControllerGetIndex') }}"><span>Holidays</span></a></li>
        <!-- <li><a class="{{ Request::is('holidays') ? 'active' : '' }}" href="{{ url('holidays') }}">Holidays</a></li>  -->
    </ul>
</li>
<li class="submenu">
    <a href="#"><i class="la la-user"></i> <span> Employees</span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Route::currentRouteName() ==  'EmployeeControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('EmployeeControllerGetIndex') }}"><span>All Employees</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'LeaveControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('LeaveControllerGetIndex') }}"><span>Leaves</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'AttendanceControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('AttendanceControllerGetIndex') }}"><span>Attendance</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'OvertimeControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('OvertimeControllerGetIndex') }}"><span>Overtime</span></a></li>
        <!-- <li><a class="{{ Request::is('leaves-employee') ? 'active' : '' }}" href="{{ url('leaves-employee') }}">Leaves</a></li>	 -->
        <!-- <li><a class="{{ Request::is('attendance-employee') ? 'active' : '' }}" href="{{ url('attendance-employee') }}">Attendance</a></li>	 -->
        <!-- <li><a class="{{ Request::is('overtime') ? 'active' : '' }}" href="{{ url('overtime') }}">Overtime</a></li>								         -->
    </ul>
</li>
<li class="submenu">
    <a href="#"><i class="la la-money"></i> <span> Payroll</span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Request::is('salary') ? 'active' : '' }}" href="{{ url('salary') }}">Employee Salary</a></li>
        <li><a class="{{ Request::is('salary-view') ? 'active' : '' }}" href="{{ url('salary-view') }}">Payslip</a></li>
        <li><a class="{{ Request::is('payroll-items') ? 'active' : '' }}" href="{{ url('payroll-items') }}">Payroll Items</a></li>
    </ul>
</li>
<li class="menu-title">
    <span>Perfomance</span>
</li>
<li class="submenu">
    <a href="#"><i class="la la-graduation-cap"></i> <span> Performance </span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Route::currentRouteName() ==  'CriticalPerformanceFactorControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('CriticalPerformanceFactorControllerGetIndex') }}"><span>Critical Performance Factor (CPF)</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'JobDescriptionControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('JobDescriptionControllerGetIndex') }}"><span>Job Description</span></a></li>
        <li><a class="{{ Route::currentRouteName() ==  'PerformanceUmumControllerGetIndex' ? 'active' : ''}}" href="{{ MITBooster::sidebarUrl('PerformanceUmumControllerGetIndex') }}"><span>Performance Umum</span></a></li>
        <!-- <li><a class="{{ Request::is('performance-indicator') ? 'active' : '' }}" href="{{ url('performance-indicator') }}"> Performance Indicator  </a></li>
        <li><a class="{{ Request::is('performance') ? 'active' : '' }}" href="{{ url('performance') }}"> Performance Review  </a></li>    
        <li><a class="{{ Request::is('performance-appraisal') ? 'active' : '' }}" href="{{ url('performance-appraisal') }}"> Performance Appraisal  </a></li> -->
    </ul>
</li>
<li class="submenu">
    <a href="#"><i class="la la-crosshairs"></i> <span> Goals </span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Request::is('goal-tracking') ? 'active' : '' }}" href="{{ url('goal-tracking') }}"> Goal List  </a></li>
        <li><a class="{{ Request::is('goal-type') ? 'active' : '' }}" href="{{ url('goal-type') }}"> Goal Type  </a></li>
    </ul>
</li>
<li class="submenu">
    <a href="#"><i class="la la-edit"></i> <span> Training </span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
    <ul style="display: none;">
        <li><a class="{{ Request::is('training') ? 'active' : '' }}" href="{{ url('training') }}"> Training List  </a></li>
        <li><a class="{{ Request::is('trainers') ? 'active' : '' }}" href="{{ url('trainers') }}"> Trainers  </a></li>
        <li><a class="{{ Request::is('training-type') ? 'active' : '' }}" href="{{ url('training-type') }}"> Training Type  </a></li>
    </ul>
</li>
