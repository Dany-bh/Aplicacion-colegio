document.addEventListener('DOMContentLoaded', function() {
    const grupoSelect = document.getElementById('grupo_id');
    const infoAulaDiv = document.getElementById('info-aula');
    const infoDocenteSpan = document.getElementById('info-docente');
    const infoCursosSpan = document.getElementById('info-cursos');
    const infoVacantesSpan = document.getElementById('info-vacantes');

    if (grupoSelect) {
        grupoSelect.addEventListener('change', function() {
            const grupoId = this.value;

            if (grupoId) {
                // Hacemos la petición a nuestra API
                fetch(`index.php?controller=api&action=getGrupoDetails&id=${grupoId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.error) {
                            console.error(data.error);
                            infoAulaDiv.style.display = 'none';
                            return;
                        }

                        // Actualizamos el DOM con la información recibida
                        infoDocenteSpan.textContent = data.docente;
                        infoCursosSpan.textContent = data.cursos.join(', ') || 'No hay cursos asignados';
                        infoVacantesSpan.textContent = data.vacantes;

                        // Hacemos visible el div de información
                        infoAulaDiv.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error al obtener los detalles del aula:', error);
                        infoAulaDiv.style.display = 'none';
                    });
            } else {
                // Si el usuario deselecciona, ocultamos el div
                infoAulaDiv.style.display = 'none';
            }
        });
    }
});
