import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['output'];
    static values = {
        toto: String,
        startTime: String,
    };

    connect() {
        const startTime = new Date(this.startTimeValue);
        this.updateTimer(startTime);

        this.timerInterval = setInterval(() => this.updateTimer(startTime), 1000);
    }

    disconnect() {
        clearInterval(this.timerInterval);
    }

    updateTimer(startTime) {
        const now = new Date();
        let diff = Math.floor((now - startTime) / 1000);
        diff = Math.abs(diff);

        const days = Math.floor(diff / 86400);
        diff -= days * 86400;
        const hours = Math.floor(diff / 3600);
        diff -= hours * 3600;
        const minutes = Math.floor(diff / 60);
        const seconds = diff % 60;

        this.outputTarget.textContent = `Dans ${days} jour(s) ${hours}h ${minutes}m ${seconds}s`;
    }
}
