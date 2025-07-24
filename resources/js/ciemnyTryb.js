document.addEventListener("DOMContentLoaded", () =>{
    const Bnt = document.getElementById("nocdzien");
    const emotka = document.getElementById("emotka");
    const stan = localStorage.getItem("stan");
    const img = document.getElementById("emo");
        
    if(stan === "dark"){
         document.documentElement.classList.add("dark");
        Bnt.checked = true;
        img.src = "ksiezyc.png";
    } else {
        document.documentElement.classList.remove("dark");
        Bnt.checked = false;
        img.src = "slonce.png";
    }

    Bnt.addEventListener("change", () =>{
        if(Bnt.checked){
            document.documentElement.classList.add("dark");
            img.src = "ksiezyc.png";
            localStorage.setItem("stan", "dark");
        }else{
            document.documentElement.classList.remove("dark");
            img.src = "slonce.png";
            localStorage.setItem("stan", "light");
        }
    });
});