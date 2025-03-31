document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('schedule-meetup-form');
    const messageDiv = document.getElementById('schedule-message');

    form.addEventListener('submit', (e) => {
        e.preventDefault(); // Prevent the default form submission

        const formData = new FormData(form);

        // Send a POST request to schedule the meetup
        fetch('../backend/schedule_meetup.php', {
            method: 'POST',
            body: formData,
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status === 'success') {
                    messageDiv.innerHTML = `<p style="color: green;">${data.message}</p>`;
                    form.reset(); // Reset the form after successful submission
                } else {
                    messageDiv.innerHTML = `<p style="color: red;">${data.message}</p>`;
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                messageDiv.innerHTML = `<p style="color: red;">An error occurred while scheduling the meetup.</p>`;
            });
    });
});