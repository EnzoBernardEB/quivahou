/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */




// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
const $ = require("jquery");
require('webpack-jquery-ui');
require('webpack-jquery-ui/tabs');

global.$ = global.jQuery = $;

// Jquery gestion menu et onglets
$("#tabs").tabs({
    active: localStorage.getItem("currentIdx"),
    activate: function(event, ui) {
        localStorage.setItem("currentIdx", $(this).tabs('option', 'active'));
    }
});

$( function() {
    $( "#menu-profil" ).menu();
} );

// Add fontawsome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

//Print cv


$("#btn").click(function (){
    $("print-non-anonyme").show();
    window.print();
});