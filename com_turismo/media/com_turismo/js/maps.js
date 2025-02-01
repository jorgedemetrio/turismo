/**
 * @package     Joomla.Site
 * @subpackage  com_turismo
 *
 * @copyright   Copyright (C) 2023 Todos os direitos reservados.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

(function ($) {
    'use strict';

    class TurismoMaps {
        constructor() {
            this.maps = new Map();
            this.markers = new Map();
            this.infoWindows = new Map();
            this.bounds = null;
            this.defaultZoom = 15;
            this.defaultCenter = { lat: -15.7801, lng: -47.9292 }; // Brasília como centro padrão
        }

        /**
         * Inicializa o mapa em um elemento específico
         * @param {string} elementId - ID do elemento HTML
         * @param {Object} options - Opções de configuração
         */
        initMap(elementId, options = {}) {
            const element = document.getElementById(elementId);
            if (!element || typeof google === 'undefined') {
                return;
            }

            const mapOptions = {
                zoom: options.zoom || this.defaultZoom,
                center: options.center || this.defaultCenter,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false,
                styles: this.getMapStyles(),
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                zoomControl: true,
                zoomControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                streetViewControl: true,
                fullscreenControl: true
            };

            const map = new google.maps.Map(element, mapOptions);
            this.maps.set(elementId, map);

            if (options.markers) {
                this.addMarkers(elementId, options.markers);
            }

            // Adiciona controle de geolocalização
            this.addGeolocationControl(map);

            return map;
        }

        /**
         * Adiciona marcadores ao mapa
         * @param {string} mapId - ID do mapa
         * @param {Array} markers - Array de marcadores
         */
        addMarkers(mapId, markers) {
            const map = this.maps.get(mapId);
            if (!map) return;

            this.bounds = new google.maps.LatLngBounds();

            markers.forEach(markerData => {
                const position = new google.maps.LatLng(markerData.lat, markerData.lng);
                const marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markerData.title,
                    icon: markerData.icon || null,
                    animation: google.maps.Animation.DROP
                });

                this.bounds.extend(position);

                if (markerData.info) {
                    this.addInfoWindow(marker, markerData.info);
                }

                if (!this.markers.has(mapId)) {
                    this.markers.set(mapId, []);
                }
                this.markers.get(mapId).push(marker);
            });

            // Ajusta o zoom para mostrar todos os marcadores
            if (markers.length > 1) {
                map.fitBounds(this.bounds);
            }
        }

        /**
         * Adiciona uma janela de informações a um marcador
         * @param {Object} marker - Marcador do Google Maps
         * @param {string|Object} info - Conteúdo da janela de informações
         */
        addInfoWindow(marker, info) {
            const content = typeof info === 'string' ? info : this.createInfoWindowContent(info);
            const infoWindow = new google.maps.InfoWindow({
                content: content,
                maxWidth: 300
            });

            marker.addListener('click', () => {
                this.closeAllInfoWindows();
                infoWindow.open(marker.getMap(), marker);
            });

            this.infoWindows.set(marker, infoWindow);
        }

        /**
         * Fecha todas as janelas de informações abertas
         */
        closeAllInfoWindows() {
            this.infoWindows.forEach(infoWindow => {
                infoWindow.close();
            });
        }

        /**
         * Cria o conteúdo HTML para a janela de informações
         * @param {Object} data - Dados para a janela de informações
         * @returns {string} HTML formatado
         */
        createInfoWindowContent(data) {
            let content = '<div class="turismo-map-info">';
            
            if (data.image) {
                content += `<img src="${data.image}" alt="${data.title}" class="info-image">`;
            }
            
            content += `<h3>${data.title}</h3>`;
            
            if (data.description) {
                content += `<p>${data.description}</p>`;
            }
            
            if (data.address) {
                content += `<p><i class="fas fa-map-marker-alt"></i> ${data.address}</p>`;
            }
            
            if (data.phone) {
                content += `<p><i class="fas fa-phone"></i> ${data.phone}</p>`;
            }
            
            if (data.url) {
                content += `<a href="${data.url}" class="btn btn-primary btn-sm" target="_blank">
                    ${Joomla.Text._('COM_TURISMO_VER_MAIS')}
                </a>`;
            }
            
            content += '</div>';
            return content;
        }

        /**
         * Adiciona controle de geolocalização ao mapa
         * @param {Object} map - Instância do mapa
         */
        addGeolocationControl(map) {
            const controlDiv = document.createElement('div');
            const controlUI = document.createElement('button');
            
            controlUI.classList.add('turismo-map-control');
            controlUI.title = Joomla.Text._('COM_TURISMO_MINHA_LOCALIZACAO');
            controlUI.innerHTML = '<i class="fas fa-location-arrow"></i>';
            
            controlDiv.appendChild(controlUI);
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(controlDiv);

            controlUI.addEventListener('click', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            map.setCenter(pos);
                            map.setZoom(15);

                            // Adiciona marcador da localização atual
                            new google.maps.Marker({
                                position: pos,
                                map: map,
                                icon: {
                                    path: google.maps.SymbolPath.CIRCLE,
                                    scale: 10,
                                    fillColor: '#4285F4',
                                    fillOpacity: 1,
                                    strokeColor: '#fff',
                                    strokeWeight: 2
                                },
                                title: Joomla.Text._('COM_TURISMO_VOCE_ESTA_AQUI')
                            });
                        },
                        () => {
                            console.error('Erro ao obter localização');
                        }
                    );
                }
            });
        }

        /**
         * Retorna os estilos personalizados do mapa
         * @returns {Array} Estilos do mapa
         */
        getMapStyles() {
            return [
                {
                    featureType: 'poi',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                },
                {
                    featureType: 'transit',
                    elementType: 'labels',
                    stylers: [{ visibility: 'off' }]
                }
            ];
        }
    }

    // Exporta a classe para uso global
    window.TurismoMaps = new TurismoMaps();

})(jQuery);
