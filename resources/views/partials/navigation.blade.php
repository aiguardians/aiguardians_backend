<nav class="d-flex n-nav">
    <div>
        <div class="n-brand">
            <img src="/img/brand_logo.png"/>
        </div>
        <div class="n-name">{{ $user->first_name }} {{ $user->last_name }}</div>
        <div class="n-link">
            <a class="{{ $page=='dashboard'?'n-active':'' }}" href="/home">Панель управления</a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='schedule'?'n-active':'' }}" href="/home/schedule">Расписание</a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='emotion'?'n-active':'' }}" href="#">Уровень Эмоции</a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='attendance'?'n-active':'' }}" href="/home/attendance">Посещаемость</a>
        </div>
    </div>
    <div class="n-bottom">
        <div class="n-link">
            <a href="#">Настройки</a>
        </div>
        <div class="n-link">
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                Выйти
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</nav>
