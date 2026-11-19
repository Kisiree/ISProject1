<?php
require __DIR__ . '/config.php';
require __DIR__ . '/auth.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$username = escape_html($_SESSION['user']);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Account | ISProject1</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f6f8; color: #192c38; font: 1rem/1.55 system-ui, sans-serif; }
        header { background: #142d39; color: #fff; }
        .bar { max-width: 61rem; margin: auto; padding: 1rem clamp(1rem, 4vw, 2rem); display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
        .brand { font-weight: 750; }
        .bar form { margin: 0; }
        main { max-width: 61rem; margin: auto; padding: clamp(1.5rem, 6vw, 4rem) clamp(1rem, 4vw, 2rem); }
        .welcome { background: #dcece9; border-radius: 1rem; padding: clamp(1.4rem, 5vw, 2.5rem); margin-bottom: 1.25rem; }
        h1 { font-size: clamp(1.8rem, 5vw, 3rem); line-height: 1.15; letter-spacing: -.035em; margin: 0; overflow-wrap: anywhere; }
        h2 { font-size: 1.15rem; margin: 0 0 .65rem; }
        p { margin: 0 0 1rem; color: #455c68; }
        .grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 18rem), 1fr)); }
        section.card { background: #fff; border: 1px solid #dbe3e8; border-radius: 1rem; padding: 1.5rem; }
        dl { margin: 0; }
        dt { color: #455c68; font-size: .9rem; }
        dd { margin: .2rem 0 0; font-weight: 650; overflow-wrap: anywhere; }
        .action, .sign-out { display: inline-block; font: inherit; font-weight: 650; padding: .55rem .9rem; border-radius: .5rem; cursor: pointer; }
        .action { background: #176052; color: #fff; text-decoration: none; }
        .action:hover { background: #104a3f; }
        .sign-out { background: transparent; color: #fff; border: 1px solid #90a8b3; }
        .sign-out:hover { background: #284955; }
        :focus-visible { outline: 3px solid #b15c22; outline-offset: 4px; }
    </style>
</head>
<body>
    <header>
        <div class="bar">
            <span class="brand">ISProject1</span>
            <form method="post" action="logout.php">
                <input type="hidden" name="csrf_token" value="<?= escape_html(csrf_token()) ?>">
                <button class="sign-out" type="submit">Sign out</button>
            </form>
        </div>
    </header>

    <main>
        <div class="welcome">
            <h1>Welcome, <?= $username ?></h1>
        </div>
        <div class="grid">
            <section class="card" aria-labelledby="profile-title">
                <h2 id="profile-title">Your profile</h2>
                <dl>
                    <dt>Username</dt>
                    <dd><?= $username ?></dd>
                </dl>
            </section>
            <section class="card" aria-labelledby="security-title">
                <h2 id="security-title">Account security</h2>
                <p>Update the password you use to sign in.</p>
                <a class="action" href="change-password.php">Change password</a>
            </section>
        </div>
    </main>
</body>
</html>
