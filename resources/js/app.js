import './bootstrap';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

function initFlatpickr() {
    const el = document.getElementById('transfer_time');
    if (el) {
        flatpickr(el, {
            enableTime: true,
            time_24hr: true,
            dateFormat: 'Y-m-d H:i',
        });
    }
}

document.addEventListener('DOMContentLoaded', initFlatpickr);
document.addEventListener('livewire:navigated', initFlatpickr);
