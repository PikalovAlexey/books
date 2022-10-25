async function addFavoriteBook(idBook) {
    
}

async function showForm() {
    let placeShowingForm = document.getElementById('show-form')
    let response = await fetch('http://127.0.0.1:8097/addAuthor', {});
    let result = await response.text();
    placeShowingForm.innerHTML = result
}