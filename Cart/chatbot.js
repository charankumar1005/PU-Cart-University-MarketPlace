// Toggle Chat Window
function toggleChatWindow() {
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.style.display = chatWindow.style.display === 'block' ? 'none' : 'block';
}

// Send Predefined Message
function sendMessage(message) {
    const chatInput = document.getElementById('chatInput');
    chatInput.value = message;
}

// Handle Chat Submission
function submitChat(event) {
    event.preventDefault(); // Prevent page reload
    const chatInput = document.getElementById('chatInput');
    const message = chatInput.value;

    // Handle message sending logic here (e.g., display message, send to server)

    console.log("Message sent:", message); // Placeholder action
    chatInput.value = ''; // Clear input after sending
}
// Function to send a message and get a reply from the server
function sendMessage() {
    const chatInput = document.getElementById('chatInput');
    const message = chatInput.value.trim();

    if (!message) return; // Don't send empty messages

    // Display the user's message
    displayMessage("user", message);

    // Clear the input box
    chatInput.value = '';

    // Send the message to the server
    fetch('reply.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `message=${encodeURIComponent(message)}`
    })
    .then(response => response.json())
    .then(data => {
        // Display the server's reply
        displayMessage("server", data.reply);
    })
    .catch(error => {
        console.error("Error:", error);
        displayMessage("server", "There was an error getting a response.");
    });
}

// Function to display messages in the chat window
function displayMessage(sender, message) {
    const messagesContainer = document.querySelector('.messages');

    // Create a new message element
    const messageElement = document.createElement('div');
    messageElement.classList.add('message', sender === "user" ? 'sent' : 'received');
    messageElement.innerHTML = `<p>${message}</p>`;

    // Add the message to the container
    messagesContainer.appendChild(messageElement);

    // Scroll to the latest message
    messagesContainer.scrollTop = messagesContainer.scrollHeight;
}

// Handle form submission
document.querySelector('.chat-form').addEventListener('submit', function(event) {
    event.preventDefault();
    sendMessage();
});
