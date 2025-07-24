document.addEventListener("DOMContentLoaded", () =>{
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
});