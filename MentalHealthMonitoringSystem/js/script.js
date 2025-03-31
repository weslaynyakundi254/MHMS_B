function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition);
    } else {
        document.getElementById("location").innerHTML = "Geolocation is not supported by this browser.";
    }
}

function showPosition(position) {
    document.getElementById("location").innerHTML = 
        "Latitude: " + position.coords.latitude + 
        "<br>Longitude: " + position.coords.longitude;
}

document.getElementById("mood-form").addEventListener("submit", function(e) {
    e.preventDefault();
    const mood = document.getElementById("mood").value;
    fetch("php/save_mood.php", {
        method: "POST",
        body: JSON.stringify({ mood }),
        headers: { "Content-Type": "application/json" }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("tips").innerHTML = "Tip: " + data.tip;
    });
});