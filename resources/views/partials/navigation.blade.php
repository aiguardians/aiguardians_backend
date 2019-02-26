<ul>
    <li class="item-menu n-brand">
        <span class="">
            <img src="/img/brand_logo.png" />
        </span>
    </li>
    <a class="{{ $page=='dashboard'?'n-active':'' }}" href="/home">
        <li class="item-menu">
            <span class="">
                <img src="/img/layout.svg" width="24"/>
            </span>
            <span class="menu">Панель управления</span>
        </li>
    </a>
    <a class="{{ $page=='schedule'?'n-active':'' }}" href="/home/schedule">
        <li class="item-menu">
            <span class="">
                <img src="/img/calendar.svg" width="24"/>
            </span>
            <span class="menu">Расписание</span>
        </li>
    </a>
    <a class="{{ $page=='emotion'?'n-active':'' }}" href="#">
        <li class="item-menu">
            <span class="">
                <img src="/img/presentation.svg" width="24"/>
            </span>
            <span class="menu">Уровень Эмоции</span>
        </li>
    </a>
    <a class="{{ $page=='attendance'?'n-active':'' }}" href="/home/attendance">
        <li class="item-menu">
            <span class="">
                <img src="/img/profile.svg" width="24"/>
            </span>
            <span class="menu">Посещаемость</span>
        </li>
    </a>
</ul>
<ul class="n-bottom">
    <a href="#">
        <li class="item-menu">
            <span class="">
                <img src="/img/settings-1.svg" width="24"/>
            </span>
            <span class="menu">Настройки</span>
        </li>
    </a>
    <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
        <li class="item-menu">
            <span class="">
                <img src="/img/logout.svg" width="24"/>
            </span>
            <span class="menu">Выйти</span>
        </li>
    </a>
</ul>
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
