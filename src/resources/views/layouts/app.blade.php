<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>coachtech勤怠管理アプリ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/common.css') }}" />
@yield('css')
</head>

<body>
    <header class="header">
        <div class="header-inner">
            <div class="header-logo">
            <img src="{{ asset('img/logo.png') }}" alt="">
            </div>

            <nav class="nav">
                <ul class="nav-list">
                    <li class="nav-item">
                        <li class="nav-item"><a href="/attendance">勤怠</a></li>
                        <li class="nav-item"><a href="/attendance/list">勤怠一覧</a></li>
                        <li class="nav-item"><a href="/stamp_correction_request/list">申請</a></li>
                        <form class="form" action="/logout" method="POST">
                        @csrf
                            <button type="submit">ログアウト</button>
                        </form>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

<main>
@yield('content')
</main>
</body>

</html>