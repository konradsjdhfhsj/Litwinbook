
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', () => {
      const postId = button.getAttribute('data-post-id');

      fetch('like_post.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'post_id=' + encodeURIComponent(postId)
      })
      .then(response => response.json())
      .then(data => {
        if(data.success) {
          const parent = button.parentElement;
          parent.querySelector('span.like-count').textContent = data.likes + ' polubień';
        } else {
          alert('Błąd podczas lajków: ' + data.message);
        }
      })
      .catch(() => alert('Błąd sieci'));
    });
  });
});

