// ==========================================
// PRÁCTICA 01: PRIMER MENSAJE
// ==========================================
console.log("Hola Mundo, desde JavaScript");

// ==========================================
// PRÁCTICA 02: VALIDAR MAYORÍA DE EDAD
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    const fechaNacimiento = document.getElementById("fechaNacimiento");
    const resultadoEdad = document.getElementById("resultadoEdad");

    if (!fechaNacimiento || !resultadoEdad) {
        console.error("No se encontraron los elementos de fecha.");
        return;
    }

    function calcularEdad() {
        console.log("Se está ejecutando calcularEdad");
        console.log("Fecha seleccionada:", fechaNacimiento.value);
        resultadoEdad.classList.remove(
            "edad-estado--mayor",
            "edad-estado--menor",
            "edad-estado--error"
        );

        if (fechaNacimiento.value === "") {
            resultadoEdad.textContent = "Selecciona una fecha para calcular tu edad.";
            return;
        }

        const [anio, mes, dia] = fechaNacimiento.value.split("-").map(Number);
        const nacimiento = new Date(anio, mes - 1, dia);
        const hoy = new Date();

        hoy.setHours(0, 0, 0, 0);

        if (nacimiento > hoy) {
            resultadoEdad.textContent =
                "Error: la fecha de nacimiento no puede ser futura.";
            resultadoEdad.classList.add("edad-estado--error");
            return;
        }

        let edad = hoy.getFullYear() - nacimiento.getFullYear();

        const diferenciaMes = hoy.getMonth() - nacimiento.getMonth();

        if (
            diferenciaMes < 0 ||
            (diferenciaMes === 0 && hoy.getDate() < nacimiento.getDate())
        ) {
            edad--;
        }

        if (edad >= 18) {
            resultadoEdad.textContent =
                "Eres mayor de edad. Tienes " + edad + " años.";
            resultadoEdad.classList.add("edad-estado--mayor");
        } else {
            resultadoEdad.textContent =
                "Eres menor de edad. Tienes " + edad + " años.";
            resultadoEdad.classList.add("edad-estado--menor");
        }
    }

    fechaNacimiento.addEventListener("change", calcularEdad);
    fechaNacimiento.addEventListener("input", calcularEdad);

    // Calcula también cuando se carga la página.
    calcularEdad();
});