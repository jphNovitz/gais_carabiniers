import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = { url: String }

    toggle() {
        alert()
        fetch(this.urlValue, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.element.classList.toggle('active', data.isActive);
                } else {
                    console.error('Échec du toggle', data);
                }
            })
            .catch(error => console.error('Erreur AJAX :', error));
    }
}
