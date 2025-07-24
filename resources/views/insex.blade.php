<!DOCTYPE html>
<html lang="pl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>LitwinBook</title>
    <script src="skrypty-js/tab-index.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
        };
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="icon/favicon.ico" type="image/x-icon">
    <meta property="og:title" content="LitwinBook">
    <meta property="og:description" content="Podziel się nowymi postami">
    <meta property="og:image" content="https://litwinbook.ct.ws/icon/android-chrome-512x512.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="https://litwinbook.ct.ws">
    <meta property="og:type" content="website">

    <style>
      html{
        scroll-behavior: smooth;
      }
    </style>
    @vite('resources/js/litwinbook.js')
  </head>
  <body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center p-4 text-gray-800 font-sans">
    <section class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-xl p-6">
      <label for="nocdzien"><p id="emotka">☀️</p></label>
      <input type="checkbox" name="nocdzien" id="nocdzien" class="hidden">
      <header class="text-center mb-6">
          <h1 class="text-3xl font-bold text-violet-600">LitwinBook</h1>
          <p class="text-sm text-gray-600">Podziel sie nowymi postami</p>
        </header>
        <!-- taby -->
        <section class="flex justify-around mb-4">
          <a href="#signup-tab-content" class="text-blue-600 hover:underline">Zarejestruj się</a>
          <a href="#login-tab-content" class="text-blue-600 hover:underline">Zaloguj sie</a>
          <a href="#rem-tab-content" class="text-blue-600 hover:underline">Zmien nazwę</a>
        </section>

        <!-- rejestracja -->
        <section id="signup-tab-content" class="mb-6">
          <form action="/rejestracja" id="form-rej" method="post" class="space-y-4">
            @csrf
            <input type="email" id="email" name="email" placeholder="Adres e-mail" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-indigo-300" required>
            <input type="text" id="nazwa" name="nazwa" placeholder="Nazwa użytkownika" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-indigo-300" required>
            <input type="password" id="haslo" name="haslo" placeholder="Hasło" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-indigo-300" required>
            <input type="submit" id="zarejestruj" value="Zarejestruj się" class="w-full bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700 transition">
          </form>

          <script>
            document.addEventListener("DOMContentLoaded", () =>{
                
                const email = document.getElementById("email");
                const nazwa = document.getElementById("nazwa");
                const haslo = document.getElementById("haslo");
                const submit = document.getElementById("zarejestruj");
                let message = '';
                const isDark = document.documentElement.classList.contains("dark");
                const form = document.getElementById("form-rej");
                
                submit.addEventListener("click", () =>{

                    const dlugosc = haslo.value.length;
                    const pass = haslo.value;

                    if(dlugosc > 0 && dlugosc < 4){
                        message += 'Hasło musi zawierać więcej niż 4 znaki.\n';
                    }
                    if(!/\d/.test(pass)){
                        message += 'Hasło musi zawierać cyfre.\n';
                    }
                    if(message){
                        event.preventDefault();
                        Swal.fire({
                            icon: "error",
                            title: "Błędy w haśle",
                            text: message,
                            background: isDark? "#1f2937" : "#ffffff",
                            color: isDark? "white" : "#1f2937"
                        }); 
                        message = '';
                    }else{
                            event.preventDefault();
                            Swal.fire({
                            position: "center",
                            icon: "success",
                            title: "Konto stworzone",
                            background: isDark? "#1f2937" : "#ffffff",
                            color: isDark? "white" : "#1f2937",
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            form.submit();
                        });
                    }

                });
                
            });
          </script>

          <p class="text-xs text-black/70 dark:text-white/70 mt-4 text-center">
            Rejestrując się, akceptujesz nasze <a href="warunki.html" class="underline">Warunki korzystania z usługi</a>
          </p>
        </section>

        <!-- logowanie -->
        <section id="login-tab-content" class="mb-6">
          <form action="/logowanie" method="post" class="space-y-4">
            @csrf
            <input type="text" name="email" placeholder="E-mail lub nazwa użytkownika" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-300" required>
            <input type="password" name="haslo" placeholder="Hasło" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-300" required>
            <input type="submit" value="Zaloguj się" class="w-full bg-purple-600 text-white py-2 rounded-lg hover:bg-purple-700 transition">
          </form>
        </section>

        <!-- zmiana nazwy -->
        <section id="rem-tab-content">
          <form action="/zmiananazwy" method="post" id="nazwa_form" class="space-y-4">
            @csrf
            <input type="text" id="aktualna_nazwa" name="user_name" placeholder="Aktualna nazwa użytkownika" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-300" required>
            <input type="text" id="now_nazwa" name="new_user_name" placeholder="Nowa nazwa użytkownika" class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-300" required>
            <input type="password" id="nazwa_haslo" name="haslo" placeholder="Hasło"class="w-full px-4 py-2 rounded-lg bg-gray-100 focus:outline-none focus:ring-2 focus:ring-pink-300" required>
            <input type="submit" id="nazwa_sumbit" value="Zmień nazwę użytkownika" class="w-full bg-pink-600 dark:bg-pink-800 text-white py-2 rounded-lg hover:bg-pink-700 transition">
          </form>
        </section>
      </section>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
    const formNazwa = document.getElementById("nazwa_form");
    const aktNazwa = document.getElementById("aktualna_nazwa");
    const nowNazwa = document.getElementById("now_nazwa");
    const pasNazwa = document.getElementById("nazwa_haslo");
    const sumNazwa = document.getElementById("nazwa_sumbit");

    sumNazwa.addEventListener("click", (event) => {
        event.preventDefault();
        const nowNazwaValue = nowNazwa.value;
        const isDark = document.documentElement.classList.contains("dark");

        Swal.fire({
            title: "Jesteś pewien?",
            text: `Po zmianie Twoim nickiem będzie ${nowNazwaValue}`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Anuluj",
            confirmButtonText: "Zmień!",
            background: isDark ? "#1f2937" : "#ffffff",
            color: isDark ? "white" : "#1f2937"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Zmieniono!",
                    text: "Twój nick został zmieniony.",
                    icon: "success",
                    timer: 450,
                    background: isDark ? "#1f2937" : "#ffffff",
                    color: isDark ? "white" : "#1f2937",
                    showConfirmButton: false
                }).then(() => {
                    formNazwa.submit();
                });
            }
        });
    });
});

    </script>

    <script>

      const Bnt = document.getElementById("nocdzien");
      const emotka = document.getElementById("emotka");
      const stan = localStorage.getItem("stan");
      
      if(stan === "dark"){
        document.documentElement.classList.add("dark");
        Bnt.checked = true;
        emotka.innerText = "🌑";
      } else {
        document.documentElement.classList.remove("dark");
        Bnt.checked = false;
        emotka.innerText = "☀️";
      }

      Bnt.addEventListener("change", () =>{
        if(Bnt.checked){
          document.documentElement.classList.add("dark");
          emotka.innerText = "🌑";
          localStorage.setItem("stan", "dark");
        }else{
          document.documentElement.classList.remove("dark");
          emotka.innerText = "☀️";
          localStorage.setItem("stan", "light");
        }
      });
    </script>
</html>