<li class="menu-title"> 
    <span>Main</span>
</li>
<li class="submenu">
    <a href="#"><i class="la la-cube"></i> <span> Apps</span> <span class="menu-arrow"><i class="fa fa-chevron-right"></i></span></a>
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