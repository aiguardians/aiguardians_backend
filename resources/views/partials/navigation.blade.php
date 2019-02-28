<ul>
    <li class="item-menu n-brand">
        <span class="">
            <img src="/img/brand_logo.png" />
        </span>
    </li>
    <a class="{{ $page=='dashboard'?'n-active':'' }}" href="/home">
        <li class="item-menu">
            <span class="">
                <img src="/img/profile.svg" width="24"/>
            </span>
            <span class="menu">Control Panel</span>
        </li>
    </a>
    <a class="{{ $page=='schedule'?'n-active':'' }}" href="/home/schedule">
        <li class="item-menu">
            <span class="">
                <img src="/img/calendar.svg" width="24"/>
            </span>
            <span class="menu">Schedule</span>
        </li>
    </a>
    @if(auth()->user()->teacher)
        <a class="{{ $page=='attendance'?'n-active':'' }}" href="/home/attendance">
            <li class="item-menu">
                <span class="">
                    <img src="/img/presentation.svg" width="24"/>
                </span>
                <span class="menu">Attendance</span>
            </li>
        </a>
    @endif
</ul>
<ul class="n-bottom">
    <a href="#">
        <li class="item-menu">
            <span class="">
                <img src="/img/settings-1.svg" width="24"/>
            </span>
            <span class="menu">Settings</span>
        </li>
    </a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <li class="item-menu">
            <span class="">
                <img src="/img/logout.svg" width="24"/>
            </span>
            <span class="menu">Logout</span>
        </li>
    </a>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
