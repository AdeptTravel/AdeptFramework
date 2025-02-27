const formUrl = w.location.pathname;

var formAjax = (data, callback) => {
  console.log('Outgoing Data: ');
  console.log(data);
  fetch(formUrl + '.json', {
    headers: {
      'Accept': 'application/json'
    },
    method: "POST",
    body: data
  })
    .then((response) => {
      console.log('Response: ', response);
      return response.json();
    })
    .then((json) => {
      console.log('JSON Reponse: ', json);
      callback(json)
    })
    .catch((error) => {
      console.error('Error: ', error); // Handle any errors
    });
}
