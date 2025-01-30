/**
 * @package     Joomla.Administrator
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // Inicialização de tooltips do Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicialização de popovers do Bootstrap
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Validação de formulários
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Manipulação de checkboxes na lista
    var checkAll = document.getElementById('checkall-toggle');
    if (checkAll) {
        checkAll.addEventListener('click', function(e) {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="cid[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = e.target.checked;
            });
        });
    }

    // Manipulação de ordenação
    var orderingInputs = document.querySelectorAll('.input-order');
    if (orderingInputs.length) {
        orderingInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                var value = parseInt(this.value, 10);
                if (!isNaN(value) && value > 0) {
                    this.value = value;
                } else {
                    this.value = '';
                }
            });
        });
    }

    // Manipulação de filtros
    var clearFiltersButton = document.getElementById('clear-filters');
    if (clearFiltersButton) {
        clearFiltersButton.addEventListener('click', function() {
            var filterInputs = document.querySelectorAll('.js-stools-field-filter input, .js-stools-field-filter select');
            filterInputs.forEach(function(input) {
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                } else {
                    input.value = '';
                }
            });
            document.getElementById('filter_search').value = '';
            this.form.submit();
        });
    }

    // Manipulação de estados (publicado/despublicado)
    var toggleState = document.querySelectorAll('.tbody-icon');
    if (toggleState.length) {
        toggleState.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                var url = this.getAttribute('data-url');
                if (url) {
                    window.location = url;
                }
            });
        });
    }

    // Manipulação de modal de exclusão
    var deleteButton = document.getElementById('delete-button');
    if (deleteButton) {
        deleteButton.addEventListener('click', function(e) {
            var checkedBoxes = document.querySelectorAll('input[name="cid[]"]:checked');
            if (checkedBoxes.length === 0) {
                e.preventDefault();
                alert(Joomla.Text._('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
                return false;
            }
            if (!confirm(Joomla.Text._('COM_TURISMO_DELETE_CONFIRM'))) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Manipulação de campos dinâmicos
    var addFieldButton = document.querySelector('.add-field');
    if (addFieldButton) {
        addFieldButton.addEventListener('click', function(e) {
            e.preventDefault();
            var container = document.querySelector('.fields-container');
            var template = document.querySelector('.field-template');
            var clone = template.cloneNode(true);
            clone.classList.remove('field-template');
            clone.classList.remove('hidden');
            container.appendChild(clone);

            // Adicionar evento para remover campo
            var removeButton = clone.querySelector('.remove-field');
            if (removeButton) {
                removeButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    clone.remove();
                });
            }
        });
    }
});
