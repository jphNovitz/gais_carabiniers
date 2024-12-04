/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
// import 'flowbite';
import * as Turbo from '@hotwired/turbo';
import './bootstrap.js';
import './styles/app.css';
import { shouldPerformTransition, performTransition } from 'turbo-view-transitions';
import { initFlowbite } from 'flowbite';
// import 'flowbite';




// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('turbo:before-render', (event) => {
    if (shouldPerformTransition()) {
        event.preventDefault();
        performTransition(document.body, event.detail.newBody, async () => {
            await event.detail.resume();
        });
    }
});
document.addEventListener('turbo:load', () => {
    // View Transitions don't play nicely with Turbo cache
    if (shouldPerformTransition()) Turbo.cache.exemptPageFromCache();
});

document.addEventListener('turbo:before-frame-render', (event) => {
    if (shouldPerformTransition()) {
        event.preventDefault();
        performTransition(event.target, event.detail.newFrame, async () => {
            await event.detail.resume();
        });
    }
});
document.addEventListener('turbo:render', () => {
    initFlowbite();
});
document.addEventListener('turbo:frame-render', () => {
    initFlowbite();
});