import './bootstrap';
import { initUi } from './ui';
import { initLocationModal, initMapPickers, initStaticMaps } from './maps';
import { initCart } from './cart';
import { initListingForm } from './listings';
import { initChat } from './chat';

document.addEventListener('DOMContentLoaded', () => {
    initUi();
    initLocationModal();
    initMapPickers();
    initStaticMaps();
    initCart();
    initListingForm();
    initChat();
});
