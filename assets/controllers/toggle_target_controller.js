import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['leftHit', 'leftTarget', 'rightHit', 'rightTarget'];

    connect() {
        console.log('Targets controller connected');
    }
    toggle(event) {
        const index = event.currentTarget.dataset.index;
        const checkbox = event.currentTarget;
        const targetL = this.leftTargetTargets[index];
        const targetR = this.rightTargetTargets[index];
        targetL.disabled = !checkbox.checked;
        targetR.disabled = !checkbox.checked;
    }

}
