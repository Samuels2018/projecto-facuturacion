<script>
    $(function() {
        $('[name="fk_tercero"]').autocomplete({
            source: "<?php echo ENLACE_WEB; ?>mod_terceros/json/terceros.json.php?cliente=1",
            appendTo: "#addTaskModal",
            minLength: 2,
            select: function(event, ui) {
                console.log(ui.item);
                $('[name="fk_tercero"]').attr('value', ui.item.id);
                // Aquí puedes llamar a una función para cargar las opciones del segundo autocomplete
                //$('[name="fk_contacto"]').val('').trigger('change');
                selectContactos(ui.item.id, null);
            }
        });


        $('[name="fk_usuario_asignado"]').autocomplete({
            source: "<?php echo ENLACE_WEB; ?>mod_usuarios/json/usuarios.json.php",
            appendTo: "#addTaskModal",
            minLength: 2,
            select: function(event, ui) {
                console.log(ui.item);
                $('[name="fk_usuario_asignado"]').attr('value', ui.item.id);
                // Aquí puedes llamar a una función para cargar las opciones del segundo autocomplete

            }
        });



        $('#fk_contacto').on('change', function() {
            console.log(this.value);
            if (this.value == 'crear') {
                $('#fk_tercero_contacto').val($('#fk_tercero').attr('value'));
                $('#addTaskModal').addClass('d-none');
                $('#nuevoContactoModal').modal('show');

            }
        })

        window.tomSelect = new TomSelect("#fk_servicio", {
            // maxItems: 3,
            load: function(query, callback) {
                if (!query.length) return callback();
                fetch(`${ENLACE_WEB}mod_productos/json/productos.json.php?term=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        callback(data);
                    })
                    .catch(() => {
                        callback();
                    });
            },
            valueField: 'id',
            labelField: 'value',
            searchField: ['value'],
            render: {
                option: function(data, escape) {

                    return `<div>
              <div>${escape(data.value)}</div> - 
              <div class="additional-info"> ${escape(data.total)}</div>
            </div>`;
                },
                item: function(data, escape) {
                    return `<div>
              <div>${escape(data.value)}</div> - 
              <div class="additional-info" >${escape(data.total)}</div>
              <button type="button" class="btn-close" aria-label="X" onclick="eliminar_servicio(${data.id})"></button>
            </div>`;
                }
            },
            onChange: function(value) {

                // Obtiene la instancia de Tom Select
                var tomSelectInstance = this;
                //console.log(tomSelectInstance.options)
                // Obtiene los valores seleccionados como un array
                var selectedValues = tomSelectInstance.getValue();

                $('#fk_servicio').val(selectedValues);

                const matchingOptions = [];

                for (const [optionID, optionObject] of Object.entries(tomSelectInstance.options)) {
                    // Convert the option ID to a number (if needed)

                    const numericID = parseInt(optionID, 10);
                    //console.log(selectedValues.includes(numericID))
                    // Check if the option ID matches any of the selected IDs
                    if (selectedValues.includes(optionID)) {
                        // Store the matching option object
                        matchingOptions.push(optionObject.total);
                    }
                }

                let sum = 0;

                for (let i = 0; i < matchingOptions.length; i++) {
                    sum += matchingOptions[i];
                }
                let inputImporte = $('#importe')
                inputImporte.val(sum);
                inputImporte.attr('value', sum);
                console.log('suma:  ', sum);

                if (matchingOptions.length > 0) {


                    // Access specific properties from the matching options
                    matchingOptions.forEach(option => {

                        // Access other properties as needed
                    });
                } else {
                    console.warn('No matching options found.');
                }

            }
        });




        /*
        =========================================
        |                                       |
        |          Variables Definations        |
        |                                       |
        =========================================
        */

        var Container = {
            scrumboard: $('.scrumboard'),
            card: $('.scrumboard .card')
        }

        // Containers
        var scrumboard = Container.scrumboard;
        var card = Container.card;


        // Make Task Sortable

        // Click on Add Task button to open the modal and more..

        // function addTask() {
        $('.addTask').on('click', function(event) {
            console.log('desde aqui');
            event.preventDefault();
            getParentElement = $(this).parents('[data-connect="sorting"]').attr('data-section');

            $('.edit-task-title').hide();
            $('.add-task-title').show();
            $('[data-btnfn="addTask"]').show();
            $('[data-btnfn="editTask"]').hide();
            // $('.addTaskAccordion .collapse').collapse('hide');
            $('#addTaskModal').modal('show');
            $_taskAdd(getParentElement);
        });
        //}

        // Get the range count value

        $('#progress-range-counter').on('input', function(event) {
            event.preventDefault();
            /* Act on the event */
            getRangeValue = $(this).val();
            $('.range-count-number').html(getRangeValue);
            $('.range-count-number').attr('data-rangeCountNumber', getRangeValue);
        });

        // Reset the input Values




        function $_taskAdd(getParent) {

            $('[data-btnfn="addTask"]').off('click').on('click', function(event) {

                getAddBtnClass = $(this).attr('class').split(' ')[1];

                var today = new Date();
                var dd = String(today.getDate()).padStart(2, '0');
                var mm = String(today.getMonth()); //January is 0!
                var yyyy = today.getFullYear();

                var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

                today = dd + ' ' + monthNames[mm] + ', ' + yyyy;

                var $_getParent = getParent;


                var $_task = document.getElementById('s-task').value;
                var $_taskText = document.getElementById('s-text').value;

                var $_taskProgress = $('.range-count-number').attr('data-rangeCountNumber');

                if ($_taskText == '') {

                    $html = '<div data-draggable="true" class="card task-text-progress" style="">' +
                        '<div class="card-body">' +

                        '<div class="task-header">' +

                        '<div class="">' +
                        '<h4 class="" data-taskTitle="' + $_task + '">' + $_task + '</h4>' +
                        '</div>' +

                        '<div class="">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>' +
                        '</div>' +

                        '</div>' +


                        '<div class="task-body">' +

                        '<div class="task-bottom">' +
                        '<div class="tb-section-1">' +
                        '<span data-taskDate="' + today + '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> ' + today + '</span>' +
                        '</div>' +
                        '<div class="tb-section-2">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                        '</div>' +
                        '</div>' +

                        '</div>' +

                        '</div>' +
                        '</div>';

                } else {
                    $html = '<div data-draggable="true" class="card task-text-progress" style="">' +
                        '<div class="card-body">' +

                        '<div class="task-header">' +

                        '<div class="">' +
                        '<h4 class="" data-taskTitle="' + $_task + '">' + $_task + '</h4>' +
                        '</div>' +

                        '<div class="">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>' +
                        '</div>' +

                        '</div>' +

                        '<div class="task-body">' +

                        '<div class="task-content">' +
                        '<p class="" data-taskText="' + $_taskText + '">' + $_taskText + '</p>' +
                        '</div>' +

                        '<div class="task-bottom">' +
                        '<div class="tb-section-1">' +
                        '<span data-taskDate="' + today + '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg> ' + today + '</span>' +
                        '</div>' +
                        '<div class="tb-section-2">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 s-task-delete"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>' +
                        '</div>' +
                        '</div>' +

                        '</div>' +

                        '</div>' +
                        '</div>';

                }

                $("[data-section='" + $_getParent + "'] .connect-sorting-content").append($html);


                $('#addTaskModal').modal('hide');

                $_taskEdit();
                $_taskDelete();
            });
        }

        $("#add-list").off('click').on('click', function(event) {
            event.preventDefault();

            $('.add-list').show();
            $('.edit-list').hide();
            $('.edit-list-title').hide();
            $('.add-list-title').show();
            $('#addListModal').modal('show');
        });
 

        function $_deleteList() {
            $('.list-delete').off('click').on('click', function(event) {
                event.preventDefault();
                $(this).parents('[data-connect]').remove();
            })
        }

        
        function $_clearList() {
            $('.list-clear-all').off('click').on('click', function(event) {
                event.preventDefault();
                $(this).parents('[data-connect="sorting"]').find('.connect-sorting-content .card').remove();
            })
        }

        // Delete the task on click 

        function $_taskDelete() {
            $('.card .s-task-delete').off('click').on('click', function(event) {
                event.preventDefault();

                get_card_parent = $(this).parents('.card');

                $('#deleteConformation').modal('show');

                $('[data-remove="task"]').on('click', function(event) {
                    event.preventDefault();
                    /* Act on the event */
                    get_card_parent.remove();
                    $('#deleteConformation').modal('hide');
                });

            })
        }

        function $_taskEdit() {
            $('.card .s-task-edit').off('click').on('click', function(event) {

                event.preventDefault();

                var $_outerThis = $(this);

                $('.add-task-title').hide();
                $('.edit-task-title').show();

                $('[data-btnfn="addTask"]').hide();
                $('[data-btnfn="editTask"]').show();

                var $_taskTitle = $_outerThis.parents('.card').find('h4').attr('data-taskTitle');
                var get_value_title = $('.task-text-progress #s-task').val($_taskTitle);

                var $_taskText = $_outerThis.parents('.card').find('p:not(".progress-count")').attr('data-taskText');
                var get_value_text = $('.task-text-progress #s-text').val($_taskText);

                var $_taskProgress = $_outerThis.parents('.card').find('div.progress-bar').attr('data-progressState');
                var get_value_progress = $('#progress-range-counter').val($_taskProgress);
                var get_value_progressHtml = $('.range-count-number').html($_taskProgress);
                var get_value_progressDataAttr = $('.range-count-number').attr('data-rangecountnumber', $_taskProgress);

                $('[data-btnfn="editTask"]').off('click').on('click', function(event) {
                    var $_innerThis = $(this);

                    var $_taskValue = document.getElementById('s-task').value;
                    var $_taskTextValue = document.getElementById('s-text').value;
                    var $_taskProgressValue = $('.range-count-number').attr('data-rangeCountNumber');

                    var $_taskDataAttr = $_outerThis.parents('.card').find('h4').attr('data-taskTitle', $_taskValue);
                    var $_taskTitle = $_outerThis.parents('.card').find('h4').html($_taskValue);
                    var $_taskTextDataAttr = $_outerThis.parents('.card').find('p:not(".progress-count")').attr('data-tasktext', $_taskTextValue);
                    var $_taskText = $_outerThis.parents('.card').find('p:not(".progress-count")').html($_taskTextValue);

                    var $_taskProgressStyle = $_outerThis.parents('.card').find('div.progress-bar').attr('style', "width: " + $_taskProgressValue + "%");
                    var $_taskProgressDataAttr = $_outerThis.parents('.card').find('div.progress-bar').attr('data-progressState', $_taskProgressValue);
                    var $_taskProgressAriaAttr = $_outerThis.parents('.card').find('div.progress-bar').attr('aria-valuenow', $_taskProgressValue);
                    var $_taskProgressProgressCount = $_outerThis.parents('.card').find('.progress-count').html($_taskProgressValue + "%");

                    $('#addTaskModal').modal('hide');
                    var setDate = $('.taskDate').html('');
                    $('.taskDate').hide();
                })
                $('#addTaskModal').modal('show');
            })
        }



        //Evento que levanta el modal
        $('.task-list-section').on('click', '.addTask', function(event) {

            event.preventDefault();
            getParentElement = $(this).parents('[data-connect="sorting"]').attr('data-section');
            let fk_funnel_detalle = getParentElement = $(this).parents('[data-connect="sorting"]').attr('data-funnel-detalle');
            console.warn(fk_funnel_detalle)
            $('#fk_funnel_detalle').val(fk_funnel_detalle);
            $('.edit-task-title').hide();
            $('.add-task-title').show();
            $('[data-btnfn="addTask"]').show();
            $('[data-btnfn="editTask"]').hide();
            // $('.addTaskAccordion .collapse').collapse('hide');
            $('#addTaskModal').modal('show');

            $('#fk_contacto').html('');

            $('#boton_crear_txt').text('Crear');
            /* habilitar para crear la tarea */
            //$_taskAdd(getParentElement);

        });


        /**
         * 
         *  Validation Horizontal  
         * 
         */
        window.stepper;
        var formValidation = document.querySelector('.stepper-form-validation-one');
        var stepper = new Stepper(formValidation, {
            animation: true
        })
        window.stepper = stepper;
        var formValidationNextButton = formValidation.querySelectorAll('.btn-nxt');
        var formValidationPrevButton = formValidation.querySelectorAll('.btn-prev');
        var formValidationSubmit = formValidation.querySelector('.btn-submit');
        var stepperPanList = [].slice.call(formValidation.querySelectorAll('.content'))

        var inputTercero = formValidation.querySelector('#fk_tercero');
        var inputContacto = formValidation.querySelector('#fk_contacto');
        var inputEtiqueta = formValidation.querySelector('#etiqueta');
        var inputNota = formValidation.querySelector('#nota');
        var inputServicios = formValidation.querySelector('#fk_servicio');
        var inputTags = formValidation.querySelector('#tags');
        var inputImporte = formValidation.querySelector('#importe');

        var formEl = formValidation.querySelector('.bs-stepper-content form')

        formValidationNextButton.forEach(element => {
            element.addEventListener('click', function() {
                stepper.next();
            })
        });

        formValidationPrevButton.forEach(element => {
            element.addEventListener('click', function() {
                stepper.previous();
            })
        });

        formValidation.addEventListener('show.bs-stepper', function(event) {
            formEl.classList.remove('was-validated')
            var nextStep = event.detail.indexStep
            var currentStep = nextStep

            if (currentStep > 0) {
                currentStep--
            }

            var stepperPan = stepperPanList[currentStep]

            if (
                (stepperPan.getAttribute('id') === 'validationStep-one' && !inputTercero.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-one' && !inputContacto.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-two' && !inputEtiqueta.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-two' && !inputNota.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-three' && !inputServicios.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-three' && !inputImporte.value.length) ||
                (stepperPan.getAttribute('id') === 'validationStep-three' && !inputTags.value.length)
            ) {
                event.preventDefault()
                error = false;
                // formEl.classList.add('input_error')
                if (!inputTercero.value.length) {
                    inputTercero.classList.add('input_error');
                    error = true;
                }
                if (!inputContacto.value.length) {
                    inputContacto.classList.add('input_error');
                    error = true;
                }
                if (!inputEtiqueta.value.length) {
                    inputEtiqueta.classList.add('input_error');
                    error = true;
                }
                if (!inputNota.value.length) {
                    inputNota.classList.add('input_error');
                    error = true;
                }
                if (!inputServicios.value.length) {
                    inputServicios.classList.add('input_error');
                    error = true;
                }
                if (!inputTags.value.length) {
                    inputTags.classList.add('input_error');
                    error = true;
                }
                if (!inputImporte.value.length) {
                    inputImporte.classList.add('input_error');
                    error = true;
                }

                if (error) {
                    add_notification({
                        text: 'Faltan Datos Obligatorios',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',

                    })
                    return true;
                }
            } else {
                if (event.detail.from < event.detail.to) {
                    formValidation.querySelectorAll('.step')[event.detail.from].classList.add('crossed');
                } else {
                    formValidation.querySelectorAll('.step')[event.detail.to].classList.remove('crossed');
                }
            }
        })

        formValidationSubmit.addEventListener('click', function() {
            // Remover la clase 'input_error' de todos los campos de entrada
            inputTercero.classList.remove('input_error');
            inputContacto.classList.remove('input_error');
            inputEtiqueta.classList.remove('input_error');
            inputNota.classList.remove('input_error');
            inputServicios.classList.remove('input_error');
            inputTags.classList.remove('input_error');
            inputImporte.classList.remove('input_error');

            // Realizar la validación y agregar la clase 'was-validated' al formulario si hay errores
            if (
                (!inputTercero.value.length) ||
                (!inputContacto.value.length) ||
                (!inputEtiqueta.value.length) ||
                (!inputNota.value.length) ||
                (!inputServicios.value.length) ||
                (!inputImporte.value.length)
            ) {
                formEl.classList.add('was-validated');
            }
        });




        /* Tagify */

        var input = document.querySelector('input[name=tags]'),
            tagify = new Tagify(input, {
                pattern: /^.{0,20}$/, // Validate typed tag(s) by Regex. Here maximum chars length is defined as "20"
                delimiters: ",| ", // add new tags when a comma or a space character is entered
                keepInvalidTags: true, // do not remove invalid tags (but keep them marked as invalid)
                editTags: {
                    clicks: 2, // single click to edit a tag
                    keepInvalid: false // if after editing, tag is invalid, auto-revert
                },
                maxTags: 6,
                blacklist: ["foo", "bar", "baz"],
                whitelist: [],
                backspace: "edit",
                placeholder: "Tags",
                dropdown: {
                    enabled: 1, // show suggestion after 1 typed character
                    fuzzySearch: false, // match only suggestions that starts with the typed characters
                    position: 'text', // position suggestions list next to typed text
                    caseSensitive: true, // allow adding duplicate items if their case is different
                },
                templates: {
                    dropdownItemNoMatch: function(data) {
                        return `<div class='${this.settings.classNames.dropdownItem}' tabindex="0" role="option">
                No suggestion found for: <strong>${data.value}</strong>
            </div>`
                    }
                }
            })






        // $_editList();
        $_deleteList();
        $_clearList();
        obtener_detalles_funnel();
        // addTask();
        $_taskEdit();
        $_taskDelete();
        //$_taskSortable();

    });


    $('#addTaskModal, #addListModal').on('hidden.bs.modal', function(e) {

        //$('input,textarea').val('');

        $('#fk_tercero').removeClass('input_error');
        $('#fk_contacto').removeClass('input_error');
        $('#fk_usuario_asignado').removeClass('input_error');
        $('#etiqueta').removeClass('input_error');
        $('#nota').removeClass('input_error');
        // Asegúrate de que todos los campos relevantes estén incluidos aquí
        $('#tags').removeClass('input_error');
        $('#importe').val(0);

        window.stepper.to(0);

        $('#fk_tercero').val('')
        $('#fk_contacto').val('')
        $('#fk_usuario_asignado').val('')
        $('#etiqueta').val('')
        $('#nota').val('')
        // $('#fk_servicio').val('')
        $('#tags').val('')

        window.tomSelect.clear();

        $('input[type="range"]').val(0);
        $('.range-count-number').attr('data-rangecountnumber', 0);
        $('.range-count-number').html(0);

    })





    /* Personalizadas */
    window.posiciones = [];

    function obtener_detalles_funnel($fecharango = '', $busqueda = '',$lista_usuarios='',$categorias = '', $prioridades='', $tags = '') {

        $.ajax({
            type: "POST",
            url: `${ENLACE_WEB}mod_funnel/class/funnel.class.php`,
            data: {
                rowid: $('[name=funnel_id]').val(),
                fecharango:$fecharango,
                busqueda:$busqueda,
                lista_usuarios:$lista_usuarios,
                categorias:$categorias,
                prioridades:$prioridades,
                tags:$tags,
                'action': 'obtener_detalles_funnel'
            },
            success: function(data) {
                console.log(data);
                let response = JSON.parse(data);
                let content = '';
                $('.task-list-section').html('');
                $.each(response, function(index, item) {

                    window.posiciones.push(item.posicion);

                    let template = `
                                <div data-section="s-${item.posicion}" data-funnel-fk="${item.id_funnel_detalle}" data-funnel-detalle="${item.posicion}" class="task-list-container" data-connect="sorting">
                                    <div class="connect-sorting">
                                        <div class="task-container-header">
                                            <h6 class="s-heading" data-listTitle="${item.etiqueta}">${item.etiqueta}  (${item.suma_total_funnel} €)</h6>
                                            <div class="dropdown">
                                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink-1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="19" cy="12" r="1"></circle>
                                                        <circle cx="5" cy="12" r="1"></circle>
                                                    </svg>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right left" aria-labelledby="dropdownMenuLink-1">
                                                    <a class="dropdown-item list-edit" onclick="modal_lista(${item.rowid},'${item.etiqueta}')">Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="connect-sorting-content paso-${item.posicion}" data-sortable="true"></div>
                                    </div>
                                </div>
                                `;


                    content += template;
                });


                $('.task-list-section').append(content);
                mostrar_tareas($fecharango,$busqueda,$lista_usuarios,$categorias,$prioridades,$tags);

            }
        });


    }


    function mostrar_tareas($fecharango = '',$busqueda = '',$lista_usuarios = '',$categorias = '',$prioridades ='', $tags = '') {

        posicion_funnel = [];

        const posicionesUnicas = [...new Set(window.posiciones)];


        $.ajax({    
            type: "POST",
            url: `${ENLACE_WEB}mod_lead/class/lead.class.php`,
            data: {
                rowid: $('[name=funnel_id]').val(),
                rangofecha:$fecharango,
                busqueda:$busqueda,
                lista_usuarios:$lista_usuarios,
                categorias:$categorias,
                prioridades:$prioridades,
                tags:$tags,
                'action': 'obtener_tareas_funnel'
            },
            success: function(data) {
                console.log(data);
                let response = JSON.parse(data);
                console.log(response);


                posicionesUnicas.forEach((posicion) => {
                    let content = '';
                    /*console.log("ALBERTO "+content);
                    console.log(posicion);
                    console.log("LA TAREA "+tarea.fk_funnel_detalle);
                    console.log("-----------");*/

                    // Filtrar las tareas que coincidan con la posición actual
                    const tareasFiltradas = response.filter(tarea => tarea.posicion_detalle === posicion);

                    // Iterar sobre las tareas filtradas para construir el contenido
                    tareasFiltradas.forEach((item) => {

                        console.log(item);
                        // Aquí construyes tu template basado en item
                        let template = `
                                    <div data-draggable="true" data_fk_usuario_asignado="${item.fk_usuario_asignado}" data-tarea="${item.rowid}" class="card img-task">
                                            <div class="card-body">
                                             <div class="d-flex align-items-center" style="   padding: 15px 10px;">
                                                <div class="label-circle mr-3 rounded-circle">
                                                    <img class="rounded-circle" style="width:60px;" src="https://ui-avatars.com/api/?name=${item.tercero}&background=888EA8&color=fff" alt="avatar">
                                                </div>
                                                <div style="margin-left:15px;">
                                                    <div class="amount">
                                                        <h4 style="font-weight:bold;">${item.importe} €</h4>
                                                    </div>
                                                    <div data-taskTitle="${item.etiqueta}">
                                                       <a href="<?php echo ENLACE_WEB; ?>ver_oportunidad/${item.oportunidad_id}">${item.etiqueta}</a>
                                                    </div>
                                                    <div>
                                                        <span style="font-size: 12px;color: #6C6E7D;font-weight: 700;"><i class="fa fa-calendar" aria-hidden="true"></i> ${item.fecha}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="task-body">
                                                <div style="
                                                        position: absolute;
                                                        top: 0;
                                                    "><span class="badge badge-${item.estilo_categoria}" style="
                                                    ">${item.categoria}</span></div>

                                                <div class="d-flex align-items-center" style="   padding: 15px 10px;">
                                                    <div class="label-circle mr-3 rounded-circle">
                                                        <img class="rounded-circle" style="width:40px;" src="https://ui-avatars.com/api/?name=${item.usuario_asignado}&background=E7515A&color=fff" alt="avatar">
                                                    </div>
                                                    <div style="margin-left:15px; width:100%; cursor:pointer;">
                                                        <div>
                                                            <strong style="font-weight:bold;">Cliente: ${item.tercero}  </strong>
                                                        </div>
                                                        <div data-taskTitle="${item.etiqueta}">
                                                           ${item.usuario_asignado}
                                                        </div>
                                                          
                                                          <!--<svg style="    float: right;
    margin-top: -40px;" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 s-task-edit" onclick="fetch_tarea(${item.rowid})">
                                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                        </svg>-->

                                                    </div>  
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    `;

                        content += template;
                    });
                    // console.log(content)
                    // console.log(`.connect-sorting-content .paso-${posicion}`);

                   /* let btn_save = ` <div class="add-${posicion}-task">
                                    <a class="addTask" data-btnfn="addTask"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg> Agregar Tarea</a>
                                </div>`;*/
                    let btn_save = '';

                    $(`.paso-${posicion}`).append(content);

                    $("[data-section=s-" + posicion + "] > .connect-sorting").append(btn_save);
                });
                $_taskSortable();

                /* fin ajax */
            }
        });
        
        var padretag;
       
        $('[data-sortable="true"]').on('sortstart', function(event, ui) {
            var initialIndex = $(ui.item).index();  // Obtener la posición inicial
            padretag = ui.item.parent().parent().find('.task-container-header').find('h6.s-heading').attr('data-listtitle');

            $(ui.item).attr('data-initial-index', initialIndex);  // Guardar en un atributo
        });


        // Manejar el evento de detención de ordenamiento
        $('[data-sortable="true"]').on('sortstop', function(event, ui)
        {

            let fk_funnel = '<?php echo $_REQUEST['fiche']; ?>';
            let fecharango = $("#fecha-rango").val();
            let busqueda =   $('#buscar_oportunidad').val();
            let lista_seleccionados = obtener_usuarios_seleccionados();
            let categorias = obtenerCategoriasSeleccionadas();
            let prioridades = obtenerPrioridadesSeleccionadas();
            let tags = obtenerTagsSeleccionadas();

            let rowid = ui.item.attr('data-tarea');
            let fk_usuario_asignado = ui.item.attr("data_fk_usuario_asignado");
            let comentario_funnel_padre = ui.item.parent().parent().find('.task-container-header').find('h6.s-heading').attr('data-listtitle');

            //let fk_funnel_detalle = ui.item.closest('[data-funnel-detalle]').attr('data-funnel-detalle');
            //AQUI DEBEMOS ENVIAR no la posicion si no el FK detalle PARA LOS CALCULOS
            let fk_funnel_detalle = ui.item.closest('[data-funnel-fk]').attr('data-funnel-fk');

            var items = Array.from(this.children);
            var posiciones = items.map(function(item, index) {
                return {
                    rowid: item.getAttribute('data-tarea'), // Asumiendo que cada elemento tiene un atributo 'data-tarea' único
                    posicion_funnel: index + 1 // La nueva posición basada en el orden actual de los elementos
                };
            });
            // Asegúrate de que este selector sea correcto para tu caso

            var finalIndex = $(ui.item).index();  // Obtener la posición final
            var initialIndex = $(ui.item).attr('data-initial-index');  // Recuperar la posición inicial

          
            // Comparar la posición inicial y final
            if (padretag != comentario_funnel_padre) {

          
            // Realiza la llamada AJAX
            $.ajax({
                type: 'POST',
                url: '<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php',
                data: {
                    rowid: rowid,
                    fk_funnel: fk_funnel,
                    fk_funnel_detalle: fk_funnel_detalle,
                    fk_usuario_asignado:fk_usuario_asignado,
                    posiciones: posiciones,
                    comentario_funnel_padre:comentario_funnel_padre,
                    fecharango:fecharango,
                    busqueda:busqueda,
                    lista_usuarios:lista_seleccionados,
                    categorias:categorias,
                    prioridades:prioridades,
                    tags:tags,
                    'action': 'cambiar_estado_oportunidad'
                },
                success: function(data) {

                    let response = JSON.parse(data);
                    
                    if (response.exito === true) {
                        add_notification({
                            text: response.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        //ACTUALIZAR LOS VALORES FUNNEL 
                        totalesfunnel = response.totales_funnel;
                        console.log(response.totales_funnel);

                        //Foreach de os totales
                        $.each(totalesfunnel, function(index, funnel) {
                            etiqueta = funnel.etiqueta;
                            total = parseFloat(funnel.suma_total_funnel);
                            console.log("Índice: " + index + ", Valor: " + etiqueta+' '+total);
                            //Vamos a actualizar la etiqueta


                            // Extraer el texto del elemento
                            var textoOriginal = $("h6.s-heading[data-listtitle='" + etiqueta + "']").text();

                            // Extraer solo los números con decimales del texto
                            var numeroConDecimales = textoOriginal.match(/[\d\.]+/);

                            // Convertir el texto extraído a un número flotante y luego a un entero
                            var soloNumeros = parseInt(parseFloat(numeroConDecimales));


                            if(total !== soloNumeros)
                            {
                                if(total<=0){
                                    $("h6.s-heading[data-listtitle='"+etiqueta+"']").text(""+etiqueta+"  ("+total+" €)");
                                }else{
                                    // Inicializa el texto con el valor inicial
                                    $("h6.s-heading[data-listtitle='" + etiqueta + "']").text(etiqueta + " (0 €)");
                                    // Llama a la función de animación
                                    $comienzo = total - 10;

                                    animateValue($("h6.s-heading[data-listtitle='" + etiqueta + "']"), $comienzo, total, 300,etiqueta); // 2000 es la  duración en milisegundos (2 segundos)
                                }
                            }

                        });

                        // obtener_detalles(fk_funnel); // Asegúrate de que esta función sea correcta para tu caso
                        // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                    } else {
                        add_notification({
                            text: response.mensaje,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                },
                error: function(error) {
                    console.error(error);
                }
            });

            } //CIERRE DE LA CONDICION LA CUAL la targeta debe  estar en otra direccion para poder hacer la llamada ajax
        });

        //Animacion para cambiar numeros 
        function animateValue(element, start, end, duration,etiqueta) {
            var current = start;
            var increment = end > start ? 1 : -1;
            var stepTime = Math.abs(Math.floor(duration / (end - start)));
            var obj = $(element);

            var timer = setInterval(function() {
                current += increment;
                $(obj).text(""+etiqueta+" (" + current + " €)");
                
                if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                    clearInterval(timer);
                    $(obj).text(""+etiqueta+" (" + end + " €)"); // Asegura que el valor final sea exacto
                }
            }, stepTime);
        }


    }


    function $_taskSortable() {
        $('[data-sortable="true"]').sortable({
            connectWith: '.connect-sorting-content',
            items: ".card",
            cursor: 'move',
            placeholder: "ui-state-highlight",
            refreshPosition: true,
            stop: function(event, ui) {
                // let rowid = ui.item.attr('data-tarea');
                // let fk_funnel_detalle = ui.item.closest('[data-funnel-detalle]').attr('data-funnel-detalle');


            },
            update: function(event, ui) {
                console.log(ui);
                console.log(ui.item);
            }
        });
    }

    /* funciones insercion */

    function crear_oportunidad(event) {
        event.preventDefault();

        error = false;

        let entidad = $('[name="entidad"]').val();
        let fk_funnel = $('[name="funnel_id"]').val();
        let fk_contacto = $('#fk_contacto').val();
        let fk_tercero = $('[name="fk_tercero"]').attr('value');
        let fk_usuario_asignado = $('[name="fk_usuario_asignado"]').attr('value');
        let etiqueta = $('[name="etiqueta"]').val();
        let nota = $('[name="nota"]').val();
        let servicios = $('#fk_servicio').val();
        let tags = $('#tags').val();
        let fk_funnel_detalle = $('#fk_funnel_detalle').val();
        let importe = $('#importe').attr('value');
        console.log('fk_contacto', fk_contacto);
        if (fk_contacto == '') {
            $('input[name="fk_contacto"]').addClass("input_error");
            error = true;
        }
        if (fk_tercero == '') {
            $('input[name="fk_tercero"]').addClass("input_error");
            error = true;
        }
        if (fk_usuario_asignado == '') {
            $('input[name="fk_usuario_asignado"]').addClass("input_error");
            error = true;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php',
            data: {
                action: 'nueva_oportunidad',
                entidad: entidad,
                fk_funnel: fk_funnel,
                fk_contacto: fk_contacto,
                fk_tercero: fk_tercero,
                fk_usuario_asignado: fk_usuario_asignado,
                etiqueta: etiqueta,
                nota: nota,
                servicios: servicios,
                tags: tags,
                fk_funnel_detalle: fk_funnel_detalle,
                importe: importe
            },
            success: function(data) {
                let response = JSON.parse(data);
                console.log('Success:', response);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });
                    obtener_detalles_funnel();
                    $('#addTaskModal').modal('hide');
                    // Aquí puedes recargar la tabla oportunidades si es necesario
                    // $('#id_tabla_oportunidades').DataTable().ajax.reload();
                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        actionText: 'Cerrar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }


    function modificar_oportunidad(event) {
        event.preventDefault();

        error = false;
        let rowid = $('[name="tarea_id"]').val();
        let entidad = $('[name="entidad"]').val();
        let fk_funnel = $('[name="funnel_id"]').val();
        let fk_contacto = $('[name="fk_contacto"]').val();
        let fk_tercero = $('[name="fk_tercero"]').attr('value');
        let fk_usuario_asignado = $('[name="fk_usuario_asignado"]').attr('value');
        let etiqueta = $('[name="etiqueta"]').val();
        let nota = $('[name="nota"]').val();
        let servicios = $('#fk_servicio').val();
        let tags = $('#tags').val();
        let fk_funnel_detalle = $('#fk_funnel_detalle').val();
        let importe = $('#importe').val();

        let errorMessage = "Debes completar los siguientes campos: ";

        if (fk_contacto == '') {
            $('input[name="fk_contacto"]').addClass("input_error");
            error = true;
        }
        if (fk_tercero == '') {
            $('input[name="fk_tercero"]').addClass("input_error");
            error = true;
        }
        if (fk_usuario_asignado == '') {
            $('input[name="fk_usuario_asignado"]').addClass("input_error");
            error = true;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        $.ajax({
            method: "POST",
            url: '<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php',
            data: {
                action: 'modificar_oportunidad',
                rowid: rowid,
                entidad: entidad,
                fk_funnel: fk_funnel,
                fk_contacto: fk_contacto,
                fk_tercero: fk_tercero,
                fk_usuario_asignado: fk_usuario_asignado,
                etiqueta: etiqueta,
                nota: nota,
                servicios: servicios,
                tags: tags,
                fk_funnel_detalle: fk_funnel_detalle,
                importe: importe
            }
        }).done(function(msg) {
            const response = JSON.parse(msg);
            console.log(response)
            if (response.exito === true) {
                add_notification({
                    text: response.mensaje,
                    actionTextColor: '#fff',
                    backgroundColor: '#00ab55',
                    dismissText: 'Cerrar'
                });

                $('#addTaskModal').modal('hide');
                // Aquí puedes recargar la tabla oportunidades si es necesario
                // $('#id_tabla_oportunidades').DataTable().ajax.reload();
            } else {
                add_notification({
                    text: response.mensaje,
                    pos: 'top-right',
                    actionTextColor: '#fff',
                    backgroundColor: '#e7515a'
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la petición AJAX:", textStatus, errorThrown);
        });
    }


    function eliminar_oportunidad(event) {
        event.preventDefault();

        let rowid = $('[name="rowid"]').val();
        let message = "¿Deseas eliminar esta oportunidad?";
        let actionText = "Confirmar";

        add_notification({
            text: message,
            width: 'auto',
            duration: 30000,
            actionText: actionText,
            dismissText: 'Cerrar',
            onActionClick: function(element) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php",
                    data: {
                        action: 'eliminar_oportunidad',
                        rowid: rowid
                    },
                    cache: false,
                }).done(function(msg) {
                    const response = JSON.parse(msg);
                    if (response.exito === true) {
                        add_notification({
                            text: response.mensaje,
                            actionTextColor: '#fff',
                            backgroundColor: '#00ab55',
                            dismissText: 'Cerrar'
                        });

                        // Aquí puedes recargar la tabla oportunidades si es necesario
                        // $('#id_tabla_oportunidades').DataTable().ajax.reload();
                    } else {
                        add_notification({
                            text: response.mensaje,
                            pos: 'top-right',
                            actionTextColor: '#fff',
                            backgroundColor: '#e7515a'
                        });
                    }
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la petición AJAX:", textStatus, errorThrown);
                    add_notification({
                        text: 'Hubo un error al marcar la oportunidad como eliminada.',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a'
                    });
                });
            },
        });
    }

    function validar_accion() {
        let accion = $('#boton_crear_txt').text();
        if (accion == 'Crear') {
            crear_oportunidad(event);
        } else {
            modificar_oportunidad(event);
        }
    }


    

    function fetch_tarea(rowid) {
        $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php",
            data: {
                action: 'fetch',
                rowid: rowid
            },
        }).done(function(data) {

            let response = JSON.parse(data);
            //console.log('fetch: ', response);

            selectContactos(response.fk_tercero, response.fk_contacto);

            // Asegurarse de que TomSelect esté inicializado solo una vez
            if (!window.tomSelect) {
                window.tomSelect = new TomSelect("#fk_servicio", {
                    valueField: 'id',
                    labelField: 'value',
                    searchField: ['value'],
                    // Configuración específica para personalizar la apariencia
                    render: {
                        option: function(data, escape) {
                            console.warn('data ', data)

                            return `<div onclick="eliminar_servicio(${data.id})">
              <div>${escape(data.value)}</div> - 
              <div class="additional-info">${escape(data.total)}<svg> ... </svg></div>
            </div>`;
                        },
                        item: function(data, escape) {
                            return `<div onclick="eliminar_servicio(${data.id})">
              <div>${escape(data.value)}</div> - 
              <div class="additional-info">${escape(data.total)} <svg> ... </svg></div>
            </div>`;
                        }
                    }
                });
            }
            if (response.servicios != null) {

                servicios_array = response.servicios.split(',');

                obtener_servicios_tarea(servicios_array).then(servicios_tarea => {
                    console.log(servicios_tarea);
                    servicios_tarea.forEach(function(servicio) {

                        // Agregar el producto a TomSelect
                        window.tomSelect.addOption({
                            id: servicio.rowid, // Usar rowid como id
                            value: servicio.descripcion, // Usar descripcion como valor
                            total: parseInt(servicio.total),
                            simbolo: servicio.simbolo
                        });
                        window.tomSelect.addItem(servicio.rowid); // Añadir el item basado en rowid
                    });

                }).catch(error => {
                    console.error('Error al procesar los servicios:', error);
                })

            }


            $('#contacto_nombre').val();
            $('#contacto_apellido').val();

            let tags = [response.tags];
            $('#tarea_id').val(rowid);


            $("#fk_tercero").attr('value', response.fk_tercero);
            $("#fk_tercero").val(response.cliente);
            $('#fk_usuario_asignado').val(response.usuario_asignado);
            $('#fk_usuario_asignado').attr('value', response.fk_usuario_asignado);
            $('#etiqueta').val(response.etiqueta);
            $('#nota').val(response.nota);
            $('#fk_funnel_detalle').attr('value', response.fk_funnel_detalle);
            $('#importe').val(response.importe);
            //$('#fk_servicio').val(servicios);

            $('#tags').val(tags);

            $('#modal_titulo').text('Modificar');
            $('#boton_crear_txt').text('Modificar');

            $('#boton_eliminar').show();

            $('#addTaskModal').modal('show');
        });
    }

    function obtener_servicios_tarea(servicios) {
        return $.ajax({
            method: "POST",
            url: "<?php echo ENLACE_WEB; ?>mod_lead/class/lead.class.php",
            data: {
                action: 'obtener_servicios_tarea',
                servicios: servicios,
                fk_oportunidad: $('#tarea_id').val()
            },
            dataType: "json" // Asegúrate de que la respuesta sea interpretada como JSON
        }).then(function(data) {

            return data; // Devuelve los datos directamente
        }).catch(function(error) {
            console.error('Error al obtener los servicios:', error);
            throw error; // Re-lanza el error para que pueda ser manejado por el llamador
        });
    }

    function crear_contacto(event) {
        event.preventDefault();

        error = false;

        // Recoger los valores del formulario usando jQuery
        let nombre = $('[name="contacto_nombre"]').val();
        let apellidos = $('[name="contacto_apellido"]').val();
        let fk_tercero = $('[name="fk_tercero_contacto"]').val();

        if (nombre == '') {
            $('input[name="contacto_nombre"]').addClass("input_error");
            error = true;
        }
        if (apellidos == '') {
            $('input[name="contacto_apellido"]').addClass("input_error");
            error = true;
        }

        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        // Preparar la petición AJAX
        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_contactos_crm/class/contacto_crm.class.php',
            data: {
                action: 'nuevo_contacto',
                nombre: nombre,
                apellidos: apellidos,
                fk_tercero: fk_tercero
            },
            success: function(data) {
                let response = JSON.parse(data);
                console.log('Success:', response);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });

                    selectContactos(fk_tercero, null);
                    $('#nuevoContactoModal').modal('hide');




                    $('#contacto_nombre').val('')
                    $('#contacto_apellido').val('')
                    $('#fk_tercero_contacto').val('')

                    $('#addTaskModal').removeClass('d-none');

                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        actionText: 'Cerrar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });


    }


    function selectContactos(terceroId, contactoSeleccionado) {
        console.warn('contacto recibido ', contactoSeleccionado);
        $('#fk_contacto').html('');
        $('#fk_contacto').append(`<option value="">Seleccionar contacto</option>`);
        $('#fk_contacto').append(`<option value="crear">Crear contacto</option>`);

        $.ajax({
            url: '<?php echo ENLACE_WEB; ?>mod_contactos_crm/json/contactos_crm.json.php',
            data: {
                fk_tercero: terceroId,
                term: $('#fk_contacto').val()
            },
            dataType: 'json',
            success: function(data) {

                if (data) {
                    const optionsHTML = data.map(item => {
                        return `<option value="${item.id}">${item.value}</option>`;

                    });

                    $('#fk_contacto').append(optionsHTML);

                    if (contactoSeleccionado) {
                        $('#fk_contacto').val(contactoSeleccionado);
                        //$('#fk_contacto').attr('value', contactoSeleccionado);
                    }
                } else {

                    $('#fk_contacto').html('');
                    $('#fk_contacto').append(`<option value="">No se encontraron contactos</option>`);
                    $('#fk_contacto').append(`<option value="crear">Crear contacto</option>`);
                }

            }
        });


    }

    function mostrar_modal_tarea() {

        $('#addTaskModal').removeClass('d-none');
        $('#fk_contacto').val('');
        $('#contacto_nombre').val('')
        $('#contacto_apellido').val('')
        $('#fk_tercero_contacto').val('')
    }

    function eliminar_servicio(id) {
        window.tomSelect.removeItem(id);
    }


    function cambiar_nombre_detalle(event) {
        event.preventDefault();
        error = false;
   
        // Recoger los valores del formulario usando jQuery
        let rowid = $('[name="detalle_id"]').val();
        let fk_funnel = $('[name="funnel_id"]').val();
        let etiqueta = $('[name="lista_etiqueta"]').val();

        // Verificar que todos los campos requeridos estén llenos

        if (etiqueta == '') {
            $('input[name="lista_etiqueta"]').addClass("input_error");
            error = true;
        }
       
        if (error) {
            add_notification({
                text: 'Faltan Datos Obligatorios',
                actionTextColor: '#fff',
                backgroundColor: '#e7515a',

            })
            return true;
        }

        $.ajax({
            type: 'POST',
            url: '<?php echo ENLACE_WEB; ?>mod_funnel/class/funnel.class.php',
            data: {
                action: 'cambiar_nombre_detalle',
                rowid: rowid,
                fk_funnel: fk_funnel,
                etiqueta: etiqueta
            },
            success: function(data) {
                let response = JSON.parse(data);
                if (response.exito === true) {
                    add_notification({
                        text: response.mensaje,
                        actionTextColor: '#fff',
                        backgroundColor: '#00ab55',
                        actionText: 'Cerrar'
                    });
                    obtener_detalles_funnel();
                    $('#addListModal').modal('hide');
                    // Aquí puedes recargar la tabla o realizar cualquier otra acción necesaria
                } else {
                    add_notification({
                        text: response.mensaje,
                        pos: 'top-right',
                        actionTextColor: '#fff',
                        backgroundColor: '#e7515a',
                        actionText: 'Cerrar'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function modal_lista(id, etiqueta)
    {
        $('#detalle_id').val(id);
        $('#lista_etiqueta').val(etiqueta);
        $('#addListModal').modal('show');
    }


    //Evento datepicker para filtrar las oportunidades del canvan por rango de fechas
    // Inicializar el Date Range Picker en el campo de entrada
      $('#fecha-rango').daterangepicker({
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' - ',
            applyLabel: 'Aplicar',
            cancelLabel: 'Cancelar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Rango personalizado',
            weekLabel: 'S',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
        },
        opens: 'left',
        autoUpdateInput: false  // Para que el campo esté vacío hasta que se seleccione un rango
    });

    // Llenar el campo con el rango de fechas cuando el usuario aplique su selección
    $('#fecha-rango').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYY-MM-DD');
        
        // Mostrar el rango seleccionado en el campo
        $(this).val(startDate + ' | ' + endDate);

        $fecha_enviar = startDate+'|'+endDate;

        busqueda =   $('#buscar_oportunidad').val();
        lista_seleccionados = obtener_usuarios_seleccionados();
        let categorias = obtenerCategoriasSeleccionadas();
        let prioridades = obtenerPrioridadesSeleccionadas();
        let tags = obtenerTagsSeleccionadas();

        //vaamos a obtener los detalles del funnel aqui con el valor de la fecha si estamos filtrando por dicho campo
        obtener_detalles_funnel($fecha_enviar,busqueda,lista_seleccionados,categorias,prioridades,tags);
        // Aquí puedes agregar el código para filtrar datos con el rango de fechas seleccionado
        console.log('Rango seleccionado:', startDate + ' a ' + endDate);
    });

    let debounceTimer;
    $('#buscar_oportunidad').on('input', function() {
        clearTimeout(debounceTimer); // Limpiar el temporizador anterior
        let fecharango = $("#fecha-rango").val();
        let busqueda = $(this).val();
        lista_seleccionados = obtener_usuarios_seleccionados();
        let categorias = obtenerCategoriasSeleccionadas();
        let prioridades = obtenerPrioridadesSeleccionadas();
        let tags = obtenerTagsSeleccionadas();

        // Establecer un nuevo temporizador
        debounceTimer = setTimeout(function() {
                // Aquí puedes realizar la búsqueda
                obtener_detalles_funnel(fecharango, busqueda,lista_seleccionados,categorias,prioridades,tags);
          
        }, 300); // Esperar 300 ms antes de ejecutar la búsqueda
    })

    // Limpiar el campo si el usuario cancela la selección de rango
    $('#fecha-rango').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val("");
        fecharango =$("#fecha-rango").val();
        busqueda =   $('#buscar_oportunidad').val();
        lista_seleccionados = obtener_usuarios_seleccionados();
        let categorias = obtenerCategoriasSeleccionadas();
        let prioridades = obtenerPrioridadesSeleccionadas();
        let tags = obtenerTagsSeleccionadas();

        obtener_detalles_funnel(fecharango,busqueda,lista_seleccionados,categorias,prioridades,tags);
        console.log('Filtro de fecha cancelado');
    });


    //para la lista de usuarios

    // Evento click en el avatar
    $('.avatar-chip').on('click', function() {
            const checkbox = $(this).find('.avatar-checkbox');
            checkbox.prop('checked', !checkbox.prop('checked')); // Alternar el estado del checkbox
            
            // Cambiar el color del círculo basado en el estado del checkbox
            if (checkbox.prop('checked')) {
                $(this).find('img').css('border', '1px solid #196EE5'); // Añadir un borde para indicar selección
            } else {
                $(this).find('img').css('border', 'none'); // Remover el borde
            }

            //pasamos los usuarios seleccionados junto con la fecha y busqueda
            lista_seleccionados = obtener_usuarios_seleccionados();
            fecharango =$("#fecha-rango").val();
            busqueda =   $('#buscar_oportunidad').val();
            let categorias = obtenerCategoriasSeleccionadas();
            let prioridades = obtenerPrioridadesSeleccionadas();
            let tags = obtenerTagsSeleccionadas();


            obtener_detalles_funnel(fecharango,busqueda,lista_seleccionados,categorias,prioridades,tags);

    });

    //Filtrado de categoria y prioridades en base al select2
    $('#filtro-categorias').select2({
        placeholder: "Selecciona Categorías",
        allowClear: true
    });

    $('#filtro-tags').select2({
        placeholder: "Selecciona los Tags",
        allowClear: true
    });



    $('#filtro-prioridad').select2({
        placeholder: "Selecciona Prioridades",
        allowClear: true
    });

    //Change de categoria
    $('#filtro-categorias,#filtro-prioridad,#filtro-tags,.form-check-input').change(function()
    {
        let categorias = obtenerCategoriasSeleccionadas();
        let tags = obtenerTagsSeleccionadas();
        let prioridades = obtenerPrioridadesSeleccionadas();
        let lista_seleccionados = obtener_usuarios_seleccionados();
        let fecharango =$("#fecha-rango").val();
        let busqueda =   $('#buscar_oportunidad').val();
        obtener_detalles_funnel(fecharango,busqueda,lista_seleccionados,categorias, prioridades,tags);
    });


    function obtenerTagsSeleccionadas() {
            var tagsSeleccionadas = [];
            
            // Recorre todos los checkboxes que están seleccionados
            $('.form-check-input:checked').each(function() {
                tagsSeleccionadas.push($(this).val());
            });

            // Verifica si hay etiquetas seleccionadas y únelas con comas
            if (tagsSeleccionadas.length > 0) {
                return tagsSeleccionadas.join(',');
            } else {
                return ''; // Devolver cadena vacía si no hay selección
            }
        }



      // Función para obtener categorías seleccionadas (separadas por comas)
      function obtenerCategoriasSeleccionadas() {
        var categoriasSeleccionadas = $('#filtro-categorias').val();
        if (categoriasSeleccionadas) {
            return categoriasSeleccionadas.join(',');
        } else {
            return ''; // Devolver cadena vacía si no hay selección
        }
    }

    // Función para obtener prioridades seleccionadas (separadas por comas)
    function obtenerPrioridadesSeleccionadas() {
        var prioridadesSeleccionadas = $('#filtro-prioridad').val();
        if (prioridadesSeleccionadas) {
            return prioridadesSeleccionadas.join(',');
        } else {
            return ''; // Devolver cadena vacía si no hay selección
        }
    }
    
    // Evento para obtener los seleccionados
    function obtener_usuarios_seleccionados()
    {
            const seleccionados = [];
            // Recorrer todos los checkboxes y obtener los que están seleccionados
            $('.avatar-checkbox:checked').each(function() {
                seleccionados.push($(this).val());
            });
            // Mostrar los valores seleccionados
            lista_usuarios = seleccionados.join(',');
            return lista_usuarios;
    }



</script>