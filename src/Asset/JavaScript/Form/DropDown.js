/**
 * Drop down with, or without filter
 */

dL("DOMContentLoaded", () => {
  //.dropdown.active div input.filter
  qA('.dropdown .filter').forEach((el) => {
    // Filter
    el.addEventListener('input', () => {

      el.parentNode.querySelectorAll('button').forEach((e) => {
        if (e.innerText.toUpperCase().includes(el.value.toUpperCase())) {
          e.style.display = '';
        } else {
          e.style.display = 'none';
        }
      });
    });
  });

  qA('.dropdown').forEach((el) => {
    el.addEventListener('focusout', (ev) => {
      setTimeout(() => {
        if (!el.contains(document.activeElement)) {
          el.classList.remove('active');
          el.querySelector('input.display').nextElementSibling.focus();
        }
      }, 0);
    });
  });

  // Show dropdown
  qA('.dropdown input.display').forEach((e) => {
    e.addEventListener('focusin', (ev) => {
      // Add an active class
      e.parentNode.classList.add('active');
      // Filter
      var f = e.parentNode.querySelector('div>input.filter');

      if (f != null) {
        // Focus on the Filter input field
        f.focus();
        f.value = '';
      } else {
        var b = e.parentNode.querySelector('div>button:first-child');
        if (b) {
          b.focus();
        }
      }
    });
  });


  // Option clicked
  qA('.dropdown button').forEach((e) => {
    e.addEventListener('click', function (ev) {
      // Prevents the form from submitting
      ev.preventDefault();
      var p = e.parentNode.parentNode.parentNode;
      p.querySelector('input.display').value = e.innerText;

      // Set the value
      var el = p.querySelector('input[type=hidden]');
      el.value = (e.dataset.value) ? e.dataset.value : '';

      // We dispatch the change event on the hidden element to trigger any
      // events depentant on it, such as the conditions library.
      var c = new Event('change');
      el.dispatchEvent(c);

      // Hide the dropdown and move the focus to the next element
      p.classList.remove('active');
      p.querySelector('input.display').nextElementSibling.focus();

    });
  });
});