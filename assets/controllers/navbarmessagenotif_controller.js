import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ "notif" ]

    connect() {

        setInterval(() => {
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
        }, 2000);
    }
}