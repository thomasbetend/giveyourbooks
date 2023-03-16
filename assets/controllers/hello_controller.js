import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {

    static targets = [
        'input', 'result'
    ];

    connect() {
        console.log('youhou');

        const eventSource = new EventSource("{{ mercure('https://giveyourboox.com/message')|escape('js') }}");
        eventSource.onmessage = event => {
            // Will be called every time an update is published by the server
            console.log(JSON.parse(event.data));
        }
    }

}
