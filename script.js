function redirectToMoreDetails(roomType) {
    window.location.href = `moredetails.html?room=${roomType}`;
}

function handleBookNow(roomType) {
    if (userIsLoggedIn()) {
        window.location.href = 'booking.html';
    } else {
        alert('Please log in or register to book a room.');
        window.location.href = 'login.html'; // Redirect to login page
    }
}

function userIsLoggedIn() {
    return localStorage.getItem('isLoggedIn') === 'true';
}