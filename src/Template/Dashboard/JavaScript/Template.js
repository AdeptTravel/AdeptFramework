// Function to get the current URL's path, query, and hash
function getUrlComponents() {
  const path = window.location.pathname; // Get the path (e.g., /foo/bar)
  const query = window.location.search; // Get query (e.g., ?a=4)
  const hash = window.location.hash;    // Get hash (e.g., #b)
  return { path, query, hash };
}

// Function to get the current cookie as an object
function getCookieObject(name) {
  const cookie = document.cookie.split('; ').find(row => row.startsWith(name + '='));
  if (!cookie) return {};
  try {
    return JSON.parse(decodeURIComponent(cookie.split('=')[1]));
  } catch (e) {
    return {};
  }
}

// Function to set the cookie as a stringified object
function setCookieObject(name, value, days) {
  const date = new Date();
  date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
  const expires = "expires=" + date.toUTCString();
  document.cookie = name + "=" + encodeURIComponent(JSON.stringify(value)) + ";" + expires + ";path=/";
}

// Update query and hash data in their respective cookies
function updateUrlData() {
  const { path, query, hash } = getUrlComponents();

  // Retrieve the existing cookie data or create new objects
  const urlQueryData = getCookieObject('urlQueryData');
  const urlHashData = getCookieObject('urlHashData');

  // If there is no hash in the current URL but hash data exists for this path, append it to the URL
  let finalHash = hash;
  if (!finalHash && urlHashData[path]) {
    finalHash = urlHashData[path]; // Use existing hash data for this path
    // Append the existing hash to the current URL
    window.history.replaceState(null, null, window.location.pathname + window.location.search + finalHash);
  }

  // Update the arrays with the current path as the index
  if (query) urlQueryData[path] = query;
  if (finalHash) urlHashData[path] = finalHash;

  // Store the updated data back into the cookies
  setCookieObject('urlQueryData', urlQueryData, 1); // Cookie expires in 1 day
  setCookieObject('urlHashData', urlHashData, 1);   // Cookie expires in 1 day
}

// Call the function whenever needed
updateUrlData();