/**
 * @package     Joomla.Site
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

    // Sistema de avaliação com estrelas
    var ratingContainers = document.querySelectorAll('.turismo-rating');
    if (ratingContainers.length) {
        ratingContainers.forEach(function(container) {
            var stars = container.querySelectorAll('.star');
            var input = container.querySelector('input[type="hidden"]');

            stars.forEach(function(star, index) {
                // Evento hover
                star.addEventListener('mouseover', function() {
                    stars.forEach(function(s, i) {
                        if (i <= index) {
                            s.classList.add('hover');
                        } else {
                            s.classList.remove('hover');
                        }
                    });
                });

                // Evento click
                star.addEventListener('click', function() {
                    var rating = index + 1;
                    input.value = rating;
                    stars.forEach(function(s, i) {
                        if (i < rating) {
                            s.classList.add('active');
                        } else {
                            s.classList.remove('active');
                        }
                    });
                });
            });

            // Remover hover
            container.addEventListener('mouseleave', function() {
                stars.forEach(function(star) {
                    star.classList.remove('hover');
                });
            });
        });
    }

    // Inicialização do mapa
    var mapElement = document.getElementById('turismo-map');
    if (mapElement && typeof google !== 'undefined') {
        var lat = parseFloat(mapElement.dataset.lat) || -23.5505;
        var lng = parseFloat(mapElement.dataset.lng) || -46.6333;
        var zoom = parseInt(mapElement.dataset.zoom) || 15;

        var map = new google.maps.Map(mapElement, {
            center: { lat: lat, lng: lng },
            zoom: zoom,
            scrollwheel: false
        });

        var marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            animation: google.maps.Animation.DROP
        });

        // Info window se houver conteúdo
        if (mapElement.dataset.info) {
            var infoWindow = new google.maps.InfoWindow({
                content: mapElement.dataset.info
            });

            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    }

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

    // Upload de imagens com preview
    var imageInputs = document.querySelectorAll('.turismo-image-upload');
    if (imageInputs.length) {
        imageInputs.forEach(function(input) {
            input.addEventListener('change', function(e) {
                var preview = document.getElementById(this.dataset.preview);
                if (preview && this.files && this.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    }

    // Filtros dinâmicos
    var filterInputs = document.querySelectorAll('.turismo-filter');
    if (filterInputs.length) {
        filterInputs.forEach(function(input) {
            input.addEventListener('change', function() {
                var form = this.closest('form');
                if (form) {
                    form.submit();
                }
            });
        });
    }

    // Carregamento lazy de imagens
    var lazyImages = document.querySelectorAll('.turismo-lazy-load');
    if ('IntersectionObserver' in window) {
        var imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    var img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.remove('turismo-lazy-load');
                    imageObserver.unobserve(img);
                }
            });
        });

        lazyImages.forEach(function(img) {
            imageObserver.observe(img);
        });
    }
});
