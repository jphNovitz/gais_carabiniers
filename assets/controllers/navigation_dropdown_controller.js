import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu', 'toggle'];

    connect() {
        this.close();
    }

    toggle(event) {
        event.preventDefault();

        this.isOpen ? this.close() : this.open();
    }

    closeOnOutsideClick(event) {
        if (!this.element.contains(event.target)) {
            this.close();
        }
    }

    close() {
        this.menuTarget.classList.add('hidden');
        this.menuTarget.setAttribute('aria-hidden', 'true');
        this.toggleTarget.setAttribute('aria-expanded', 'false');
    }

    open() {
        this.menuTarget.classList.remove('hidden');
        this.menuTarget.setAttribute('aria-hidden', 'false');
        this.toggleTarget.setAttribute('aria-expanded', 'true');
    }

    get isOpen() {
        return !this.menuTarget.classList.contains('hidden');
    }
}
