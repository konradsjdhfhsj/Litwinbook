document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("posts-container");
  let offset = 10;
  const limit = 10;
  let loading = false;
  let noMorePosts = false;

  function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>"']/g, m => ({
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#39;'
    })[m]);
  }

  function nl2br(str) {
    return str.replace(/\n/g, '<br>');
  }

  function getLikedPosts() {
    const liked = localStorage.getItem('likedPosts');
    return liked ? new Set(JSON.parse(liked)) : new Set();
  }

  function saveLikedPosts(likedSet) {
    localStorage.setItem('likedPosts', JSON.stringify([...likedSet]));
  }

  function renderPosts(posts) {
    const likedPosts = getLikedPosts();

    posts.forEach(post => {
      const liked = likedPosts.has(String(post.id));
      const postDiv = document.createElement("div");
      postDiv.classList.add("border-b", "border-gray-200", "py-4");
      postDiv.innerHTML = `
        <div class="flex items-center gap-2 mb-2">
          <img src="${post.avatar}" class="w-10 h-10 rounded-full" alt="avatar">
          <span class="font-semibold">${escapeHtml(post.imie)}</span>
        </div>
        <p class="text-sm text-gray-600">${escapeHtml(post.data)}</p>
        <p class="mt-2">${nl2br(escapeHtml(post.post))}</p>
        ${post.zdj ? `<img src="${post.zdj}" class="mt-2 rounded-lg max-w-xs" alt="post image">` : ''}
        <div class="text-sm text-gray-600 mt-2">
          <span class="like-count" data-post-id="${post.id}">${parseInt(post.like)} polubień</span>
          <button class="ml-2 text-blue-500 hover:underline like-button" data-post-id="${post.id}">
            ${liked ? 'Polubione' : 'Polub'}
          </button>
        </div>
      `;
      container.appendChild(postDiv);
    });
  }

  async function loadPosts() {
    if (loading || noMorePosts) return;
    loading = true;

    try {
      const res = await fetch(`load-posts.php?offset=${offset}`);
      if (!res.ok) throw new Error("Błąd sieci");
      const posts = await res.json();
      if (posts.length === 0) {
        noMorePosts = true;
      } else {
        renderPosts(posts);
        offset += limit;
      }
    } catch (e) {
      console.error("Błąd ładowania postów:", e);
    } finally {
      loading = false;
    }
  }

  document.addEventListener('click', function (e) {
  if (!e.target.classList.contains('like-button')) return;

  const button = e.target;
  const postId = button.getAttribute('data-post-id');
  if (!postId) return;

  const likedPosts = getLikedPosts();
  const liked = likedPosts.has(postId);
  const action = liked ? 'unlike' : 'like';

  console.log("Wysyłam:", { postId, action });

  fetch('like-posts.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `post_id=${encodeURIComponent(postId)}&action=${action}`
  })
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      button.textContent = liked ? 'Polub' : 'Polubione';

      const likeCountElement = document.querySelector(`.like-count[data-post-id="${postId}"]`);
      if (likeCountElement) {
        likeCountElement.textContent = `${data.likeCount} polubień`;
      }

      if (liked) {
        likedPosts.delete(postId);
      } else {
        likedPosts.add(postId);
      }
      saveLikedPosts(likedPosts);
    } else {
      console.error("Błąd lajku:", data.message);
    }
  })
  .catch(err => {
    console.error("Błąd zapytania AJAX:", err);
  });
});
});