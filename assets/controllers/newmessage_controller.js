import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'message', 'messageContent', 'messageDate', 'conversationId' ]

    connect() {

        let conversationId = this.conversationIdTarget.textContent;

        setInterval(() => {
            fetch('/new_message_in_conversation/' + conversationId)
            .then((response) => response.json())
            .then((data) => {
                    let newMessage = data.newMessagesToPass[0]
                    let newMessageContent = newMessage[0];
                    let newMessageDate = newMessage[1].date;


                    let messageHtml = "<div class='d-flex'>\
                                            <div class='col-6'>\
                                                <div class='card text-right p-2 m-1 message-left'>" + newMessageContent + "</div>\
                                                <p class='px-3 text-detail' data-newmessage-target='messageDate'>" + this.formatDateForMessage(newMessageDate) + "</p>\
                                            </div>\
                                        </div>";
                    
                    const div = document.createElement("div");
                    this.messageTarget.prepend(div);
                    div.innerHTML = messageHtml;

                    // console.log(newMessageContent, this.formatDateForMessage(newMessageDate));
            })
        }, 3000);
    }

    formatDateForMessage(dateMessage) {
        let date = new Date(dateMessage);
        
        return date.toLocaleDateString('fr') + ' ' + date.getHours() + ':' + date.getUTCMinutes();
    }
}