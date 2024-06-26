<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Chat</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
<div id="app">
    <div id="messages"></div>
    <input id="messageInput" type="text" placeholder="Type a message">
    <button id="sendButton">Send</button>
</div>
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.socket.io/4.0.0/socket.io.min.js"></script>
<script>
    const socket = io('http://localhost:3000');

    const messages = document.getElementById('messages');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');

    // Ambil pesan dari server
    fetch('/messages')
        .then(response => response.json())
        .then(data => {
            data.forEach(msg => {
                const item = document.createElement('div');
                item.textContent = `${msg.user.name}: ${msg.message}`;
                messages.appendChild(item);
            });
        });

    socket.on('chat message', (msg) => {
        const item = document.createElement('div');
        item.textContent = `${msg.user.name}: ${msg.message}`;
        messages.appendChild(item);
        window.scrollTo(0, document.body.scrollHeight);
    });

    sendButton.addEventListener('click', () => {
        const message = messageInput.value;
        socket.emit('chat message', { message });
        messageInput.value = '';

        // Kirim pesan ke server Laravel
        fetch('/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message })
        });
    });

    messageInput.addEventListener('keyup', (event) => {
        if (event.key === 'Enter') {
            sendButton.click();
        }
    });
</script>
</body>
</html>
