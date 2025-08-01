 <?php
      $users = [];
      $resultUsers = $conn->query("SELECT * FROM urzytkownicy");
      while ($row = $resultUsers->fetch_assoc()) {
        $users[$row['nazwa']] = $row;
      }

      // Pobieranie postów w kolejności od najnowszych
      $resultPosts = $conn->query("SELECT * FROM post ORDER BY data DESC");
      if ($resultPosts->num_rows > 0) {
        while ($post = $resultPosts->fetch_assoc()) {
          $userName = $post['imie'];
          $avatar = isset($users[$userName]) ? $users[$userName]['avat'] : 'default-avatar.png';
          ?>
          <section class="post-header flex items-center gap-2 mb-2">
            <img src="<?= htmlspecialchars($avatar) ?>" alt="Profile Image" class="w-10 h-10 rounded-full">
            <section class="username font-semibold"><?= htmlspecialchars($userName) ?></section>
          </section>
          <p class="text-sm text-gray-600"><?= htmlspecialchars($post['data']) ?></p>
          <section class="post-content mt-2">
            <p><?= htmlspecialchars($post['post']) ?></p>
            <?php if (!empty($post['zdj'])): ?>
              <img src="<?= htmlspecialchars($post['zdj']) ?>" alt="Post Image" class="mt-2 rounded-lg max-w-xs">
            <?php endif; ?>
          </section>
          <hr class="my-4 border-gray-200">
          <?php
        }
      } else {
        echo "<p>Brak postów do wyświetlenia.</p>";
      }
      ?>