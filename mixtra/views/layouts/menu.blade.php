<div class="main-wrapper">
	<div class="sidebar" id="sidebar">
		<div class="sidebar-inner slimscroll">
			<div id="sidebar-menu" class="sidebar-menu">
				<ul>
					<li class="menu-title"> 
						<span>Main</span>
					</li>
					<li class="submenu">
						<a href="#"><i class="la la-dashboard"></i> <span> Dashboard</span> <span class="menu-arrow"></span></a>
						<ul style="display: none;">
							<li><a  class="{{ Request::is('index') ? 'active' : '' }}" href="{{ url('index') }}">Admin Dashboard</a></li>
							<li><a class="{{ Request::is('employee-dashboard') ? 'active' : '' }}" href="{{ url('employee-dashboard') }}">Employee Dashboard</a></li>
						</ul>
					</li>
					<li class="submenu">
						<a href="#"><i class="la la-cube"></i> <span> Apps</span> <span class="menu-arrow"></span></a>
						<ul style="display: none;">
							<li class="{{ Request::is('chat') ? 'active' : '' }}"><a  href="{{ url('chat') }}">Chat</a></li>
							<li class="submenu">
								<a href="#"><span> Calls</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li class="{{ Request::is('voice-call') ? 'active' : '' }}"><a  href="{{ url('voice-call') }}">Voice Call</a></li>
									<li class="{{ Request::is('video-call') ? 'active' : '' }}"><a  href="{{ url('video-call') }}">Video Call</a></li>
									<li class="{{ Request::is('outgoing-call') ? 'active' : '' }}"><a  href="{{ url('outgoing-call') }}">Outgoing Call</a></li>
									<li class="{{ Request::is('incoming-call') ? 'active' : '' }}"><a  href="{{ url('incoming-call') }}">Incoming Call</a></li>
								</ul>
							</li>
							<li><a class="{{ Request::is('events') ? 'active' : '' }}" href="{{ url('events') }}">Calendar</a></li>
							<li><a class="{{ Request::is('contacts') ? 'active' : '' }}" href="{{ url('contacts') }}">Contacts</a></li>
							<li><a class="{{ Request::is('inbox') ? 'active' : '' }}" href="{{ url('inbox') }}">Email</a></li>						
							<li><a class="{{ Request::is('file-manager') ? 'active' : '' }}"  href="{{ url('file-manager') }}">File Manager</a></li>								
						</ul>
					</li>
					<li class="menu-title"> <span>Employees</span></li>
							<li class="submenu">
								<a href="#" class="noti-dot"><i class="la la-user"></i> <span> Employees</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('employees') ? 'active' : '' }}" href="{{ url('employees') }}">All Employees</a></li>			

		<li>
        <a class="{{ Request::is('holidays') ? 'active' : '' }}" href="{{ url('holidays') }}">Holidays</a></li>	
									
		<li>
        <a class="{{ Request::is('leaves') ? 'active' : '' }}" href="{{ url('leaves') }}">Leaves (Admin) <span class="badge badge-pill bg-primary float-right">1</span></a></li>								
								
		<li>
        <a class="{{ Request::is('leaves-employee') ? 'active' : '' }}" href="{{ url('leaves-employee') }}">Leaves (Employee)</a></li>	

		<li>
        <a class="{{ Request::is('leave-settings') ? 'active' : '' }}" href="{{ url('leave-settings') }}">Leave Settings</a></li>	

		<li>
        <a class="{{ Request::is('attendance') ? 'active' : '' }}" href="{{ url('attendance') }}">Attendance (Admin)</a></li>	

		<li>
        <a class="{{ Request::is('attendance-employee') ? 'active' : '' }}" href="{{ url('attendance-employee') }}">Attendance (Employee)</a></li>	
									
		<li>
        <a class="{{ Request::is('departments') ? 'active' : '' }}" href="{{ url('departments') }}">Departments</a></li>								
									
		<li>
        <a class="{{ Request::is('designations') ? 'active' : '' }}" href="{{ url('designations') }}">Designations</a></li>								
									
        <li>
        <a class="{{ Request::is('timesheet') ? 'active' : '' }}" href="{{ url('timesheet') }}">Timesheet</a></li>	

        <li><a class="{{ Request::is('shift-scheduling','shift-list') ? 'active' : '' }}" href="{{ url('shift-scheduling') }}">Shift & Schedule</a></li>
			
		<li>
        <a class="{{ Request::is('overtime') ? 'active' : '' }}" href="{{ url('overtime') }}">Overtime</a></li>								
									
								</ul>
							</li>

							<li class="{{ Request::is('clients') ? 'active' : '' }}">
        <a  href="{{ url('clients') }}"><i class="la la-users"></i> <span>Clients</span></a></li>	

							
							<li class="submenu">
								<a href="#"><i class="la la-rocket"></i> <span> Projects</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
				
								<li>
        <a class="{{ Request::is('projects') ? 'active' : '' }}" href="{{ url('projects') }}">Projects</a></li>	

		<li>
        <a class="{{ Request::is('tasks') ? 'active' : '' }}" href="{{ url('tasks') }}">Tasks</a></li>	
									
		<li>
        <a class="{{ Request::is('task-board') ? 'active' : '' }}" href="{{ url('task-board') }}">Task Board</a></li>							
									
								
								</ul>
							</li>

							<li class="{{ Request::is('leads') ? 'active' : '' }}">
        <a  href="{{ url('leads') }}"><i class="la la-user-secret"></i><span>Leads</span></a></li>		

							
		<li class="{{ Request::is('tickets') ? 'active' : '' }}">
        <a  href="{{ url('tickets') }}"><i class="la la-ticket"></i><span>Tickets</span></a></li>	

							
							<li class="menu-title"> 
								<span>HR</span>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-files-o"></i> <span> Sales </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('estimates') ? 'active' : '' }}" href="{{ url('estimates') }}">Estimates</a></li>			

		<li>
        <a class="{{ Request::is('invoices') ? 'active' : '' }}" href="{{ url('invoices') }}">Invoices</a></li>	
									
		<li>
        <a class="{{ Request::is('payments') ? 'active' : '' }}" href="{{ url('payments') }}">Payments</a></li>	

        <li>
        <a class="{{ Request::is('expenses') ? 'active' : '' }}" href="{{ url('expenses') }}">Expenses</a></li>	
									
		<li>
        <a class="{{ Request::is('provident-fund') ? 'active' : '' }}" href="{{ url('provident-fund') }}">Provident Fund</a></li>	

		<li>
        <a class="{{ Request::is('taxes') ? 'active' : '' }}" href="{{ url('taxes') }}">Taxes</a></li>	

									
									
								</ul>
							</li>


                            <li class="submenu">
								<a href="#"><i class="la la-files-o"></i> <span> Accounting </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li><a class="{{ Request::is('categories','sub-category') ? 'active' : '' }}" href="{{ url('categories') }}">Categories</a></li>
									<li><a class="{{ Request::is('budgets') ? 'active' : '' }}" href="{{ url('budgets') }}">Budgets</a></li>
									<li><a class="{{ Request::is('budget-expenses') ? 'active' : '' }}" href="{{ url('budget-expenses') }}">Budget Expenses</a></li>
									<li><a class="{{ Request::is('budget-revenues') ? 'active' : '' }}" href="{{ url('budget-revenues') }}">Budget Revenues</a></li>
								</ul>
							</li>






							<li class="submenu">
								<a href="#"><i class="la la-money"></i> <span> Payroll </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('salary') ? 'active' : '' }}" href="{{ url('salary') }}">Employee Salary</a></li>	


		<li>
        <a class="{{ Request::is('salary-view') ? 'active' : '' }}" href="{{ url('salary-view') }}">Payslip</a></li>	
									
		<li>
        <a class="{{ Request::is('payroll-items') ? 'active' : '' }}" href="{{ url('payroll-items') }}">Payroll Items</a></li>	

								
								</ul>
							</li>


							<li class="{{ Request::is('policies') ? 'active' : '' }}">
        <a  href="{{ url('policies') }}"><i class="la la-file-pdf-o"></i><span>Policies</span></a></li>	

							
							<li class="submenu">
								<a href="#"><i class="la la-pie-chart"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">


								<li>
        <a class="{{ Request::is('expense-reports') ? 'active' : '' }}" href="{{ url('expense-reports') }}">Expense Report</a></li>


		<li>
        <a class="{{ Request::is('invoice-reports') ? 'active' : '' }}" href="{{ url('invoice-reports') }}"> Invoice Report </a></li>
        <li><a class="{{ Request::is('payments-reports') ? 'active' : '' }}" href="{{ url('payments-reports') }}"> Payments Report </a></li>
									<li><a class="{{ Request::is('project-reports') ? 'active' : '' }}" href="{{ url('project-reports') }}"> Project Report </a></li>
									<li><a class="{{ Request::is('task-reports') ? 'active' : '' }}" href="{{ url('task-reports') }}"> Task Report </a></li>
									<li><a class="{{ Request::is('user-reports') ? 'active' : '' }}" href="{{ url('user-reports') }}"> User Report </a></li>
									<li><a class="{{ Request::is('employee-reports') ? 'active' : '' }}" href="{{ url('employee-reports') }}"> Employee Report </a></li>
									<li><a class="{{ Request::is('payslip-reports') ? 'active' : '' }}" href="{{ url('payslip-reports') }}"> Payslip Report </a></li>
									<li><a class="{{ Request::is('attendance-reports') ? 'active' : '' }}" href="{{ url('attendance-reports') }}"> Attendance Report </a></li>
									<li><a class="{{ Request::is('leave-reports') ? 'active' : '' }}" href="{{ url('leave-reports') }}"> Leave Report </a></li>
									<li><a class="{{ Request::is('daily-reports') ? 'active' : '' }}" href="{{ url('daily-reports') }}"> Daily Report </a></li>

								
								
								</ul>
							</li>
							<li class="menu-title"> 
								<span>Performance</span>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-graduation-cap"></i> <span> Performance </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('performance-indicator') ? 'active' : '' }}" href="{{ url('performance-indicator') }}"> Performance Indicator  </a></li>

		<li>
        <a class="{{ Request::is('performance') ? 'active' : '' }}" href="{{ url('performance') }}"> Performance Review  </a></li>
								
		<li>
        <a class="{{ Request::is('performance-appraisal') ? 'active' : '' }}" href="{{ url('performance-appraisal') }}"> Performance Appraisal  </a></li>

					
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-crosshairs"></i> <span> Goals </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('goal-tracking') ? 'active' : '' }}" href="{{ url('goal-tracking') }}"> Goal List  </a></li>

		<li>
        <a class="{{ Request::is('goal-type') ? 'active' : '' }}" href="{{ url('goal-type') }}"> Goal Type  </a></li>

									
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-edit"></i> <span> Training </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('training') ? 'active' : '' }}" href="{{ url('training') }}"> Training List  </a></li>

		<li>
        <a class="{{ Request::is('trainers') ? 'active' : '' }}" href="{{ url('trainers') }}"> Trainers  </a></li>

		<li>
        <a class="{{ Request::is('training-type') ? 'active' : '' }}" href="{{ url('training-type') }}"> Training Type  </a></li>

		
								</ul>
							</li>

							<li class="{{ Request::is('promotion') ? 'active' : '' }}">
        <a  href="{{ url('promotion') }}"><i class="la la-bullhorn"></i> <span>Promotion</span>  </a></li>

		<li class="{{ Request::is('resignation') ? 'active' : '' }}">
        <a  href="{{ url('resignation') }}"><i class="la la-external-link-square"></i> <span>Resignation</span>  </a></li>
						
        <li class="{{ Request::is('termination') ? 'active' : '' }}">
        <a  href="{{ url('termination') }}"><i class="la la-external-link-square"></i> <span>Termination</span>  </a></li>

							
							<li class="menu-title"> 
								<span>Administration</span>
							</li>

							<li class="{{ Request::is('assets') ? 'active' : '' }}">
        <a  href="{{ url('assets') }}"><i class="la la-object-ungroup"></i><span>Assets</span>  </a></li>

							
							<li class="submenu">
								<a href="#"><i class="la la-briefcase"></i> <span> Jobs </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

                                     <li><a class="{{ Request::is('user-dashboard','user-all-jobs','saved-jobs','applied-jobs','interviewing','offered-jobs','visited-jobs','archived-jobs') ? 'active' : '' }}" href="{{ url('user-dashboard') }}"> User Dasboard </a></li>
									<li><a class="{{ Request::is('jobs-dashboard') ? 'active' : '' }}" href="{{ url('jobs-dashboard') }}"> Jobs Dasboard </a></li>
									<li><a class="{{ Request::is('jobs') ? 'active' : '' }}" href="{{ url('jobs') }}"> Manage Jobs </a></li>
									<li><a class="{{ Request::is('manage-resumes') ? 'active' : '' }}" href="{{ url('manage-resumes') }}"> Manage Resumes </a></li>
									<li><a class="{{ Request::is('shortlist-candidates') ? 'active' : '' }}" href="{{ url('shortlist-candidates') }}"> Shortlist Candidates </a></li>
									<li><a class="{{ Request::is('interview-questions') ? 'active' : '' }}" href="{{ url('interview-questions') }}"> Interview Questions </a></li>
									<li><a class="{{ Request::is('offer-approvals') ? 'active' : '' }}" href="{{ url('offer-approvals') }}"> Offer Approvals </a></li>
									<li><a class="{{ Request::is('experience-level') ? 'active' : '' }}" href="{{ url('experience-level') }}"> Experience Level </a></li>
									<li><a class="{{ Request::is('candidates') ? 'active' : '' }}" href="{{ url('candidates') }}"> Candidates List </a></li>
									<li><a class="{{ Request::is('schedule-timing') ? 'active' : '' }}" href="{{ url('schedule-timing') }}"> Schedule timing </a></li>
									<li><a class="{{ Request::is('apptitude-result') ? 'active' : '' }}" href="{{ url('apptitude-result') }}"> Aptitude Results </a></li>
									<li>
        <a class="{{ Request::is('jobs-applicants') ? 'active' : '' }}" href="{{ url('jobs-applicants') }}"> Applied Candidates  </a></li>
							
									
								</ul>
							</li>

							<li class="{{ Request::is('knowledgebase') ? 'active' : '' }}">
        <a  href="{{ url('knowledgebase') }}"> <i class="la la-question"></i><span>Knowledgebase</span>  </a></li>

        <li class="{{ Request::is('activities') ? 'active' : '' }}">
        <a  href="{{ url('activities') }}"><i class="la la-bell"></i><span>Activities</span>  </a></li>

		<li class="{{ Request::is('users') ? 'active' : '' }}">
        <a  href="{{ url('users') }}"><i class="la la-user-plus"></i><span>Users</span>  </a></li>
						
		<li class="{{ Request::is('settings') ? 'active' : '' }}">
        <a  href="{{ url('settings') }}"><i class="la la-cog"></i><span>Settings</span>  </a></li>

							
							<li class="menu-title"> 
								<span>Pages</span>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-user"></i> <span> Profile </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">


								<li>
        <a class="{{ Request::is('profile') ? 'active' : '' }}" href="{{ url('profile') }}"> Employee Profile  </a></li>

		<li>
        <a class="{{ Request::is('client-profile') ? 'active' : '' }}" href="{{ url('client-profile') }}"> Client Profile  </a></li>

								
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-key"></i> <span> Authentication </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li class="{{ Request::is('login') ? 'active' : '' }}">
        <a  href="{{ url('login') }}"> Login </a></li>

		<li class="{{ Request::is('register') ? 'active' : '' }}">
        <a  href="{{ url('register') }}"> Register </a></li>
								
		<li class="{{ Request::is('forgot-password') ? 'active' : '' }}">
        <a  href="{{ url('forgot-password') }}"> Forgot Password </a></li>

		<li class="{{ Request::is('otp') ? 'active' : '' }}">
        <a  href="{{ url('otp') }}"> OTP </a></li>

		<li class="{{ Request::is('lock-screen') ? 'active' : '' }}">
        <a  href="{{ url('lock-screen') }}"> Lock Screen </a></li>							
								
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-exclamation-triangle"></i> <span> Error Pages </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li class="{{ Request::is('error-404') ? 'active' : '' }}">
        <a  href="{{ url('error-404') }}"> 404 Error </a></li>	
		<li class="{{ Request::is('error-500') ? 'active' : '' }}">
        <a  href="{{ url('error-500') }}"> 500 Error  </a></li>
								
				
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-hand-o-up"></i> <span> Subscriptions </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('subscriptions') ? 'active' : '' }}" href="{{ url('subscriptions') }}"> Subscriptions (Admin)  </a></li>
            
		<li>
        <a class="{{ Request::is('subscriptions-company') ? 'active' : '' }}" href="{{ url('subscriptions-company') }}"> Subscriptions (Company)  </a></li>
								
		<li>
        <a class="{{ Request::is('subscribed-companies') ? 'active' : '' }}" href="{{ url('subscribed-companies') }}">Subscribed Companies  </a></li>

								
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-columns"></i> <span> Pages </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('search') ? 'active' : '' }}" href="{{ url('search') }}">Search  </a></li>

		<li>
        <a class="{{ Request::is('faq') ? 'active' : '' }}" href="{{ url('faq') }}"> FAQ  </a></li>

		<li>
        <a class="{{ Request::is('terms') ? 'active' : '' }}"  href="{{ url('terms') }}"> Terms </a></li>

        <li>
        <a class="{{ Request::is('privacy-policy') ? 'active' : '' }}" href="{{ url('privacy-policy') }}"> Privacy Policy </a></li>

		<li>
        <a class="{{ Request::is('blank-page') ? 'active' : '' }}" href="{{ url('blank-page') }}"> Blank Page </a></li>
								
								</ul>
							</li>
							<li class="menu-title"> 
								<span>UI Interface</span>
							</li>

							<li class="{{ Request::is('components') ? 'active' : '' }}">
        <a  href="{{ url('components') }}"> <i class="la la-puzzle-piece"></i><span>Components</span></a></li>


						
							<li class="submenu">
								<a href="#"><i class="la la-object-group"></i> <span> Forms </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('form-basic-inputs') ? 'active' : '' }}" href="{{ url('form-basic-inputs') }}"> Basic Inputs </a></li>

		<li>
        <a class="{{ Request::is('form-input-groups') ? 'active' : '' }}" href="{{ url('form-input-groups') }}"> Input Groups </a></li>

		<li>
        <a class="{{ Request::is('form-horizontal') ? 'active' : '' }}" href="{{ url('form-horizontal') }}"> Horizontal Form </a></li>

		<li>
        <a class="{{ Request::is('form-vertical') ? 'active' : '' }}" href="{{ url('form-vertical') }}"> Vertical Form </a></li>

		<li>
        <a class="{{ Request::is('form-mask') ? 'active' : '' }}" href="{{ url('form-mask') }}"> Form Mask </a></li>
							
		<li>
        <a class="{{ Request::is('form-validation') ? 'active' : '' }}" href="{{ url('form-validation') }}"> Form Validation  </a></li>							
									
								</ul>
							</li>
							<li class="submenu">
								<a href="#"><i class="la la-table"></i> <span> Tables </span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">

								<li>
        <a class="{{ Request::is('tables-basic') ? 'active' : '' }}" href="{{ url('tables-basic') }}"> Basic Tables   </a></li>	

		<li>
        <a class="{{ Request::is('data-tables') ? 'active' : '' }}" href="{{ url('data-tables') }}"> Data Table  </a></li>	
									
							
								</ul>
							</li>
							<li class="menu-title"> 
								<span>Extras</span>
							</li>
							<li> 
								<a href="#"><i class="la la-file-text"></i> <span>Documentation</span></a>
							</li>
							<li> 
								<a href="javascript:void(0);"><i class="la la-info"></i> <span>Change Log</span> <span class="badge badge-primary ml-auto">v3.4</span></a>
							</li>
							<li class="submenu">
								<a href="javascript:void(0);"><i class="la la-share-alt"></i> <span>Multi Level</span> <span class="menu-arrow"></span></a>
								<ul style="display: none;">
									<li class="submenu">
										<a href="javascript:void(0);"> <span>Level 1</span> <span class="menu-arrow"></span></a>
										<ul style="display: none;">
											<li><a href="javascript:void(0);"><span>Level 2</span></a></li>
											<li class="submenu">
												<a href="javascript:void(0);"> <span> Level 2</span> <span class="menu-arrow"></span></a>
												<ul style="display: none;">
													<li><a href="javascript:void(0);">Level 3</a></li>
													<li><a href="javascript:void(0);">Level 3</a></li>
												</ul>
											</li>
											<li><a href="javascript:void(0);"> <span>Level 2</span></a></li>
										</ul>
									</li>
									<li>
										<a href="javascript:void(0);"> <span>Level 1</span></a>
									</li>
								</ul>
							</li>
						</ul>
					</div>
                </div>
            </div>
            </div>


			