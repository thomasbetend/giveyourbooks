import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'message', 'messageContent', 'messageDate', 'conversationId' ]

    connect() {

       // console.log('youhou');
        //this.messagesTarget.innerHTML = 'coucou';
        let conversationId = this.conversationIdTarget.textContent;

        fetch('/new_message_in_conversation/' + conversationId)
        .then((response) => response.json())
        .then((data) => {
            console.log(data.newMessagesToPass);
        })

        setInterval(() => {
            fetch('/new_message_in_conversation/' + conversationId)
            .then((response) => response.json())
            .then((data) => {
                    let newMessage = data.newMessagesToPass[0]
                    let newMessageContent = newMessage[0];
                    let newMessageDate = newMessage[1].date;

                    this.messageContentTarget.style.display = 'block';
                    this.messageContentTarget.textContent = newMessageContent;
                    this.messageDateTarget.textContent = newMessageDate;
    
                    console.log(newMessageContent, newMessageDate);
            })
        }, 500);
    }
}