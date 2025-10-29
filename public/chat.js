// console.log("Chat script loaded.");
// document.addEventListener("DOMContentLoaded", function() {


//     fetch("/online",
//         {
//             method: "POST",
//             headers:{
//                 "Content-Type":"application/json",
//                 "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
//             }
//         }
//     )


//     window.Echo.private("chat."+ senderId)
//     .listen("MessageEvent",(e)=>{
//         console.log("Message received:", e.message);
//     });

//     window.Echo.private("typing."+ ReceiverId)
//     .listen("MessageEvent",(e)=>{
//         console.log("Message received:", e.message);
//     });

//     window.Echo.private("channel."+ ReceiverId)
//     .listen("MessageEvent",(e)=>{
//         console.log("Message received:", e.message);
//     });


//     let message_form = document.getElementById("message_form");

//     if(message_form){
//         message_form.addEventListener("submit", function(event){
//             event.preventDefault();

//             let formData = new FormData(message_form);
//             let message = formData.get("message");

//             fetch("/chat/{receiverId}/send", {
//                 method: "POST",
//                 headers: {
//                     "Content-Type": "application/json",
//                     "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
//                 },
//                 body: JSON.stringify({
//                     message: message,
//                 })
//             })
//             .then(response => response.json())
//             .then(data => {
//                 console.log("Message sent:", data);
//                 message_form.reset();
//             })
//             .catch(error => {
//                 console.error("Error sending message:", error);
//             });
//         });
//     }



// });