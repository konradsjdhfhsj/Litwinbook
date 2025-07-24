document.addEventListener("DOMContentLoaded", () => {
	const tabLinks = document.querySelectorAll("a[href^='#']");
	const tabContents = document.querySelectorAll("section[id$='-tab-content']");

	tabLinks.forEach(link => {
		link.addEventListener("click", (e) =>{
			e.preventDefault();

			tabContents.forEach(tab => tab.classList.add("hidden"));

			tabLinks.forEach(tab => tab.classList.remove("font-bold","underline"));

			const target = document.querySelector(link.getAttribute("href"));
			if(target){
				target.classList.remove("hidden");
			}

			link.classList.add("font-bold", "underline");
		});
	});

	if(tabContents.length) tabContents.forEach((tab, i) => {
		if(i !== 0) tab.classList.add("hidden");
	});
});