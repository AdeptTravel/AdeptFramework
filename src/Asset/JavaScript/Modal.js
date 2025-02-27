
var createModal = (url) => {

  let modal = gId('modal');
  // Check if a modal with the given ID already exists
  if (!modal) {
    modal = document.createElement("div");
  }

  modal.id = 'modal';
  modal.className = "modal";

  // Add modal content
  modal.innerHTML = `
        <div class="modal-content">
            <span class="fa-solid fa-circle-xmark close" style="cursor: pointer;"></span>
            <iframe id="modalFrame" src="` + url + `" style="width: 100%; height: 100%; border: none;"></iframe>
        </div>
    `;

  // Append modal to the body
  document.body.appendChild(modal);

  // Add close event listener
  modal.querySelector(".close").addEventListener("click", () => {
    destroyModal(modal);
  });

  // Close modal on clicking outside the content
  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      destroyModal(modal);
    }
  });

}

var destroyModal = (modal) => {
  modal.style.display = "none";
  modal.querySelector("iframe").src = "";
  modal.remove();
}