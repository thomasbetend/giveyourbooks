import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'notif', 'conversationId' ]

    connect() {
        let conversationId = this.conversationIdTarget.textContent;

        setInterval(() => {
            fetch('/messages_in_conversation_read/' + conversationId)
                .then((response) => response.json())
                .then((data) => {
                    console.log(data.arelMessagesInConversationRead);
                    if (data.arelMessagesInConversationRead === false) {
                        this.notifTarget.classList.add('red-notif-message');
                        this.notifTarget.textContent = data.totalMessagesNotReadInConversation;
                    }
                });
        }, 10000);
    }
}