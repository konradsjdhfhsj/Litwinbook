document.addEventListener("DOMContentLoaded", () => {
  const container = document.getElementById("posts-container");
  let offset = 0;
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
          <span class="font-semibold">${escapeHtml(post.author)}</span>
        </div>
        <p class="text-sm text-gray-600">${escapeHtml(post.date)}</p>
        <p class="mt-2">${nl2br(escapeHtml(post.content))}</p>
        ${post.image ? `<img src="${post.image}" class="mt-2 rounded-lg max-w-xs" alt="post image">` : ''}
        <div class="text-sm text-gray-600 mt-2">
          <span class="like-count" data-post-id="${post.id}">${parseInt(post.like)} polubieĹ„</span>
          <button class="ml-2 text-blue-500 hover:underline like-button" data-post-id="${post.id}">
            ${liked ? 'Polubione' : 'Polub'}
          </button>
        </div>
      `;
      container.appendChild(postDiv);
    });

    attachLikeListeners();
  }

  function loadMorePosts() {
    if (loading || noMorePosts) return;
    loading = true;

    fetch(`get-posts.php?offset=${offset}&limit=${limit}`)
      .then(res => res.json())
      .then(data => {
        if (data.length === 0) {
          noMorePosts = true;
          return;
        }
        renderPosts(data);
        offset += limit;
      })
      .catch(err => {
        console.error("BĹ‚Ä…d Ĺ‚adowania postĂłw:", err);
      })
      .finally(() => {
        loading = false;
      });
  }

  function handleLikeClick(e) {
    const button = e.target;
    const postId = button.getAttribute("data-post-id");
    const likeCountSpan = document.querySelector(`.like-count[data-post-id="${postId}"]`);
    const likedPosts = getLikedPosts();

    if (!postId || !likeCountSpan) return;

    const liked = likedPosts.has(postId);
    const action = liked ? 'unlike' : 'like';


    fetch('like-post.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `post_id=${postId}&action=${action}`
    })
      .then(res => res.json())
      .then(data => {
        if (!data.success) {
          alert('BĹ‚Ä…d: ' + data.message);
          return;
        }


        likeCountSpan.textContent = `${data.likes} polubieĹ„`;

        if (liked) {
          likedPosts.delete(postId);
          button.textContent = "Polub";
        } else {
          likedPosts.add(postId);
          button.textContent = "Polubione";
        }

        saveLikedPosts(likedPosts);
      })
      .catch(err => {
        console.error("BĹ‚Ä…d poĹ‚Ä…czenia z serwerem:", err);
      });
  }

  function attachLikeListeners() {
    const likeButtons = document.querySelectorAll(".like-button");

    likeButtons.forEach(button => {
      button.removeEventListener("click", handleLikeClick);
      button.addEventListener("click", handleLikeClick);
    });
  }


  loadMorePosts();


  window.addEventListener("scroll", () => {
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
      loadMorePosts();
    }
  });
});