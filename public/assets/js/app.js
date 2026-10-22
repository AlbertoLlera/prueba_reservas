document.addEventListener('DOMContentLoaded', () => {
    // 1. Lógica para el formulario de CREAR RESERVA
    const formCrear = document.getElementById('form-crear-reserva');
    
    if (formCrear) {
        formCrear.addEventListener('submit', async (e) => {
            e.preventDefault();

            const errorDiv = document.getElementById('mensaje-error');
            const errorTitulo = document.getElementById('error-titulo');
            const errorLista = document.getElementById('error-lista');
            
            // Limpiamos errores anteriores
            errorDiv.style.display = 'none';
            errorTitulo.textContent = '';
            errorLista.innerHTML = ''; // Vaciar la lista (esto es seguro, no es HTML)

            const formData = new FormData(formCrear);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('/reservas_api/public/api/reservas', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const json = await response.json();

                if (!response.ok) {
                    if (response.status === 400 && json.details) {
                        // Si es error de validación, rellenamos la lista
                        errorTitulo.textContent = 'Por favor, corrige los siguientes errores:';
                        for (const [campo, mensaje] of Object.entries(json.details)) {
                            const li = document.createElement('li');
                            li.textContent = `${campo}: ${mensaje}`; // textContent, no innerHTML
                            errorLista.appendChild(li);
                        }
                    } else {
                        // Error genérico
                        errorTitulo.textContent = json.error || 'Error al crear la reserva.';
                    }
                    errorDiv.style.display = 'block';
                    return;
                }

                // Éxito: redirigimos
                window.location.href = '/reservas_api/public/reservas';

            } catch (error) {
                errorTitulo.textContent = 'Error de conexión con el servidor.';
                errorDiv.style.display = 'block';
            }
        });
    }

    // 2. Lógica para CAMBIAR ESTADO
    const botonesEstado = document.querySelectorAll('.btn-cambiar-estado');
    
    botonesEstado.forEach(boton => {
        boton.addEventListener('click', async (e) => {
            const btn = e.currentTarget;
            const id = btn.dataset.id;
            const nuevoEstado = btn.dataset.estado;

            const textoOriginal = btn.textContent;
            btn.disabled = true;
            btn.textContent = '...';

            try {
                const response = await fetch(`/reservas_api/public/api/reservas/${id}/estado`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ estado: nuevoEstado })
                });

                const json = await response.json();

                if (!response.ok) {
                    alert(json.error || 'No se pudo cambiar el estado.');
                    btn.disabled = false;
                    btn.textContent = textoOriginal;
                    return;
                }

                window.location.reload();

            } catch (error) {
                alert('Error de conexión.');
                btn.disabled = false;
                btn.textContent = textoOriginal;
            }
        });
    });
});