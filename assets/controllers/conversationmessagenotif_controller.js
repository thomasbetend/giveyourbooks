import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'notif', 'conversationId' ]

    static values = {
        mercure: String,
    }

    connect() {

        this.fetchMessagesInConversation(this.conversationIdTarget.textContent);

        const eventSource = new EventSource(this.mercureValue);
        eventSource.onmessage = event => {

            const result = JSON.parse(event.data);

            console.log('conversation notif', result, result.conversationId, this.conversationIdTarget.textContent);

            if (result.conversationId == this.conversationIdTarget.textContent) {
                this.fetchMessagesInConversation(result.conversationId);
            }
        }
    }

    fetchMessagesInConversation(conversationId) {
        fetch('/messages_in_conversation_read/' + conversationId)
        .then((response) => response.json())
        .then((data) => {
            console.log(data.arelMessagesInConversationRead);
            if (data.arelMessagesInConversationRead === false) {
                this.notifTarget.classList.add('red-notif-message');
                this.notifTarget.textContent = data.totalMessagesNotReadInConversation;
            }
        });
    }
}