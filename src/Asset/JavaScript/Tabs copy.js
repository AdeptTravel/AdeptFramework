var tabReset = (tabs) => {
  tabs.querySelectorAll('.tabnav>button').forEach((tab) => {
    tab.classList.remove('active');
  });

  tabs.querySelectorAll('.tab').forEach((tab) => {
    tab.classList.remove('active');
  });
}


dL("DOMContentLoaded", () => {
  // Cycle through all tab groups
  qA('div.tabs').forEach((tabs) => {
    tabReset(tabs);
    // TODO: Add a saved tab position here
    tabs.querySelectorAll('.tabnav>button').forEach((button, i) => {
      if (i == 0) {
        button.classList.add('active');
      }

      button.addEventListener('click', (e) => {
        console.log('Click on'); console.log(e.target);
        e.preventDefault();
        tabs = e.target.parentElement.parentElement;
        tabReset(tabs);
        e.target.classList.add('active');

        tabs.querySelectorAll('.tab')[i].classList.add('active');
      });

    });

    tabs.querySelectorAll('.tab')[0].classList.add('active');
  });

});