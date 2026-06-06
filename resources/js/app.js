import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/js/all';

window.Swal = Swal;

Swal.setDefaults({
    confirmButtonColor: '#556F3E',
    cancelButtonColor: '#6b7280',
});

document.addEventListener('livewire:init', () => {
    Livewire.on('swal', (data) => {
        Swal.fire(data[0]);
    });

    Livewire.on('confirm', (data) => {
        Swal.fire({
            title: data[0].title || '¿Estás seguro?',
            text: data[0].text || '',
            icon: data[0].icon || 'warning',
            showCancelButton: true,
            confirmButtonText: data[0].confirmText || 'Sí, confirmar',
            cancelButtonText: data[0].cancelText || 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed && data[0].callback) {
                Livewire.dispatch(data[0].callback, data[0].params || {});
            }
        });
    });
});
