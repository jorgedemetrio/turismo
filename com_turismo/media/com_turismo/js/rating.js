/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    class TurismoRating {
        constructor() {
            this.init();
        }

        /**
         * Inicializa o sistema de avaliações
         */
        init() {
            this.initializeRatingStars();
            this.initializeRatingForm();
            this.initializeRatingFilter();
            this.setupAjaxErrorHandler();
        }

        /**
         * Inicializa as estrelas de avaliação
         */
        initializeRatingStars() {
            const self = this;
            const $ratingContainer = $('.turismo-rating-input');

            $ratingContainer.each(function() {
                const $container = $(this);
                const $stars = $container.find('.rating-star');
                const $input = $container.find('input[type="hidden"]');
                const $display = $container.find('.rating-display');

                // Eventos de mouse
                $stars.on('mouseover', function() {
                    self.updateStarsDisplay($(this).data('rating'), $stars);
                });

                $container.on('mouseleave', function() {
                    self.updateStarsDisplay($input.val(), $stars);
                });

                // Evento de clique
                $stars.on('click', function() {
                    const rating = $(this).data('rating');
                    $input.val(rating);
                    self.updateStarsDisplay(rating, $stars);
                    $display.text(rating);
                });

                // Eventos de teclado para acessibilidade
                $stars.on('keydown', function(e) {
                    const currentRating = parseInt($(this).data('rating'));
                    let newRating = currentRating;

                    switch(e.key) {
                        case 'ArrowRight':
                        case 'ArrowUp':
                            newRating = Math.min(currentRating + 1, 5);
                            break;
                        case 'ArrowLeft':
                        case 'ArrowDown':
                            newRating = Math.max(currentRating - 1, 1);
                            break;
                        case 'Enter':
                        case ' ':
                            $(this).click();
                            e.preventDefault();
                            return;
                    }

                    if (newRating !== currentRating) {
                        $stars.filter(`[data-rating="${newRating}"]`).focus().click();
                        e.preventDefault();
                    }
                });
            });
        }

        /**
         * Atualiza a exibição das estrelas
         * @param {number} rating - Valor da avaliação
         * @param {jQuery} $stars - Elementos das estrelas
         */
        updateStarsDisplay(rating, $stars) {
            $stars.each(function() {
                const starRating = $(this).data('rating');
                $(this).toggleClass('active', starRating <= rating);
                $(this).attr('aria-checked', starRating <= rating);
            });
        }

        /**
         * Inicializa o formulário de avaliação
         */
        initializeRatingForm() {
            const self = this;
            $('.turismo-rating-form').on('submit', function(e) {
                e.preventDefault();
                const $form = $(this);
                const $submitButton = $form.find('[type="submit"]');

                if (!self.validateRatingForm($form)) {
                    return;
                }

                $submitButton.prop('disabled', true);
                
                const formData = new FormData($form[0]);
                formData.append('format', 'json');

                $.ajax({
                    url: 'index.php?option=com_turismo&task=local.saveRating',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            self.handleRatingSuccess(response, $form);
                        } else {
                            self.showError($form, response.message);
                        }
                    },
                    error: function(xhr) {
                        self.handleAjaxError(xhr, $form);
                    },
                    complete: function() {
                        $submitButton.prop('disabled', false);
                    }
                });
            });
        }

        /**
         * Valida o formulário de avaliação
         * @param {jQuery} $form - Formulário
         * @returns {boolean} Válido ou não
         */
        validateRatingForm($form) {
            const rating = $form.find('input[name="rating"]').val();
            const comment = $form.find('textarea[name="comment"]').val();

            if (!rating) {
                this.showError($form, Joomla.Text._('COM_TURISMO_ERROR_RATING_REQUIRED'));
                return false;
            }

            if (!comment || comment.length < 10) {
                this.showError($form, Joomla.Text._('COM_TURISMO_ERROR_COMMENT_MIN_LENGTH'));
                return false;
            }

            return true;
        }

        /**
         * Inicializa o filtro de avaliações
         */
        initializeRatingFilter() {
            const self = this;
            $('.turismo-rating-filter').on('change', function() {
                const $container = $('.turismo-reviews-list');
                const rating = $(this).val();

                $container.addClass('loading');

                $.ajax({
                    url: 'index.php?option=com_turismo&task=local.filterRatings',
                    type: 'POST',
                    data: {
                        local_id: $container.data('local-id'),
                        rating: rating,
                        format: 'json'
                    },
                    success: function(response) {
                        if (response.success) {
                            $container.html(response.html);
                            self.updateRatingStats(response.stats);
                        }
                    },
                    complete: function() {
                        $container.removeClass('loading');
                    }
                });
            });
        }

        /**
         * Atualiza as estatísticas de avaliação
         * @param {Object} stats - Estatísticas de avaliação
         */
        updateRatingStats(stats) {
            $('.turismo-rating-average').text(stats.average.toFixed(1));
            $('.turismo-rating-count').text(stats.total);

            // Atualiza o gráfico de barras das avaliações
            Object.keys(stats.distribution).forEach(rating => {
                const percentage = (stats.distribution[rating] / stats.total) * 100;
                $(`.turismo-rating-bar[data-rating="${rating}"]`)
                    .css('width', percentage + '%')
                    .attr('aria-valuenow', percentage);
            });
        }

        /**
         * Manipula o sucesso do envio da avaliação
         * @param {Object} response - Resposta do servidor
         * @param {jQuery} $form - Formulário
         */
        handleRatingSuccess(response, $form) {
            // Limpa o formulário
            $form[0].reset();
            $form.find('.rating-star').removeClass('active');
            $form.find('input[name="rating"]').val('');

            // Atualiza a lista de avaliações
            if (response.html) {
                $('.turismo-reviews-list').html(response.html);
            }

            // Atualiza as estatísticas
            if (response.stats) {
                this.updateRatingStats(response.stats);
            }

            // Mostra mensagem de sucesso
            this.showSuccess($form, Joomla.Text._('COM_TURISMO_RATING_SAVED'));
        }

        /**
         * Configura o manipulador de erros Ajax global
         */
        setupAjaxErrorHandler() {
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 403) {
                    window.location = 'index.php?option=com_users&view=login';
                }
            });
        }

        /**
         * Exibe mensagem de erro
         * @param {jQuery} $form - Formulário
         * @param {string} message - Mensagem de erro
         */
        showError($form, message) {
            const $alert = $('<div>')
                .addClass('alert alert-danger alert-dismissible fade show')
                .html(`
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `);

            $form.find('.turismo-rating-messages').html($alert);
        }

        /**
         * Exibe mensagem de sucesso
         * @param {jQuery} $form - Formulário
         * @param {string} message - Mensagem de sucesso
         */
        showSuccess($form, message) {
            const $alert = $('<div>')
                .addClass('alert alert-success alert-dismissible fade show')
                .html(`
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `);

            $form.find('.turismo-rating-messages').html($alert);
        }

        /**
         * Manipula erros de Ajax
         * @param {Object} xhr - Objeto XHR
         * @param {jQuery} $form - Formulário
         */
        handleAjaxError(xhr, $form) {
            let message = Joomla.Text._('COM_TURISMO_ERROR_SAVING_RATING');
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            this.showError($form, message);
        }
    }

    // Inicializa o sistema de avaliações quando o DOM estiver pronto
    $(document).ready(function() {
        window.TurismoRating = new TurismoRating();
    });

})(jQuery);
