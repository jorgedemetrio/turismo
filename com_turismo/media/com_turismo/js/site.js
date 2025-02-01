/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    // Inicialização quando o DOM estiver pronto
    $(document).ready(function () {
        initializeGallery();
        initializeRating();
        initializeValidation();
        initializeMasks();
        initializeFilters();
        initializeMap();
    });

    // Galeria de Fotos
    function initializeGallery() {
        const $mainImage = $('.turismo-main-image');
        const $thumbnails = $('.turismo-thumbnail');

        $thumbnails.on('click', function () {
            const newSrc = $(this).attr('src');
            $mainImage.fadeOut(300, function () {
                $(this).attr('src', newSrc).fadeIn(300);
            });
            $thumbnails.removeClass('active');
            $(this).addClass('active');
        });

        // Navegação por teclado
        $thumbnails.on('keypress', function (e) {
            if (e.which === 13 || e.which === 32) {
                $(this).click();
            }
        });
    }

    // Sistema de Avaliação
    function initializeRating() {
        const $ratingStars = $('.turismo-rating-input .fa-star');
        const $ratingInput = $('#rating-value');

        $ratingStars.on('mouseover', function () {
            const rating = $(this).data('rating');
            updateStars(rating);
        });

        $ratingStars.on('mouseout', function () {
            const currentRating = $ratingInput.val();
            updateStars(currentRating);
        });

        $ratingStars.on('click', function () {
            const rating = $(this).data('rating');
            $ratingInput.val(rating);
            updateStars(rating);
        });

        function updateStars(rating) {
            $ratingStars.each(function () {
                const starRating = $(this).data('rating');
                $(this).toggleClass('fas', starRating <= rating);
                $(this).toggleClass('far', starRating > rating);
            });
        }
    }

    // Validação de Formulários
    function initializeValidation() {
        $('.turismo-form').on('submit', function (e) {
            const $form = $(this);
            const $requiredFields = $form.find('[required]');
            let isValid = true;

            $requiredFields.each(function () {
                const $field = $(this);
                const value = $field.val().trim();

                if (!value) {
                    isValid = false;
                    showError($field, Joomla.Text._('COM_TURISMO_CAMPO_OBRIGATORIO'));
                } else {
                    clearError($field);

                    // Validação específica por tipo
                    if ($field.attr('type') === 'email' && !isValidEmail(value)) {
                        isValid = false;
                        showError($field, Joomla.Text._('COM_TURISMO_EMAIL_INVALIDO'));
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    // Máscaras de Input
    function initializeMasks() {
        if ($.fn.mask) {
            $('.phone-mask').mask('(00) 00000-0000');
            $('.cep-mask').mask('00000-000');
            $('.cnpj-mask').mask('00.000.000/0000-00');
        }
    }

    // Filtros de Busca
    function initializeFilters() {
        const $filters = $('.turismo-filter select, .turismo-filter input');
        let timeout;

        $filters.on('change keyup', function () {
            clearTimeout(timeout);
            timeout = setTimeout(applyFilters, 500);
        });

        function applyFilters() {
            const filters = {};
            $filters.each(function () {
                const value = $(this).val();
                if (value) {
                    filters[$(this).attr('name')] = value;
                }
            });

            // Mostra loading
            $('.turismo-list').addClass('loading');

            // Faz a requisição AJAX
            $.ajax({
                url: 'index.php?option=com_turismo&task=locais.filter&format=json',
                method: 'POST',
                data: filters,
                success: function (response) {
                    updateResults(response);
                },
                error: function (xhr) {
                    showAlert('error', Joomla.Text._('COM_TURISMO_ERRO_FILTRAR'));
                },
                complete: function () {
                    $('.turismo-list').removeClass('loading');
                }
            });
        }
    }

    // Inicialização do Mapa
    function initializeMap() {
        const $map = $('#turismo-map');
        if ($map.length && typeof google !== 'undefined') {
            const lat = parseFloat($map.data('lat'));
            const lng = parseFloat($map.data('lng'));
            const zoom = parseInt($map.data('zoom')) || 15;

            const map = new google.maps.Map($map[0], {
                center: { lat, lng },
                zoom: zoom,
                scrollwheel: false
            });

            new google.maps.Marker({
                position: { lat, lng },
                map: map,
                title: $map.data('title')
            });
        }
    }

    // Funções Auxiliares
    function showError($field, message) {
        clearError($field);
        $field.addClass('is-invalid');
        $('<div class="invalid-feedback">' + message + '</div>').insertAfter($field);
    }

    function clearError($field) {
        $field.removeClass('is-invalid');
        $field.next('.invalid-feedback').remove();
    }

    function isValidEmail(email) {
        const re = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return re.test(email);
    }

    function showAlert(type, message) {
        const alertClass = type === 'error' ? 'danger' : type;
        const $alert = $('<div class="alert alert-' + alertClass + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>');

        $('.turismo-alerts').append($alert);

        setTimeout(function () {
            $alert.alert('close');
        }, 5000);
    }

    function updateResults(response) {
        const $container = $('.turismo-list');
        $container.html(response.html);

        // Atualiza a URL com os parâmetros de filtro
        if (history.pushState) {
            const url = new URL(window.location);
            Object.entries(response.filters).forEach(([key, value]) => {
                if (value) {
                    url.searchParams.set(key, value);
                } else {
                    url.searchParams.delete(key);
                }
            });
            history.pushState({}, '', url);
        }
    }

})(jQuery);
