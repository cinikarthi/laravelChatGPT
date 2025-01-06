
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatGPT Integration</title>
    <style>
        /* Basic styling for the chat interface */
        #chat-container {
            width: 70%;
            margin: 20px auto;
            border: 1px solid #ccc;
            border-radius: 8px;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        #chat-history {
            height: 80%;
            overflow-y: auto;
            padding: 10px;
            background-color: #f9f9f9;
            border-bottom: 1px solid #ccc;
        }

        #chat-history .user {
            text-align: right;
            color: blue;
            margin: 5px 0;
        }

        #chat-history .assistant {
            text-align: left;
            color: green;
            margin: 5px 0;
        }

        #chat-history .error {
            text-align: center;
            color: red;
            margin: 5px 0;
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

         body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: #0f9d58;
            text-align: center;
        }

        .scrollbox {
            background-color: #fff;
            border: 2px solid #0f9d58;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            width: 100%;
            height: 200px;
            overflow-y: auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Scrollbar Styles */
        .scrollbox::-webkit-scrollbar {
            width: 12px;
        }

        .scrollbox::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 8px;
        }

        .scrollbox::-webkit-scrollbar-thumb {
            background: #0f9d58;
            border-radius: 8px;
        }

        .scrollbox::-webkit-scrollbar-thumb:hover {
            background: #0b8043;
        }

        /* For Firefox */
        .scrollbox {
            scrollbar-width: thin;
            scrollbar-color: #0f9d58 #f1f1f1;
        }

        @media screen and (max-width: 600px) {
            .scrollbox {
                height: 150px;
            }
        }
        #heading{
        text-align: center;
        }
    </style>
</head>
<body>
<h4 id="heading">chatGPT Integration</h4>
    <div id="chat-container">
    
        <div id="chat-history">
            <!-- Chat messages will appear here -->
        </div>
        <div id="chat-input">
            <input type="text" id="user-message" placeholder="Type your message..." />
            <button id="send-button">Send</button>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // References to UI elements
        const chatHistory = document.getElementById('chat-history');
        const userMessageInput = document.getElementById('user-message');
        const sendButton = document.getElementById('send-button');

        // Add event listener to the Send button
        sendButton.addEventListener('click', async function () {
            const userMessage = userMessageInput.value.trim();

            if (!userMessage) {
                alert('Please type a message!');
                return;
            }

            // Display the user's message in the chat history
            addMessageToChat('user', userMessage);

            // Prepare the payload for ChatGPT
            const messages = [
                // { role: 'system', content: 'You are a helpful assistant.' },
                { role: 'user', content: userMessage }
            ];

            try {
                // Send the user's message to the Laravel backend
                const response = await axios.post('/api/chat', { messages });

                // Extract ChatGPT's response and display it
                const botReply = response.data.choices[0].message.content;
                addMessageToChat('assistant', botReply);
                // chatDispaly();

            } catch (error) {
                console.error('Error:', error);
                addMessageToChat('error', 'Failed to get a response from ChatGPT.');
                chatDispaly();
            }
            // Clear the input field
            userMessageInput.value = '';
        });

     

        function addMessageToChat(role, message) {
    const messageContainer = document.createElement('div');
    messageContainer.classList.add(role, 'message-container');

    const messageContent = document.createElement('div');
    messageContent.classList.add('message');

    // Check if the message is from the assistant to allow HTML rendering
    if (role === 'assistant') {
        messageContent.innerHTML = formatMessage(message);
    } else {
        messageContent.textContent = message;
    }

    messageContainer.appendChild(messageContent);
    chatHistory.appendChild(messageContainer);

    // Scroll to the bottom of the chat history
    chatHistory.scrollTop = chatHistory.scrollHeight;
}

function formatMessage(message) {
    // Replace markdown-like syntax with HTML tags
    return message
        .replace(/^###\s(.+)/gm, '<h3>$1</h3>') // Convert ### to h3
        .replace(/^##\s(.+)/gm, '<h2>$1</h2>') // Convert ## to h2
        .replace(/^#\s(.+)/gm, '<h1>$1</h1>')  // Convert # to h1
        .replace(/```([^`]+)```/gs, '<pre><code>$1</code></pre>') // Convert code blocks
        .replace(/`([^`]+)`/g, '<code>$1</code>') // Convert inline code
        .replace(/^- (.+)/gm, '<li>$1</li>') // Convert lists
        .replace(/<\/li>\s<li>/g, '</li><li>') // Ensure list items are nested correctly
        .replace(/(?:\r\n|\r|\n)/g, '<br>'); // Convert new lines to <br>
}

        
    </script>
</body>
</html>
