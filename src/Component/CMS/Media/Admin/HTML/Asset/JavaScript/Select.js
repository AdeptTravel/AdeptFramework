
dL("DOMContentLoaded", () => {

  qA('div.medialist>div.select').forEach((el) => {

    if (el.classList.contains('select')) {
      el.addEventListener("click", (evt) => {
        const data = {
          id: el.querySelector('input[name="id"]')?.value || null,
          alias: el.querySelector('input[name="alias"]')?.value || null,
          title: el.querySelector('h3').innerText || null,
          thumbnail: el.querySelector('input[name="thumbnail"]')?.value || null,
        };

        // Specify the target origin (parent domain)
        const origin = 'https://' + w.location.hostname;
        // Send the object to the parent
        w.parent.postMessage(data, origin);
      });
    }
  });
});
