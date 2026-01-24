import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['leftHit', 'leftTarget', 'rightHit', 'rightTarget'];

    connect() {
        console.log('Targets controller connected');
    }

    toggleLeft(event) {
        const index = event.currentTarget.dataset.index;
        const checkbox = event.currentTarget;
        const target = this.leftTargetTargets[index];

        target.disabled = !checkbox.checked;
    }

    toggleRight(event) {
        const index = event.currentTarget.dataset.index;
        const checkbox = event.currentTarget;
        const target = this.rightTargetTargets[index];

        target.disabled = !checkbox.checked;
    }
}