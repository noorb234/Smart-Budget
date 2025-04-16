// Function to handle the "Look up Username" form submission
function lookupUsername(event) {
    event.preventDefault(); // Prevent form submission

    const username = document.getElementById('passwordUsername').value;

    // Check if username is provided
    if (!username) {
        alert('Please enter a username');
        return;
    }

    // Send an AJAX request to check if the username exists in the database
    fetch('checkUsername.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // If username is found, show the security questions form
            document.getElementById('formPassword').style.display = 'block';
            document.getElementById('firstQuestionLabel').textContent = data.securityQuestions.firstQuestion;
            document.getElementById('secondQuestionLabel').textContent = data.securityQuestions.secondQuestion;
        } else {
            alert('Username not found. Please check and try again or register.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    });
}

document.getElementById('resetPassword').addEventListener('click', resetPassword);

function resetPassword(event) {
    event.preventDefault(); // Prevent form submission
    console.log("Reset password button clicked");  // Ensure the button click is working

    const username = document.getElementById('passwordUsername').value;
    const firstAnswer = document.getElementById('firstAnswerField').value;
    const secondAnswer = document.getElementById('secondAnswerField').value;
    const newPassword = document.getElementById('ResetPasswordLabelField').value;

    if (!firstAnswer || !secondAnswer || !newPassword) {
        alert('Please fill out all fields.');
        return;
    }

    console.log({ username, firstAnswer, secondAnswer, newPassword });

    fetch('resetPassword.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, firstAnswer, secondAnswer, newPassword })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Response from server:', data); // Log the server response
        if (data.success) {
            alert('Your password has been reset. You will now be redirected to the login page.');
            window.location.href = 'login.php'; // Redirect to login page
        } else {
            alert('Security answers are incorrect. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.');
    });
}