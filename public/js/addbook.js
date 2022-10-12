let form = document.getElementById('form_add_book')

form.onsubmit = async (e) => {
    e.preventDefault();
    let formData = new FormData(form)
    formData.append('authorID', authorID)
    let response = await fetch('http://127.0.0.1:8097/addNewBookAPI', {
        method: 'POST',
        body: formData,
      });
      form.reset();
      
      let result = await response.json();
      console.log(result);
}