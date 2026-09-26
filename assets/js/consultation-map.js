/**
 * Carte des lieux de consultation (Leaflet + OpenStreetMap).
 *
 * Chaque élément .nf-map__canvas porte en data-nf-map la liste JSON des lieux
 * ({ nom, adresse, lat, lng, url }). Le cadrage englobe automatiquement tous
 * les repères ; un lieu unique est centré au niveau du quartier.
 */
(function () {
	'use strict';

	function escapeHtml(value) {
		return String(value == null ? '' : value)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;');
	}

	function initMap(el) {
		if (el.dataset.nfMapReady) {
			return;
		}
		var points;
		try {
			points = JSON.parse(el.getAttribute('data-nf-map'));
		} catch (e) {
			return;
		}
		if (!points || !points.length) {
			return;
		}
		el.dataset.nfMapReady = '1';

		var map = L.map(el, { scrollWheelZoom: false });
		L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a>'
		}).addTo(map);

		var group = L.featureGroup();
		points.forEach(function (point) {
			var marker = L.marker([point.lat, point.lng]);
			var html = '<strong>' + escapeHtml(point.nom) + '</strong>';
			if (point.adresse) {
				html += '<br>' + escapeHtml(point.adresse);
			}
			if (point.url) {
				html += '<br><a href="' + escapeHtml(point.url) + '" target="_blank" rel="noopener">Itinéraire</a>';
			}
			marker.bindPopup(html);
			group.addLayer(marker);
		});
		group.addTo(map);

		function frame() {
			if (points.length === 1) {
				map.setView([points[0].lat, points[0].lng], 16);
			} else {
				map.fitBounds(group.getBounds(), { padding: [40, 40], maxZoom: 16 });
			}
		}
		frame();

		// Le conteneur peut être animé (fondu/translation) à l'apparition : on recalcule la taille ensuite.
		setTimeout(function () {
			map.invalidateSize();
			frame();
		}, 600);
	}

	function init() {
		if (typeof L === 'undefined') {
			return;
		}
		var canvases = document.querySelectorAll('.nf-map__canvas[data-nf-map]');
		for (var i = 0; i < canvases.length; i++) {
			initMap(canvases[i]);
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
