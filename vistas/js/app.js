/*
 * Script principal de Argus MVC.
 * Se utiliza para manejar eventos comunes en la vista, como mostrar modales
 * y realizar peticiones AJAX para la creación de registros.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Función para decodificar entidades HTML (para data-lotes)
    const decodeHtml = (html) => {
        return html
            .replace(/&quot;/g, '"')
            .replace(/&#039;/g, "'")
            .replace(/&lt;/g, '<')
            .replace(/&gt;/g, '>')
            .replace(/&amp;/g, '&');
    };
    // Formateo numérico
    const formatNumber = (value) => {
        const num = parseFloat(value.toString().replace(/[^0-9.]/g, ''));
        if (isNaN(num)) return '';
        return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };
    const cleanNumberString = (value) => {
        return value.toString().replace(/[^0-9.]/g, '');
    };

    // Manejar formularios dinámicamente
    // Nuevo cliente
    const formCliente = document.getElementById('formCliente');
    if (formCliente) {
        formCliente.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(formCliente);
            // Usar la acción definida en el formulario para enviar la petición
            const url = formCliente.getAttribute('action');
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(r => r.text()).then(resp => {
                // Mostrar mensaje de éxito o error dependiendo de la respuesta
                let title = 'Guardado';
                let text = 'Cliente registrado correctamente.';
                let icon = 'success';
                if (resp.includes('error')) {
                    title = 'Error';
                    text = 'No se pudo guardar.';
                    icon = 'error';
                }
                Swal.fire(title, text, icon).then(() => {
                    window.location.reload();
                });
            }).catch(() => {
                Swal.fire('Error', 'No se pudo guardar.', 'error');
            });
        });
    }

    // Nuevo desarrollo
    const formDesarrollo = document.getElementById('formDesarrollo');
    if (formDesarrollo) {
        formDesarrollo.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(formDesarrollo);
            const url = formDesarrollo.getAttribute('action');
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(r => r.text()).then(resp => {
                let title = 'Guardado';
                let text = 'Desarrollo registrado correctamente.';
                let icon = 'success';
                if (resp.includes('error')) {
                    title = 'Error';
                    text = 'No se pudo guardar.';
                    icon = 'error';
                }
                Swal.fire(title, text, icon).then(() => {
                    window.location.reload();
                });
            }).catch(() => {
                Swal.fire('Error', 'No se pudo guardar.', 'error');
            });
        });
    }

    // Manejar lotes dinámicos en la creación de desarrollos
    const inputLoteNuevo = document.getElementById('inputLoteNuevo');
    const contenedorLotesNuevo = document.getElementById('contenedorLotesNuevo');
    const inputHiddenLotesNuevo = document.getElementById('lotesDisponiblesNuevo');
    let lotesNuevo = [];
    if (inputLoteNuevo && contenedorLotesNuevo && inputHiddenLotesNuevo) {
        // Inicializar campo oculto con un arreglo vacío
        inputHiddenLotesNuevo.value = JSON.stringify(lotesNuevo);
        // Función para renderizar las etiquetas de lotes y actualizar el valor oculto
        const renderLotesNuevo = () => {
            contenedorLotesNuevo.innerHTML = '';
            lotesNuevo.forEach((lote, idx) => {
                const badge = document.createElement('span');
                // Estilos inline para asegurar el formato de pill
                badge.style.display = 'inline-flex';
                badge.style.alignItems = 'center';
                badge.style.borderRadius = '12px';
                badge.style.backgroundColor = '#f0f2f5';
                badge.style.color = '#333';
                badge.style.padding = '4px 8px';
                badge.style.margin = '2px';
                badge.style.fontSize = '0.8rem';
                badge.textContent = lote;
                // botón (x) para eliminar
                const removeSpan = document.createElement('span');
                removeSpan.style.marginLeft = '6px';
                removeSpan.style.color = '#dc3545';
                removeSpan.style.cursor = 'pointer';
                removeSpan.textContent = '×';
                removeSpan.addEventListener('click', () => {
                    lotesNuevo.splice(idx, 1);
                    renderLotesNuevo();
                });
                badge.appendChild(removeSpan);
                contenedorLotesNuevo.appendChild(badge);
            });
            inputHiddenLotesNuevo.value = JSON.stringify(lotesNuevo);
        };
        // Agregar lote cuando el usuario presiona Enter
        inputLoteNuevo.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const valor = this.value.trim();
                if (valor && !lotesNuevo.includes(valor)) {
                    lotesNuevo.push(valor);
                    renderLotesNuevo();
                }
                this.value = '';
            }
        });
    }

    // Editar desarrollo
    const btnsEditarDesarrollo = document.querySelectorAll('.btnEditarDesarrollo');
    const modalEditar = document.getElementById('modalEditarDesarrollo');
    const formEditarDesarrollo = document.getElementById('formEditarDesarrollo');
    if (btnsEditarDesarrollo && modalEditar && formEditarDesarrollo) {
        // Al hacer clic en un botón editar, llenar el formulario con los datos del desarrollo
        btnsEditarDesarrollo.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                const tipocontrato = this.getAttribute('data-tipocontrato');
                const descripcion = this.getAttribute('data-descripcion');
                const superficie = this.getAttribute('data-superficie');
                const clave = this.getAttribute('data-clave');
                let lotesStr = this.getAttribute('data-lotes');
                // Decodificar entidades HTML para obtener JSON válido
                if (lotesStr) lotesStr = decodeHtml(lotesStr);
                const precioLote = this.getAttribute('data-preciolote');
                const precioTotal = this.getAttribute('data-preciototal');
                document.getElementById('editarIdDesarrollo').value = id;
                document.getElementById('editarNombreDesarrollo').value = nombre;
                document.getElementById('editarTipoContrato').value = tipocontrato;
                document.getElementById('editarDescripcion').value = descripcion;
                document.getElementById('editarSuperficie').value = superficie;
                document.getElementById('editarClaveCatastral').value = clave;
                document.getElementById('editarPrecioLote').value = precioLote;
                document.getElementById('editarPrecioTotal').value = precioTotal;
                // Parsear lotes existentes (JSON) y mostrarlos como etiquetas
                lotesEditar = [];
                try {
                    const arr = JSON.parse(lotesStr);
                    if (Array.isArray(arr)) {
                        lotesEditar = arr;
                    }
                } catch (err) {
                    // Si no es JSON válido, intentar dividir por comas
                    if (lotesStr) {
                        lotesEditar = lotesStr.split(',').map(l => l.trim()).filter(Boolean);
                    }
                }
                renderLotesEditar();
            });
        });
        // Enviar formulario de edición via fetch
        formEditarDesarrollo.addEventListener('submit', function (e) {
            e.preventDefault();
            // Confirmación antes de enviar
            Swal.fire({
                title: '¿Estás seguro de modificar los datos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formEditarDesarrollo);
                    const url = formEditarDesarrollo.getAttribute('action');
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    }).then(r => r.text()).then(resp => {
                        let title = 'Guardado';
                        let text = 'Desarrollo actualizado correctamente.';
                        let icon = 'success';
                        if (resp.includes('error')) {
                            title = 'Error';
                            text = 'No se pudo actualizar.';
                            icon = 'error';
                        }
                        Swal.fire(title, text, icon).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo actualizar.', 'error');
                    });
                }
            });
        });
    }

    // Manejar lotes dinámicos en la edición de desarrollos
    const inputLoteEditar = document.getElementById('inputLoteEditar');
    const contenedorLotesEditar = document.getElementById('contenedorLotesEditar');
    const inputHiddenLotesEditar = document.getElementById('lotesDisponiblesEditar');
    var lotesEditar = [];
    // Si el campo oculto para lotes en edición existe, inicializarlo como arreglo vacío
    if (inputHiddenLotesEditar) {
        inputHiddenLotesEditar.value = JSON.stringify(lotesEditar);
    }
    // Función para renderizar los lotes en edición y actualizar el valor oculto
    function renderLotesEditar() {
        if (!contenedorLotesEditar) return;
        contenedorLotesEditar.innerHTML = '';
        lotesEditar.forEach((lote, idx) => {
            const badge = document.createElement('span');
            // Estilos inline para asegurar el formato de pill
            badge.style.display = 'inline-flex';
            badge.style.alignItems = 'center';
            badge.style.borderRadius = '12px';
            badge.style.backgroundColor = '#f0f2f5';
            badge.style.color = '#333';
            badge.style.padding = '4px 8px';
            badge.style.margin = '2px';
            badge.style.fontSize = '0.8rem';
            badge.textContent = lote;
            const removeSpan = document.createElement('span');
            removeSpan.style.marginLeft = '6px';
            removeSpan.style.color = '#dc3545';
            removeSpan.style.cursor = 'pointer';
            removeSpan.textContent = '×';
            removeSpan.addEventListener('click', () => {
                lotesEditar.splice(idx, 1);
                renderLotesEditar();
            });
            badge.appendChild(removeSpan);
            contenedorLotesEditar.appendChild(badge);
        });
        if (inputHiddenLotesEditar) {
            inputHiddenLotesEditar.value = JSON.stringify(lotesEditar);
        }
    }
    if (inputLoteEditar) {
        inputLoteEditar.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const valor = this.value.trim();
                if (valor && !lotesEditar.includes(valor)) {
                    lotesEditar.push(valor);
                    renderLotesEditar();
                }
                this.value = '';
            }
        });
    }

    // Ver desarrollo
    const btnsVerDesarrollo = document.querySelectorAll('.btnVerDesarrollo');
    const modalVer = document.getElementById('modalVerDesarrollo');
    if (btnsVerDesarrollo && modalVer) {
        btnsVerDesarrollo.forEach(btn => {
            btn.addEventListener('click', function () {
                // Extraer datos del desarrollo
                const nombre = this.getAttribute('data-nombre');
                const tipocontrato = this.getAttribute('data-tipocontrato');
                const descripcion = this.getAttribute('data-descripcion');
                const superficie = this.getAttribute('data-superficie');
                const clave = this.getAttribute('data-clave');
                let lotesStr = this.getAttribute('data-lotes');
                if (lotesStr) lotesStr = decodeHtml(lotesStr);
                const precioLote = this.getAttribute('data-preciolote');
                const precioTotal = this.getAttribute('data-preciototal');
                // Llenar campos de la vista
                document.getElementById('verNombreDesarrollo').value = nombre;
                document.getElementById('verTipoContrato').value = tipocontrato;
                document.getElementById('verDescripcion').value = descripcion;
                document.getElementById('verSuperficie').value = superficie;
                document.getElementById('verClaveCatastral').value = clave;
                // Formatear los precios con separadores y símbolo
                if (precioLote) {
                    document.getElementById('verPrecioLote').value = '$' + formatNumber(precioLote);
                } else {
                    document.getElementById('verPrecioLote').value = '';
                }
                if (precioTotal) {
                    document.getElementById('verPrecioTotal').value = '$' + formatNumber(precioTotal);
                } else {
                    document.getElementById('verPrecioTotal').value = '';
                }
                // Renderizar lotes
                const contenedorVer = document.getElementById('contenedorLotesVer');
                contenedorVer.innerHTML = '';
                let arrVer = [];
                try {
                    const arr = JSON.parse(lotesStr);
                    if (Array.isArray(arr)) arrVer = arr;
                } catch (err) {
                    if (lotesStr) {
                        arrVer = lotesStr.split(',').map(l => l.trim()).filter(Boolean);
                    }
                }
                arrVer.forEach(lote => {
                    const span = document.createElement('span');
                    // Estilos inline para pill
                    span.style.display = 'inline-flex';
                    span.style.alignItems = 'center';
                    span.style.borderRadius = '12px';
                    span.style.backgroundColor = '#f0f2f5';
                    span.style.color = '#333';
                    span.style.padding = '4px 8px';
                    span.style.margin = '2px';
                    span.style.fontSize = '0.8rem';
                    span.textContent = lote;
                    contenedorVer.appendChild(span);
                });
            });
        });
    }

    // Ver cliente
    const btnsVerCliente = document.querySelectorAll('.btnVerCliente, .verClienteNombre');
    const modalVerCliente = document.getElementById('modalVerCliente');
    if (btnsVerCliente && modalVerCliente) {
        btnsVerCliente.forEach(btn => {
            btn.addEventListener('click', function (e) {
                // Si es un enlace, prevenir la navegación
                if (this.tagName.toLowerCase() === 'a') e.preventDefault();
                document.getElementById('verNombreCliente').value = this.getAttribute('data-nombre');
                document.getElementById('verNacionalidadCliente').value = this.getAttribute('data-nacionalidad');
                document.getElementById('verFechaCliente').value = this.getAttribute('data-fecha');
                document.getElementById('verRfcCliente').value = this.getAttribute('data-rfc');
                document.getElementById('verCurpCliente').value = this.getAttribute('data-curp');
                document.getElementById('verIneCliente').value = this.getAttribute('data-ine');
                document.getElementById('verEstadoCivilCliente').value = this.getAttribute('data-estado_civil');
                document.getElementById('verOcupacionCliente').value = this.getAttribute('data-ocupacion');
                document.getElementById('verTelefonoCliente').value = this.getAttribute('data-telefono');
                document.getElementById('verDomicilioCliente').value = this.getAttribute('data-domicilio');
                document.getElementById('verEmailCliente').value = this.getAttribute('data-email');
                document.getElementById('verBeneficiarioCliente').value = this.getAttribute('data-beneficiario');
            });
        });
    }

    // Editar cliente
    const btnsEditarCliente = document.querySelectorAll('.btnEditarCliente');
    const formEditarCliente = document.getElementById('formEditarCliente');
    if (btnsEditarCliente && formEditarCliente) {
        btnsEditarCliente.forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('editarIdCliente').value = this.getAttribute('data-id');
                document.getElementById('editarNombreCliente').value = this.getAttribute('data-nombre');
                // Selección de nacionalidad
                const nacionalidad = this.getAttribute('data-nacionalidad');
                const selectNac = document.getElementById('editarNacionalidadCliente');
                if (selectNac) {
                    selectNac.value = nacionalidad;
                }
                document.getElementById('editarFechaCliente').value = this.getAttribute('data-fecha');
                document.getElementById('editarRfcCliente').value = this.getAttribute('data-rfc');
                document.getElementById('editarCurpCliente').value = this.getAttribute('data-curp');
                document.getElementById('editarIneCliente').value = this.getAttribute('data-ine');
                document.getElementById('editarEstadoCivilCliente').value = this.getAttribute('data-estado_civil');
                document.getElementById('editarOcupacionCliente').value = this.getAttribute('data-ocupacion');
                document.getElementById('editarTelefonoCliente').value = this.getAttribute('data-telefono');
                document.getElementById('editarDomicilioCliente').value = this.getAttribute('data-domicilio');
                document.getElementById('editarEmailCliente').value = this.getAttribute('data-email');
                document.getElementById('editarBeneficiarioCliente').value = this.getAttribute('data-beneficiario');
            });
        });
        formEditarCliente.addEventListener('submit', function (e) {
            e.preventDefault();
            // Confirmación antes de enviar
            Swal.fire({
                title: '¿Estás seguro de modificar los datos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formEditarCliente);
                    const url = formEditarCliente.getAttribute('action');
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    }).then(r => r.text()).then(resp => {
                        let title = 'Guardado';
                        let text = 'Cliente actualizado correctamente.';
                        let icon = 'success';
                        if (resp.includes('error')) {
                            title = 'Error';
                            text = 'No se pudo actualizar.';
                            icon = 'error';
                        }
                        Swal.fire(title, text, icon).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo actualizar.', 'error');
                    });
                }
            });
        });
    }

    // Crear contrato
    const btnsCrearContrato = document.querySelectorAll('.btnCrearContrato');
    const formCrearContrato = document.getElementById('formCrearContrato');
    const selectDesarrolloContrato = document.getElementById('selectDesarrolloContrato');
    const contratoSuperficie = document.getElementById('contratoSuperficie');
    const contratoTipo = document.getElementById('contratoTipo');
    // Campos para manejar fracciones como etiquetas en la creación de contratos
    const inputFraccionContrato = document.getElementById('inputFraccionContrato');
    const contenedorFraccionesContrato = document.getElementById('contenedorFraccionesContrato');
    const hiddenFraccionesContrato = document.getElementById('hiddenFraccionesContrato');
    // Arrays para almacenar las fracciones seleccionadas y las disponibles
    let fraccionesContrato = [];
    let fraccionesDisponibles = [];
    if (btnsCrearContrato && formCrearContrato) {
        btnsCrearContrato.forEach(btn => {
            btn.addEventListener('click', function () {
                const clienteId = this.getAttribute('data-cliente-id');
                document.getElementById('crearContratoClienteId').value = clienteId;
                // Resetear select y campos
                if (selectDesarrolloContrato) {
                    selectDesarrolloContrato.value = '';
                }
                if (contratoSuperficie) contratoSuperficie.value = '';
                if (contratoTipo) contratoTipo.value = '';
                // Reiniciar fracciones seleccionadas y su representación
                fraccionesContrato = [];
                if (contenedorFraccionesContrato) {
                    contenedorFraccionesContrato.innerHTML = '';
                }
                if (hiddenFraccionesContrato) {
                    hiddenFraccionesContrato.value = '';
                }
                // Reiniciar disponibilidad
                fraccionesDisponibles = [];
            });
        });
        // Al cambiar de desarrollo llenar superficie y tipo
        if (selectDesarrolloContrato) {
        selectDesarrolloContrato.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const sup = selected.getAttribute('data-superficie');
                const tipo = selected.getAttribute('data-tipo');
                const lotes = selected.getAttribute('data-lotes');
                // Asignar superficie y tipo de contrato
                if (contratoSuperficie) contratoSuperficie.value = sup || '';
                if (contratoTipo) contratoTipo.value = tipo || '';
                // Decodificar lotes disponibles para fracciones
                fraccionesDisponibles = [];
                if (lotes) {
                    const decoded = decodeHtml(lotes);
                    try {
                        const parsed = JSON.parse(decoded);
                        if (Array.isArray(parsed)) {
                            fraccionesDisponibles = parsed;
                        }
                    } catch (err) {
                        // Si falla JSON parse, asumir lista separada por comas
                        fraccionesDisponibles = decoded.split(',').map(l => l.trim()).filter(Boolean);
                    }
                }
                // Al cambiar el desarrollo reiniciamos las fracciones seleccionadas
                fraccionesContrato = [];
                if (contenedorFraccionesContrato) {
                    contenedorFraccionesContrato.innerHTML = '';
                }
                if (hiddenFraccionesContrato) {
                    hiddenFraccionesContrato.value = '';
                }
            });
        }
        formCrearContrato.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(formCrearContrato);
            const url = formCrearContrato.getAttribute('action');
            fetch(url, {
                method: 'POST',
                body: formData
            }).then(r => r.text()).then(resp => {
                let title = 'Guardado';
                let text = 'Contrato creado correctamente.';
                let icon = 'success';
                if (resp.includes('error')) {
                    title = 'Error';
                    if (resp.includes('ya-existe')) {
                        text = 'El cliente ya cuenta con un contrato.';
                    } else if (resp.includes('token')) {
                        text = 'Token CSRF inválido.';
                    } else if (resp.includes('sesion')) {
                        text = 'Sesión no válida.';
                    } else {
                        text = 'No se pudo crear.';
                    }
                    icon = 'error';
                }
                Swal.fire(title, text, icon).then(() => {
                    window.location.reload();
                });
            }).catch(() => {
                Swal.fire('Error', 'No se pudo crear.', 'error');
            });
        });
        // Configurar manejo de fracciones como etiquetas para creación
        if (inputFraccionContrato && contenedorFraccionesContrato && hiddenFraccionesContrato) {
            // Función para mostrar etiquetas y actualizar campo oculto
            const renderFraccionesContrato = () => {
                contenedorFraccionesContrato.innerHTML = '';
                fraccionesContrato.forEach((frac, idx) => {
                    const badge = document.createElement('span');
                    badge.style.display = 'inline-flex';
                    badge.style.alignItems = 'center';
                    badge.style.borderRadius = '12px';
                    badge.style.backgroundColor = '#f0f2f5';
                    badge.style.color = '#333';
                    badge.style.padding = '4px 8px';
                    badge.style.margin = '2px';
                    badge.style.fontSize = '0.8rem';
                    badge.textContent = frac;
                    const removeSpan = document.createElement('span');
                    removeSpan.style.marginLeft = '6px';
                    removeSpan.style.color = '#dc3545';
                    removeSpan.style.cursor = 'pointer';
                    removeSpan.textContent = '×';
                    removeSpan.addEventListener('click', () => {
                        fraccionesContrato.splice(idx, 1);
                        renderFraccionesContrato();
                    });
                        badge.appendChild(removeSpan);
                    contenedorFraccionesContrato.appendChild(badge);
                });
                hiddenFraccionesContrato.value = fraccionesContrato.join(',');
            };
            inputFraccionContrato.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const valor = this.value.trim();
                    if (valor && !fraccionesContrato.includes(valor)) {
                        // Si hay fracciones disponibles, verificar que el valor esté permitido
                        if (fraccionesDisponibles.length === 0 || fraccionesDisponibles.includes(valor)) {
                            fraccionesContrato.push(valor);
                            renderFraccionesContrato();
                        }
                    }
                    this.value = '';
                }
            });
        }
    }

    // Ver contrato
    const btnsVerContrato = document.querySelectorAll('.btnVerContrato');
    const modalVerContrato = document.getElementById('modalVerContrato');
    if (btnsVerContrato && modalVerContrato) {
        btnsVerContrato.forEach(btn => {
            btn.addEventListener('click', function () {
                // Llenar campos con los atributos de datos
                document.getElementById('verContratoDesarrollo').value = this.getAttribute('data-nombre-desarrollo');
                document.getElementById('verContratoMensualidades').value = this.getAttribute('data-mensualidades');
                document.getElementById('verContratoSuperficie').value = this.getAttribute('data-superficie');
                document.getElementById('verContratoFraccion').value = this.getAttribute('data-fraccion');
                document.getElementById('verContratoEntrega').value = this.getAttribute('data-entrega');
                document.getElementById('verContratoFirma').value = this.getAttribute('data-firma');
                document.getElementById('verContratoHabitacional').value = this.getAttribute('data-habitacional');
                document.getElementById('verContratoInicio').value = this.getAttribute('data-inicio');
                document.getElementById('verContratoTipo').value = this.getAttribute('data-tipo');
            });
        });
    }

    /*
     * Inicializar DataTables en las listas para paginar y buscar dinámicamente.
     * Mostrará 20 registros por página y utilizará idioma español.
     */
    const esLang = {
        decimal: ",",
        thousands: ".",
        processing: "Procesando...",
        search: "Buscar:",
        lengthMenu: "Mostrar _MENU_ registros",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Mostrando 0 a 0 de 0 registros",
        infoFiltered: "(filtrado de _MAX_ registros totales)",
        loadingRecords: "Cargando...",
        zeroRecords: "No se encontraron registros",
        emptyTable: "No hay datos disponibles",
        paginate: {
            first: "Primero",
            previous: "Anterior",
            next: "Siguiente",
            last: "Último"
        }
    };
    // Inicializar en tabla de clientes
    if (typeof $ !== 'undefined') {
        const $tablaClientes = $('#tablaClientes');
        if ($tablaClientes.length) {
            $tablaClientes.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
        }
        const $tablaDesarrollos = $('#tablaDesarrollos');
        if ($tablaDesarrollos.length) {
            $tablaDesarrollos.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
        }

        // Inicializar DataTables en parámetros si existen
        const $tablaNacionalidades = $('#tablaNacionalidades');
        if ($tablaNacionalidades.length) {
            $tablaNacionalidades.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
        }
        const $tablaTipos = $('#tablaTipos');
        if ($tablaTipos.length) {
            $tablaTipos.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
        }
        const $tablaPlantillas = $('#tablaPlantillas');
        if ($tablaPlantillas.length) {
            $tablaPlantillas.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
        }

        // Inicializar tabla de contratos con DataTables
        const $tablaContratos = $('#tablaContratos');
        if ($tablaContratos.length) {
            const dataTableContratos = $tablaContratos.DataTable({
                pageLength: 20,
                language: esLang,
                responsive: true
            });
            // Filtros por desarrollo y tipo de contrato
            const filtroDesarrollo = document.getElementById('filtroDesarrollo');
            const filtroTipo = document.getElementById('filtroTipo');
            if (filtroDesarrollo) {
                filtroDesarrollo.addEventListener('change', function () {
                    const val = this.value;
                    if (val) {
                        // columna 2: Desarrollo
                        dataTableContratos.column(2).search('^' + val + '$', true, false).draw();
                    } else {
                        dataTableContratos.column(2).search('').draw();
                    }
                });
            }
            if (filtroTipo) {
                filtroTipo.addEventListener('change', function () {
                    const val = this.value;
                    if (val) {
                        // columna 3: Tipo contrato
                        dataTableContratos.column(3).search('^' + val + '$', true, false).draw();
                    } else {
                        dataTableContratos.column(3).search('').draw();
                    }
                });
            }
        }
    }

    /*
     * Formatear campos de precio en formularios de desarrollos.
     * Estos inputs muestran un símbolo de pesos en un grupo y permiten la entrada con
     * separadores de miles. Se formatean al escribir y se limpian antes de enviar el formulario.
     */
    const priceInputs = document.querySelectorAll('input[name="precio_lote"], input[name="precio_total"], #crearPrecioLote, #crearPrecioTotal, #editarPrecioLote, #editarPrecioTotal');
    priceInputs.forEach(input => {
        // Formatear al cargar si ya tiene valor
        if (input.value) {
            input.value = formatNumber(input.value);
        }
        // Al enfocarse, eliminar formato para facilitar la edición
        input.addEventListener('focus', function () {
            const cleaned = cleanNumberString(this.value);
            this.value = cleaned ? parseFloat(cleaned).toString() : '';
        });
        // Al perder el foco, aplicar formato con separadores y dos decimales
        input.addEventListener('blur', function () {
            const cleaned = cleanNumberString(this.value);
            if (cleaned) {
                this.value = formatNumber(cleaned);
            } else {
                this.value = '';
            }
        });
    });

    // Limpia los separadores antes de enviar formularios de desarrollos
    if (formDesarrollo) {
        formDesarrollo.addEventListener('submit', function () {
            const precioLote = formDesarrollo.querySelector('input[name="precio_lote"]');
            const precioTotal = formDesarrollo.querySelector('input[name="precio_total"]');
            if (precioLote) precioLote.value = cleanNumberString(precioLote.value);
            if (precioTotal) precioTotal.value = cleanNumberString(precioTotal.value);
        }, true);
    }
    if (formEditarDesarrollo) {
        formEditarDesarrollo.addEventListener('submit', function () {
            const precioLote = formEditarDesarrollo.querySelector('input[name="precio_lote"]');
            const precioTotal = formEditarDesarrollo.querySelector('input[name="precio_total"]');
            if (precioLote) precioLote.value = cleanNumberString(precioLote.value);
            if (precioTotal) precioTotal.value = cleanNumberString(precioTotal.value);
        }, true);
    }

    /*
     * Gestión de parámetros: variables y plantillas
     */
    // Formulario agregar nacionalidad o tipo de contrato (comparten clase)
    const formAddNacionalidad = document.getElementById('formAddNacionalidad');
    const formAddTipo = document.getElementById('formAddTipo');
    [formAddNacionalidad, formAddTipo].forEach(form => {
        if (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const formData = new FormData(form);
                fetch('index.php?ruta=parametros', {
                    method: 'POST',
                    body: formData
                }).then(r => r.text()).then(resp => {
                    let title = 'Guardado';
                    let text = 'Registro añadido correctamente.';
                    let icon = 'success';
                    if (resp.includes('error')) {
                        title = 'Error';
                        text = 'No se pudo guardar.';
                        icon = 'error';
                    }
                    Swal.fire(title, text, icon).then(() => {
                        window.location.reload();
                    });
                }).catch(() => {
                    Swal.fire('Error', 'No se pudo guardar.', 'error');
                });
            });
        }
    });
    // Botones editar variable
    const btnsEditarVariable = document.querySelectorAll('.btnEditarVariable');
    const formEditarVariable = document.getElementById('formEditarVariable');
    if (btnsEditarVariable && formEditarVariable) {
        btnsEditarVariable.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const ident = this.getAttribute('data-identificador');
                const nombre = this.getAttribute('data-nombre');
                document.getElementById('editarVariableId').value = id;
                document.getElementById('editarVariableIdentificador').value = ident;
                document.getElementById('editarVariableNombre').value = nombre;
            });
        });
        formEditarVariable.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro de modificar los datos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formEditarVariable);
                    fetch('index.php?ruta=parametros', {
                        method: 'POST',
                        body: formData
                    }).then(r => r.text()).then(resp => {
                        let title = 'Guardado';
                        let text = 'Registro actualizado correctamente.';
                        let icon = 'success';
                        if (resp.includes('error')) {
                            title = 'Error';
                            text = 'No se pudo actualizar.';
                            icon = 'error';
                        }
                        Swal.fire(title, text, icon).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo actualizar.', 'error');
                    });
                }
            });
        });
    }
    // Subir plantilla
    const formSubirPlantilla = document.getElementById('formSubirPlantilla');
    if (formSubirPlantilla) {
        formSubirPlantilla.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(formSubirPlantilla);
            fetch('index.php?ruta=parametros', {
                method: 'POST',
                body: formData
            }).then(r => r.text()).then(resp => {
                let title = 'Guardado';
                let text = 'Plantilla subida correctamente.';
                let icon = 'success';
                if (resp.includes('error')) {
                    title = 'Error';
                    text = 'No se pudo subir la plantilla.';
                    icon = 'error';
                }
                Swal.fire(title, text, icon).then(() => {
                    window.location.reload();
                });
            }).catch(() => {
                Swal.fire('Error', 'No se pudo subir la plantilla.', 'error');
            });
        });
    }

    /*
     * Gestión de contratos: edición
     */
    const btnsEditarContrato = document.querySelectorAll('.btnEditarContrato');
    const modalEditarContrato = document.getElementById('modalEditarContrato');
    const formEditarContrato = document.getElementById('formEditarContrato');
    if (btnsEditarContrato && modalEditarContrato && formEditarContrato) {
        // Elementos para manejo de fracciones en edición
        const inputFraccionEditar = document.getElementById('inputFraccionEditar');
        const contenedorFraccionesEditar = document.getElementById('contenedorFraccionesEditar');
        const hiddenFraccionesEditar = document.getElementById('hiddenFraccionesEditar');
        let fraccionesEditar = [];
        // Función para renderizar las etiquetas de fracciones en edición
        const renderFraccionesEditar = () => {
            if (!contenedorFraccionesEditar) return;
            contenedorFraccionesEditar.innerHTML = '';
            fraccionesEditar.forEach((frac, idx) => {
                const badge = document.createElement('span');
                badge.style.display = 'inline-flex';
                badge.style.alignItems = 'center';
                badge.style.borderRadius = '12px';
                badge.style.backgroundColor = '#f0f2f5';
                badge.style.color = '#333';
                badge.style.padding = '4px 8px';
                badge.style.margin = '2px';
                badge.style.fontSize = '0.8rem';
                badge.textContent = frac;
                const removeSpan = document.createElement('span');
                removeSpan.style.marginLeft = '6px';
                removeSpan.style.color = '#dc3545';
                removeSpan.style.cursor = 'pointer';
                removeSpan.textContent = '×';
                removeSpan.addEventListener('click', () => {
                    fraccionesEditar.splice(idx, 1);
                    renderFraccionesEditar();
                });
                badge.appendChild(removeSpan);
                contenedorFraccionesEditar.appendChild(badge);
            });
            if (hiddenFraccionesEditar) {
                hiddenFraccionesEditar.value = fraccionesEditar.join(',');
            }
        };
        // Configurar ingreso de fracciones al presionar Enter
        if (inputFraccionEditar) {
            inputFraccionEditar.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const valor = this.value.trim();
                    if (valor && !fraccionesEditar.includes(valor)) {
                        fraccionesEditar.push(valor);
                        renderFraccionesEditar();
                    }
                    this.value = '';
                }
            });
        }
        btnsEditarContrato.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-contrato-id');
                document.getElementById('editarContratoId').value = id;
                document.getElementById('editarContratoMensualidades').value = this.getAttribute('data-mensualidades') || '';
                document.getElementById('editarContratoSuperficie').value = this.getAttribute('data-superficie') || '';
                // Cargar fracciones existentes
                const fracString = this.getAttribute('data-fraccion') || '';
                fraccionesEditar = [];
                if (fracString) {
                    fracString.split(',').forEach(item => {
                        const trimmed = item.trim();
                        if (trimmed && !fraccionesEditar.includes(trimmed)) {
                            fraccionesEditar.push(trimmed);
                        }
                    });
                }
                renderFraccionesEditar();
                document.getElementById('editarContratoEntrega').value = this.getAttribute('data-entrega') || '';
                document.getElementById('editarContratoFirma').value = this.getAttribute('data-firma') || '';
                document.getElementById('editarContratoHabitacional').value = this.getAttribute('data-habitacional') || '';
                document.getElementById('editarContratoInicio').value = this.getAttribute('data-inicio') || '';
                document.getElementById('editarContratoTipo').value = this.getAttribute('data-tipo') || '';
            });
        });
        formEditarContrato.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Estás seguro de modificar los datos?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, modificar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formEditarContrato);
                    const url = formEditarContrato.getAttribute('action');
                    fetch(url, {
                        method: 'POST',
                        body: formData
                    }).then(r => r.text()).then(resp => {
                        let title = 'Guardado';
                        let text = 'Contrato actualizado correctamente.';
                        let icon = 'success';
                        if (resp.includes('error')) {
                            title = 'Error';
                            text = 'No se pudo actualizar.';
                            icon = 'error';
                        }
                        Swal.fire(title, text, icon).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo actualizar.', 'error');
                    });
                }
            });
        });
    }
});