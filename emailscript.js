document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();
    // Fetch API call to send the form data to the server
    fetch('https://formsubmit.co/meerabharadwa@gmail.com', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: document.getElementById('name').value,
            email: document.getElementById('email').value,
            message: document.getElementById('message').value
        })
    })
    .then(response => {
        if (response.ok) {
            alert('Message sent successfully!');
            document.getElementById('contactForm').reset();
        } else {
            throw new Error('Failed to send message');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the message. Please try again later.');
    });
});
