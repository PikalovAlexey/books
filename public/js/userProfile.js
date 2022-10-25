function showFieldToChangeUsername() {
    document.getElementById('form_username').classList.remove('d-none');
    document.getElementById('form_username').focus();
    document.getElementById('usernameField').classList.add('d-none');
    document.getElementById('submit-edit-user-name').classList.remove('d-none');
}