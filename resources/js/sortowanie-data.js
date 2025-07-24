document.addEventListener("DOMContentLoaded", () =>{
    function dataPost(){
        fetch('get-postsdate.php')
        .then(res => res.json())
        .then(posts =>{
            const container = document.getElementById('posts-cointainer');
            container.innerHTML = '';

            posts.forEach(post =>{
                const postHtml = `
                    <section class="post-header">
                        <img src="${post.avatar}" alt="Profile Image" class="profile-image">
                        <section class="username">${post.author}</section>
                    </section>
                    <p>${post.date}</p>
                    <section class="post-content">
                        <p>${post.content}</p>
                        ${post.image ? `<img src="${post.image}" alt="Post Image">` : ''}
                    </section>
                    <section class="post-actions">
                        <section class="like-wrapper">
                            <span class="like-count">${Number(post.like) || 0}</span> Osoby polubiły ten post
                            <button class="like-button" data-post-id="${post.id}">Polub post</button>
                        </section>
                    </section>
                    <hr>
                    `;

                const postElement = document.createElement('section');
                postElement.innerHTML= postHtml;
                container.appendChild(postElement);
            });
        })
        .catch(err =>{
            alert('Blął ładowania postów' + err);
        });
    };
});