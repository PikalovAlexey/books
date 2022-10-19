async function showBooks(thisClickedElement, id, path) {
    changeActive(thisClickedElement);

    let placeShowingBooks = document.getElementById('show-books');

    let response = await fetch('http://127.0.0.1:8097/'+path+'?id='+id, {});
    let result = await response.text();
    console.log(result);

    if(result == 'false') {
        placeShowingBooks.innerHTML = 'Ничего не найдено...';
    }
    else {
        placeShowingBooks.innerHTML = result;
    }
}

function changeActive(el) {
    let listGroup = document.querySelectorAll('ul > li.active');

    listGroup.forEach(element => {
        element.classList.remove('active')
    });

    el.classList.add('active');
}