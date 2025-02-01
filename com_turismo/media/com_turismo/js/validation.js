
/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    class TurismoValidation {
        constructor() {
            this.init();
        }

        /**
         * Inicializa a validação
         */
        init() {
            this.initializeMasks();
            this.initializeValidation();
            this.initializeCustomValidators();
            this.initializeRemoteValidation();
        }

        /**
         * Inicializa as máscaras de input
         */
        initializeMasks() {
            if ($.fn.mask) {
                $('.phone-mask').mask('(00) 00000-0000', {
                    onKeyPress: (val, e, field, options) => {
                        const masks = ['(00) 0000-00009', '(00) 00000-0000'];
                        const mask = (val.length > 14) ? masks[1] : masks[0];
                        $('.phone-mask').mask(mask, options);
                    }
                });
                
                $('.cep-mask').mask('00000-000');
                $('.cnpj-mask').mask('00.000.000/0000-00');
                $('.cpf-mask').mask('000.000.000-00');
                $('.date-mask').mask('00/00/0000');
                $('.time-mask').mask('00:00');
                $('.money-mask').mask('000.000.000.000.000,00', {
                    reverse: true,
                    placeholder: '0,00'
                });
            }
        }

        /**
         * Inicializa a validação de formulários
         */
        initializeValidation() {
            $('.turismo-form').on('submit', (e) => {
                const $form = $(e.currentTarget);
                if (!this.validateForm($form)) {
                    e.preventDefault();
                    this.scrollToFirstError($form);
                }
            });

            // Validação em tempo real
            $('.turismo-form .validate').on('blur', (e) => {
                this.validateField($(e.currentTarget));
            });

            // Validação de campos dependentes
            $('.turismo-form').on('change', '[data-depends-on]', (e) => {
                this.validateDependentField($(e.currentTarget));
            });
        }

        /**
         * Inicializa validadores customizados
         */
        initializeCustomValidators() {
            // Validador de CNPJ
            this.addValidator('cnpj', (value) => {
                value = value.replace(/[^\d]+/g, '');
                
                if (value.length !== 14) return false;
                
                // Elimina CNPJs inválidos conhecidos
                if (/^(\d)\1+$/.test(value)) return false;
                
                // Valida DVs
                let tamanho = value.length - 2;
                let numeros = value.substring(0, tamanho);
                const digitos = value.substring(tamanho);
                let soma = 0;
                let pos = tamanho - 7;
                
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                
                let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado !== parseInt(digitos.charAt(0))) return false;
                
                tamanho = tamanho + 1;
                numeros = value.substring(0, tamanho);
                soma = 0;
                pos = tamanho - 7;
                
                for (let i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2) pos = 9;
                }
                
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                
                return resultado === parseInt(digitos.charAt(1));
            });

            // Validador de CEP
            this.addValidator('cep', (value) => {
                return /^\d{5}-?\d{3}$/.test(value);
            });

            // Validador de telefone
            this.addValidator('phone', (value) => {
                return /^\(\d{2}\) \d{4,5}-\d{4}$/.test(value);
            });

            // Validador de data
            this.addValidator('date', (value) => {
                if (!/^\d{2}\/\d{2}\/\d{4}$/.test(value)) return false;
                
                const [day, month, year] = value.split('/').map(Number);
                const date = new Date(year, month - 1, day);
                
                return date.getDate() === day &&
                       date.getMonth() === month - 1 &&
                       date.getFullYear() === year;
            });

            // Validador de horário
            this.addValidator('time', (value) => {
                if (!/^\d{2}:\d{2}$/.test(value)) return false;
                
                const [hours, minutes] = value.split(':').map(Number);
                return hours >= 0 && hours <= 23 && minutes >= 0 && minutes <= 59;
            });
        }

        /**
         * Inicializa validação remota
         */
        initializeRemoteValidation() {
            let timeouts = {};

            $('.turismo-form [data-remote]').on('blur', (e) => {
                const $field = $(e.currentTarget);
                const fieldName = $field.attr('name');
                
                // Cancela requisição anterior pendente
                if (timeouts[fieldName]) {
                    clearTimeout(timeouts[fieldName]);
                }

                timeouts[fieldName] = setTimeout(() => {
                    this.validateRemoteField($field);
                }, 300);
            });
        }

        /**
         * Valida um formulário
         * @param {jQuery} $form - Formulário
         * @returns {boolean} Válido ou não
         */
        validateForm($form) {
            let isValid = true;
            const self = this;

            $form.find('.validate').each(function() {
                if (!self.validateField($(this))) {
                    isValid = false;
                }
            });

            return isValid;
        }

        /**
         * Valida um campo
         * @param {jQuery} $field - Campo
         * @returns {boolean} Válido ou não
         */
        validateField($field) {
            const value = $field.val().trim();
            const rules = $field.data('rules') ? $field.data('rules').split('|') : [];
            let isValid = true;
            let errorMessage = '';

            // Validação requerida
            if (rules.includes('required') && !value) {
                isValid = false;
                errorMessage = Joomla.Text._('COM_TURISMO_CAMPO_OBRIGATORIO');
            }

            // Validação de email
            if (isValid && rules.includes('email') && value) {
                isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
                if (!isValid) {
                    errorMessage = Joomla.Text._('COM_TURISMO_EMAIL_INVALIDO');
                }
            }

            // Validação de comprimento mínimo
            const minLength = rules.find(rule => rule.startsWith('min:'));
            if (isValid && minLength && value) {
                const min = parseInt(minLength.split(':')[1]);
                isValid = value.length >= min;
                if (!isValid) {
                    errorMessage = Joomla.Text._('COM_TURISMO_MIN_LENGTH').replace('%d', min);
                }
            }

            // Validação de comprimento máximo
            const maxLength = rules.find(rule => rule.startsWith('max:'));
            if (isValid && maxLength && value) {
                const max = parseInt(maxLength.split(':')[1]);
                isValid = value.length <= max;
                if (!isValid) {
                    errorMessage = Joomla.Text._('COM_TURISMO_MAX_LENGTH').replace('%d', max);
                }
            }

            // Validação customizada
            rules.forEach(rule => {
                if (isValid && this.validators[rule] && value) {
                    isValid = this.validators[rule](value);
                    if (!isValid) {
                        errorMessage = Joomla.Text._(`COM_TURISMO_INVALID_${rule.toUpperCase()}`);
                    }
                }
            });

            this.setFieldValidationState($field, isValid, errorMessage);
            return isValid;
        }

        /**
         * Valida campo dependente
         * @param {jQuery} $field - Campo
         */
        validateDependentField($field) {
            const dependsOn = $field.data('depends-on');
            const $dependentField = $(`[name="${dependsOn}"]`);
            const dependentValue = $dependentField.val();
            const requiredValue = $field.data('depends-value');

            if (dependentValue === requiredValue) {
                $field.prop('disabled', false).addClass('validate');
            } else {
                $field.prop('disabled', true).removeClass('validate');
                this.clearFieldValidation($field);
            }
        }

        /**
         * Valida campo remotamente
         * @param {jQuery} $field - Campo
         */
        validateRemoteField($field) {
            const value = $field.val().trim();
            if (!value) return;

            const url = $field.data('remote');
            const $form = $field.closest('form');
            
            $field.addClass('validating');

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    field: $field.attr('name'),
                    value: value,
                    [Joomla.getOptions('csrf.token')]: 1
                },
                success: (response) => {
                    this.setFieldValidationState(
                        $field,
                        response.valid,
                        response.message
                    );
                },
                error: () => {
                    this.setFieldValidationState(
                        $field,
                        false,
                        Joomla.Text._('COM_TURISMO_ERROR_VALIDATION')
                    );
                },
                complete: () => {
                    $field.removeClass('validating');
                }
            });
        }

        /**
         * Define o estado de validação de um campo
         * @param {jQuery} $field - Campo
         * @param {boolean} isValid - Estado de validação
         * @param {string} errorMessage - Mensagem de erro
         */
        setFieldValidationState($field, isValid, errorMessage) {
            const $formGroup = $field.closest('.form-group');
            const $feedback = $formGroup.find('.invalid-feedback');

            $field.toggleClass('is-invalid', !isValid)
                  .toggleClass('is-valid', isValid);

            if (!isValid) {
                if ($feedback.length) {
                    $feedback.text(errorMessage);
                } else {
                    $formGroup.append(`<div class="invalid-feedback">${errorMessage}</div>`);
                }
            } else {
                $feedback.remove();
            }
        }

        /**
         * Limpa a validação de um campo
         * @param {jQuery} $field - Campo
         */
        clearFieldValidation($field) {
            const $formGroup = $field.closest('.form-group');
            $field.removeClass('is-invalid is-valid');
            $formGroup.find('.invalid-feedback').remove();
        }

        /**
         * Rola até o primeiro erro
         * @param {jQuery} $form - Formulário
         */
        scrollToFirstError($form) {
            const $firstError = $form.find('.is-invalid').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
                $firstError.focus();
            }
        }

        /**
         * Adiciona um validador customizado
         * @param {string} name - Nome do validador
         * @param {Function} validator - Função validadora
         */
        addValidator(name, validator) {
            this.validators = this.validators || {};
            this.validators[name] = validator;
        }
    }

    // Inicializa a validação quando o DOM estiver pronto
    $(document).ready(function() {
        window.TurismoValidation = new TurismoValidation();
    });

})(jQuery);
