import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['leftHit', 'leftTarget', 'rightHit', 'rightTarget'];
    static values = {
        lastLeft: Number,
        lastRight: Number
    }
    toggleLeft(event) {
        const index = parseInt(event.currentTarget.dataset.index);
        const checkbox = event.currentTarget;
        const target = this.leftTargetTargets[index];

        target.disabled = !checkbox.checked;
        this.recalculate(this.lastLeftValue, this.leftHitTargets, this.leftTargetTargets);

    }

    toggleRight(event) {
        const index = parseInt(event.currentTarget.dataset.index);
        const checkbox = event.currentTarget;
        const target = this.rightTargetTargets[index];

        target.disabled = !checkbox.checked;
        this.recalculate(this.lastRightValue, this.rightHitTargets, this.rightTargetTargets);
    }

    recalculate(base, hitTargets, targets) {
        hitTargets.forEach((hit, index) => {
            if (hit.checked) {
                base++
                if (base > 24) base = 1
                targets[index].value = base
            } else {
                targets[index].value = ""
            }
        })
    }
}
