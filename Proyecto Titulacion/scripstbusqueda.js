document.getElementById("buscar-sede-button").addEventListener("click", function () {
    const nombreSede = document.getElementById("buscar-nombre-sede").value.trim();

    if (!nombreSede) {
        alert("Por favor, ingrese el nombre de una sede.");
        return;
    }

    fetch("buscar_sede.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `nombre_sede=${encodeURIComponent(nombreSede)}`,
    })
        .then((response) => response.json())
        .then((data) => {
            const resultados = document.getElementById("resultados");
            resultados.innerHTML = "";

            if (data.error) {
                resultados.innerHTML = `<p style="color: red;">${data.error}</p>`;
            } else if (data.length > 0) {
                let html = `
                    <h2>Resultados de la Búsqueda:</h2>
                    <table border="1">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Dirección</th>
                                <th>Director</th>
                                <th>Secretaria</th>
                                <th>Horario</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data
                                .map(
                                    (sede) => `
                            <tr>
                                <td>${sede.nombre_sede}</td>
                                <td>${sede.direccion_sede}</td>
                                <td>${sede.director_sede}</td>
                                <td>${sede.secretaria_sede}</td>
                                <td>${sede.horario_sede}</td>
                            </tr>`
                                )
                                .join("")}
                        </tbody>
                    </table>
                `;
                resultados.innerHTML = html;
            } else {
                resultados.innerHTML = `<p>No se encontraron resultados para "${nombreSede}".</p>`;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Hubo un problema al realizar la búsqueda. Intente de nuevo.");
        });
});
