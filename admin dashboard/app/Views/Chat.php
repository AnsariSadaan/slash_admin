<div class="h-screen flex bg-gray-100">
    <nav class="w-80 bg-white border-r border-gray-200 flex-none">
        <div class="p-4 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-800">Users</h1>
        </div>
        <div class="overflow-y-auto h-[calc(100vh-73px)]">
            <?php $loggedInUser = session()->get('user')->email;
            foreach ($users as $row) {
                // PRINT_R($users); DIE;
                if ($row->email === $loggedInUser) {
                    continue;
                } ?>
                <div class="contact hover:bg-gray-50 p-4 cursor-pointer border-b border-gray-100 flex items-center space-x-4" onclick="selectReceiver('<?php echo $row->email; ?>', '<?php echo $row->name; ?>')">
                    <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde" alt="Contact 1" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-800"><?php echo $row->name;?></h3>
                        <p class="text-sm text-gray-500 truncate">this is message</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </nav>

    <main class="flex-1 flex flex-col">
    <header class="bg-white border-b border-gray-200 p-4 flex items-center space-x-4">
        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde" 
            alt="Current chat" 
            class="w-12 h-12 rounded-full object-cover" 
            id="currentUserImage" 
            style="display: none;">
        <div class="flex-1">
            <h2 class="font-medium text-gray-800" id="currentUserName">Select a user to start chatting</h2>
            <div class="flex items-center space-x-2" id="userStatus" style="display: none;">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                <span class="text-sm text-gray-500">Online</span>
            </div>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="messages">
        <!-- Default message -->
        <p class="text-gray-500 text-center">No user selected. Please click on a user to view chat.</p>
    </div>

    <div class="bg-white border-t border-gray-200 p-4" id="messageInputContainer" style="display: none;">
        <div class="flex space-x-4 items-center">
            <input type="text" id="message" placeholder="Type a message..."
                class="flex-1 rounded-full border border-gray-300 px-4 py-2 focus:outline-none focus:border-blue-500" 
                aria-label="Type a message">
            <button class="bg-blue-500 text-white rounded-full p-3 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" 
                    onclick="sendMessage()">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
</main>

</div>

<!-- --------------------------------------------script--------------------------------------------------------------------- -->
<script src="https://cdn.socket.io/4.8.1/socket.io.min.js" integrity="sha384-mkQ3/7FUtcGyoppY6bz/PORYoGqOl7/aSUMn2ymDOJcapfS6PHqxhRTMh1RR0Q6+" crossorigin="anonymous"></script>
<script>
    const socket = io("http://localhost:3000");

    async function socketConnection() {
        try {
            //---------------------fetch--stored--message--from--db------------------------------------------
            const result = await fetch('http://localhost:3000/api/get-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json;charset=utf-8'
                },
                body: JSON.stringify({
                    email: "<?= session()->get('user')->email ?>",
                    name: "<?= session()->get('user')->name ?>"
                })
            });

            const message = await result.json();
            console.log(message);
            const messagesDiv = document.getElementById('messages');
            message.messages.forEach((message) => {
                let messageElement = document.createElement('div');
                messageElement.className = message.email === "<?= session()->get('user')->email ?>" ?
                    'flex items-end justify-end space-x-2' :
                    'flex items-start space-x-2';
                messageElement.innerHTML = `<div class="max-w-md ${message.email === "<?= session()->get('user')->email ?>" ? 'bg-green-200' : 'bg-white'} rounded-lg p-3 shadow-sm">
                                            <p class="text-gray-800 text-pretty font-bold">${message.name}</p>
                                            <p class="text-gray-800 text-pretty">${message.message}</p>
                                            <span class="text-xs text-gray-500">${new Date(message.timestamp).toLocaleTimeString()}</span>
                                        </div>`;
                messagesDiv.appendChild(messageElement);
                messagesDiv.scrollTop = messagesDiv.scrollHeight;
            });

            socket.emit('join', {
                email: "<?= session()->get('user')->email ?>", name: "<?= session()->get('user')->name ?>"
            });

        } catch (err) {
            console.log(err);
        }
    }
    socketConnection();


    socket.on('receive_message', (data) => {
        const messagesDiv = document.getElementById('messages');
        const messageElement = document.createElement('div');
        messageElement.className = 'flex items-start space-x-2';
        messageElement.innerHTML = `<div class="max-w-md bg-white rounded-lg p-3 shadow-sm">
                                    <p class="text-gray-800 text-pretty font-bold">${data.sender}</p>
                                    <p class="text-gray-800">${data.message}</p>
                                    <span class="text-xs text-gray-500">${new Date(data.timestamp).toLocaleTimeString()}</span>
                                </div>`;
        messagesDiv.appendChild(messageElement);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    });



    let selectedReceiver = null; // Declare selectedReceiver in a broader scope

    function selectReceiver(email, name) {
    selectedReceiver = email; // Set selectedReceiver when a user is clicked
    document.getElementById('currentUserName').innerText = name; // Update the header with the selected user's name
    document.getElementById('currentUserImage').style.display = "block"; // Show user image
    document.getElementById('userStatus').style.display = "block"; // Show online status
    document.getElementById('messageInputContainer').style.display = "block"; // Enable message input
    
    // Clear previous messages
    const messagesDiv = document.getElementById('messages');
    messagesDiv.innerHTML = "<p class='text-gray-500 text-center'>Loading messages...</p>";

    // Fetch and display the chat messages
    fetchMessages(email);
}

async function fetchMessages(receiver) {
    const sender = "<?= session()->get('user')->email ?>";

    try {
        const response = await fetch('http://localhost:3000/api/get-message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json;charset=utf-8' },
            body: JSON.stringify({ sender, receiver }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        const messagesDiv = document.getElementById('messages');
        messagesDiv.innerHTML = ""; // Clear loading message

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach((msg) => {
                const isSender = msg.sender === sender;
                const messageElement = document.createElement('div');
                messageElement.className = `flex items-${isSender ? "end justify-end" : "start"} space-x-2`;
                messageElement.innerHTML = `<div class="max-w-md ${isSender ? "bg-green-200" : "bg-white"} rounded-lg p-3 shadow-sm">
                                                <p class="font-bold">${isSender ? "You" : msg.sender}</p>
                                                <p>${msg.message}</p>
                                                <span class="text-xs text-gray-500">${new Date(msg.timestamp).toLocaleTimeString()}</span>
                                            </div>`;
                messagesDiv.appendChild(messageElement);
            });
        } else {
            messagesDiv.innerHTML = "<p class='text-gray-500 text-center'>No messages found. Start a conversation!</p>";
        }
    } catch (err) {
        console.error("Error fetching messages:", err);
    }
}


    function sendMessage() {
    const messageInput = document.getElementById('message');
    const message = messageInput.value.trim();

    if (!message) {
        alert("Please type a message.");
        return;
    }

    const sender = "<?= session()->get('user')->email ?>";
    const receiver = selectedReceiver;

    // Send message via API
    fetch('http://localhost:3000/api/send-message', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json;charset=utf-8' },
        body: JSON.stringify({ sender, receiver, message }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            // Check if placeholder exists and remove it
            const messagesDiv = document.getElementById('messages');
            const placeholder = messagesDiv.querySelector('.text-center');
            if (placeholder) {
                messagesDiv.innerHTML = ""; // Clear placeholder
            }

            // Append the new message
            const messageElement = document.createElement('div');
            messageElement.className = `flex items-end justify-end space-x-2`;
            messageElement.innerHTML = `
                <div class="max-w-md bg-green-200 rounded-lg p-3 shadow-sm">
                    <p class="font-bold">You</p>
                    <p>${message}</p>
                    <span class="text-xs text-gray-500">${new Date().toLocaleTimeString()}</span>
                </div>`;
            messagesDiv.appendChild(messageElement);

            // Clear the input field
            messageInput.value = "";
            messageInput.focus();

            // Scroll to the latest message
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        })
        .catch((err) => {
            console.error("Error sending message:", err);
        });
}


async function fetchMessages(receiver) {
    const sender = "<?= session()->get('user')->email ?>";

    try {
        const response = await fetch('http://localhost:3000/api/get-message', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json;charset=utf-8' },
            body: JSON.stringify({ sender, receiver }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();

        const messagesDiv = document.getElementById('messages');
        messagesDiv.innerHTML = ""; // Clear loading message

        if (data.messages && data.messages.length > 0) {
            data.messages.forEach((msg) => {
                const isSender = msg.sender === sender;
                const messageElement = document.createElement('div');
                messageElement.className = `flex items-${isSender ? "end justify-end" : "start"} space-x-2`;
                messageElement.innerHTML = `<div class="max-w-md ${isSender ? "bg-green-200" : "bg-white"} rounded-lg p-3 shadow-sm">
                                                <p class="font-bold">${isSender ? "You" : msg.sender}</p>
                                                <p>${msg.message}</p>
                                                <span class="text-xs text-gray-500">${new Date(msg.timestamp).toLocaleTimeString()}</span>
                                            </div>`;
                messagesDiv.appendChild(messageElement);
            });
        } else {
            messagesDiv.innerHTML = "<p class='text-gray-500 text-center'>No messages found. Start a conversation!</p>";
        }
    } catch (err) {
        console.error("Error fetching messages:", err);
    }
}
</script>