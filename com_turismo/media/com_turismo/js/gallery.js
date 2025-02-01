/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    class TurismoGallery {
        constructor() {
            this.currentIndex = 0;
            this.isFullscreen = false;
            this.touchStartX = 0;
            this.touchEndX = 0;
            
            this.init();
        }

        /**
         * Inicializa a galeria
         */
        init() {
            this.createLightbox();
            this.bindEvents();
            this.initializeLazyLoading();
            this.setupKeyboardNavigation();
            this.setupTouchNavigation();
        }

        /**
         * Cria o HTML do lightbox
         */
        createLightbox() {
            const lightboxHtml = `
                <div id="turismo-lightbox" class="turismo-lightbox" role="dialog" aria-hidden="true">
                    <div class="lightbox-content">
                        <button type="button" class="close-button" aria-label="${Joomla.Text._('COM_TURISMO_FECHAR')}">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <button type="button" class="nav-button prev" aria-label="${Joomla.Text._('COM_TURISMO_ANTERIOR')}">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button type="button" class="nav-button next" aria-label="${Joomla.Text._('COM_TURISMO_PROXIMO')}">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                        <div class="image-container">
                            <img src="" alt="" class="lightbox-image">
                        </div>
                        <div class="caption"></div>
                        <div class="counter"></div>
                        <button type="button" class="fullscreen-button" aria-label="${Joomla.Text._('COM_TURISMO_TELA_CHEIA')}">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                    <div class="thumbnails-container">
                        <div class="thumbnails-wrapper"></div>
                    </div>
                </div>`;

            $('body').append(lightboxHtml);
        }

        /**
         * Vincula eventos aos elementos
         */
        bindEvents() {
            const self = this;
            const $lightbox = $('#turismo-lightbox');

            // Abre o lightbox ao clicar em uma imagem da galeria
            $('.turismo-gallery-item').on('click', function(e) {
                e.preventDefault();
                const $gallery = $(this).closest('.turismo-gallery');
                const images = self.getGalleryImages($gallery);
                const index = $(this).data('index');
                self.openLightbox(images, index);
            });

            // Eventos do lightbox
            $lightbox.find('.close-button').on('click', () => this.closeLightbox());
            $lightbox.find('.prev').on('click', () => this.showPrevImage());
            $lightbox.find('.next').on('click', () => this.showNextImage());
            $lightbox.find('.fullscreen-button').on('click', () => this.toggleFullscreen());

            // Fecha o lightbox ao clicar fora da imagem
            $lightbox.on('click', function(e) {
                if ($(e.target).is($lightbox)) {
                    self.closeLightbox();
                }
            });

            // Atualiza o layout em caso de redimensionamento
            $(window).on('resize', () => this.updateLightboxLayout());
        }

        /**
         * Inicializa o carregamento preguiçoso das imagens
         */
        initializeLazyLoading() {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            img.src = img.dataset.src;
                            img.classList.remove('lazy');
                            observer.unobserve(img);
                        }
                    });
                });

                document.querySelectorAll('img.lazy').forEach(img => {
                    imageObserver.observe(img);
                });
            }
        }

        /**
         * Configura navegação por teclado
         */
        setupKeyboardNavigation() {
            $(document).on('keydown', (e) => {
                if ($('#turismo-lightbox').is(':visible')) {
                    switch(e.key) {
                        case 'Escape':
                            this.closeLightbox();
                            break;
                        case 'ArrowLeft':
                            this.showPrevImage();
                            break;
                        case 'ArrowRight':
                            this.showNextImage();
                            break;
                        case 'f':
                            this.toggleFullscreen();
                            break;
                    }
                }
            });
        }

        /**
         * Configura navegação por toque
         */
        setupTouchNavigation() {
            const $lightbox = $('#turismo-lightbox');

            $lightbox.on('touchstart', (e) => {
                this.touchStartX = e.touches[0].clientX;
            });

            $lightbox.on('touchend', (e) => {
                this.touchEndX = e.changedTouches[0].clientX;
                this.handleTouchNavigation();
            });
        }

        /**
         * Manipula navegação por toque
         */
        handleTouchNavigation() {
            const difference = this.touchStartX - this.touchEndX;
            const threshold = 50;

            if (Math.abs(difference) > threshold) {
                if (difference > 0) {
                    this.showNextImage();
                } else {
                    this.showPrevImage();
                }
            }
        }

        /**
         * Obtém imagens da galeria
         * @param {jQuery} $gallery - Elemento da galeria
         * @returns {Array} Array de objetos de imagem
         */
        getGalleryImages($gallery) {
            const images = [];
            $gallery.find('.turismo-gallery-item').each(function() {
                images.push({
                    src: $(this).data('full'),
                    thumb: $(this).find('img').attr('src'),
                    caption: $(this).data('caption')
                });
            });
            return images;
        }

        /**
         * Abre o lightbox
         * @param {Array} images - Array de imagens
         * @param {number} index - Índice inicial
         */
        openLightbox(images, index) {
            this.images = images;
            this.currentIndex = index;
            this.updateLightboxContent();
            this.updateThumbnails();
            
            const $lightbox = $('#turismo-lightbox');
            $lightbox.fadeIn().attr('aria-hidden', 'false');
            $('body').addClass('lightbox-open');
            
            this.preloadImages();
        }

        /**
         * Fecha o lightbox
         */
        closeLightbox() {
            if (this.isFullscreen) {
                this.exitFullscreen();
            }
            
            const $lightbox = $('#turismo-lightbox');
            $lightbox.fadeOut().attr('aria-hidden', 'true');
            $('body').removeClass('lightbox-open');
        }

        /**
         * Atualiza o conteúdo do lightbox
         */
        updateLightboxContent() {
            const $lightbox = $('#turismo-lightbox');
            const image = this.images[this.currentIndex];

            $lightbox.find('.lightbox-image')
                .attr('src', image.src)
                .attr('alt', image.caption || '');

            $lightbox.find('.caption').text(image.caption || '');
            $lightbox.find('.counter').text(`${this.currentIndex + 1} / ${this.images.length}`);

            this.updateNavigationButtons();
        }

        /**
         * Atualiza os botões de navegação
         */
        updateNavigationButtons() {
            const $lightbox = $('#turismo-lightbox');
            $lightbox.find('.prev').toggleClass('disabled', this.currentIndex === 0);
            $lightbox.find('.next').toggleClass('disabled', this.currentIndex === this.images.length - 1);
        }

        /**
         * Atualiza as miniaturas
         */
        updateThumbnails() {
            const $wrapper = $('#turismo-lightbox .thumbnails-wrapper');
            $wrapper.empty();

            this.images.forEach((image, index) => {
                const $thumb = $('<img>', {
                    src: image.thumb,
                    alt: image.caption || '',
                    class: index === this.currentIndex ? 'active' : ''
                }).on('click', () => this.showImage(index));

                $wrapper.append($thumb);
            });

            this.scrollThumbnailIntoView();
        }

        /**
         * Rola a miniatura ativa para a visualização
         */
        scrollThumbnailIntoView() {
            const $wrapper = $('#turismo-lightbox .thumbnails-wrapper');
            const $activeThumb = $wrapper.find('img.active');
            
            if ($activeThumb.length) {
                $wrapper.animate({
                    scrollLeft: $activeThumb.position().left + $wrapper.scrollLeft() - 
                              ($wrapper.width() / 2) + ($activeThumb.width() / 2)
                }, 300);
            }
        }

        /**
         * Mostra a imagem anterior
         */
        showPrevImage() {
            if (this.currentIndex > 0) {
                this.showImage(this.currentIndex - 1);
            }
        }

        /**
         * Mostra a próxima imagem
         */
        showNextImage() {
            if (this.currentIndex < this.images.length - 1) {
                this.showImage(this.currentIndex + 1);
            }
        }

        /**
         * Mostra uma imagem específica
         * @param {number} index - Índice da imagem
         */
        showImage(index) {
            this.currentIndex = index;
            this.updateLightboxContent();
            this.updateThumbnails();
        }

        /**
         * Alterna modo tela cheia
         */
        toggleFullscreen() {
            if (!this.isFullscreen) {
                this.enterFullscreen();
            } else {
                this.exitFullscreen();
            }
        }

        /**
         * Entra no modo tela cheia
         */
        enterFullscreen() {
            const element = document.getElementById('turismo-lightbox');
            
            if (element.requestFullscreen) {
                element.requestFullscreen();
            } else if (element.webkitRequestFullscreen) {
                element.webkitRequestFullscreen();
            } else if (element.msRequestFullscreen) {
                element.msRequestFullscreen();
            }

            this.isFullscreen = true;
            this.updateFullscreenButton();
        }

        /**
         * Sai do modo tela cheia
         */
        exitFullscreen() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            this.isFullscreen = false;
            this.updateFullscreenButton();
        }

        /**
         * Atualiza o botão de tela cheia
         */
        updateFullscreenButton() {
            const $button = $('#turismo-lightbox .fullscreen-button i');
            $button.toggleClass('fa-expand', !this.isFullscreen);
            $button.toggleClass('fa-compress', this.isFullscreen);
        }

        /**
         * Pré-carrega imagens
         */
        preloadImages() {
            const preloadNext = this.currentIndex + 2;
            const preloadPrev = this.currentIndex - 1;

            if (preloadNext < this.images.length) {
                new Image().src = this.images[preloadNext].src;
            }
            if (preloadPrev >= 0) {
                new Image().src = this.images[preloadPrev].src;
            }
        }

        /**
         * Atualiza o layout do lightbox
         */
        updateLightboxLayout() {
            if ($('#turismo-lightbox').is(':visible')) {
                this.scrollThumbnailIntoView();
            }
        }
    }

    // Inicializa a galeria quando o DOM estiver pronto
    $(document).ready(function() {
        window.TurismoGallery = new TurismoGallery();
    });

})(jQuery);
