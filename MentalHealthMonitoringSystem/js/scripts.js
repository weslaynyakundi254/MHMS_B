document.getElementById("mood-form").addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent the default form submission

    const mood = document.getElementById("mood").value;

    // Optional: Add location if available
    const location = document.getElementById("location").innerText || null;

    // Send data to the backend
    fetch("../backend/save_mood.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `mood=${encodeURIComponent(mood)}&location=${encodeURIComponent(location)}`,
    })
        .then((response) => response.text())
        .then((data) => {
            alert(data); // Show success or error message
        })
        .catch((error) => {
            console.error("Error:", error);
        });
});

fetch("../backend/fetch_professionals.php")
    .then((response) => {
        console.log("Response received:", response);
        return response.json();
    })
    .then((data) => {
        console.log("Data fetched:", data);
        const professionalsDiv = document.getElementById("professionals");
        if (data.length > 0) {
            let list = `<ul>`;
            data.forEach((professional) => {
                list += `
                    <li>
                        <strong>${professional.name}</strong> - ${professional.specialization}<br>
                        Location: ${professional.location}<br>
                        <button onclick="scheduleMeetup('${professional.id}')">Schedule Meetup</button>
                    </li>`;
            });
            list += `</ul>`;
            professionalsDiv.innerHTML = list;
        } else {
            professionalsDiv.innerHTML = "<p>No professionals available.</p>";
        }
    })
    .catch((error) => {
        console.error("Error fetching professionals:", error);
        document.getElementById("professionals").innerHTML = "<p>Error loading professionals.</p>";
    });

document.addEventListener('DOMContentLoaded', () => {
    const likeButtons = document.querySelectorAll('.like-btn');

    likeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const postElement = button.closest('.post');
            const postId = postElement.getAttribute('data-post-id');
            const likeCountElement = postElement.querySelector('.like-count');

            console.log(`Liking post with ID: ${postId}`); // Debugging

            // Send a POST request to like the post
            fetch('../backend/like_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `post_id=${postId}`,
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Response from server:', data); // Debugging
                    if (data.status === 'success') {
                        likeCountElement.textContent = `${data.likes} Likes`;
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        });
    });
});

function likePost(element) {
    const postId = element.getAttribute('data-post-id');
    const likeCountElement = element.nextElementSibling;

    // Send a POST request to like the post
    fetch('../backend/like_post.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `post_id=${postId}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                likeCountElement.textContent = `${data.likes} Likes`;
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function scheduleMeetup(professionalId) {
    fetch('../backend/schedule_meetup.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `professional_id=${professionalId}`,
    })
        .then(response => response.text())
        .then(data => console.log(data))
        .catch(error => console.error('Error:', error));
}