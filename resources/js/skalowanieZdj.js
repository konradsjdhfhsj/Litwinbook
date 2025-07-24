document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("upload_zdj");
  if (!form) {
    console.error("Nie znaleziono formularza o ID 'upload_zdj'.");
    return;
  }

  form.addEventListener("submit", async function (e) {
    e.preventDefault();

    try {
      const inputZdj = document.getElementById("zdj");
      const opisInput = document.getElementById("opis");

      if (!inputZdj || !opisInput) {
        alert("Brakuje elementów formularza (zdjęcie lub opis).");
        return;
      }

      const file = inputZdj.files[0];
      const formData = new FormData();

      if (file) {
        let zdjBitmap;
        try {
          zdjBitmap = await createImageBitmap(file);
        } catch (err) {
          alert("Nie udało się wczytać obrazu.");
          console.error(err);
          return;
        }

        const maxWidth = 1024;
        let scale = 1;
        if (zdjBitmap.width > maxWidth) {
          scale = maxWidth / zdjBitmap.width;
        }

        const canvas = document.createElement("canvas");
        canvas.width = zdjBitmap.width * scale;
        canvas.height = zdjBitmap.height * scale;

        const ctx = canvas.getContext("2d");
        ctx.drawImage(zdjBitmap, 0, 0, canvas.width, canvas.height);

        const blob = await new Promise((resolve) => {
          canvas.toBlob((b) => resolve(b), "image/webp", 0.8);
        });

        if (!blob) {
          alert("Błąd konwersji obrazu do WebP.");
          return;
        }

        formData.append("zdj", blob, "converted.webp");
      }

      formData.append("opis", opisInput.value);

      await sendForm(formData);
    } catch (err) {
      alert("Wystąpił nieoczekiwany błąd.");
      console.error("Błąd podczas przetwarzania formularza:", err);
    }
  });

  async function sendForm(fd) {
    try {
      const response = await fetch("DodajPost.php", {
        method: "POST",
        body: fd,
      });

      const result = await response.json();

      if (result.status === "success") {
        window.location.replace("https://litwinbook.lb.info.pl/lb.php")
      } else {
        alert("Błąd: " + result.message);
      }
    } catch (err) {
      alert("Błąd po stronie klienta: " + err.message);
      console.error("Błąd fetch:", err);
    }
  }
});
