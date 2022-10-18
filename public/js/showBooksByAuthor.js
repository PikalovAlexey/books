let placeShowingBooks = document.getElementById('show-books')
let result = '';
async function showBooks(authorID) {
    let response = await fetch('http://127.0.0.1:8097/authors?id='+authorID, {});
    let result = await response.text();
    console.log(result)
    placeShowingBooks.innerHTML = result
}
// let form = document.getElementById('form_add_book')

// form.onsubmit = async (e) => {
//     e.preventDefault();
//     let formData = new FormData(form)
//     formData.append('authorID', authorID)
//     let response = await fetch('http://127.0.0.1:8097/addNewBookAPI', {
//         method: 'GET',
//         body: formData,
//       });
//       form.reset();
      
//       let result = await response.json();
//       console.log(result);
// }