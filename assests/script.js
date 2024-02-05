document.addEventListener('DOMContentLoaded', function () {
    var errorMessage = document.getElementById('username-error');
    if (errorMessage.innerText === '') {
        errorMessage.style.display = 'none';
    } else {
        errorMessage.style.display = 'block';
    }

    const wrapper = document.querySelector('.wrapper');
    const loginlink = document.querySelector('.login-link');
    const registerlink = document.querySelector('.register-link');

    registerlink.addEventListener('click', (event) => {
        event.preventDefault();
        wrapper.classList.add('active');
    });

    loginlink.addEventListener('click', (event) => {
        event.preventDefault();
        wrapper.classList.remove('active');
    });
});

function validatePasswords() {
    var password = document.getElementById('password').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    var errorSpan = document.getElementById('passwordMatchError');

    // Check if passwords match
    if (password !== confirmPassword) {
        errorSpan.innerText = 'Passwords do not match';
        return false; // Prevent form submission
    } else {
        errorSpan.innerText = ''; // Clear error message
        return true; // Allow form submission
    }
}
function validateEmail() {
        var email = document.getElementById('email').value;
        var emailFormatError = document.getElementById('emailFormatError');

        // Use a regular expression to check if the email format is valid
        var emailRegex = /^[a-zA-Z0-9._-]+@gmail\.com$/;

        if (!emailRegex.test(email)) {
            emailFormatError.innerText = 'Enter a valid Gmail address';
            return false; // Prevent form submission
        } else {
            emailFormatError.innerText = ''; // Clear error message
            return true; // Allow form submission
        }
    }
