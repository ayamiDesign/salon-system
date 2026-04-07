<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ダッシュボード</title>
</head>
<body>
    <h1>ダッシュボード</h1>

    <p>ログインしました。</p>
    <p>{{ Auth::user()->name ?? 'ユーザー' }} さん</p>

    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button type="submit">ログアウト</button>
    </form>
</body>
</html>