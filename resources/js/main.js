document.addEventListener("DOMContentLoaded", () => {
  const homepageLink = document.getElementById("homepage");
  const profileLink = document.getElementById("profile");
  const addPostLink = document.getElementById("add-post-button");

  const postSections = document.querySelectorAll("#posts-container");
  const addPostSections = document.querySelectorAll("#add-posts");
  const profilePageSections = document.querySelectorAll("#profilePage");
  const sidebarSections = document.querySelectorAll("aside");
  const profilePostSections = document.querySelectorAll(".profilePost");
  const gapSections = document.querySelectorAll(".gap");
  const postButtonSections = document.querySelectorAll(".post-button");

  function setDisplay(elements, value) {
    elements.forEach(el => {
      if (el) el.style.display = value;
    });
  }

  function toggleSections(state) {
    setDisplay(postSections, state.post);
    setDisplay(addPostSections, state.addPost);
    setDisplay(profilePageSections, state.profilePage);
    setDisplay(sidebarSections, state.sidebar);
    setDisplay(profilePostSections, state.profilePost);
    setDisplay(gapSections, state.gap);
    setDisplay(postButtonSections, state.postButton);
  }

  homepageLink?.addEventListener("click", (e) => {
    e.preventDefault();
    toggleSections({
      post: "block",
      addPost: "none",
      profilePage: "none",
      sidebar: "block",
      profilePost: "none",
      gap: "none",
      postButton: "block"
    });
  });

  addPostLink?.addEventListener("click", (e) => {
    e.preventDefault();
    toggleSections({
      post: "none",
      addPost: "block",
      profilePage: "none",
      sidebar: "none",
      profilePost: "none",
      gap: "none",
      postButton: "none"
    });
  });

  profileLink?.addEventListener("click", (e) => {
    e.preventDefault();
    toggleSections({
      post: "none",
      addPost: "none",
      profilePage: "block",
      sidebar: "none",
      profilePost: "block",
      gap: "flex",
      postButton: "none"
    });
  });

  // ObsĹ‚uga edycji opisu profilu
  const editBtn = document.getElementById("editBtn");
  const acceptBtn = document.getElementById("acceptBtn");
  const profileDesc = document.getElementById("profileDesc");
  const profileEdit = document.getElementById("profileEdit");

  if (editBtn && acceptBtn && profileDesc && profileEdit) {
    editBtn.addEventListener("click", () => {
      profileDesc.classList.add("hidden");
      profileEdit.classList.remove("hidden");
      acceptBtn.classList.remove("hidden");
      editBtn.classList.add("hidden");
      profileEdit.value = profileDesc.value.trim();
    });

    acceptBtn.addEventListener("click", () => {
      profileDesc.classList.remove("hidden");
      profileEdit.classList.add("hidden");
      acceptBtn.classList.add("hidden");
      editBtn.classList.remove("hidden");
    });
  }

	document.getElementById('posts-container').addEventListener('click', function(e)
	{
		if(e.target && e.target.classList.contains('like-button'))
		{
			e.preventDefault();

			const postId = e.target.getAttribute('data-post-id');
			const wrapper = e.target.closest('.like-wrapper');
			const countSpan = wrapper.querySelector('.like-count');

			const formData = new FormData();
			formData.append('post_id', postId);

			fetch('Likecontroller.php',
				{
					method: 'POST',
					body: formData
				})
				.then(res => res.json())
				.then(data =>
				{
					if(data.success)
					{
						countSpan.textContent = data.likes;
					}
					else
					{
						alert('BĹ‚Ä…d: ' + data.error);
					}
				})
				.catch(err =>
				{
					console.error('BĹ‚Ä…d zapytania:', err);
					alert('WystÄ…piĹ‚ bĹ‚Ä…d podczas lajka.');
				});
		}
	});

	// ajax like
	/*
	function likepost()
	{
		fetch('get-posts.php')
			.then(res => res.json())
			.then(posts =>
			{
				const container = document.getElementById('posts-container');
				container.innerHTML = '';

				posts.forEach(post =>
				{
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
    <div class="like-wrapper">
        <span class="like-count">${Number(post.like) || 0}</span> Osoby polubiĹ‚y ten post
        <button class="like-button" data-post-id="${post.id}">Polub post</button>
    </div>
</section>


                    <hr>
                `;

					const postElement = document.createElement('div');
					postElement.innerHTML = postHtml;
					container.appendChild(postElement);
				});
			})
			.catch(err =>
			{
				alert('BĹ‚Ä…d Ĺ‚adowania postĂłw: ' + err);
			});
	};
	*/

	function datepost()
	{
		fetch('get-posts-date.php')
			.then(res => res.json())
			.then(posts =>
			{
				const container = document.getElementById('posts-container');
				container.innerHTML = '';

				posts.forEach(post =>
				{
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
    <div class="like-wrapper">
        <span class="like-count">${Number(post.like) || 0}</span> Osoby polubiĹ‚y ten post
        <button class="like-button" data-post-id="${post.id}">Polub post</button>
    </div>
</section>


                    <hr>
                `;

					const postElement = document.createElement('div');
					postElement.innerHTML = postHtml;
					container.appendChild(postElement);
				});
			})
			.catch(err =>
			{
				alert('BĹ‚Ä…d Ĺ‚adowania postĂłw: ' + err);
			});
	};

	// filtrowanie postow
	const toggleSort = document.getElementById('toggleSort');

	toggleSort.addEventListener('click', function()
	{
		if(toggleSort.dataset.mode === 'date')
		{
			toggleSort.dataset.mode = 'like';
			toggleSort.textContent = 'Sortuj po dacie';
			likepost();
		}
		else
		{
			toggleSort.dataset.mode = 'date';
			toggleSort.textContent = 'Sortuj po polubieniach';
			datepost();
		}

	});

	//lazy loading postow
let offset = 0;
const limit = 10;
let loading = false;
let allLoaded = false;

function loadPosts() {
    if (loading || allLoaded) return;

    loading = true;
    document.getElementById('loading').classList.remove('hidden');

    fetch(`load-posts.php?offset=${offset}`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('loading').classList.add('hidden');
            if (data.length === 0) {
                allLoaded = true;
                return;
            }

            const container = document.getElementById('posts-list');
            data.forEach(post => {
                const postEl = document.createElement('div');
                postEl.className = 'border-b border-gray-200 py-4';
                postEl.innerHTML = `
                    <div class="flex items-center gap-2 mb-2">
                        <img src="${post.avat}" class="w-10 h-10 rounded-full">
                        <span class="font-semibold">${post.imie}</span>
                    </div>
                    <p class="text-sm text-gray-600">${post.data}</p>
                    <p class="mt-2">${post.post}</p>
                    ${post.zdj ? `<img src="${post.zdj}" class="mt-2 rounded-lg max-w-xs">` : ''}
                    <div class="text-sm text-gray-600 mt-2">
                        ${post.like} polubieĹ„
                        <button class="ml-2 text-blue-500 hover:underline like-button" data-post-id="${post.id}">Polub</button>
                    </div>
                `;
                container.appendChild(postEl);
            });

            offset += limit;
            loading = false;
        })
        .catch(() => {
            loading = false;
            document.getElementById('loading').classList.add('hidden');
        });
}

function handleScroll() {
    const scrollPosition = window.innerHeight + window.scrollY;
    const threshold = document.body.offsetHeight - 200; // 200px przed koĹ„cem

    if (scrollPosition >= threshold) {
        loadPosts();
    }
}


    loadPosts(); // ZaĹ‚aduj pierwszÄ… partiÄ™
    window.addEventListener('scroll', handleScroll); // ObsĹ‚uga przewijania


    // zmiana trybu nocnego

	const toggleButton = document.getElementById('toggle-theme');
	const currentTheme = localStorage.getItem('theme');

	const body = document.body;
	const main = document.querySelector('main');
	const aside = document.querySelector('aside');
	const footer = document.querySelector('footer');

	if(currentTheme === 'dark'){
		body.classList.add('dark-mode');
		main?.classList.add('dark-mode');
		aside?.classList.add('dark-mode');
		footer?.classList.add('dark-mode');
		toggleButton.checked = true;
	}

	toggleButton.addEventListener('click', () =>{
		body.classList.toggle('dark-mode');
		main?.classList.toggle('dark-mode');
		aside?.classList.toggle('dark-mode');
		footer?.classList.toggle('dark-mode');

		if(body.classList.contains('dark-mode')){
			localStorage.setItem('theme', 'dark');
		}else{
			localStorage.setItem('theme', 'light');
		}
	});


	//skalowanie jakosci zdjec zmiana w webp

	document.getElementById('upload').addEventListener('change', function(e) {
		const file = e.target.files[0];
		const reader = new FileReader();
		const img = new Image();

		reader.onload = function(event) {
			img.src = event.target.result;
		};

		img.onload = function() {
			const canvas = document.createElement('canvas');
			const MAX_WIDTH = 800;
			const scale = MAX_WIDTH / img.width;
			canvas.width = MAX_WIDTH;
			canvas.height = img.height * scale;

			const ctx = canvas.getContext('2d');
			ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

			const originalName = file.name.split('.').slice(0, -1).join('.');
			const newFileName = `${originalName}.webp`;
			const savePath = `/upload/${newFileName}`;

			canvas.toBlob(function(blob) {
			const formData = new FormData();
			formData.append('image', blob, newFileName);
			formData.append('relativePath', savePath);

			fetch('/upload', {
				method: 'POST',
				body: formData
			});
			}, 'image/webp', 0.8);
		};

  reader.readAsDataURL(file);
});



});