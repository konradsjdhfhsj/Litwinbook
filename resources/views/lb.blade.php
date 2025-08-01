<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LitwinBook</title>
    <script src="skrypty-js/main.js?v=4.1"></script>
    <script src="like.js?v=1.8"></script>
    <script src="skalowanieZdj.js?v=1.9"></script>
    <script src="skrypty-js/ciemnyTryb.js"></script>
    <script src="skrypty-js/edycja-pfp.js"></script>
    <script src="skrypty-js/sortowanie-data.js"></script>
    <script src="skrypty-js/tabs.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: "class",
      };
    </script>
    @vite('resources/js/main.js')
    @vite('resources/js/like.js')
    <link rel="shortcut icon" href="icon/favicon.ico" type="image/x-icon">
</head>
<body class="bg-gray-100 dark:bg-gray-800 text-gray-800 font-sans">


</form>

<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

$nazwa = $_SESSION['nazwa'] ?? 'nazwa';
$stmt = $conn->prepare("SELECT * FROM urzytkownicy WHERE nazwa = ?");
$stmt->bind_param("s", $nazwa);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if(!isset($_COOKIE['log'])){
   $_SESSION['nazwa'];
  setcookie('log',  $_SESSION['nazwa'], time() +10, '/');
  echo"dziala";
} else {
  echo"Nie dziala";
  session_destroy();
  //header('Locate: /Litwinbook');
  exit;
}?>
<header class="bg-white dark:bg-gray-700 shadow-md sticky top-0">
  <section class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
    <h1 class="text-2xl font-bold text-violet-600">LitwinBook</h1>
    <section class="hidden sm:block text-sm text-gray-600">
      <?= date('d-m-Y'); ?>
    </section>
    <nav>
      <ul class="flex gap-4 text-sm text-gray-700 dark:text-black">
        <li><a href="#" id="homepage" class="hover:text-blue-500">Strona gĹ‚Ăłwna</a></li>
        <li><a href="#" id="profile" class="hover:text-blue-500">Profil</a></li>
        <li><a href="#" id="message" class="hidden sm:block hover:text-blue-500">WiadomoĹ›ci</a></li>
        <li><a href="#" id="add-post-button" class="hover:text-blue-500">Dodaj post</a></li>
        <li><a href="/wyloguj" class="text-red-500 hover:underline">Wyloguj siÄ™</a></li>
      </ul>
    </nav>
  </section>
</header>

<main class="max-w-6xl mx-auto p-4 grid grid-cols-1 md:grid-cols-3 gap-6">

<!-- dodawanie postow-->
 <section id="add-posts" class="hidden bg-white p-6 rounded-xl shadow md:col-span-2 dark:bg-gray-700 dark:text-black">
  <h3 class="text-xl font-semibold mb-4">Dodaj nowy post</h3>
  <form action="/dodajpost" method="post" id="upload_zdj" enctype="multipart/form-data">
    @csrf
    <textarea name="opis" id="opis" required class="w-full border rounded p-2 mb-4 dark:bg-gray-800 dark:text-black" placeholder="Co nowego?"></textarea>
    <input type="file" name="zdj" id="zdj" accept="image/jpeg, image/png, image/webp, image/heic, image/heif" class="mb-4">
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Opublikuj</button>
  </form>
</section>

  <!-- GĹ‚Ăłwna kolumna -->
  <section class="md:col-span-2 space-y-6">
    <!-- Profil -->
    <section  id="profilePage" class="hidden bg-white p-6 rounded-xl shadow space-y-4 dark:bg-gray-700 dark:text-black">
      <label for="nocdzien"><img src="ksiezyc.png" alt="" class="w-8" id="emo"></label>
      <input type="checkbox" name="nocdzien" id="nocdzien" class="hidden">
      <div class="flex items-center gap-4">
                <label for="avat"><img src="<?= htmlspecialchars($user['avat'] ?? 'default-avatar.png') ?>" alt="Avatar" class="w-16 h-16 rounded-full object-cover"></label>
        <div class="flex-1">
          <h2 class="font-semibold text-lg"><?= htmlspecialchars($nazwa) ?></h2>
          <form action="/profil" method="post" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <input type="file" name="avat" id="avat" class="block w-full text-sm text-gray-500 hidden">
            <textarea id="profileDesc" class="w-full resize-none border border-gray-200 rounded-md p-2 bg-gray-50 dark:bg-gray-700 dark:text-black" readonly><?= htmlspecialchars($user['opis'] ?? 'Brak opisu') ?></textarea>
            <textarea name="opis" id="profileEdit" class="w-full resize-none border border-gray-300 rounded-md p-2 hidden"></textarea>
            <input type="submit" value="Akceptuj Opis" id="acceptBtn" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded hidden">
          </form>
          <button id="editBtn" onclick="enableEdit()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mt-2">Edytuj Profil</button>
        </div>
      </div>
    </section>

    <!-- Twoje posty -->
    <section class="profilePost hidden bg-white p-6 rounded-xl shadow dark:bg-gray-700 dark:text-black">
      <h3 class="text-xl font-semibold mb-4">Twoje posty</h3>
      <?php
      if (!isset($_SESSION['nazwa'])) {
          echo "<p>Musisz byÄ‡ zalogowany, aby zobaczyÄ‡ swoje posty.</p>";
      } else {
          $zalogowanyUzytkownik = $_SESSION['nazwa'];
          $users = [];
          $resultUsers = $conn->query("SELECT * FROM urzytkownicy");
          while ($row = $resultUsers->fetch_assoc()) {
              $users[$row['nazwa']] = $row;
          }

          $stmt = $conn->prepare("SELECT * FROM post WHERE imie = ? ORDER BY data DESC");
          $stmt->bind_param("s", $zalogowanyUzytkownik);
          $stmt->execute();
          $resultPosts = $stmt->get_result();

          if ($resultPosts->num_rows > 0) {
              while ($post = $resultPosts->fetch_assoc()) {
                  $avatar = $users[$post['imie']]['avat'] ?? 'default-avatar.png';
                  echo '<div class="border-b border-gray-200 py-4">';
                  echo '<div class="flex items-center gap-2 mb-2">';
                  echo '<img src="'.htmlspecialchars($avatar).'" class="w-10 h-10 rounded-full">';
                  echo '<span class="font-semibold">'.htmlspecialchars($post['imie']).'</span>';
                  echo '</div>';
                  echo '<p class="text-sm text-gray-600">'.htmlspecialchars($post['data']).'</p>';
                  echo '<p class="mt-2">'.htmlspecialchars($post['post']).'</p>';
                  if (!empty($post['zdj'])) {
                      echo '<img src="'.htmlspecialchars($post['zdj']).'" class="mt-2 rounded-lg max-w-xs">';
                  }
                  echo '<form action="usun.php" method="post" class="mt-2">';
                  echo '<input type="hidden" name="post_id" value="'.htmlspecialchars($post['id']).'">';
                  echo '<input type="submit" value="UsuĹ„ post" class="text-red-500 hover:underline text-sm">';
                  echo '</form>';
                  echo '</div>';
              }
          } else {
              echo "<p>Brak Twoich postĂłw do wyĹ›wietlenia.</p>";
          }
      }
      ?>
    </section>

    <!-- Wszystkie posty -->
    <section id="posts-container" class="bg-white p-4 rounded-xl shadow dark:bg-gray-700">
      <h4 class="text-xl font-semibold mb-4 dark:text-black">Wszystkie posty</h4>
<?php require_once('post.php'); ?>
    </section>
  </section>

  <!-- Sidebar -->
  <aside class="hidden sm:block space-y-6">
    <section class="bg-white p-4 rounded-xl shadow dark:bg-gray-700">
      <h4 class="text-lg font-semibold mb-2 dark:text-black">Propozycje znajomych</h4>
      <?php
      $result = $conn->query("SELECT * FROM urzytkownicy");
      while ($row = $result->fetch_assoc()) {
          echo '<div class="flex items-center gap-2 mb-4 dark:text-black">';
          echo '<img src="'.htmlspecialchars($row['avat']).'" class="w-10 h-10 rounded-full">';
          echo '<div>'; 
          echo '<p class="font-semibold">'.htmlspecialchars($row['nazwa']).'</p>';
          echo '<form action="/dodaj">';
          echo '<input type="submit" value="Dodaj" class="text-blue-500 hover:underline text-sm">';
          echo '</form>';
          echo '</div></div>';
      }
      ?>
    </section>

    <section class="bg-white p-4 rounded-xl shadow dark:bg-gray-700">
      <h4 class="text-lg font-semibold mb-2">Popularne tematy</h4>
      <div class="space-y-2">
        <div class="bg-gray-100 rounded-full px-4 py-1 text-sm dark:bg-gray-800 dark:text-black">#WalenieKonia</div>
        <div class="bg-gray-100 rounded-full px-4 py-1 text-sm dark:bg-gray-800 dark:text-black">#SpanieZHujemWGorze</div>
        <div class="bg-gray-100 rounded-full px-4 py-1 text-sm dark:bg-gray-800 dark:text-black">#KalenieWonia</div>
      </div>
    </section>
    <form action="" method="post">
  @csrf
  <input type="text" name="osoba">
  <input type="submit" value="Znajdz posty konkretnej osoby">
  <?php
  if ($_POST) {
    $conn = mysqli_connect('localhost', 'root', '', 'litwinbook');

    $osoba = $_POST['osoba'];
    $like = "%$osoba%";

    $avat = $conn->prepare('SELECT * FROM urzytkownicy WHERE nazwa LIKE ?');
    $avat->bind_param("s", $like);
    $avat->execute();
    $wynikavat = $avat->get_result();
    $avatarRow = $wynikavat->fetch_assoc(); 

    $posty = $conn->prepare("SELECT * FROM post WHERE imie LIKE ? ORDER BY data DESC");
    $posty->bind_param("s", $like);
    $posty->execute();
    $wynikpost = $posty->get_result();

    while ($row = $wynikpost->fetch_assoc()) {
      echo '<h1>' . htmlspecialchars($row['imie']);
      if ($avatarRow) {
        echo ' <img src="' . $avatarRow['avat']. '" alt="Avatar" style="width:32px;height:32px;border-radius:50%;display:inline-block;vertical-align:middle;">';
      }
      echo '</h1><br>';
      echo "<h1>" . htmlspecialchars($row['post']) . "</h1><br>";
      if (!empty($row['zdj'])) {
        echo ' <img src="' . htmlspecialchars($row['zdj']) . '" alt="Post Image" class="mt-2 rounded-lg max-w-xs">';
      }
      echo "<h1>" . htmlspecialchars($row['data']) . "</h1><br>";
    }
  }
  ?>
</form>
  </aside>
</main>

<script>
function enableEdit() {
  document.getElementById('profileDesc').classList.add('hidden');
  document.getElementById('profileEdit').classList.remove('hidden');
  document.getElementById('acceptBtn').classList.remove('hidden');
  document.getElementById('profileEdit').value = document.getElementById('profileDesc').value.trim();
}
</script>

<footer class="bg-white text-center text-sm text-gray-500 py-6 mt-10 bg-gray-700">
  Â© 2025 LitwinBook. Wszystkie prawa zastrzeĹĽone.
</footer>

<?php $conn->close(); ?>
</body>
</html>
