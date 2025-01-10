// Función para generar PDF
function generarPDF(trabajador) {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    pdf.setFontSize(16);
    pdf.text("Información del Trabajador", 20, 20);

    pdf.setFontSize(12);
    pdf.text(`Nombre: ${trabajador.nombre_completo}`, 20, 40);
    pdf.text(`RUT: ${trabajador.rut}`, 20, 50);
    pdf.text(`Cargo: ${trabajador.cargo}`, 20, 60);
    pdf.text(`Horario: ${trabajador.horario}`, 20, 70);
    pdf.text(`Sede: ${trabajador.sede}`, 20, 80);
    pdf.text(`Sueldo: ${trabajador.sueldo}`, 20, 90);
    pdf.text(`Tipo de Contrato: ${trabajador.tipo_contrato}`, 20, 100);
    pdf.text(`Status: ${trabajador.status}`, 20, 110);

    pdf.save(`${trabajador.nombre_completo.replace(/\s+/g, "_")}_info.pdf`);
}

// Función para buscar un trabajador por RUT desde la base de datos
function buscarTrabajador() {
    const rutIngresado = document.getElementById("rut").value.trim();

    if (!rutIngresado) {
        alert("Por favor, ingrese un RUT.");
        return;
    }

    console.log(`Enviando RUT: ${rutIngresado}`); // Log para verificar el RUT enviado

    fetch("buscar_trabajador.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `rut=${encodeURIComponent(rutIngresado)}`,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor.");
            }
            return response.json();
        })
        .then((data) => {
            const resultContainer = document.getElementById("result-container");
            resultContainer.innerHTML = "";

            if (data.error) {
                resultContainer.innerHTML = `<p style="color: red;">${data.error}</p>`;
            } else {
                const tablaTrabajador = `
                    <table>
                        <tr>
                            <th>NOMBRE</th>
                            <th>RUT</th>
                            <th>CARGO</th>
                        </tr>
                        <tr>
                            <td>${data.nombre_completo}</td>
                            <td>${data.rut}</td>
                            <td>${data.cargo}</td>
                        </tr>
                        <tr>
                            <th>HORARIO</th>
                            <th>SEDE</th>
                            <th>SUELDO</th>
                        </tr>
                        <tr>
                            <td>${data.horario}</td>
                            <td>${data.sede}</td>
                            <td>${data.sueldo}</td>
                        </tr>
                        <tr>
                            <th>TIPO DE CONTRATO</th>
                            <th>STATUS</th>
                            <th>DESCARGAR</th>
                        </tr>
                        <tr>
                            <td>${data.tipo_contrato}</td>
                            <td>${data.status}</td>
                            <td><button onclick='generarPDF(${JSON.stringify(data)})'>Descargar PDF</button></td>
                        </tr>
                    </table>
                `;

                const tablaDocumentos = `
                    <h3>Documentos Asociados</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre Documento</th>
                                <th>Descripción</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${
                                data.documentos && data.documentos.length > 0
                                    ? data.documentos
                                          .map(
                                              (doc) => `
                                <tr>
                                    <td>${doc.nombre_documento}</td>
                                    <td>${doc.descripcion}</td>
                                    <td><a href="documentos/${doc.ruta_documento}" target="_blank">Ver</a></td>
                                </tr>`
                                          )
                                          .join("")
                                    : "<tr><td colspan='3'>No hay documentos asociados.</td></tr>"
                            }
                        </tbody>
                    </table>
                `;

                resultContainer.innerHTML = tablaTrabajador + tablaDocumentos;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Hubo un problema con la búsqueda. Intente de nuevo.");
        });
}

// Función para buscar una sede por nombre desde la base de datos
function buscarSede() {
    const nombreSede = document.getElementById("nombre_sede").value.trim();

    if (!nombreSede) {
        alert("Por favor, ingrese un nombre de sede.");
        return;
    }

    fetch("buscar_sede.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `nombre_sede=${encodeURIComponent(nombreSede)}`,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Error en la respuesta del servidor.");
            }
            return response.json();
        })
        .then((data) => {
            const resultContainer = document.getElementById("result-container");
            resultContainer.innerHTML = "";

            if (data.error) {
                resultContainer.innerHTML = `<p style="color: red;">${data.error}</p>`;
            } else {
                let html = `
                    <h3>Resultados de Búsqueda de Sedes</h3>
                    <table>
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
                resultContainer.innerHTML = html;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Hubo un problema con la búsqueda. Intente de nuevo.");
        });
}

// Asignar el evento click al botón de búsqueda
document.getElementById("search-button").addEventListener("click", buscarTrabajador);
document.getElementById("buscar-sede-button").addEventListener("click", buscarSede);