let i = 0;

const isValidHandler = (event) => {
  e = event.target;
  p = e.parentElement;

  p.classList.remove('error');
  p.classList.remove('success');

  if (isValid(e)) {
    p.classList.add('success');

    if (e.name == 'postalcode') {
      ajax('/api/area?type=diff&postalcode=' + e.value, (data) => {
        if (Array.isArray(data.city)) {
          createDataList('datalist-city', data.city);
        } else {
          q('input[name="city"]').value = data.city;
          q('input[name="city"]').parentElement.classList.add('success');

        }

        if (Array.isArray(data.county)) {
          createDataList('datalist-county', data.county);
        } else {
          q('input[name="county"]').value = data.county;
          q('input[name="county"]').parentElement.classList.add('success');

        }

        if (Array.isArray(data.state)) {
          createDataList('datalist-state', data.state);
        } else {
          q('input[name="state"]').value = data.state;
          q('input[name="state"]').parentElement.classList.add('success');
        }

        q('input[name="city"]').type = 'text';

        setTimeout(() => {
          q('input[name="county"]').type = 'text';
        }, 50);


        setTimeout(() => {
          q('input[name="state"]').type = 'text';
        }, 100);

      });
    }


    b = q('input[type="submit"]');
    b.removeAttribute('disabled');

    console.log(i + '. Not disabled');

    qA('form>div.row').forEach((e, ii) => {
      var name = '';
      if (e.querySelector('input') !== null) {
        name = e.querySelector('input').name
      } else if (e.querySelector('select') !== null) {
        name = e.querySelector('select').name
      }
      if (name != '') {

        if (e.classList.contains('error')) {
          b.setAttribute('disabled', 'disabled');
          //console.log(i + '. 1(' + name + ') Disabled');
          //console.log(e.classList);
        } else if (e.classList.contains('isValid') && !e.classList.contains('success')) {
          //console.log(i + '. 2(' + name + ') Disabled');
          b.setAttribute('disabled', 'disabled');
        }
      }
    });

    i++;


  } else {
    p.classList.add('error');
    console.log('2. Adding Error to ' + e.name);
  }
};

var isValid = (e) => {

  var v = e.value;
  var t = e.type;
  var valid = false;

  if (t == 'email') {
    v = v.toLowerCase();
    valid = regexEmail.test(String(v));
  } else if (t == 'password') {
    if (v.length >= 8
      && v.match(/[a-z]/g)
      && v.match(/[A-Z]/g)
      && v.match(/[0-9]/g)) {

      // Password field validates
      if (e.name == 'password-verify') {
        // This field is a password verify field, check if value matches main password field
        if (v == q('input[name="password"]').value) {
          valid = true;
        }
      } else {
        valid = true;
      }
    }
  } else if (t == 'tel') {
    if (e.type == 'focus') {
      e.pattern = '[0-9]*';
    } else if (e.type == 'blur') {
      e.removeAttribute('pattern');
    } else {
      v = v.replace(/\D/g, '');

      if (v.length >= 10) {
        valid = true;
      }

      if (v.length > 1) {
        v = '(' + v;

        if (v.length > 4) {
          v = v.substring(0, 4) + ') ' + v.substring(4);

          if (v.length > 9) {
            v = v.substring(0, 9) + '-' + v.substring(9);

            if (v.length >= 15) {
              v = v.substring(0, 14) + ' x' + v.substring(14);
            }
          }
        }
      }
    }
  } else if (t == 'select-one') {
    if (e.name == 'month' || e.name == 'day' || e.name == 'year') {
      var m = (e.name == 'month') ? v : q('select[name="month"]').value;
      var d = (e.name == 'day') ? v : q('select[name="day"]').value;
      var y = (e.name == 'year') ? v : q('select[name="year"]').value;

      if (m.length > 0 && d.length > 0 && y.length > 0) {
        valid = true;
      }
    }
  } else if (t == 'text') {

    if (e.name.indexOf('name') >= 0) {
      v = v.replace(/[^a-zA-Z-. ]/g, "");

      if (v.length > 3) {
        valid = true;
      }
    }

    if (e.name == 'street-address'
      || e.name == 'address-line2'
      || e.name == 'city'
      || e.name == 'county'
      || e.name == 'state'
    ) {

      v = v.replace(/[^0-9a-zA-Z-. ]/g, "");
      if (v.length > 3) {
        valid = true;
      }

    }
  } else if (t == 'number') {
    if (e.name == 'postalcode') {
      v = v.replace(/\D/g, '');

      if (v.length >= 5) {
        valid = true;

        if (v.length > 5) {
          v = v.substring(0, 5);
        }
      }
    }
  }

  e.value = v;

  return valid;
};

dL("DOMContentLoaded", () => {
  var iV = q('#iteration').value;

  qA('div.row input').forEach((e) => {
    if (e.type != 'button' && e.type != 'submit' && e.name != 'address-line2') {

      if (iV > 0) {
        if (isValid(e)) {
          e.parentElement.classList.add('success');

          if (e.name == 'postalcode') {
            q('input[name="city"]').type = 'text';
            q('input[name="county"]').type = 'text';
            q('input[name="state"]').type = 'text';

          }
        } else {
          console.log('Adding Error to ' + e.name);
          e.parentElement.classList.add('error');
        }
      }

      e.addEventListener('input', isValidHandler);
    }
  });

  qA('div.row select').forEach((e) => {
    if (iV > 0) {
      isValid(e);
    }
    e.addEventListener('input', isValidHandler);
  });
});