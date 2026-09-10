import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    closeOnOutsideClick(event) {
        if (!this.element.contains(event.target)) {
            this.close();
        }
    }

    close() {
        this.element.removeAttribute('open');
    }
}
