var addClick = (e) => {

  if (e.classList.contains('active')) {

    var nodeCur = e.parentNode.parentNode;
    var listInput = nodeCur.querySelectorAll('input');

    // We are breaking the element name into the name and the id seperated by a '.'
    if (listInput[0].name.split('.')[1] > 0) {
      var nodeNew = nodeCur.cloneNode(true);
      var btnAdd = nodeNew.querySelector('i.add');

      btnAdd.addEventListener('click', (e) => addClick(e.target));
      btnAdd.classList.add('active');

      nodeNew.querySelectorAll('input').forEach((e) => {
        var parts = e.name.split(':');
        e.name = parts[0] + ':0';
        e.value = '';
      });

      nodeCur.after(nodeNew);
      e.classList.remove('active');
    }
  }
}

dL("DOMContentLoaded", () => {
  qA('form .repeat').forEach((f) => {
    f.querySelector('i.add').addEventListener('click', (e) => addClick(e.target));

    f.querySelector('i.del').addEventListener('click', (e) => {
      console.log('Deleting');
    });
  });
});