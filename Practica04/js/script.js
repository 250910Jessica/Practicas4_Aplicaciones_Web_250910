// ==========================================
// PRÁCTICA 01: PRIMER MENSAJE
// ==========================================
console.log("Hola Mundo, desde JavaScript");

// Variables globales para el control del Wizard
let currentStep = 1;
const totalSteps = 5;
// ==========================================
// ESTADOS Y CODIGOS POSTALES DE MÉXICO
// ==========================================
console.log("Hola Mundo, desde JavaScript");
// Base de datos completa con los 32 estados de México, municipios y códigos postales
const datosUbicacion = {
  "estados": [
    {
      "id": "AGU",
      "nombre": "Aguascalientes",
      "municipios": [
        { "nombre": "Aguascalientes", "codigos_postales": ["20000", "20100", "20200"] },
        { "nombre": "Jesús María", "codigos_postales": ["20900", "20910"] },
        { "nombre": "Calvillo", "codigos_postales": ["20800", "20810"] }
      ]
    },
    {
      "id": "BCN",
      "nombre": "Baja California",
      "municipios": [
        { "nombre": "Mexicali", "codigos_postales": ["21000", "21100", "21200"] },
        { "nombre": "Tijuana", "codigos_postales": ["22000", "22100", "22200"] },
        { "nombre": "Ensenada", "codigos_postales": ["22800", "22830"] }
      ]
    },
    {
      "id": "BCS",
      "nombre": "Baja California Sur",
      "municipios": [
        { "nombre": "La Paz", "codigos_postales": ["23000", "23010", "23020"] },
        { "nombre": "Los Cabos", "codigos_postales": ["23400", "23410", "23450"] }
      ]
    },
    {
      "id": "CAM",
      "nombre": "Campeche",
      "municipios": [
        { "nombre": "Campeche", "codigos_postales": ["24000", "24010", "24020"] },
        { "nombre": "Carmen", "codigos_postales": ["24100", "24115", "24120"] }
      ]
    },
    {
      "id": "CHP",
      "nombre": "Chiapas",
      "municipios": [
        { "nombre": "Tuxtla Gutiérrez", "codigos_postales": ["29000", "29010", "29020"] },
        { "nombre": "San Cristóbal de las Casas", "codigos_postales": ["29200", "29230", "29250"] },
        { "nombre": "Tapachula", "codigos_postales": ["30700", "30790"] }
      ]
    },
    {
      "id": "CHH",
      "nombre": "Chihuahua",
      "municipios": [
        { "nombre": "Chihuahua", "codigos_postales": ["31000", "31020", "31100"] },
        { "nombre": "Juárez", "codigos_postales": ["32000", "32300", "32500"] }
      ]
    },
    {
      "id": "CMX",
      "nombre": "Ciudad de México",
      "municipios": [
        { "nombre": "Cuauhtémoc", "codigos_postales": ["06000", "06100", "06300"] },
        { "nombre": "Benito Juárez", "codigos_postales": ["03100", "03300", "03720"] },
        { "nombre": "Coyoacán", "codigos_postales": ["04000", "04300", "04510"] },
        { "nombre": "Miguel Hidalgo", "codigos_postales": ["11000", "11500", "11830"] }
      ]
    },
    {
      "id": "COA",
      "nombre": "Coahuila",
      "municipios": [
        { "nombre": "Saltillo", "codigos_postales": ["25000", "25010", "25280"] },
        { "nombre": "Torreón", "codigos_postales": ["27000", "27100", "27200"] }
      ]
    },
    {
      "id": "COL",
      "nombre": "Colima",
      "municipios": [
        { "nombre": "Colima", "codigos_postales": ["28000", "28010", "28018"] },
        { "nombre": "Manzanillo", "codigos_postales": ["28200", "28219", "28239"] }
      ]
    },
    {
      "id": "DUR",
      "nombre": "Durango",
      "municipios": [
        { "nombre": "Durango", "codigos_postales": ["34000", "34010", "34100"] },
        { "nombre": "Gómez Palacio", "codigos_postales": ["35000", "35020"] }
      ]
    },
    {
      "id": "GUA",
      "nombre": "Guanajuato",
      "municipios": [
        { "nombre": "León", "codigos_postales": ["37000", "37100", "37320"] },
        { "nombre": "Irapuato", "codigos_postales": ["36500", "36550"] },
        { "nombre": "Celaya", "codigos_postales": ["38000", "38010", "38040"] },
        { "nombre": "Guanajuato", "codigos_postales": ["36000", "36250"] }
      ]
    },
    {
      "id": "GRO",
      "nombre": "Guerrero",
      "municipios": [
        { "nombre": "Acapulco de Juárez", "codigos_postales": ["39300", "39350", "39650"] },
        { "nombre": "Chilpancingo de los Bravo", "codigos_postales": ["39000", "39010"] }
      ]
    },
    {
      "id": "HID",
      "nombre": "Hidalgo",
      "municipios": [
        { "nombre": "Pachuca de Soto", "codigos_postales": ["42000", "42080", "42090"] },
        { "nombre": "Tulancingo de Bravo", "codigos_postales": ["43600", "43630"] },
        { "nombre": "Tula de Allende", "codigos_postales": ["42800", "42820"] }
      ]
    },
    {
      "id": "JAL",
      "nombre": "Jalisco",
      "municipios": [
        { "nombre": "Guadalajara", "codigos_postales": ["44100", "44200", "44400"] },
        { "nombre": "Zapopan", "codigos_postales": ["45010", "45050", "45116"] },
        { "nombre": "Tlaquepaque", "codigos_postales": ["45500", "45580"] }
      ]
    },
    {
      "id": "MEX",
      "nombre": "Estado de México",
      "municipios": [
        { "nombre": "Toluca", "codigos_postales": ["50000", "50010", "50120"] },
        { "nombre": "Ecatepec de Morelos", "codigos_postales": ["55000", "55010", "55210"] },
        { "nombre": "Naucalpan de Juárez", "codigos_postales": ["53000", "53100", "53240"] },
        { "nombre": "Tlalnepantla de Baz", "codigos_postales": ["54000", "54030", "54080"] }
      ]
    },
    {
      "id": "MIC",
      "nombre": "Michoacán",
      "municipios": [
        { "nombre": "Morelia", "codigos_postales": ["58000", "58010", "58130"] },
        { "nombre": "Uruapan", "codigos_postales": ["60000", "60050", "60180"] },
        { "nombre": "Zamora", "codigos_postales": ["59600", "59610"] }
      ]
    },
    {
      "id": "MOR",
      "nombre": "Morelos",
      "municipios": [
        { "nombre": "Cuernavaca", "codigos_postales": ["62000", "62010", "62250"] },
        { "nombre": "Jiutepec", "codigos_postales": ["62550", "62570"] }
      ]
    },
    {
      "id": "NAY",
      "nombre": "Nayarit",
      "municipios": [
        { "nombre": "Tepic", "codigos_postales": ["63000", "63010", "63173"] },
        { "nombre": "Bahía de Banderas", "codigos_postales": ["63735", "63737"] }
      ]
    },
    {
      "id": "NLE",
      "nombre": "Nuevo León",
      "municipios": [
        { "nombre": "Monterrey", "codigos_postales": ["64000", "64010", "64349"] },
        { "nombre": "San Pedro Garza García", "codigos_postales": ["66220", "66260", "66278"] },
        { "nombre": "San Nicolás de los Garza", "codigos_postales": ["66400", "66450"] }
      ]
    },
    {
      "id": "OAX",
      "nombre": "Oaxaca",
      "municipios": [
        { "nombre": "Oaxaca de Juárez", "codigos_postales": ["68000", "68020", "68100"] },
        { "nombre": "San Juan Bautista Tuxtepec", "codigos_postales": ["68300", "68310"] }
      ]
    },
    {
      "id": "PUE",
      "nombre": "Puebla",
      "municipios": [
        { "nombre": "Puebla", "codigos_postales": ["72000", "72010", "72500", "72530"] },
        { "nombre": "San Andrés Cholula", "codigos_postales": ["72810", "72820", "72830"] },
        { "nombre": "Tehuacán", "codigos_postales": ["75700", "75730", "75740"] },
        { "nombre": "Xicotepec", "codigos_postales": ["73080", "73081"] },
        { "nombre": "Huauchinango", "codigos_postales": ["73160", "73170"] }
      ]
    },
    {
      "id": "QUE",
      "nombre": "Querétaro",
      "municipios": [
        { "nombre": "Querétaro", "codigos_postales": ["76000", "76010", "76140"] },
        { "nombre": "San Juan del Río", "codigos_postales": ["76800", "76805"] }
      ]
    },
    {
      "id": "ROO",
      "nombre": "Quintana Roo",
      "municipios": [
        { "nombre": "Benito Juárez (Cancún)", "codigos_postales": ["77500", "77505", "77533"] },
        { "nombre": "Othón P. Blanco", "codigos_postales": ["77000", "77010"] },
        { "nombre": "Solidaridad (Playa del Carmen)", "codigos_postales": ["77710", "77720"] }
      ]
    },
    {
      "id": "SLP",
      "nombre": "San Luis Potosí",
      "municipios": [
        { "nombre": "San Luis Potosí", "codigos_postales": ["78000", "78030", "78210"] },
        { "nombre": "Soledad de Graciano Sánchez", "codigos_postales": ["78430", "78435"] }
      ]
    },
    {
      "id": "SIN",
      "nombre": "Sinaloa",
      "municipios": [
        { "nombre": "Culiacán", "codigos_postales": ["80000", "80010", "80100"] },
        { "nombre": "Mazatlán", "codigos_postales": ["82000", "82010", "82110"] }
      ]
    },
    {
      "id": "SON",
      "nombre": "Sonora",
      "municipios": [
        { "nombre": "Hermosillo", "codigos_postales": ["83000", "83010", "83100"] },
        { "nombre": "Cajeme (Ciudad Obregón)", "codigos_postales": ["85000", "85010"] }
      ]
    },
    {
      "id": "TAB",
      "nombre": "Tabasco",
      "municipios": [
        { "nombre": "Centro (Villahermosa)", "codigos_postales": ["86000", "86010", "86100"] },
        { "nombre": "Cárdenas", "codigos_postales": ["86500", "86570"] }
      ]
    },
    {
      "id": "TAM",
      "nombre": "Tamaulipas",
      "municipios": [
        { "nombre": "Tampico", "codigos_postales": ["89000", "89010", "89100"] },
        { "nombre": "Reynosa", "codigos_postales": ["88500", "88550", "88710"] },
        { "nombre": "Victoria", "codigos_postales": ["87000", "87010"] }
      ]
    },
    {
      "id": "TLX",
      "nombre": "Tlaxcala",
      "municipios": [
        { "nombre": "Tlaxcala", "codigos_postales": ["90000", "90010"] },
        { "nombre": "Apizaco", "codigos_postales": ["90300", "90330"] }
      ]
    },
    {
      "id": "VER",
      "nombre": "Veracruz",
      "municipios": [
        { "nombre": "Veracruz", "codigos_postales": ["91700", "91720", "91897"] },
        { "nombre": "Xalapa", "codigos_postales": ["91000", "91010", "91190"] },
        { "nombre": "Coatzacoalcos", "codigos_postales": ["96400", "96450"] }
      ]
    },
    {
      "id": "YUC",
      "nombre": "Yucatán",
      "municipios": [
        { "nombre": "Mérida", "codigos_postales": ["97000", "97010", "97100"] },
        { "nombre": "Kanasín", "codigos_postales": ["97370", "97380"] }
      ]
    },
    {
      "id": "ZAC",
      "nombre": "Zacatecas",
      "municipios": [
        { "nombre": "Zacatecas", "codigos_postales": ["98000", "98040", "98060"] },
        { "nombre": "Fresnillo", "codigos_postales": ["99000", "99030"] }
      ]
    }
  ]
};

// ==========================================
// INICIALIZACIÓN Y VALIDACIONES (DOMContentLoaded)
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

    // Inicializar catálogos de ubicación (Estados)
    cargarDatosUbicacion();

    const selectEstado = document.getElementById('estado');
    const selectMunicipio = document.getElementById('municipio');

    if (selectEstado) {
        selectEstado.addEventListener('change', () => {
            actualizarMunicipios();
            checkInputState(selectEstado);
        });
    }

    if (selectMunicipio) {
        selectMunicipio.addEventListener('change', () => {
            actualizarCodigosPostales();
            checkInputState(selectMunicipio);
        });
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
// FUNCIONES DE SELECTORES DEPENDIENTES DE UBICACIÓN
// ==========================================
function cargarDatosUbicacion() {
  const selectEstado = document.getElementById('estado');
  if (!selectEstado) return;
  
  selectEstado.innerHTML = '<option value="">Selecciona un estado...</option>';
  datosUbicacion.estados.forEach(estado => {
    const option = document.createElement('option');
    option.value = estado.nombre;
    option.textContent = estado.nombre;
    selectEstado.appendChild(option);
  });
}

function actualizarMunicipios() {
  const selectEstado = document.getElementById('estado');
  const estadoNombre = selectEstado.value;
  const selectMunicipio = document.getElementById('municipio');
  const selectCP = document.getElementById('codigoPostal');
  
  if (!selectMunicipio) return;
  selectMunicipio.innerHTML = '<option value="">Selecciona un municipio...</option>';
  if (selectCP) selectCP.innerHTML = '<option value="">Selecciona un código postal...</option>';
  
  const estadoEncontrado = datosUbicacion.estados.find(e => e.nombre === estadoNombre);
  if (estadoEncontrado) {
    estadoEncontrado.municipios.forEach(mun => {
      const option = document.createElement('option');
      option.value = mun.nombre;
      option.textContent = mun.nombre;
      selectMunicipio.appendChild(option);
    });
  }
}

function actualizarCodigosPostales() {
  const selectEstado = document.getElementById('estado');
  const estadoNombre = selectEstado.value;
  const municipioNombre = document.getElementById('municipio').value;
  const selectCP = document.getElementById('codigoPostal');
  
  if (!selectCP) return;
  selectCP.innerHTML = '<option value="">Selecciona un código postal...</option>';
  
  const estadoEncontrado = datosUbicacion.estados.find(e => e.nombre === estadoNombre);
  if (estadoEncontrado) {
    const munEncontrado = estadoEncontrado.municipios.find(m => m.nombre === municipioNombre);
    if (munEncontrado) {
      munEncontrado.codigos_postales.forEach(cp => {
        const option = document.createElement('option');
        option.value = cp;
        option.textContent = cp;
        selectCP.appendChild(option);
      });
    }
  }
}

// ==========================================
// FUNCIONES GLOBALES DE NAVEGACIÓN
// ==========================================
window.nextStep = function() {
    if (validateStep()) {
        if (currentStep < totalSteps) {
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
        setFormMessage(currentStep, '');
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

// ==========================================
// VALIDACIONES DE PASOS (CORREO, TELÉFONO, CONTRASEÑA)
// ==========================================
function validateStep() {
    const currentStepEl = document.getElementById(`step-${currentStep}`);
    if (!currentStepEl) return true;
    
    // 1. Validar campos requeridos generales del paso actual
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
        setFormMessage(currentStep, 'Por favor complete los campos obligatorios antes de continuar.');
        return false;
    }

    // 2. Validación específica Paso 2 (Contacto: Correo y Teléfono de 10 dígitos)
    if (currentStep === 2) {
        const email = document.getElementById('email')?.value.trim() || '';
        const telefono = document.getElementById('telefono')?.value.trim() || '';

        const regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const regexTelefono = /^\d{10}$/;

        if (!regexEmail.test(email)) {
            setFormMessage(currentStep, 'Por favor ingresa un correo electrónico válido (ej. usuario@dominio.com).');
            return false;
        }

        if (!regexTelefono.test(telefono)) {
            setFormMessage(currentStep, 'El número telefónico debe contener exactamente 10 dígitos numéricos.');
            return false;
        }
    }

    // 3. Validación específica Paso 3 (Datos de Acceso: Contraseña segura y coincidencia)
    if (currentStep === 3) {
        const password = document.getElementById('contrasena')?.value || '';
        const confirmPassword = document.getElementById('confirmarContrasena')?.value || '';

        // Mínimo 8 caracteres, al menos una mayúscula, un número y un carácter especial
        const regexPassword = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!regexPassword.test(password)) {
            setFormMessage(currentStep, 'La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo especial.');
            return false;
        }

        if (password !== confirmPassword) {
            setFormMessage(currentStep, 'Las contraseñas no coinciden.');
            return false;
        }
    }

    setFormMessage(currentStep, '');
    return true;
}

// Función auxiliar para mostrar avisos de error dentro del paso actual
function setFormMessage(stepIndex, message) {
    let msgContainer = document.getElementById(`step-msg-${stepIndex}`);
    
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
    
