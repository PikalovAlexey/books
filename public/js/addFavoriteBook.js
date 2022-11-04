async function addFavoriteBook(el) {
    let bookId = el.getAttribute('data-id-book');
    
    var formdata = new FormData();
    formdata.append("bookId", bookId);

    var requestOptions = {
    method: 'POST',
    body: formdata,
    };

    fetch("http://127.0.0.1:8097/favoriteBookAPI", requestOptions)
    .then(response => response.json())
    .then(result => console.log(result))
    .catch(error => console.log('error', error));
}