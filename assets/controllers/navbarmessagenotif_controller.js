import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ "notif" ]

    connect() {
        console.log('youhou');

        setInterval(() => {
            fetch('/all_messages_read')
                .then((response) => response.json())
                .then((data) => {
                    console.log(data.areAllMessagesRead);
                    if (data.areAllMessagesRead === false) {
                        this.notifTarget.classList.add('red-notif-message');
                    }
                });
        }, 3000)
    }
}