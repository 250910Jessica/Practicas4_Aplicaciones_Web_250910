// ==========================================
// PRÁCTICA 01: PRIMER MENSAJE
// ==========================================
console.log("Hola Mundo, desde JavaScript");

// Variables globales para el control del Wizard
let currentStep = 1;
const totalSteps = 5;

// ==========================================
// PRÁCTICA 02: VALIDAR MAYORÍA DE EDAD
// ==========================================
document.addEventListener("DOMContentLoaded", function () {
    const fechaNacimiento = document.getElementById("fechaNacimiento");
    const resultadoEdad = document.getElementById("resultadoEdad");

    function calcularEdad() {
        if (!fechaNacimiento || !resultadoEdad) return;

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
            resultadoEdad.textContent = "Error: la fecha de nacimiento no puede ser futura.";
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
            resultadoEdad.textContent = "Eres mayor de edad. Tienes " + edad + " años.";
            resultadoEdad.classList.add("edad-estado--mayor");
        } else {
            resultadoEdad.textContent = "Eres menor de edad. Tienes " + edad + " años.";
            resultadoEdad.classList.add("edad-estado--menor");
        }
    }

    if (fechaNacimiento) {
        fechaNacimiento.addEventListener("change", calcularEdad);
        fechaNacimiento.addEventListener("input", calcularEdad);
        calcularEdad();
    }

    // Subrayado dinámico de casillas
    const inputs = document.querySelectorAll('input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):not([type="reset"]), select, textarea');

    inputs.forEach(input => {
        input.addEventListener('input', () => checkInputState(input));
        input.addEventListener('change', () => checkInputState(input));
    });

    function checkInputState(input) {
        if (input.value.trim() !== '') {
            input.classList.add('filled');
        } else {
            input.classList.remove('filled');
        }
        updateWizardProgress();
    }

    // Inicializar estado de la barra al cargar
    updateWizardProgress();

    const form = document.getElementById('multiStepForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            if (validateStep()) {
                alert('¡Solicitud de registro enviada con éxito!');
            }
        });
    }
});

// ==========================================
// FUNCIONES GLOBALES DE NAVEGACIÓN
// ==========================================
window.nextStep = function() {
    if (validateStep()) {
        if (currentStep < totalSteps) {
            // Limpiar mensaje de error si la validación es exitosa
            setFormMessage(currentStep, '');
            
            document.getElementById(`step-${currentStep}`).classList.remove('active');
            currentStep++;
            document.getElementById(`step-${currentStep}`).classList.add('active');
            updateSidebarActive();
            updateWizardProgress();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
};

window.prevStep = function() {
    if (currentStep > 1) {
        setFormMessage(currentStep, ''); // Limpiar al retroceder
        document.getElementById(`step-${currentStep}`).classList.remove('active');
        currentStep--;
        document.getElementById(`step-${currentStep}`).classList.add('active');
        updateSidebarActive();
        updateWizardProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

window.goToStep = function(targetStep) {
    if (targetStep >= 1 && targetStep <= totalSteps) {
        setFormMessage(currentStep, '');
        const currentStepEl = document.getElementById(`step-${currentStep}`);
        if (currentStepEl) {
            currentStepEl.classList.remove('active');
        }

        currentStep = targetStep;

        const nextStepEl = document.getElementById(`step-${currentStep}`);
        if (nextStepEl) {
            nextStepEl.classList.add('active');
        }

        updateSidebarActive();
        updateWizardProgress();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
};

function updateSidebarActive() {
    const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
    navItems.forEach((item, index) => {
        if (index + 1 === currentStep) {
            item.classList.add('active');
        } else {
            item.classList.remove('active');
        }
    });
}

function updateWizardProgress() {
    const progressPercent = ((currentStep - 1) / (totalSteps - 1)) * 100;
    const progressBar = document.getElementById('wizardProgress');
    if (progressBar) progressBar.style.width = progressPercent + '%';

    for (let i = 1; i <= totalSteps; i++) {
        const stepItem = document.getElementById(`step-ind-${i}`);
        const statusLabel = document.getElementById(`status-${i}`);
        if (!stepItem) continue;

        const stepEl = document.getElementById(`step-${i}`);
        let stepFilled = true;
        if (stepEl) {
            const stepInputs = stepEl.querySelectorAll('input[required], select[required]');
            stepInputs.forEach(inp => {
                if (inp.value.trim() === '') stepFilled = false;
            });
        }

        stepItem.classList.remove('active', 'completed');

        if (i < currentStep || (stepFilled && i === currentStep && i !== totalSteps)) {
            stepItem.classList.add('completed');
            if (statusLabel) statusLabel.textContent = "Completado";
        } else if (i === currentStep) {
            stepItem.classList.add('active');
            if (statusLabel) statusLabel.textContent = "En proceso";
        } else {
            if (statusLabel) statusLabel.textContent = "Pendiente";
        }
    }
}

function validateStep() {
    const currentStepEl = document.getElementById(`step-${currentStep}`);
    if (!currentStepEl) return true;
    
    const stepInputs = currentStepEl.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    stepInputs.forEach(input => {
        if (input.value.trim() === '') {
            isValid = false;
            input.style.borderBottomColor = 'var(--rojo)';
            setTimeout(() => {
                input.style.borderBottomColor = '';
            }, 1200);
        }
    });

    if (!isValid) {
        // En lugar de alert, mostramos texto de advertencia en el formulario
        setFormMessage(currentStep, 'Por favor complete los campos obligatorios antes de continuar.');
    } else {
        setFormMessage(currentStep, '');
    }
    return isValid;
}

// Función auxiliar para mostrar u ocultar texto de aviso dentro del paso actual
function setFormMessage(stepIndex, message) {
    let msgContainer = document.getElementById(`step-msg-${stepIndex}`);
    
    // Si no existe el contenedor de texto en el HTML, lo creamos dinámicamente arriba del paso
    if (!msgContainer) {
        const stepEl = document.getElementById(`step-${stepIndex}`);
        if (stepEl) {
            msgContainer = document.createElement('div');
            msgContainer.id = `step-msg-${stepIndex}`;
            msgContainer.style.color = 'var(--rojo)';
            msgContainer.style.fontSize = '0.85rem';
            msgContainer.style.marginTop = '8px';
            msgContainer.style.marginBottom = '12px';
            msgContainer.style.fontWeight = '500';
            stepEl.prepend(msgContainer);
        }
    }
    
    if (msgContainer) {
        msgContainer.textContent = message;
    }
}
