import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
    static targets = [ "plus" ]

    increment() {
        let count = 1;
        this.plusTarget.textContent += count;
    }

    decrement() {
        let count = 1;
        this.plusTarget.textContent -= count;
    }
}