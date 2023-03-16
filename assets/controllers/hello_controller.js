import { Controller } from '@hotwired/stimulus';
export default class extends Controller {

    static values = {
        mercure: String,
    }

    static targets = [
        'text'
    ]

    connect() {
        const eventSource = new EventSource(this.mercureValue);
        eventSource.onmessage = event => {
            console.log(JSON.parse(event.data));
        }
    }

}
