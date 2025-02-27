// Function to handle showing or hiding elements based on the source input or select that changed
function updateElementVisibility(source, form) {
  // Get all elements in the form that might have conditions
  form.querySelectorAll('[data-showon], [data-hideon]').forEach(el => {
    var showOn = el.getAttribute("data-showon");
    var hideOn = el.getAttribute("data-hideon");

    // Initial setup to ensure proper visibility
    if (showOn) {
      el.style.display = 'none'; // Hide by default
    }
    if (hideOn) {
      el.style.display = ''; // Show by default
    }

    // Parse conditions and update visibility
    parseConditions(el, showOn, true, form);
    parseConditions(el, hideOn, false, form);
  });
}

function parseConditions(element, conditions, show, form) {


  if (!conditions) {
    return;
  }

  var conditionList = conditions.split(',');

  conditionList.forEach(condition => {
    var [k, v] = condition.split('=');
    var sourceElement = form.querySelector(`[name="${k}"]`);

    if (sourceElement && sourceElement.value === v) {

      if (show) {
        element.style.display = '';
        element.disabled = false;
      } else {
        element.style.display = 'none';
        element.disabled = true;
      }
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  var i = 0;
  qA('form').forEach(form => {
    // Add event listeners to all input and select elements that might affect conditions
    qA('input, select').forEach(el => {

      if (el.name.length > 0) {

        el.addEventListener('change', () => {
          console.log('Triggered');
          updateElementVisibility(el, form);
        });

        // Initialize visibility on page load for each input
        updateElementVisibility(el, form);
      }
    });
  });
});