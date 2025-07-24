document.addEventListener("DOMContentLoaded", () =>{

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
});