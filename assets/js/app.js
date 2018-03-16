
require('jquery');
require('bootstrap/dist/js/bootstrap.bundle.js');

global.$ = global.jQuery = require('jquery');

jQuery(document).ready(function ($) {
    var nbLivres = parseInt(document.getElementById('books').value);
    var i;
    for (i = 1; i <= nbLivres; i++) {
        window['idHoverDown' + i] = 'flecheDown' + i;
        window['idHoverUp' + i] = 'flecheUp' + i;
        //Variables permettant le scroll du texte vers le bas
        var incrementScroll = function (id) {
            var taille = id.length;
            var nombre = id.substring(10, taille);
            var resumBook = 'resumeLivre' + nombre;
            document.getElementById(resumBook).scrollTop += 25;
        };

        var scrollLoopId;
        var startScrollLoop = function (id) {
            scrollLoopId = setInterval(incrementScroll(id), 70);
        };


        //Hover permettant le scroll vers le bas
        $('#' + window['idHoverDown' + i]).on('click', function () {
            startScrollLoop($(this).attr('id'));
        });

        //Variables permettant le scroll du texte vers le haut
        var incrementScrollTop = function (id) {
            var taille = id.length;
            var nombre = id.substring(8, taille);
            var resumBook = 'resumeLivre' + nombre;
            document.getElementById(resumBook).scrollTop -= 25;
        };

        var scrollLoopIdTop;
        var startScrollLoopTop = function (id) {
            scrollLoopIdTop = setInterval(incrementScrollTop(id), 70);
        };

        //Hover permettant le scroll vers le bas
        $('#' + window['idHoverUp' + i]).on('click', function () {
            startScrollLoopTop($(this).attr('id'));
        });
    }
    ;


    var CheminComplet = document.location.href;
    var CheminRepertoire = CheminComplet.substring(0, CheminComplet.lastIndexOf("/") + 1);
    var CheminAAvoir = CheminComplet.substring(0, CheminComplet.lastIndexOf("/") + 8);
    
    var NomDuFichier = CheminComplet.substring(CheminComplet.lastIndexOf("/") + 1);
    
    var PanierChemin = CheminComplet.substring(0, CheminComplet.lastIndexOf("/") - 6);
    
    if (NomDuFichier === 'panier') {
        var nombreLivres = document.getElementById('books').value;
        var montantTotal = 0;
        for (var i = 1; i <= nombreLivres; i++) {
            var id = 'montant' + i;
            if (document.getElementById(id) !== null) {
                var inner = document.getElementById(id).innerHTML;
                var montant = parseFloat(inner);
                montantTotal += montant;
            } else {
                montantTotal += 0;
            }
        }
        montantTotal = montantTotal.toFixed(2);
        document.getElementById('montantTotal').innerHTML = montantTotal;
    }

    if (CheminAAvoir === CheminRepertoire + 'panierR') {
        setTimeout(function () {
            window.location = '../panier';
        }, 800);
    }
    if (CheminAAvoir === CheminRepertoire + 'panierM') {
        setTimeout(function () {
            window.location = '../panier';
        }, 800);
    }
    if (CheminAAvoir === CheminRepertoire + 'panierP') {
        setTimeout(function () {
            window.location = '../panier';
        }, 800);
    }
    if (CheminRepertoire === PanierChemin + 'panier/') {
        setTimeout(function () {
            window.location = '../produits';
        }, 800);
    }
    

});