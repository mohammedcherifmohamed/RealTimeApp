<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Users</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(["resources/css/app.css", "resources/js/app.js"])
</head>
<body class="bg-gray-50 min-h-screen text-gray-800">
<div class="container mx-auto max-w-4xl">
    <ul id="online-users" class="bg-white shadow rounded p-3 mb-4">
        
    </ul>

    <div class="bg-white rounded-lg shadow-lg p-6 m-4">
        {{-- USER NAME --}}
        <h2 class="text-2xl font-semibold mb-4">Chat with {{$receiver->name}}</h2>

        <!-- Chat Messages Container -->
        <div class="h-96 overflow-y-auto mb-4 p-4 bg-gray-50 rounded-lg" id="chat-messages">
            <!-- Example Message - Left (Received) -->
            @forelse ($messages as $msg)
                @if ($msg->sender_id === auth()->id())
                    <div class="flex mb-4 justify-end">
                        <div class="mr-3 bg-blue-500 rounded-lg py-2 px-4">
                            <p class="text-sm text-white">{{ $msg->content }}</p>
                        </div>
                    </div>
                @else
                    <div class="flex mb-4">
                        <div class="flex-shrink-0">
                            {{-- <img class="h-10 w-10 rounded-full" src="https://via.placeholder.com/40" alt="User avatar"> --}}
                        </div>
                        <div class="ml-3 bg-gray-100 rounded-lg py-2 px-4">
                            <p class="text-sm text-gray-800">{{ $msg->content }}</p>
                        </div>
                    </div>
                @endif
                
            @empty
                <h1 id="no-msg" >No messages yet</h1>
            @endforelse
           
            
        </div>
        {{-- Typing indicator --}}
        <div id="typing-indicator" class="mb-4 text-gray-500 italic" style="display: none;">
            {{$receiver->name}} is typing...
        </div>
        <!-- Message Input Form -->
        <div class="border-t pt-4">
            <form id="message_form" class="flex space-x-4">
                @csrf
                <input type="text" 
                       class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       name="message"
                       id="text-input"
                       placeholder="Type your message...">
                <button type="submit" 
                        class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition duration-200">
                    Send
                </button>
            </form>
        </div>
    </div>
</div>
<script>
    const ReceiverId = {{$receiver->id}};
    const currentUserId = {{auth()->id()}};

</script>
<script>

document.addEventListener("DOMContentLoaded", function() {
    // console.log(window.Echo);
        let message_form = document.getElementById("message_form");
       const textInput = document.getElementById("text-input");

window.Echo.private(`chat.${currentUserId}`)
    .subscribed(() => console.log('Subscribed to private chat channel', `chat.${currentUserId}`))
    .error((err) => console.error('Private chat channel error', err))
    .listen("MessageEvent", (e) => {
        // console.log("Message received:", e.message);
        
        const chatMessages = document.getElementById("chat-messages");
        const messageDiv = document.createElement("div");
        const no_msg = document.getElementById("no-msg");
        if(no_msg){
            no_msg.remove();
        }
        messageDiv.classList.add("flex", "mb-4");
        messageDiv.innerHTML = `
            <div class="ml-3 bg-gray-100 rounded-lg py-2 px-4">
                <p class="text-sm text-gray-800">${e.message}</p>
            </div>
        `;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    })
    .listenForWhisper('typing', (e) => {
        console.log(`${e.name} is typing...`);
        showTypingIndicator(e.name);
    });
textInput.addEventListener('input', () => {
    window.Echo.private(`chat.${ReceiverId}`)
        .whisper('typing', {
            userId: currentUserId,
            name: '{{ auth()->user()->name }}',
        });
});


let typingTimeout;

function showTypingIndicator(name) {
    const indicator = document.getElementById('typing-indicator');
    indicator.textContent = `${name} is typing...`;
    indicator.style.display = 'block';

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
        indicator.style.display = 'none';
    }, 2000);
}

    if(message_form){
        message_form.addEventListener("submit", function(event){
            event.preventDefault();

            let formData = new FormData(message_form);
            let message = formData.get("message");

            console.log("Sending message:", message);

            fetch(`/chat/${ReceiverId}/send`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                },
                body: JSON.stringify({
                    message: message,
                })
            })
           
            const chatMessages = document.getElementById("chat-messages");
            const messageDiv = document.createElement("div");
            messageDiv.classList.add("flex", "mb-4", "justify-end");
            messageDiv.innerHTML = `
                <div class="mr-3 bg-blue-500 rounded-lg py-2 px-4">
                    <p class="text-sm text-white">${message}</p>
                </div>
            `;
             const no_msg = document.getElementById("no-msg");
        if(no_msg){
            no_msg.remove();
        }
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
        if (textInput) textInput.value = "";

               
        });
    }
// PRESENCE CHANNEL TO SHOW ONLINE USERS
window.Echo.join('presence.chat')
    .here((users) => {
        console.log("Currently online:", users);
        updateOnlineUsers(users);
    })
    .joining((user) => {
        console.log(user.name + " joined");
        addOnlineUser(user);
    })
    .leaving((user) => {
        console.log(user.name + " left");
        removeOnlineUser(user);
    })
    .error((err) => {
        console.error('Presence channel error:', err);
    });

function updateOnlineUsers(users) {
    const list = document.getElementById('online-users');
    list.innerHTML = '';
    users.forEach(u => {
        const li = document.createElement('li');
        li.id = `user-${u.id}`;
        li.className = "p-2 border-b text-green-600";
        li.textContent = u.name;
        list.appendChild(li);
    });
}

function addOnlineUser(user) {
    const list = document.getElementById('online-users');
    const li = document.createElement('li');
    li.id = `user-${user.id}`;
    li.className = "p-2 border-b text-green-600";
    li.textContent = user.name;
    list.appendChild(li);
}

function removeOnlineUser(user) {
    document.getElementById(`user-${user.id}`)?.remove();
}

});


</script>

</body>
</html>
