import Alpine from 'alpinejs';
import penilaianForm, { rowKI3, rowKI4 } from './penilaian';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.data('penilaianForm', penilaianForm);
    Alpine.data('rowKI3', rowKI3);
    Alpine.data('rowKI4', rowKI4);
});

Alpine.start();