const isValidHandler = (e) => {
  t = e.target;
  p = t.parentElement;

  if (checkStatus(t)) {
    if (t.autocomplete == 'postal-code') {
      // PrefixName is used is get the matching city/county/state as the postal-code
      var prefixName = t.name;
      prefixName = prefixName.substring(0, prefixName.indexOf('postalcode'));
      // PrefixData is used for datalists
      var prefixData = prefixName.replace('_', '-');

      if (t.value.length == 5) {
        ajax('/api/area',
          {
            type: 'diff',
            postalcode: t.value
          },
          (data) => {
            if (Array.isArray(data.city)) {
              createDataList('datalist-' + prefixData + 'city', data.city);
            } else {
              q('input[name="' + prefixName + 'city"]').value = data.city;
              q('input[name="' + prefixName + 'city"]').parentElement.classList.add('success');
            }

            if (Array.isArray(data.county)) {
              createDataList('datalist-' + prefixData + 'county', data.county);
            } else {
              q('input[name="' + prefixName + 'county"]').value = data.county;
              q('input[name="' + prefixName + 'county"]').parentElement.classList.add('success');
            }

            if (Array.isArray(data.state)) {
              createDataList('datalist-' + prefixData + 'state', data.state);
            } else {
              q('input[name="' + prefixName + 'state"]').value = data.state;
              q('input[name="' + prefixName + 'state"]').parentElement.classList.add('success');
            }

            q('input.city').type = 'text';

            setTimeout(() => {
              q('input.county').type = 'text';
            }, 50);

            setTimeout(() => {
              q('input.state').type = 'text';
            }, 100);

          });
      }
    } else if (t.name == 'type' && t.classList.contains('program')) {

      var param = t.value.replace(' ', '_');
      ajax('/api/program?type=' + t.value, (data) => {

        if (Array.isArray(data)) {
          createDataList('datalist-program', data);
        }
      });

      var d = q('select[name="expire_day"]');
      var m = q('select[name="expire_month"]');
      var y = q('select[name="expire_year"]');

      if (t.value != 'Membership') {
        d.disabled = true;
        m.disabled = true;
        y.disabled = true;
        y.parentElement.parentElement.parentElement.classList.add('hide');
      } else {
        d.disabled = false;
        m.disabled = false;
        y.disabled = false;
        y.parentElement.parentElement.parentElement.classList.remove('hide');
      }
    }
    /*
    qA('form>div.row').forEach((e) => {
      var name = '';

      if (e.querySelector('input') !== null) {
        name = e.querySelector('input').name
      } else if (e.querySelector('select') !== null) {
        name = e.querySelector('select').name
      }
    });
    */
  }
};

var isValid = (e) => {
  var valid = false;

  if (e.type == 'email') {
    e.value = e.value.toLowerCase();
    valid = regexEmail.test(String(e.value));
  } else if (e.type == 'password') {
    valid = isValidPassword(e.value);
  } else if (e.type == 'tel') {
    if (e.type == 'focus') {
      e.pattern = '[0-9]*';
    } else if (e.type == 'blur') {
      e.removeAttribute('pattern');
    }

    e.value = formatTel(e.value);
    valid = isValidTel(e.value);

  } else if (e.type == 'select-one') {
    // Select
    if (e.name == 'year' || e.name.includes('_year')) {
      e.value = e.value.replace(/[^0-9]/g, '');
      valid = (e.value != '' && e.value >= 1900 && e.value <= 2036);

    } else if (e.name == 'month' || e.name.includes('_month')) {
      e.value = e.value.replace(/[^0-9]/g, '');
      valid = (e.value != '' && e.value >= 0 && e.value <= 12);
    } else if (e.name == 'day' || e.name.includes('_day')) {
      e.value = e.value.replace(/[^0-9]/g, '');
      valid = (e.value != '' && e.value >= 1 && e.value <= 31);
    } else if (e.name == 'id_type') {
      valid = (e.value != '');

      var idCountry = q('select[name="id_country"]');
      var idState = q('select[name="id_state"]');

      if (e.value == 'State ID' || e.value == 'Drivers License') {
        idCountry.disabled = true;
        idCountry.parentElement.classList.add('hidden');
        idState.disabled = false;
        idState.parentElement.classList.remove('hidden');
      } else if (e.value == 'Passport' || e.value == 'Global Entry' || e.value == 'TSA Pre') {
        idCountry.disabled = false;
        idCountry.parentElement.classList.remove('hidden');
        idState.disabled = true;
        idState.parentElement.classList.add('hidden');
      }
    }
    else {
      valid = (e.value.length > 0);
    }

  } else if (e.type == 'text') {

    if (e.name.indexOf('name') >= 0) {
      e.value = e.value.replace(/[^a-zA-Z- ]/g, "");
      valid = (e.value.length > 1);
    } else if (e.autocomplete == 'street-address'
      || e.autocomplete == 'address-level2'
      || e.classList.contains('county')
      || e.autocomplete == 'address-level1'
    ) {

      e.value = e.value.replace(/[^0-9a-zA-Z- ]/g, "");
      valid = (e.value.length > 2)

    } else if (e.name == 'ccNum') {
      e.value = e.value.replace(/\D/g, '');

      // Identify Credit card Type
      e.classList.remove('amex');
      e.classList.remove('visa');
      e.classList.remove('discover');
      e.classList.remove('mastercard');

      switch (e.value.charAt(0)) {
        case '3':
          e.classList.add('amex');
          break;

        case '4':
          e.classList.add('visa');
          break;

        case '5':
          e.classList.add('mastercard');
          break;

        case '6':
          e.classList.add('discover');
          break;

        default:
          break;
      }

      e.value = formatCC(e.value);
    } else if (e.name == 'ccExp') {

    } else if (e.name == 'ccCvc') {
    } else if (e.name == 'identNum') {
      e.value = e.value.replace(/[^a-zA-Z0-9- ]/g, "");
      valid = (e.value.length >= 1)
    } else {
      valid = (e.value.length >= 1)
    }
  } else if (e.type == 'number') {
    if (e.autocomplete == 'postal-code') {
      e.value = e.value.replace(/\D/g, '');

      valid = (e.value.length >= 5);

      if (e.value.length > 5) {
        e.value = e.value.substring(0, 5);
      }
    }
  }

  return valid;
};

var isValidTel = (v) => {
  v = v.replace(/[^0-9]/g, '');
  return (v.length >= 10);
}
''
var isValidPassword = (v) => {

  return (v.length >= 8
    && /[A-Z]/.test(v)
    && /[a-z]/.test(v)
    && /[0-9]/.test(v)
    && /[\!\@\#\$\%\^\&\*\(\)\-\_\+\=\[\]\{\}\;\:\'\"\\|\<\>\,\.\/\?\~]/.test(v));
}

var formatTel = (v) => {
  v = v.replace(/[^0-9]/g, '');

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

  return v;
}

var formatCC = (v) => {
  //34 & 37
  // NNNN-NNNNNN-NNNNN
  // XXXX-XXXXXX-XXXXX
  if (e.value.substring(0, 1) == 3) {
    v = v.substring(0, 4) + '-' + v.substring(4, 10) + '-' + v.substring(10);
  } else {
    // Format Credit Card Number
    var matches = v.match(/\d{4,16}/g);
    var match = matches && matches[0] || ''
    var parts = []

    for (i = 0, len = match.length; i < len; i += 4) {
      parts.push(match.substring(i, i + 4))
    }

    if (parts.length) {
      v = parts.join('-')
    }
  }
}

var checkStatus = (e) => {
  var status = false;

  e.parentElement.classList.remove('error');
  e.parentElement.classList.remove('success');

  if (isValid(e)) {
    status = true;

    e.parentElement.classList.add('success');

    if (e.autocomplete == 'postal-code') {
      q('input.city').type = 'text';
      q('input.county').type = 'text';
      q('input.state').type = 'text';
    }
  } else {
    e.parentElement.classList.add('error');
  }

  return status;
}

var formSubmit = (e) => {

  var status = true;
  var scroll = null;

  e.preventDefault();

  qA('div.row.isValid>input').forEach((el) => {
    if (!el.disabled) {
      if (checkStatus(el)) {
        if (scroll == null) {
          scroll = el.parentElement;
        }
      } else {
        status = false;
      }
    }
  });

  qA('div.row>select').forEach((el) => {
    if (!el.disabled) {
      if (checkStatus(el)) {
        if (scroll == null) {
          scroll = el.parentElement;
        }
      } else {
        status = false;
      }
    }
  });

  if (status) {
    console.log('C');
    //q('form').submit();
  } else if (scroll != null) {
    scroll.scrollIntoView();
  }

  return status;
}

var formAddStatus = (form, type, title, message) => {
  var container = form.querySelector('.status');
  var html = '<div class="' + type + '">';
  html += '<div class="icon">';

  switch (type) {
    case 'error':
      html += '<i class="fa-solid fa-circle-xmark"></i>';
      break;
    case 'warning':
      html += '<i class="fa-solid fa-triangle-exclamation"></i>';
      break;
    case 'success':
      html += '<i class="fa-solid fa-circle-check"></i>';
      break;
    case 'information':
      html += '<i class="fa-solid fa-circle-info"></i>';
      break;
  }
  html += '</div>';

  html += '<div class="msg"><strong>' + title + '</strong> • ' + message + '</div>';
  html += '<div class="close"><i class="fa-solid fa-xmark"></i></div>';
  html += '</div>';
  container.innerHTML = html;
}

dL("DOMContentLoaded", () => {

  qA('form').forEach((f) => {

    if (f != null) {
      var hasPost = 0;

      if (q('input[name="p"]') != null) {
        hasPost = q('input[name="p"]').value;
      }

      if (q('button[name="save"]')) {
        q('button[name="save"]').addEventListener('click', (e) => {
          console.log('B');
          if (formSubmit(e)) {
            console.log('Submitting');
            //f.submit();
          }
        });
      }

      qA('div.row.isValid>input').forEach((e) => {
        if (
          (hasPost > 0 || e.value.length > 0)
          && e.type != 'button'
          && e.type != 'submit') {

          checkStatus(e);
        }

        e.addEventListener('input', isValidHandler);
      });

      qA('div.row.isValid>select').forEach((e) => {

        if (hasPost > 0 || e.value.length > 0) {
          checkStatus(e);
        }
        e.addEventListener('input', isValidHandler);
      });

      f.addEventListener("submit", (e) => {
        //formSubmit(e);
        console.log('A');
      });

      qA('div.status .close').forEach((e) => {
        e.addEventListener('click', (el) => {
          el.target.parentElement.parentElement.classList.add('hide');
        });
      });
    }
  });
});