const proveedors = document.getElementById('proveedors');

if (proveedors) {
    proveedors.addEventListener('click', e => {
        if (e.target.className === 'btn btn-danger delete-proveedor') {
            if (confirm('Estàs seguro que lo quieres borrar?')) {
                const id = e.target.getAttribute('data-id');

                fetch(`/proveedor/borrar/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}