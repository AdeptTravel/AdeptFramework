dL("DOMContentLoaded", () => {

  qA('form.filter').forEach((f) => {

    f.querySelectorAll('div.dropdown > input[type="hidden"]').forEach((el) => {

      el.addEventListener('change', (ev) => {
        filterSubmit(ev.target.form);
      });

    });

    f.querySelectorAll('div.search > button').forEach((el) => {
      el.addEventListener('click', (ev) => {
        ev.preventDefault();
        filterSubmit(ev.target.form);
      });
    });
  });
});

function filterSubmit(f) {

  // Url Params
  var up = new URLSearchParams(window.location.search);

  // Create an object to store the key-value pairs
  var p = {};

  // Iterate over each entry in the URLSearchParams object
  up.forEach((v, k) => {
    if (v !== null) {
      p[k] = v;
    }
  });

  // Form data
  const d = new FormData(f);
  // Iterate over each form field and get the value
  d.forEach((v, k) => {
    // The filter key is no longer set
    if (v == '') {
      delete p[k];
    } else if (v || v === false) {
      p[k] = v;
    }
  });


  window.location = w.location.origin +
    w.location.pathname + '?' +
    Object.keys(p)
      .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(p[key])}`)
      .join('&');

}