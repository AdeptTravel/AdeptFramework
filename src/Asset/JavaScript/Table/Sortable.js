
dL("DOMContentLoaded", () => {
  // Select the sortable list container
  const reprderList = document.querySelector("tbody.reorder");

  // Select all items (rows) within the sortable list
  const reorderItems = reprderList.querySelectorAll("tr");

  // Starting order of things
  const oldValues = Array.from(reorderItems).map(item => item.querySelector('input[name="id"]').value);

  // Add event listeners to each item
  reorderItems.forEach(el => {
    el.addEventListener("dragstart", (e) => {
      // Adding dragging class to item after a delay
      //setTimeout(() => el.classList.add("dragging"), 0);
      el.classList.add("dragging")
    });

    // Add 'dragend' event listener
    el.addEventListener("dragend", (ev) => {

      el.classList.remove("dragging");

      let newItems = reprderList.querySelectorAll("tr");
      let newValues = Array.from(newItems).map(item => item.querySelector('input[name="id"]').value);

      let update = [];

      for (let i = 0; i < oldValues.length; i++) {
        if (oldValues[i] !== newValues[i]) {
          update.push({ 'id': newValues[i], 'index': i });
        }
      }

      // Submit reorder here
      console.log(update);

    });
  });

  // Function to initialize
  // Add 'dragover' event listener to the sortable list
  reprderList.addEventListener("dragover", (e) => {
    e.preventDefault();
    const draggingItem = document.querySelector(".dragging");

    // Get all items except currently dragging item
    let siblings = [...reprderList.querySelectorAll("tr:not(.dragging)")];

    // Find the sibling after which the dragging item should be placed
    let nextSibling = siblings.find(sibling => {
      return e.clientY <= sibling.offsetTop + sibling.offsetHeight / 3;
    });

    if (nextSibling) {
      var prevSibling = nextSibling.previousElementSibling;
      if (draggingItem.dataset.group == prevSibling.dataset.group || draggingItem.dataset.group == nextSibling.dataset.group) {
        reprderList.insertBefore(draggingItem, nextSibling);
      }
    } else {
      reprderList.appendChild(draggingItem);
    }
  });

  // Add 'dragenter' event listener to the sortable list
  reprderList.addEventListener("dragenter", e => e.preventDefault());

  // Add 'drop' event listener to the sortable list
  reprderList.addEventListener("drop", (e) => {
    console.log('Drop Event');
    e.preventDefault();
    // Custom drop logic here if needed
  });
});