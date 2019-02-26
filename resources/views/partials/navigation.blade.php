<nav class="d-flex n-nav">
    <div>
        <div class="n-brand">
            <img src="/img/brand_logo.png"/>
        </div>
        <div class="n-link">
            <a class="{{ $page=='dashboard'?'n-active':'' }}" href="/home">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/layout.svg" width="24"/>
                    <span>Панель управления</span>
                </p>
            </a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='schedule'?'n-active':'' }}" href="/home/schedule">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/calendar.svg" width="24"/>
                    <span>Расписание</span>
                </p>
            </a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='emotion'?'n-active':'' }}" href="#">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/presentation.svg" width="24"/>
                    <span>Уровень Эмоции</span>
                </p>
            </a>
        </div>
        <div class="n-link">
            <a class="{{ $page=='attendance'?'n-active':'' }}" href="/home/attendance">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/profile.svg" width="24"/>
                    <span>Посещаемость</span>
                </p>
            </a>
        </div>
    </div>
    <div class="n-bottom">
        <div class="n-link">
            <a href="#">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/settings-1.svg" width="24"/>
                    <span>Настройки</span>
                </p>
            </a>
        </div>
        <div class="n-link">
            <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <p class="" style="white-space: nowrap;">
                    <img src="/img/logout.svg" width="24"/>
                    <span>Выйти</span>

                </p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</nav>
