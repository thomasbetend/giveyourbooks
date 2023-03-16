import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ 'notif', 'userId' ]

    static values = {
        mercure: String,
    }

    connect() {

        this.fetchAllMessages();

        const eventSource = new EventSource(this.mercureValue);
        eventSource.onmessage = event => {

            const result = JSON.parse(event.data);

            console.log('navbar notif', result.content, result.userId, result.userDestinationId, this.userIdTarget.textContent);

            if (result.userDestinationId == this.userIdTarget.textContent) {
                this.fetchAllMessages();
            }

            if (result.content == 'conversation' && result.userId == this.userIdTarget.textContent) {
                this.fetchAllMessages();
                console.log('coucou navbar');
            }
        }
    }

    fetchAllMessages() {
        fetch('/all_messages_read')
        .then((response) => response.json())
        .then((data) => {
            if (data.areAllMessagesRead === false) {
                this.notifTarget.style.display = 'flex';
                this.notifTarget.classList.add('red-notif-message');
                this.notifTarget.textContent = data.totalMessages;
            }

            if (data.areAllMessagesRead === true) {
                this.notifTarget.style.display = 'none';
            }
        });
    }
}