<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Dynamic UI</title>
    <style>
        #chat-container {
            width: 70%;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            font-family: Arial, sans-serif;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #chat-history {
            height: 400px;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
        }

        .message-container {
            margin: 10px 0;
        }

        .user .message {
            text-align: right;
            background-color: #e7f1ff;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            max-width: 70%;
        }

        .assistant .message {
            text-align: left;
            background-color: #e9fbe7;
            padding: 10px;
            border-radius: 8px;
            display: inline-block;
            max-width: 70%;
        }

        #chat-input {
            display: flex;
            padding: 10px;
            background-color: #fff;
        }

        #chat-input input {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        #chat-input button {
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #chat-input button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center; color: #0f9d58;">ChatGPT Dynamic UI</h1>
    <div id="chat-container">
        <div id="chat-history">
            <!-- Chat messages will appear here dynamically -->
        </div>
        <div id="chat-input">
            <input type="text" id="user-message" placeholder="Type your message..." />
            <button id="send-button">Send</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const chatHistory = document.getElementById('chat-history');
        const userMessageInput = document.getElementById('user-message');
        const sendButton = document.getElementById('send-button');

        sendButton.addEventListener('click', async () => {
            const userMessage = userMessageInput.value.trim();

            if (!userMessage) {
                alert('Please type a message!');
                return;
            }

            // Add user message to the chat
            addMessageToChat('user', userMessage);

            try {
                // Simulating API call for response
               const response = await fakeAPICall(userMessage);

                // Add assistant's reply to the chat
                addMessageToChat('assistant', response);

            } catch (error) {
                console.error('Error:', error);
                addMessageToChat('assistant', 'Failed to fetch response.');
            }

            // Clear input field
            userMessageInput.value = '';
        });

        function addMessageToChat(role, message) {
            const messageContainer = document.createElement('div');
            messageContainer.classList.add(role, 'message-container');

            const messageBubble = document.createElement('div');
            messageBubble.classList.add('message');
            messageBubble.textContent = message;

            messageContainer.appendChild(messageBubble);
            chatHistory.appendChild(messageContainer);

            // Scroll to the bottom of the chat history
            chatHistory.scrollTop = chatHistory.scrollHeight;
        }

        // Mock API call for dynamic responses
        async function fakeAPICall(userInput) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(`You said: "${userInput}". Here's a response from ChatGPT.`);
                }, 1000);
            });
        }
    </script>
</body>
</html>
