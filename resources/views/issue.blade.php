<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
<title>Zgłoś Issue na GitHubie</title>
<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    input, textarea, button { display: block; width: 100%; margin-bottom: 10px; padding: 8px; }
</style>
</head>
<body>
<h2>Zgłoś problem do GitHub</h2>
<form method="POST" action="/issues">
    @csrf
    <input type="text" name="title" placeholder="Tytuł zgłoszenia" required>
    <textarea name="body" placeholder="Opis zgłoszenia" rows="6"></textarea>
    <button type="submit">Wyślij</button>
</form>
</body>
</html>

