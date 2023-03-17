import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'message', 'messageContent', 'messageDate', 'conversationId' ]

    static values = {
        mercure: String,
    }

    connect() {

        console.log('controller newmessage');

        const eventSource = new EventSource(this.mercureValue);
        eventSource.onmessage = event => {

            const result = JSON.parse(event.data);

            console.log('message', result);

            this.fetchMessagesInConversation(result.conversationId);
        }   
    }

    fetchMessagesInConversation(conversationId) {
        
        fetch('/new_message_in_conversation/' + conversationId)
            .then((response) => response.json())
            .then((data) => {
                console.log('data', data);

                if (!data.newMessagesToPass[0]) {
                    return;
                }

                data.newMessagesToPass.forEach(message => {
                    let newMessageContent = message[0];
                    let newMessageDate = message[1].date;

                    let messageHtml = "<div class='d-flex'>\
                                        <div class='col-6'>\
                                            <div class='card text-right p-2 m-1 message-left'>" + newMessageContent + "</div>\
                                            <p class='px-3 text-detail' data-newmessage-target='messageDate'>" + this.formatDateForMessage(newMessageDate) + "</p>\
                                        </div>\
                                    </div>";
                
                    const div = document.createElement("div");
                    console.log({'message content':newMessageContent}, this.messageTarget, div);
                    this.messageTarget.prepend(div);
                    div.innerHTML = messageHtml;
                    
                });
                
            })
    }

    formatDateForMessage(dateMessage) {
        let date = new Date(dateMessage);
        
        return date.toLocaleDateString('fr') + ' ' + date.getHours() + ':' + date.getUTCMinutes();
    }
}