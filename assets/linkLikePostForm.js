/**
 * Contains functions that changes liks to the
 * form with method POST
 */

// Get the link element by its id
var postSuffle = document.getElementById("shufflaKortspel");

// Add an event listener to the link
postSuffle.addEventListener("click", function(event) {
  event.preventDefault(); // Prevent the default behavior of the link
  
  // Create a new form element
  var form = document.createElement("form");
  form.setAttribute("method", "post");
  form.setAttribute("action", "{{ path('api_desk_shuffle') }}");

  // Add any data you want to send as hidden input fields
//   var data = {
//     key1: "value1",
//     key2: "value2"
//   };
//   for (var key in data) {
//     if (data.hasOwnProperty(key)) {
//       var input = document.createElement("input");
//       input.setAttribute("type", "hidden");
//       input.setAttribute("name", key);
//       input.setAttribute("value", data[key]);
//       form.appendChild(input);
//     }
//   }

  // Add the form to the document body and submit it
  document.body.appendChild(form);
  form.submit();
});


console.log('This log comes from assets/limkLikePostForm.js - use it to change your links! 🎉');