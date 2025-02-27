
function imageSelectEvent(evt, el, handler) {
  const container = el.parentElement.parentElement;
  const id = container.querySelector('input[type="hidden"]');
  const img = container.querySelector('img');
  const title = container.querySelector('div.title');

  id.value = evt.data.id;
  img.src = evt.data.thumbnail;
  container.classList.remove('empty');
  title.innerText = evt.data.title;




  // Self-remove the event listener
  window.removeEventListener("message", handler);

  const modal = gId('modal');
  modal.style.display = "none";
  modal.querySelector("iframe").src = "";
  modal.remove();
}

dL("DOMContentLoaded", () => {

  // TODO: Change to a query selector to get image select button
  qA('div.image>div.controls>button').forEach((el) => {
    if (el.classList.contains('select')) {
      el.addEventListener("click", (evt) => {
        evt.preventDefault();
        createModal('/media/select');

        const handler = (event) => imageSelectEvent(event, el, handler);
        window.addEventListener("message", handler);
      });
    } else if (el.classList.contains('clear')) {
      el.addEventListener("click", (evt) => {
        const container = el.parentElement.parentElement;
        const id = container.querySelector('input[type="hidden"]')
        const img = container.querySelector('img');
        const title = container.querySelector('div.title');

        id.value = 0;
        img.src = '';
        container.classList.add('empty');
        title.innerText = '';
      });
    }
  });


  /*
  // Modal and iframe elements
  const modal = document.getElementById("myModal");
  const iframe = document.getElementById("modalIframe");
  const close = document.querySelector(".close");

  // Event delegation for opening the modal
  document.body.addEventListener("click", (event) => {
    if (event.target.classList.contains("openModal")) {
      const group = event.target.closest(".group");
      const hiddenInput = group.querySelector(".hiddenValue");
      const id = hiddenInput.value;

      // Set the iframe URL dynamically
      const url = `https://example.com?id=${id}`;
      iframe.src = url;

      // Show the modal
      modal.style.display = "block";

      // Listen for messages from the iframe
      window.addEventListener("message", (messageEvent) => {
        if (messageEvent.origin !== "https://example.com") return;

        // Update the hidden input value
        hiddenInput.value = messageEvent.data;
        modal.style.display = "none"; // Close the modal
        iframe.src = ""; // Clear the iframe URL
      }, { once: true });
    }
  });

});
*/
});