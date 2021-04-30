import Places from 'places.js'
import Map from './modules/map'
import 'slick-carousel'
import 'slick-carousel/slick/slick.css'
import 'slick-carousel/slick/slick-theme.css'

Map.init()

let inputAddress = document.querySelector('#address')
if (inputAddress !== null) {
    let place = Places({
        container: inputAddress,
        templates: {
            value: function(suggestion) {
                return suggestion.name;
            }
        }
    }).configure({
        type: 'address'
    });
    place.on('change', e => {
        document.querySelector('#city').value = e.suggestion.city
        document.querySelector('#postalCode').value = e.suggestion.postcode
        document.querySelector('#country').value = e.suggestion.countryCode
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
}

let searchAddress = document.querySelector('#search_address')
if (searchAddress !== null) {
    let place = Places({
        container: searchAddress
    })
    place.on('change', e => {
        document.querySelector('#lat').value = e.suggestion.latlng.lat
        document.querySelector('#lng').value = e.suggestion.latlng.lng
    })
}

//Get the button
let mybutton = document.getElementById("back-to-top");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {
    scrollFunction();
};

function scrollFunction() {
    if (
        document.body.scrollTop > 20 ||
        document.documentElement.scrollTop > 20
    ) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
mybutton.addEventListener("click", backToTop);

function backToTop() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

let $ = require('jquery')
require('../css/app.css');

let $contactButton = $('#contactButton')
$contactButton.click(e => {
    e.preventDefault();
    $('#contactForm').slideDown();
    $contactButton.slideUp();
})

if (localStorage.getItem("cookie-consent") === null) {
    $('#cookie').modal({ backdrop: 'static', keyboard: false, show: true });
    $("#cookieConsent").click(function() {
        localStorage.setItem("cookie-consent", "accord-ok")
        $('#cookie').modal({ backdrop: 'static', keyboard: false, show: false });
    });
}


$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})


// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// var $ = require('jquery');