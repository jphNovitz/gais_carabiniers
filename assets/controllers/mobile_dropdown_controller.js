import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['menu', 'checkbox'];

    connect() {
        this.close();
    }

    toggle(event) {
        this.menuTarget.classList.toggle('-translate-y-full', !event.currentTarget.checked);
    }

    close() {
        if (this.hasCheckboxTarget) {
            this.checkboxTarget.checked = false;
        }
        this.menuTarget.classList.add('-translate-y-full');
    }
}
