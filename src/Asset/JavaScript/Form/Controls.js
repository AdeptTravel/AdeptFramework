var actionSelected = (action, ids) => {
  var data = new FormData();
  data.append('ids', ids);
  data.append('action', action);

  formAjax(data, (val) => {
    ids.forEach((id) => {
      var tr = q('tr[data-id="' + id + '"]');

      if (action == 'publish' || action == 'unpublish') {
        var icon = tr.querySelector('i.status');

        if (val.status[id] && action == 'publish') {
          icon.classList.remove('off');
          icon.classList.add('on');
        } else if (val.status[id] && action == 'unpublish') {
          icon.classList.remove('on');
          icon.classList.add('off');
        }
      } else if (action == 'delete') {
        tr.parentNode.removeChild(tr);
      }
    });


  });
}

dL("DOMContentLoaded", () => {

  qA('#controls>button').forEach((e) => {
    e.addEventListener('click', () => {
      var loc = w.location.href;
      var form = q('main>form');
      var action = e.dataset.action;
      switch (action) {

        case 'close':
          w.location = loc.split('/edit')[0];
          break;

        case 'delete':
        case 'publish':
        case 'unpublish':
          // Extract their values
          const ids = Array.from(qA('table input[name="select"]:checked')).map((checkbox) => checkbox.value);

          actionSelected(action, ids)
          //console.log(ids);

          break;


        case 'duplicate':
          break;

        case 'edit':
          break;

        case 'new':
          w.location = w.location.origin + w.location.pathname + '/edit';
          break;


        case 'save':
        case 'saveclose':
        case 'savecopy':
        case 'savenew':
          var input = document.createElement("input");
          input.setAttribute("type", "hidden");
          input.setAttribute("name", "action");
          input.setAttribute("value", e.dataset.action);
          form.appendChild(input);
          form.submit();
          break;

        default:
          break;
      }
    });
  });
});