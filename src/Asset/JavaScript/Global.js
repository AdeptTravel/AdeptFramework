var w = window;
var d = document;
var q = d.querySelector.bind(d);
var qA = d.querySelectorAll.bind(d);
var wL = w.addEventListener.bind(d);
var dL = d.addEventListener.bind(d);
var gId = d.getElementById.bind(d);


/**
 * 
 * @param {*} key - Key 
 * @param {*} val - Value
 * @param {*} exp - Expire
 */
var setCookie = (key, val, exp) => {
  const d = new Date();
  d.setTime(d.getTime() + (exp * 24 * 60 * 60 * 1000));
  d.cookie = key + "=" + val + ";" + "expires=" + d.toUTCString() + ";path=/";
}

var getCookie = (key) => {
  key = key + "=";

  let val = '';
  let data = decodeURIComponent(d.cookie).split(';');


  for (let i = 0; i < data.length; i++) {

    let c = data[i];

    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }

    if (c.indexOf(key) == 0) {
      val = c.substring(key.length, c.length);
    }
  }

  return v;
}