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
                const tipocontratoId = this.getAttribute('data-tipocontrato-id');
                const tipocontratoNombre = this.getAttribute('data-tipocontrato-nombre');
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
                // Asignar identificador al select de tipo de contrato
                document.getElementById('editarTipoContrato').value = tipocontratoId;
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
                const tipocontratoNombre = this.getAttribute('data-tipocontrato-nombre');
                const descripcion = this.getAttribute('data-descripcion');
                const superficie = this.getAttribute('data-superficie');
                const clave = this.getAttribute('data-clave');
                let lotesStr = this.getAttribute('data-lotes');
                if (lotesStr) lotesStr = decodeHtml(lotesStr);
                const precioLote = this.getAttribute('data-preciolote');
                const precioTotal = this.getAttribute('data-preciototal');
                // Llenar campos de la vista
                document.getElementById('verNombreDesarrollo').value = nombre;
                // Mostrar nombre del tipo de contrato en la vista
                document.getElementById('verTipoContrato').value = tipocontratoNombre;
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
                // Mostrar nombre de la nacionalidad en la vista
                document.getElementById('verNacionalidadCliente').value = this.getAttribute('data-nacionalidad-nombre');
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
                const nacionalidadId = this.getAttribute('data-nacionalidad-id');
                const selectNac = document.getElementById('editarNacionalidadCliente');
                if (selectNac) {
                    selectNac.value = nacionalidadId;
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
    // Identificador y nombre del tipo de contrato en la creación
    const contratoTipoId = document.getElementById('contratoTipoId');
    const contratoTipoNombre = document.getElementById('contratoTipoNombre');

    // === Campos adicionales para manejo financiero de contratos (creación) ===
    const montoInmueble = document.getElementById('montoInmueble');
    const montoInmuebleFixed = document.getElementById('montoInmuebleFixed');
    const enganche = document.getElementById('enganche');
    const engancheFixed = document.getElementById('engancheFixed');
    const saldoPago = document.getElementById('saldoPago');
    const saldoPagoFixed = document.getElementById('saldoPagoFixed');
    const penalizacion = document.getElementById('penalizacion');
    const penalizacionFixed = document.getElementById('penalizacionFixed');

    /*
     * Convierte un número a letras solicitando al backend el resultado.
     * Actualiza el input oculto asociado con el resultado devuelto.
     * Si el número no es válido, se limpia el input de destino.
     * @param {number|string} num Valor numérico a convertir
     * @param {HTMLElement} target Elemento input hidden donde se colocará el resultado
     */
    function convertirNumeroALetras(num, target) {
        if (!target) return;
        const val = parseFloat(num);
        if (isNaN(val) || val === 0) {
            target.value = '';
            return;
        }
        const formData = new URLSearchParams();
        formData.append('num', val);
        fetch('ajax/numero_a_letras.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: formData.toString()
        }).then(r => r.text()).then(res => {
            target.value = res.trim();
        }).catch(() => {
            target.value = String(val);
        });
    }

    /*
     * Actualiza los campos derivados (saldo y penalización) en tiempo real
     * para el formulario de creación de contratos. Calcula saldo como
     * monto - enganche y penalización como 10% del monto. También
     * actualiza los campos ocultos con las cantidades en letras.
     
    function actualizarCalculosContrato() {
        const montoVal = parseFloat(montoInmueble && montoInmueble.value ? montoInmueble.value : 0) || 0;
        const engancheVal = parseFloat(enganche && enganche.value ? enganche.value : 0) || 0;
        const saldoVal = montoVal - engancheVal;
        const penalVal = montoVal * 0.10;
        if (saldoPago) {
            saldoPago.value = saldoVal.toFixed(2);
        }
        if (penalizacion) {
            penalizacion.value = penalVal.toFixed(2);
        }
        convertirNumeroALetras(montoVal, montoInmuebleFixed);
        convertirNumeroALetras(engancheVal, engancheFixed);
        convertirNumeroALetras(saldoVal, saldoPagoFixed);
        convertirNumeroALetras(penalVal, penalizacionFixed);
    }
    */
    function actualizarCalculosContrato() {
        const montoVal = Math.round(parseFloat(montoInmueble && montoInmueble.value ? montoInmueble.value : 0) || 0);
        const engancheVal = Math.round(parseFloat(enganche && enganche.value ? enganche.value : 0) || 0);
        const saldoVal = Math.round(montoVal - engancheVal);
        const penalVal = Math.round(montoVal * 0.20);

        if (saldoPago) {
            saldoPago.value = saldoVal; // entero
        }
        if (penalizacion) {
            penalizacion.value = penalVal; // entero
        }

        convertirNumeroALetras(montoVal, montoInmuebleFixed);
        convertirNumeroALetras(engancheVal, engancheFixed);
        convertirNumeroALetras(saldoVal, saldoPagoFixed);
        convertirNumeroALetras(penalVal, penalizacionFixed);
    }

    if (montoInmueble) montoInmueble.addEventListener('input', actualizarCalculosContrato);
    if (enganche) enganche.addEventListener('input', actualizarCalculosContrato);
    // Campos para manejar fracciones como etiquetas en la creación de contratos
    const inputFraccionContrato = document.getElementById('inputFraccionContrato');
    const contenedorFraccionesContrato = document.getElementById('contenedorFraccionesContrato');
    const hiddenFraccionesContrato = document.getElementById('hiddenFraccionesContrato');
    // Arrays para almacenar las fracciones seleccionadas y las disponibles
    let fraccionesContrato = [];
    let fraccionesDisponibles = [];
    // Contenedor que mostrará las fracciones disponibles del desarrollo en la creación de contratos
    const listaFraccionesDisponiblesContrato = document.getElementById('listaFraccionesDisponiblesContrato');
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
                // Reiniciar los campos de tipo de contrato
                if (contratoTipoId) contratoTipoId.value = '';
                if (contratoTipoNombre) contratoTipoNombre.value = '';
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
                // Limpiar la lista visual de fracciones disponibles
                if (listaFraccionesDisponiblesContrato) {
                    listaFraccionesDisponiblesContrato.innerHTML = '';
                }
                // Limpiar campos financieros para creación de contrato
                if (montoInmueble) montoInmueble.value = '';
                if (montoInmuebleFixed) montoInmuebleFixed.value = '';
                if (enganche) enganche.value = '';
                if (engancheFixed) engancheFixed.value = '';
                if (saldoPago) saldoPago.value = '';
                if (saldoPagoFixed) saldoPagoFixed.value = '';
                if (penalizacion) penalizacion.value = '';
                if (penalizacionFixed) penalizacionFixed.value = '';
                const parcial = document.getElementById('parcialidadesAnuales');
                if (parcial) parcial.value = '';
                const dia = document.getElementById('diaPago');
                if (dia) dia.value = '';
                const rango = document.getElementById('rangoCompromisoPago');
                if (rango) rango.value = '';
                const vigencia = document.getElementById('vigenciaPagare');
                if (vigencia) vigencia.value = '';
            });
        });
        // Al cambiar de desarrollo llenar superficie y tipo
        if (selectDesarrolloContrato) {
        selectDesarrolloContrato.addEventListener('change', function () {
                const selected = this.options[this.selectedIndex];
                const sup = selected.getAttribute('data-superficie');
                const tipoId = selected.getAttribute('data-tipo-id');
                const tipoNombre = selected.getAttribute('data-tipo-nombre');
                const lotes = selected.getAttribute('data-lotes');
                // Asignar superficie y tipo de contrato (identificador y nombre)
                if (contratoSuperficie) contratoSuperficie.value = sup || '';
                if (contratoTipoId) contratoTipoId.value = tipoId || '';
                if (contratoTipoNombre) contratoTipoNombre.value = tipoNombre || '';
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
                // Renderizar la lista de opciones disponibles
                if (typeof renderListaDisponiblesContrato === 'function') {
                    renderListaDisponiblesContrato();
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
                // Actualizar la lista de fracciones disponibles para reflejar las seleccionadas
                if (typeof renderListaDisponiblesContrato === 'function') {
                    renderListaDisponiblesContrato();
                }
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

            // Función para renderizar la lista de fracciones disponibles como botones clicables.
            function renderListaDisponiblesContrato() {
                if (!listaFraccionesDisponiblesContrato) return;
                listaFraccionesDisponiblesContrato.innerHTML = '';
                if (Array.isArray(fraccionesDisponibles) && fraccionesDisponibles.length > 0) {
                    fraccionesDisponibles.forEach(frac => {
                        // Mostrar sólo las fracciones que aún no han sido seleccionadas
                        if (!fraccionesContrato.includes(frac)) {
                            const item = document.createElement('span');
                            item.style.display = 'inline-block';
                            item.style.margin = '2px';
                            item.style.padding = '4px 8px';
                            item.style.borderRadius = '12px';
                            item.style.backgroundColor = '#e2e6ea';
                            item.style.color = '#333';
                            item.style.cursor = 'pointer';
                            item.style.fontSize = '0.8rem';
                            item.textContent = frac;
                            item.addEventListener('click', () => {
                                if (!fraccionesContrato.includes(frac)) {
                                    fraccionesContrato.push(frac);
                                    renderFraccionesContrato();
                                }
                            });
                            listaFraccionesDisponiblesContrato.appendChild(item);
                        }
                    });
                }
            }
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
                // Mostrar nombre del tipo de contrato en vista del contrato
                document.getElementById('verContratoTipo').value = this.getAttribute('data-tipo-nombre');
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
                responsive: true,
                order: [[1, 'dec']] // Ordenar por segunda columna (índice 1) descendente por defecto
            });
            // Filtros por desarrollo y tipo de contrato
            const filtroDesarrollo = document.getElementById('filtroDesarrollo');
            const filtroTipo = document.getElementById('filtroTipo');
            if (filtroDesarrollo) {
                filtroDesarrollo.addEventListener('change', function () {
                    const val = this.value;
                    if (val) {
                        // columna 2: Desarrollo
                        dataTableContratos.column(4).search('^' + val + '$', true, false).draw();
                    } else {
                        dataTableContratos.column(4).search('').draw();
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

    // Editar plantilla
    const btnsEditarPlantilla = document.querySelectorAll('.btnEditarPlantilla');
    const formEditarPlantilla = document.getElementById('formEditarPlantilla');
    if (btnsEditarPlantilla && formEditarPlantilla) {
        btnsEditarPlantilla.forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const tipoId = this.getAttribute('data-tipo-id');
                const modalIdInput = document.getElementById('editarPlantillaId');
                const modalTipoSelect = document.getElementById('editarPlantillaTipo');
                if (modalIdInput) modalIdInput.value = id;
                if (modalTipoSelect) modalTipoSelect.value = tipoId;
            });
        });
        formEditarPlantilla.addEventListener('submit', function (e) {
            // Confirmar edición de plantilla
            e.preventDefault();
            Swal.fire({
                title: '¿Actualizar plantilla?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, actualizar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(formEditarPlantilla);
                    fetch('index.php?ruta=parametros', {
                        method: 'POST',
                        body: formData
                    }).then(r => r.text()).then(resp => {
                        let title = 'Actualizado';
                        let text = 'Plantilla actualizada correctamente.';
                        let icon = 'success';
                        if (resp.includes('error')) {
                            title = 'Error';
                            text = 'No se pudo actualizar la plantilla.';
                            icon = 'error';
                        }
                        Swal.fire(title, text, icon).then(() => {
                            window.location.reload();
                        });
                    }).catch(() => {
                        Swal.fire('Error', 'No se pudo actualizar la plantilla.', 'error');
                    });
                }
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
        // Contenedor de fracciones disponibles para el desarrollo seleccionado en edición
        const listaFraccionesDisponiblesEditar = document.getElementById('listaFraccionesDisponiblesEditar');
        // Arreglo para almacenar las fracciones seleccionadas actualmente
        let fraccionesEditar = [];
        // Arreglo para almacenar todas las fracciones disponibles del desarrollo
        let fraccionesDisponiblesEditar = [];

        // === Campos financieros para edición de contrato ===
        const editarMontoInmueble = document.getElementById('editarMontoInmueble');
        const editarMontoInmuebleFixed = document.getElementById('editarMontoInmuebleFixed');
        const editarEnganche = document.getElementById('editarEnganche');
        const editarEngancheFixed = document.getElementById('editarEngancheFixed');
        const editarSaldoPago = document.getElementById('editarSaldoPago');
        const editarSaldoPagoFixed = document.getElementById('editarSaldoPagoFixed');
        const editarPenalizacion = document.getElementById('editarPenalizacion');
        const editarPenalizacionFixed = document.getElementById('editarPenalizacionFixed');
        const editarParcialidades = document.getElementById('editarParcialidadesAnuales');
        const editarVigenciaPagare = document.getElementById('editarVigenciaPagare');
        // Nuevo campo de folio y rango de pago en edición
        const editarContratoFolio   = document.getElementById('editarContratoFolio');
        const editarRangoPagoInicio = document.getElementById('editarRangoPagoInicio');
        const editarRangoPagoFin    = document.getElementById('editarRangoPagoFin');

        /*
         * Actualiza saldo y penalización en tiempo real para la edición de contratos.
         */
        function actualizarCalculosEditar() {
            const montoVal = parseFloat(editarMontoInmueble && editarMontoInmueble.value ? editarMontoInmueble.value : 0) || 0;
            const engancheVal = parseFloat(editarEnganche && editarEnganche.value ? editarEnganche.value : 0) || 0;
            const saldoVal = montoVal - engancheVal;
            const penalVal = montoVal * 0.10;
            if (editarSaldoPago) {
                editarSaldoPago.value = saldoVal.toFixed(2);
            }
            if (editarPenalizacion) {
                editarPenalizacion.value = penalVal.toFixed(2);
            }
            // Actualizar campos en letras
            convertirNumeroALetras(montoVal, editarMontoInmuebleFixed);
            convertirNumeroALetras(engancheVal, editarEngancheFixed);
            convertirNumeroALetras(saldoVal, editarSaldoPagoFixed);
            convertirNumeroALetras(penalVal, editarPenalizacionFixed);

            // Actualizar pago mensual en letras si existe el campo
            const editarPagoMensual = document.getElementById('editarPagoMensual');
            const editarPagoMensualFixed = document.getElementById('editarPagoMensualFixed');
            if (editarPagoMensual && editarPagoMensualFixed) {
                const pmVal = parseFloat(editarPagoMensual.value || '0');
                convertirNumeroALetras(pmVal, editarPagoMensualFixed);
            }
        }
        if (editarMontoInmueble) editarMontoInmueble.addEventListener('input', actualizarCalculosEditar);
        if (editarEnganche) editarEnganche.addEventListener('input', actualizarCalculosEditar);

        // Convertir pago mensual a letras en edición
        const campoPagoMensualEditar = document.getElementById('editarPagoMensual');
        const campoPagoMensualEditarFixed = document.getElementById('editarPagoMensualFixed');
        if (campoPagoMensualEditar && campoPagoMensualEditarFixed) {
            campoPagoMensualEditar.addEventListener('input', () => {
                const pmVal = parseFloat(campoPagoMensualEditar.value || '0');
                convertirNumeroALetras(pmVal, campoPagoMensualEditarFixed);
            });
        }

        // Actualizar superficie convertida a letras en edición cuando cambia el valor
        const editarSuperficieInput = document.getElementById('editarContratoSuperficie');
        const editarSuperficieHidden = document.getElementById('editarSuperficieFixed');
        if (editarSuperficieInput && editarSuperficieHidden) {
            const actualizarSuperficieLetras = () => {
                const val = parseFloat(editarSuperficieInput.value || '0');
                const entero = Math.floor(val);
                convertirNumeroALetras(entero, editarSuperficieHidden);
            };
            editarSuperficieInput.addEventListener('input', actualizarSuperficieLetras);
        }
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
            // Después de renderizar etiquetas, actualizar la lista de disponibles
            if (typeof renderListaDisponiblesEditar === 'function') {
                renderListaDisponiblesEditar();
            }
        };

        // Función para renderizar la lista de fracciones disponibles en edición
        function renderListaDisponiblesEditar() {
            if (!listaFraccionesDisponiblesEditar) return;
            listaFraccionesDisponiblesEditar.innerHTML = '';
            if (Array.isArray(fraccionesDisponiblesEditar) && fraccionesDisponiblesEditar.length > 0) {
                fraccionesDisponiblesEditar.forEach(frac => {
                    if (!fraccionesEditar.includes(frac)) {
                        const item = document.createElement('span');
                        item.style.display = 'inline-block';
                        item.style.margin = '2px';
                        item.style.padding = '4px 8px';
                        item.style.borderRadius = '12px';
                        item.style.backgroundColor = '#e2e6ea';
                        item.style.color = '#333';
                        item.style.cursor = 'pointer';
                        item.style.fontSize = '0.8rem';
                        item.textContent = frac;
                        item.addEventListener('click', () => {
                            if (!fraccionesEditar.includes(frac)) {
                                fraccionesEditar.push(frac);
                                renderFraccionesEditar();
                            }
                        });
                        listaFraccionesDisponiblesEditar.appendChild(item);
                    }
                });
            }
        }
        // Configurar ingreso de fracciones al presionar Enter
        if (inputFraccionEditar) {
            inputFraccionEditar.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const valor = this.value.trim();
                    if (valor && !fraccionesEditar.includes(valor)) {
                        // Si existen fracciones disponibles, sólo permitir las que están en la lista
                        if (fraccionesDisponiblesEditar.length === 0 || fraccionesDisponiblesEditar.includes(valor)) {
                            fraccionesEditar.push(valor);
                            renderFraccionesEditar();
                        }
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
                // Asignar superficie convertida a letras si existe
                const supFixedVal = this.getAttribute('data-superficie-fixed') || '';
                const editarSupFixed = document.getElementById('editarSuperficieFixed');
                if (editarSupFixed) {
                    editarSupFixed.value = supFixedVal;
                }
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
                // Decodificar fracciones disponibles desde el atributo data-lotes
                fraccionesDisponiblesEditar = [];
                const lotesAttr = this.getAttribute('data-lotes') || '';
                if (lotesAttr) {
                    const decoded = decodeHtml(lotesAttr);
                    try {
                        const parsed = JSON.parse(decoded);
                        if (Array.isArray(parsed)) {
                            fraccionesDisponiblesEditar = parsed;
                        }
                    } catch (err) {
                        fraccionesDisponiblesEditar = decoded.split(',').map(l => l.trim()).filter(Boolean);
                    }
                }
                renderFraccionesEditar();
                document.getElementById('editarContratoEntrega').value = this.getAttribute('data-entrega') || '';
                document.getElementById('editarContratoFirma').value = this.getAttribute('data-firma') || '';
                // Habitacional: cargar el contenido en el textarea simple
                const habContent = this.getAttribute('data-habitacional') || '';
                const habitacionalTextarea = document.getElementById('editarHabitacional');
                if (habitacionalTextarea) {
                    habitacionalTextarea.value = habContent.toUpperCase();
                }
                document.getElementById('editarContratoInicio').value = this.getAttribute('data-inicio') || '';
                // Asignar identificador y nombre del tipo de contrato en edición
                const tipoId = this.getAttribute('data-tipo-id') || '';
                const tipoNombre = this.getAttribute('data-tipo-nombre') || '';
                const inputTipoId = document.getElementById('editarContratoTipoId');
                const inputTipoNombre = document.getElementById('editarContratoTipoNombre');
                if (inputTipoId) inputTipoId.value = tipoId;
                if (inputTipoNombre) inputTipoNombre.value = tipoNombre;

                // Rellenar campos financieros desde los atributos de datos
                if (editarMontoInmueble) {
                    editarMontoInmueble.value = this.getAttribute('data-monto') || '';
                }
                if (editarMontoInmuebleFixed) {
                    editarMontoInmuebleFixed.value = this.getAttribute('data-monto-fixed') || '';
                }
                if (editarEnganche) {
                    editarEnganche.value = this.getAttribute('data-enganche') || '';
                }
                if (editarEngancheFixed) {
                    editarEngancheFixed.value = this.getAttribute('data-enganche-fixed') || '';
                }
                if (editarSaldoPago) {
                    editarSaldoPago.value = this.getAttribute('data-saldo') || '';
                }
                if (editarSaldoPagoFixed) {
                    editarSaldoPagoFixed.value = this.getAttribute('data-saldo-fixed') || '';
                }
                if (editarParcialidades) {
                    editarParcialidades.value = this.getAttribute('data-parcialidades') || '';
                }
                // Asignar pago mensual y su versión fija
                const pagoMensualVal = this.getAttribute('data-pago-mensual') || '';
                const pagoMensualFixedVal = this.getAttribute('data-pago-mensual-fixed') || '';
                const editarPagoMensual = document.getElementById('editarPagoMensual');
                const editarPagoMensualFixed = document.getElementById('editarPagoMensualFixed');
                if (editarPagoMensual) editarPagoMensual.value = pagoMensualVal;
                if (editarPagoMensualFixed) editarPagoMensualFixed.value = pagoMensualFixedVal;
                // Convertir a letras después de asignar
                if (editarPagoMensual && editarPagoMensualFixed) {
                    const pmNum = parseFloat(pagoMensualVal || '0');
                    convertirNumeroALetras(pmNum, editarPagoMensualFixed);
                }
                if (editarPenalizacion) {
                    editarPenalizacion.value = this.getAttribute('data-penalizacion') || '';
                }
                if (editarPenalizacionFixed) {
                    editarPenalizacionFixed.value = this.getAttribute('data-penalizacion-fixed') || '';
                }
                if (editarVigenciaPagare) {
                    editarVigenciaPagare.value = this.getAttribute('data-vigencia-pagare') || '';
                }
                // Asignar fecha del contrato (convertir de formato largo a YYYY-MM-DD) y su campo fijo
                const fechaContratoStr = this.getAttribute('data-fecha-contrato') || '';
                const fechaContratoFixedStr = this.getAttribute('data-fecha-contrato-fixed') || '';
                if (fechaContratoStr) {
                    // Parsear "DD de Mes de YYYY" a YYYY-MM-DD
                    const mapMes = {
                        'enero': '01','febrero': '02','marzo': '03','abril': '04','mayo': '05','junio': '06',
                        'julio': '07','agosto': '08','septiembre': '09','octubre': '10','noviembre': '11','diciembre': '12'
                    };
                    const parts = fechaContratoStr.trim().toLowerCase().split(' de ');
                    if (parts.length === 3) {
                        const dia = parts[0].padStart(2, '0');
                        const mesTxt = parts[1];
                        const anio = parts[2];
                        const mesNum = mapMes[mesTxt] || '01';
                        const fechaISO = `${anio}-${mesNum}-${dia}`;
                        const fcInput = document.getElementById('editarFechaContrato');
                        if (fcInput) fcInput.value = fechaISO;
                    }
                }
                // Asignar valor fijo
                const fcFixedInput = document.getElementById('editarFechaContratoFixed');
                if (fcFixedInput) fcFixedInput.value = fechaContratoFixedStr;
                // Asignar folio
                if (editarContratoFolio) {
                    editarContratoFolio.value = this.getAttribute('data-folio') || '';
                }
                // Asignar rango de pago (inicio y fin) parseando la cadena "DD de Mes de YYYY a DD de Mes de YYYY"
                const rangoPagoStr = this.getAttribute('data-rango-pago') || '';
                if (rangoPagoStr && (editarRangoPagoInicio || editarRangoPagoFin)) {
                    // Función de ayuda para convertir una fecha larga a YYYY-MM-DD
                    const mesesMap = {
                        'enero': '01',
                        'febrero': '02',
                        'marzo': '03',
                        'abril': '04',
                        'mayo': '05',
                        'junio': '06',
                        'julio': '07',
                        'agosto': '08',
                        'septiembre': '09',
                        'octubre': '10',
                        'noviembre': '11',
                        'diciembre': '12'
                    };
                    const partes = rangoPagoStr.split(' a ');
                    const parseFecha = (f) => {
                        const arr = f.trim().toLowerCase().split(' de ');
                        if (arr.length === 3) {
                            const dia = arr[0].padStart(2, '0');
                            const mesTxt = arr[1];
                            const anio = arr[2];
                            const mesNum = mesesMap[mesTxt] || '01';
                            return `${anio}-${mesNum}-${dia}`;
                        }
                        return '';
                    };
                    const inicio = parseFecha(partes[0]);
                    const fin = partes.length > 1 ? parseFecha(partes[1]) : '';
                    if (editarRangoPagoInicio) editarRangoPagoInicio.value = inicio;
                    if (editarRangoPagoFin) editarRangoPagoFin.value = fin;
                }
                // Después de asignar valores, actualizar cálculos por si hiciera falta
                actualizarCalculosEditar();
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

    /*
     * Generación de contratos: manejar clic en botón
     *
     * Al pulsar el botón de generación se muestra una barra de progreso utilizando
     * SweetAlert2. Se envía una solicitud fetch al endpoint AJAX y, una vez
     * completada la generación, se descarga automáticamente el archivo ZIP.
     */
    const generarBtns = document.querySelectorAll('.btnGenerarContrato');
    if (generarBtns) {
        generarBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const contratoId = this.getAttribute('data-contrato-id');
                if (!contratoId) return;
                // Mostrar cuadro de progreso
                Swal.fire({
                    title: 'Generando contrato',
                    html: '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="barraProgresoContrato"></div></div>',
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
                const barra = document.getElementById('barraProgresoContrato');
                let progreso = 0;
                const intervalo = setInterval(() => {
                    progreso = Math.min(progreso + 10, 90);
                    if (barra) barra.style.width = progreso + '%';
                }, 500);
                fetch('ajax/generar_contrato.php?contrato_id=' + contratoId)
                    .then(response => response.json())
                    .then(data => {
                        clearInterval(intervalo);
                        if (barra) barra.style.width = '100%';
                        if (data.status === 'ok' && data.zip) {
                            // Esperar un momento para mostrar el 100% y luego cerrar
                            setTimeout(() => {
                                Swal.close();
                                const enlace = document.createElement('a');
                                enlace.href = data.zip;
                                // Nombre sugerido para descarga
                                enlace.download = data.nombre || 'contrato.zip';
                                document.body.appendChild(enlace);
                                enlace.click();
                                enlace.remove();
                            }, 600);
                        } else {
                            Swal.fire('Error', data.msg || 'No se pudo generar el contrato', 'error');
                        }
                    })
                    .catch(() => {
                        clearInterval(intervalo);
                        Swal.fire('Error', 'No se pudo generar el contrato', 'error');
                    });
            });
        });
    }

    /*
     * === Gestión de formulario Crear Contrato (página completa) ===
     * Este bloque se activa en la página crearContrato.php. Maneja la selección del desarrollo,
     * muestra el tipo de contrato y superficie, gestiona la selección de fracciones (lotes) y
     * permite al usuario agregar manualmente fracciones como etiquetas. También actualiza en tiempo
     * real los cálculos financieros (saldo y penalización) y convierte los números a letras para
     * los campos "fixed" utilizando el servicio AJAX numero_a_letras.php.
     * Configuración de la página Crear contrato. Esta sección gestiona la
     * selección de desarrollo, la carga de lotes disponibles, el ingreso de
     * fracciones manuales y las operaciones aritméticas y de conversión
     * numérica a letras para los campos financieros. Utiliza IDs de la vista
     * crearContrato.php.
     */
    (function () {
        // Identificar elementos de la nueva página de creación de contrato
        const selectDesarrollo   = document.getElementById('selectDesarrolloCrear');
        const inputTipoId        = document.getElementById('crearTipoId');
        const inputTipoNombre    = document.getElementById('crearTipoNombre');
        const inputSuperficie    = document.getElementById('crearSuperficie');
        const inputSuperficieFixed = document.getElementById('crearSuperficieFixed');
        const inputFraccion      = document.getElementById('inputFraccionCrear');
        const contenedorFracciones = document.getElementById('contenedorFraccionesCrear');
        const listaFraccionesDisponibles = document.getElementById('listaFraccionesDisponiblesCrear');
        const hiddenFracciones   = document.getElementById('hiddenFraccionesCrear');
        const crearMontoInmueble      = document.getElementById('crearMontoInmueble');
        const crearMontoInmuebleFixed = document.getElementById('crearMontoInmuebleFixed');
        const crearEnganche           = document.getElementById('crearEnganche');
        const crearEngancheFixed      = document.getElementById('crearEngancheFixed');
        const crearSaldoPago          = document.getElementById('crearSaldoPago');
        const crearSaldoPagoFixed     = document.getElementById('crearSaldoPagoFixed');
        const crearPenalizacion       = document.getElementById('crearPenalizacion');
        const crearPenalizacionFixed  = document.getElementById('crearPenalizacionFixed');
        // Arreglos para almacenar fracciones
        let fraccionesSeleccionadas = [];
        let fraccionesDisponibles = [];
        if (selectDesarrollo) {
            // Renderizar etiquetas de fracciones seleccionadas
            const renderFraccionesCrear = () => {
                if (!contenedorFracciones) return;
                contenedorFracciones.innerHTML = '';
                fraccionesSeleccionadas.forEach((frac, idx) => {
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
                        fraccionesSeleccionadas.splice(idx, 1);
                        renderFraccionesCrear();
                    });
                    badge.appendChild(removeSpan);
                    contenedorFracciones.appendChild(badge);
                });
                if (hiddenFracciones) {
                    hiddenFracciones.value = fraccionesSeleccionadas.join(',');
                }
                // Actualizar lista disponible
                renderListaDisponiblesCrear();
            };
            // Renderizar lista de fracciones disponibles
            const renderListaDisponiblesCrear = () => {
                if (!listaFraccionesDisponibles) return;
                listaFraccionesDisponibles.innerHTML = '';
                if (Array.isArray(fraccionesDisponibles)) {
                    fraccionesDisponibles.forEach(frac => {
                        if (!fraccionesSeleccionadas.includes(frac)) {
                            const item = document.createElement('span');
                            item.style.display = 'inline-block';
                            item.style.margin = '2px';
                            item.style.padding = '4px 8px';
                            item.style.borderRadius = '12px';
                            item.style.backgroundColor = '#e2e6ea';
                            item.style.color = '#333';
                            item.style.cursor = 'pointer';
                            item.style.fontSize = '0.8rem';
                            item.textContent = frac;
                            item.addEventListener('click', () => {
                                fraccionesSeleccionadas.push(frac);
                                renderFraccionesCrear();
                            });
                            listaFraccionesDisponibles.appendChild(item);
                        }
                    });
                }
            };
            // Al cambiar de desarrollo actualizar tipo, superficie y lotes disponibles
            selectDesarrollo.addEventListener('change', function () {
                const selected = this.selectedOptions[0];
                if (selected) {
                    const tipoId    = selected.getAttribute('data-tipo-id') || '';
                    const tipoNombre= selected.getAttribute('data-tipo-nombre') || '';
                    const superficie= selected.getAttribute('data-superficie') || '';
                    const lotesAttr = selected.getAttribute('data-lotes') || '';
                    if (inputTipoId) inputTipoId.value = tipoId;
                    if (inputTipoNombre) inputTipoNombre.value = tipoNombre;
                    if (inputSuperficie) inputSuperficie.value = superficie;
                    // Convertir superficie a letras sólo con la parte entera y almacenarla en el campo oculto
                    if (inputSuperficieFixed) {
                        const supVal = parseFloat(superficie || '0');
                        // Utilizar Math.floor para evitar decimales
                        const entero = Math.floor(supVal);
                        convertirNumeroALetras(entero, inputSuperficieFixed);
                    }
                    // Parsear lotes
                    fraccionesDisponibles = [];
                    if (lotesAttr) {
                        let decoded = decodeHtml(lotesAttr);
                        try {
                            const parsed = JSON.parse(decoded);
                            if (Array.isArray(parsed)) {
                                fraccionesDisponibles = parsed;
                            }
                        } catch (err) {
                            fraccionesDisponibles = decoded.split(',').map(l => l.trim()).filter(Boolean);
                        }
                    }
                    fraccionesSeleccionadas = [];
                    renderFraccionesCrear();
                }
            });
            // Ingreso manual de fracciones
            if (inputFraccion) {
                inputFraccion.addEventListener('keypress', function (e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const valor = this.value.trim().toUpperCase();
                        if (valor && !fraccionesSeleccionadas.includes(valor)) {
                            fraccionesSeleccionadas.push(valor);
                            renderFraccionesCrear();
                        }
                        this.value = '';
                    }
                });
            }
            // Calcular saldo y penalización y convertir a letras
            const actualizarCalculosCrear = () => {
                const montoVal    = parseFloat(crearMontoInmueble && crearMontoInmueble.value ? crearMontoInmueble.value : 0) || 0;
                const engancheVal = parseFloat(crearEnganche && crearEnganche.value ? crearEnganche.value : 0) || 0;
                const saldoVal    = montoVal - engancheVal;
                const penalVal    = montoVal * 0.20;
                if (crearSaldoPago) {
                    crearSaldoPago.value = saldoVal.toFixed(2);
                }
                if (crearPenalizacion) {
                    crearPenalizacion.value = penalVal.toFixed(2);
                }
                convertirNumeroALetras(montoVal, crearMontoInmuebleFixed);
                convertirNumeroALetras(engancheVal, crearEngancheFixed);
                convertirNumeroALetras(saldoVal, crearSaldoPagoFixed);
                convertirNumeroALetras(penalVal, crearPenalizacionFixed);
            };
            if (crearMontoInmueble) crearMontoInmueble.addEventListener('input', actualizarCalculosCrear);
            if (crearEnganche) crearEnganche.addEventListener('input', actualizarCalculosCrear);
        }
    })();

    /*
     * CONFIRMACIÓN DE ENVÍO PARA CREAR CONTRATO
     * Antes de enviar el formulario completo de contrato, se mostrará una
     * alerta de confirmación para que el usuario revise la información. Si
     * confirma, se envía el formulario; de lo contrario, puede seguir
     * editando. Esto no afecta a otros formularios.
     */
    (function confirmarEnvioCrearContrato() {
        const formCrear = document.getElementById('formCrearContratoCompleto');
        if (formCrear) {
            formCrear.setAttribute('novalidate', true);

            formCrear.addEventListener('submit', function (e) {
                e.preventDefault();

                // 🔎 Validación manual de campos
                let esValido = true;
                Array.from(formCrear.elements).forEach(field => {
                    if (!['INPUT', 'TEXTAREA', 'SELECT'].includes(field.tagName)) return;

                    if (!field.checkValidity()) {
                        field.classList.remove('is-valid');
                        field.classList.add('is-invalid');
                        esValido = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });

                if (!esValido) {
                    Swal.fire({
                        title: 'Campos incompletos',
                        text: 'Por favor complete todos los campos obligatorios antes de enviar.',
                        icon: 'error'
                    });
                    return; // 🚫 Detenemos aquí si no es válido
                }

                // ✅ Si todo es válido → confirmación
                Swal.fire({
                    title: 'Confirmar envío',
                    text: 'Verifique que la información capturada es correcta antes de continuar.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, enviar',
                    cancelButtonText: 'Revisar información'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const formData = new FormData(formCrear);
                        const url = formCrear.getAttribute('action');

                        fetch(url, {
                            method: 'POST',
                            body: formData
                        })
                        .then(r => r.text())
                        .then(resp => {
                            let title = 'Guardado';
                            let text = 'Contrato creado correctamente.';
                            let icon = 'success';

                            if (resp.includes('error')) {
                                title = 'Error';
                                text = 'No se pudo crear el contrato.';
                                icon = 'error';
                            } else if (!resp.includes('ok')) {
                                title = 'Aviso';
                                text = 'Respuesta inesperada: ' + resp;
                                icon = 'info';
                            }

                            Swal.fire(title, text, icon).then(() => {
                                if (icon === 'success') {
                                    window.location.reload(); // 🔄 recarga solo si todo salió bien
                                }
                            });
                        })
                        .catch(() => {
                            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                        });
                    } else {
                        Swal.fire('Continúe editando', '', 'info');
                    }
                });
            });
        }
    })();



   


    


    /*
     * CALCULAR EDAD DEL CLIENTE
     * Cuando el usuario selecciona una fecha de nacimiento en la página de
     * crear contrato, se calculará la edad en años completos y se almacenará
     * en el campo oculto correspondiente (#clienteEdad). Esta lógica se
     * ejecutará solo si los elementos existen en la página actual.
     */
    (function calcularEdadCliente() {
        const fechaNacimiento = document.getElementById('clienteFechaNacimiento');
        const campoEdad       = document.getElementById('clienteEdad');
        if (fechaNacimiento && campoEdad) {
            fechaNacimiento.addEventListener('change', () => {
                const fechaStr = fechaNacimiento.value;
                if (!fechaStr) {
                    campoEdad.value = '';
                    return;
                }
                const fn = new Date(fechaStr);
                const hoy = new Date();
                let edad = hoy.getFullYear() - fn.getFullYear();
                const m = hoy.getMonth() - fn.getMonth();
                if (m < 0 || (m === 0 && hoy.getDate() < fn.getDate())) {
                    edad--;
                }
                campoEdad.value = edad.toString();
            });
        }
    })();

    /*
     * ACTUALIZAR PAGO MENSUAL Y SU REPRESENTACIÓN EN LETRAS
     * Al ingresar un valor en el campo de pago mensual, se convertirá a un
     * texto con la función convertirNumeroALetras y se almacenará en su
     * correspondiente campo oculto. Esto se ejecuta solo si los campos
     * existen en la página de creación de contrato.
     */
    (function manejarPagoMensual() {
        const pagoMensual       = document.getElementById('crearPagoMensual');
        const pagoMensualFixed  = document.getElementById('crearPagoMensualFixed');
        if (pagoMensual && pagoMensualFixed) {
            const actualizarPago = () => {
                const val = parseFloat(pagoMensual.value || '0');
                convertirNumeroALetras(val, pagoMensualFixed);
            };
            pagoMensual.addEventListener('input', actualizarPago);
        }
    })();

    /*
     * INICIALIZAR EL EDITOR WYSIWYG (SUMMERNOTE) PARA HABITACIONAL Y COLINDANCIAS
     * Se utiliza Summernote para proporcionar un editor de texto enriquecido en
     * los campos habitacional. El contenido HTML se actualiza en un campo
     * oculto antes de enviar el formulario.
     
    (function inicializarHabitacionalEditor() {
        // Crear
        const editorCrear  = document.getElementById('crearHabitacionalEditor');
        const hiddenCrear  = document.getElementById('crearHabitacionalHtml');
        if (editorCrear && hiddenCrear && typeof $ !== 'undefined' && $(editorCrear).summernote) {
            $(editorCrear).summernote({
                placeholder: 'Describe las colindancias y uso habitacional...',
                tabsize: 2,
                height: 120
            });
            // Al enviar el formulario, copiar el contenido del editor al campo oculto
            const formCrear = document.getElementById('formCrearContratoCompleto');
            if (formCrear) {
                formCrear.addEventListener('submit', () => {
                    const html = $(editorCrear).summernote('code');
                    hiddenCrear.value = html;
                });
            }
        }
        // Editar
        const editorEditar = document.getElementById('editarHabitacionalEditor');
        const hiddenEditar = document.getElementById('editarHabitacionalHtml');
        if (editorEditar && hiddenEditar && typeof $ !== 'undefined' && $(editorEditar).summernote) {
            $(editorEditar).summernote({
                placeholder: 'Describe las colindancias y uso habitacional...',
                tabsize: 2,
                height: 120
            });
            const formEditar = document.getElementById('formEditarContrato');
            if (formEditar) {
                formEditar.addEventListener('submit', () => {
                    const html = $(editorEditar).summernote('code');
                    hiddenEditar.value = html;
                });
            }
        }
    })();
    */

    /*
     * ACTUALIZAR FECHA DE CONTRATO FIJA
     * Convierte una fecha seleccionada en el formato YYYY-MM-DD al formato
     * "DIA DÍAS DE MES DE {MES} DEL AÑO {AÑO}" y lo almacena en un campo
     * oculto. Aplica para la creación y edición de contratos.
     */
    (function manejarFechaContrato() {
        // Mapeo de números de mes a nombres en español en mayúsculas
        const mesesMayus = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEPTIEMBRE','OCTUBRE','NOVIEMBRE','DICIEMBRE'];
        const crearFecha = document.getElementById('crearFechaContrato');
        const crearFechaFixed = document.getElementById('crearFechaContratoFixed');
        if (crearFecha && crearFechaFixed) {
            crearFecha.addEventListener('change', () => {
                const val = crearFecha.value;
                if (!val) {
                    crearFechaFixed.value = '';
                    return;
                }
                const [anio, mes, dia] = val.split('-');
                const mesNombre = mesesMayus[parseInt(mes, 10) - 1] || '';
                // Dia en número seguido de la palabra DÍAS en plural
                const diaNum = parseInt(dia, 10);
                const fixed = `${diaNum} DÍAS DEL MES DE ${mesNombre} DEL AÑO ${anio}`;
                crearFechaFixed.value = fixed;
                // Actualizar día de inicio oculto
                const crearDiaInicio = document.getElementById('crearDiaInicio');
                if (crearDiaInicio) {
                    crearDiaInicio.value = diaNum;
                }
            });
        }
        // Edición
        const editarFecha = document.getElementById('editarFechaContrato');
        const editarFechaFixed = document.getElementById('editarFechaContratoFixed');
        if (editarFecha && editarFechaFixed) {
            editarFecha.addEventListener('change', () => {
                const val = editarFecha.value;
                if (!val) {
                    editarFechaFixed.value = '';
                    return;
                }
                const [anio, mes, dia] = val.split('-');
                const mesNombre = mesesMayus[parseInt(mes, 10) - 1] || '';
                const diaNum = parseInt(dia, 10);
                const fixed = `${diaNum} DÍAS DEL MES DE ${mesNombre} DEL AÑO ${anio}`;
                editarFechaFixed.value = fixed;
                const editarDiaInicio = document.getElementById('editarDiaInicio');
                if (editarDiaInicio) {
                    editarDiaInicio.value = diaNum;
                }
            });
        }
    })();

    // Inicializar intl-tel-input
        const inputTel = document.querySelector("#telefono_cliente");
        const iti = window.intlTelInput(inputTel, {
            initialCountry: "mx",            // País inicial (México)
            separateDialCode: true,          // Mostrar el código (+52) separado
            preferredCountries: ["mx","us","es","co","ar"], // Lista de favoritos
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"
        });

        // Capturar el form
        const form = document.getElementById("formCrearContratoCompleto");
        if (form) {
            form.addEventListener("submit", function(e) {
            // Si el número no es válido, mostramos error
            if (!iti.isValidNumber()) {
                e.preventDefault();
                inputTel.classList.add("is-invalid");
                return;
            }

            // Guardar el número internacional en el input oculto
            document.getElementById("cliente_telefono").value = iti.getNumber(); 
            // Ejemplo: +523323282919
            });
        }
        inputTel.addEventListener("input", function() {
        this.value = this.value.replace(/\D/g, ""); // Solo números
        });
        // Forzar que todos los inputs con class="number" solo acepten dígitos
        document.querySelectorAll('.number').forEach(input => {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, ''); // quita todo lo que no sea número
        });
    });

// tooltip y sincronización de mensualidades y rango de pago

        (function () {
        const inputMeses = document.querySelector('input[name="mensualidades"]');
        const inputAnios = document.getElementById('crearRangoPago');

        if (inputMeses && inputAnios) {
            // Inicializar tooltip de Bootstrap
            const tooltip = new bootstrap.Tooltip(inputAnios, {
            trigger: 'manual' // se controla con JS
            });

            // 🔹 De mensualidades → texto normalizado
            inputMeses.addEventListener('input', function () {
            const meses = parseInt(this.value, 10);
            if (!isNaN(meses) && meses > 0) {
                const anios = Math.floor(meses / 12);
                const mesesRestantes = meses % 12;
                inputAnios.value = formatearTiempo(anios, mesesRestantes);
                tooltip.hide();
            } else {
                inputAnios.value = '';
                tooltip.hide();
            }
            });

            // 🔹 Mientras escribe en rango_pago → mostrar tooltip
            inputAnios.addEventListener('input', function () {
            const interpretacion = interpretarTexto(this.value);
            if (interpretacion.totalMeses > 0) {
                inputAnios.setAttribute("data-bs-original-title", "= " + formatearTiempo(interpretacion.anios, interpretacion.meses));
                tooltip.show();
            } else {
                tooltip.hide();
            }
            });

            // 🔹 Al salir → normalizar valor y ocultar tooltip
            inputAnios.addEventListener('blur', function () {
            const interpretacion = interpretarTexto(this.value);
            if (interpretacion.totalMeses > 0) {
                inputMeses.value = interpretacion.totalMeses;
                this.value = formatearTiempo(interpretacion.anios, interpretacion.meses);
            }
            tooltip.hide();
            });

            // Helpers
            function interpretarTexto(texto) {
            texto = texto.toLowerCase().trim();
            let anios = 0, meses = 0;

            if (/^\d+[\.,]\d+$/.test(texto)) {
                const partes = texto.split(/[\.,]/);
                anios = parseInt(partes[0], 10);
                meses = parseInt(partes[1], 10);
            } else {
                const matchAnios = texto.match(/(\d+)\s*(a|años|año)/);
                const matchMeses = texto.match(/(\d+)\s*(m|meses|mes)/);

                if (matchAnios) anios = parseInt(matchAnios[1], 10);
                if (matchMeses) meses = parseInt(matchMeses[1], 10);

                if (!matchAnios && !matchMeses) {
                const numeros = texto.match(/\d+/g) || [];
                if (numeros.length === 1) {
                    meses = parseInt(numeros[0], 10);
                } else if (numeros.length >= 2) {
                    anios = parseInt(numeros[0], 10);
                    meses = parseInt(numeros[1], 10);
                }
                }
            }

            const totalMeses = (anios * 12) + meses;
            return { anios: Math.floor(totalMeses / 12), meses: totalMeses % 12, totalMeses };
            }

            function formatearTiempo(anios, meses) {
            let texto = '';
            if (anios > 0) texto += anios + (anios === 1 ? ' AÑO ' : ' AÑOS ');
            if (meses > 0) texto += meses + (meses === 1 ? ' MES' : ' MESES');
            if (!texto) texto = '';
            return texto.trim();
            }
        }
        })();

        // === Calcular meses automáticamente según rango de fechas ===
        (function () {
            const inicioInput = document.getElementById('rangoPagoInicio');
            const finInput = document.getElementById('rangoPagoFin');
            const mensualidadesInput = document.querySelector('input[name="mensualidades"]');
            const rangoAniosInput = document.getElementById('crearRangoPago');

            if (inicioInput && finInput && mensualidadesInput) {
                function calcularMeses() {
                    const inicioVal = inicioInput.value;
                    const finVal = finInput.value;
                    if (!inicioVal || !finVal) return;

                    const inicio = new Date(inicioVal);
                    const fin = new Date(finVal);

                    if (isNaN(inicio) || isNaN(fin) || fin < inicio) return;

                    // Calcular diferencia en meses
                    let meses = (fin.getFullYear() - inicio.getFullYear()) * 12;
                    meses += fin.getMonth() - inicio.getMonth();

                    // Ajustar si el día de fin es menor al de inicio (ej. 15 ene a 10 feb → cuenta como 0 meses completos)
                    if (fin.getDate() < inicio.getDate()) {
                        meses -= 1;
                    }

                    if (meses < 1) meses = 1; // mínimo 1 mes
                    mensualidadesInput.value = meses;

                    // Actualizar rango en años/meses (ej. "2 AÑOS, 6 MESES")
                    if (rangoAniosInput) {
                        const anios = Math.floor(meses / 12);
                        const restoMeses = meses % 12;
                        let texto = "";
                        if (anios > 0) texto += anios + (anios === 1 ? " AÑO" : " AÑOS");
                        if (restoMeses > 0) {
                            if (texto) texto += " ";
                            texto += restoMeses + (restoMeses === 1 ? " MES" : " MESES");
                        }
                        rangoAniosInput.value = texto || "1 MES";
                    }
                }

                inicioInput.addEventListener('change', calcularMeses);
                finInput.addEventListener('change', calcularMeses);
            }
        })();




});