/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    class TurismoUpload {
        constructor() {
            this.maxFileSize = 5 * 1024 * 1024; // 5MB
            this.allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            this.maxFiles = 10;
            this.uploadQueue = [];
            this.uploading = false;

            this.init();
        }

        /**
         * Inicializa o upload de imagens
         */
        init() {
            this.initializeDropZone();
            this.initializeFileInput();
            this.initializeSortable();
            this.bindEvents();
        }

        /**
         * Inicializa a área de arrastar e soltar
         */
        initializeDropZone() {
            const dropZone = document.querySelector('.turismo-dropzone');
            if (!dropZone) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                });
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => {
                    dropZone.classList.remove('dragover');
                });
            });

            dropZone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                this.handleFiles(files);
            });
        }

        /**
         * Inicializa o input de arquivo
         */
        initializeFileInput() {
            const fileInput = document.querySelector('.turismo-file-input');
            if (!fileInput) return;

            fileInput.addEventListener('change', (e) => {
                this.handleFiles(e.target.files);
            });
        }

        /**
         * Inicializa a ordenação das imagens
         */
        initializeSortable() {
            const container = document.querySelector('.turismo-image-preview');
            if (container && typeof Sortable !== 'undefined') {
                Sortable.create(container, {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: () => {
                        this.updateImageOrder();
                    }
                });
            }
        }

        /**
         * Vincula eventos
         */
        bindEvents() {
            $(document).on('click', '.turismo-remove-image', (e) => {
                e.preventDefault();
                this.removeImage($(e.currentTarget).closest('.image-item'));
            });

            $(document).on('click', '.turismo-set-featured', (e) => {
                e.preventDefault();
                this.setFeaturedImage($(e.currentTarget).closest('.image-item'));
            });
        }

        /**
         * Manipula os arquivos selecionados
         * @param {FileList} files - Lista de arquivos
         */
        handleFiles(files) {
            const validFiles = Array.from(files).filter(file => this.validateFile(file));

            if (validFiles.length + this.getUploadedCount() > this.maxFiles) {
                this.showError(Joomla.Text._('COM_TURISMO_ERROR_MAX_FILES'));
                return;
            }

            validFiles.forEach(file => {
                this.previewFile(file);
                this.uploadQueue.push(file);
            });

            if (!this.uploading) {
                this.processQueue();
            }
        }

        /**
         * Valida um arquivo
         * @param {File} file - Arquivo a ser validado
         * @returns {boolean} Válido ou não
         */
        validateFile(file) {
            if (!this.allowedTypes.includes(file.type)) {
                this.showError(Joomla.Text._('COM_TURISMO_ERROR_INVALID_TYPE'));
                return false;
            }

            if (file.size > this.maxFileSize) {
                this.showError(Joomla.Text._('COM_TURISMO_ERROR_FILE_TOO_LARGE'));
                return false;
            }

            return true;
        }

        /**
         * Exibe preview do arquivo
         * @param {File} file - Arquivo para preview
         */
        previewFile(file) {
            const reader = new FileReader();
            const $preview = $('<div>').addClass('image-item loading');
            
            reader.onload = (e) => {
                $preview.html(`
                    <div class="image-wrapper">
                        <img src="${e.target.result}" alt="">
                        <div class="image-overlay">
                            <button type="button" class="turismo-remove-image btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                            <button type="button" class="turismo-set-featured btn btn-primary btn-sm">
                                <i class="fas fa-star"></i>
                            </button>
                            <div class="drag-handle">
                                <i class="fas fa-grip-vertical"></i>
                            </div>
                        </div>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                `);
            };

            reader.readAsDataURL(file);
            $('.turismo-image-preview').append($preview);
        }

        /**
         * Processa a fila de upload
         */
        async processQueue() {
            if (this.uploadQueue.length === 0) {
                this.uploading = false;
                return;
            }

            this.uploading = true;
            const file = this.uploadQueue.shift();
            await this.uploadFile(file);
            this.processQueue();
        }

        /**
         * Faz upload de um arquivo
         * @param {File} file - Arquivo para upload
         */
        async uploadFile(file) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('format', 'json');
            formData.append(Joomla.getOptions('csrf.token'), '1');

            const $item = $('.image-item.loading').first();
            const $progress = $item.find('.progress-bar');

            try {
                const response = await $.ajax({
                    url: 'index.php?option=com_turismo&task=local.uploadImage',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: () => {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                const percentComplete = (e.loaded / e.total) * 100;
                                $progress.css('width', percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    }
                });

                if (response.success) {
                    $item.removeClass('loading')
                         .attr('data-id', response.id)
                         .find('img').attr('src', response.url);
                    $item.find('.progress').remove();
                } else {
                    this.showError(response.message);
                    $item.remove();
                }
            } catch (error) {
                this.showError(Joomla.Text._('COM_TURISMO_ERROR_UPLOAD_FAILED'));
                $item.remove();
            }
        }

        /**
         * Remove uma imagem
         * @param {jQuery} $item - Item da imagem
         */
        removeImage($item) {
            const imageId = $item.data('id');
            if (!imageId) {
                $item.remove();
                return;
            }

            if (confirm(Joomla.Text._('COM_TURISMO_CONFIRM_DELETE_IMAGE'))) {
                $.ajax({
                    url: 'index.php?option=com_turismo&task=local.deleteImage',
                    type: 'POST',
                    data: {
                        id: imageId,
                        format: 'json',
                        [Joomla.getOptions('csrf.token')]: 1
                    },
                    success: (response) => {
                        if (response.success) {
                            $item.fadeOut(() => $item.remove());
                        } else {
                            this.showError(response.message);
                        }
                    },
                    error: () => {
                        this.showError(Joomla.Text._('COM_TURISMO_ERROR_DELETE_FAILED'));
                    }
                });
            }
        }

        /**
         * Define imagem como destaque
         * @param {jQuery} $item - Item da imagem
         */
        setFeaturedImage($item) {
            const imageId = $item.data('id');
            if (!imageId) return;

            $.ajax({
                url: 'index.php?option=com_turismo&task=local.setFeaturedImage',
                type: 'POST',
                data: {
                    id: imageId,
                    format: 'json',
                    [Joomla.getOptions('csrf.token')]: 1
                },
                success: (response) => {
                    if (response.success) {
                        $('.image-item').removeClass('featured');
                        $item.addClass('featured');
                    } else {
                        this.showError(response.message);
                    }
                },
                error: () => {
                    this.showError(Joomla.Text._('COM_TURISMO_ERROR_SET_FEATURED_FAILED'));
                }
            });
        }

        /**
         * Atualiza a ordem das imagens
         */
        updateImageOrder() {
            const order = $('.image-item').map(function() {
                return $(this).data('id');
            }).get();

            $.ajax({
                url: 'index.php?option=com_turismo&task=local.updateImageOrder',
                type: 'POST',
                data: {
                    order: order,
                    format: 'json',
                    [Joomla.getOptions('csrf.token')]: 1
                },
                error: () => {
                    this.showError(Joomla.Text._('COM_TURISMO_ERROR_UPDATE_ORDER_FAILED'));
                }
            });
        }

        /**
         * Obtém o número de imagens carregadas
         * @returns {number} Número de imagens
         */
        getUploadedCount() {
            return $('.image-item').length;
        }

        /**
         * Exibe mensagem de erro
         * @param {string} message - Mensagem de erro
         */
        showError(message) {
            const $alert = $('<div>')
                .addClass('alert alert-danger alert-dismissible fade show')
                .html(`
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `);

            $('.turismo-upload-messages').append($alert);

            setTimeout(() => {
                $alert.alert('close');
            }, 5000);
        }
    }

    // Inicializa o upload quando o DOM estiver pronto
    $(document).ready(function() {
        window.TurismoUpload = new TurismoUpload();
    });

})(jQuery);
