let form = document.getElementById('form_add_book')

form.onsubmit = async (e) => {
    e.preventDefault();
    console.log(new FormData(form))
    let response = await fetch('http://127.0.0.1:8097/addNewBookAPI', {
        method: 'POST',
        body: new FormData(form)
      });
      form.reset();
      
      let result = await response.json();
      console.log(result);
}