var formTableRowToggle = (row, col, val) => {
  var id = row.querySelector('input[name="id"]').value;

  if (id > 0) {
    var icon = row.querySelector('i.' + col);

    var data = new FormData();
    data.append('id', id);
    data.append('action', 'toggle');
    data.append('column', col);
    data.append('value', val);

    formAjax(data, (v) => {
      console.log('Value: ', val);
      if (val) {
        icon.classList.remove('off');
        icon.classList.add('on');
      } else {
        icon.classList.remove('on');
        icon.classList.add('off');
      }
    });
  }
}

dL("DOMContentLoaded", () => {
  qA('td i').forEach((e) => {
    e.addEventListener('click', (evt) => {
      //console.log(e.classList[0]);
      var row = e.parentElement.parentElement;
      var column = e.classList[0];
      var status = (e.classList.contains('on')) ? 0 : 1;
      formTableRowToggle(row, column, status);

      console.log(column + ' = ' + status);
    });
  });
});