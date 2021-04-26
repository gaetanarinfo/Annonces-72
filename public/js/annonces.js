// Vote annonces //

$('#vote-stars1').mouseover(function() {
    $("#stars1").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars1').mouseout(function() {
    $("#stars1").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars2').mouseover(function() {
    $("#stars1").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1").attr('class', 'glyphicon glyphicon-star')
    $("#stars2").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars2').mouseout(function() {
    $("#stars1").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars3').mouseover(function() {
    $("#stars1").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1").attr('class', 'glyphicon glyphicon-star')
    $("#stars2").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star')
    $("#stars3").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars3').mouseout(function() {
    $("#stars1").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars4').mouseover(function() {
    $("#stars1").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1").attr('class', 'glyphicon glyphicon-star')
    $("#stars2").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star')
    $("#stars3").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star')
    $("#stars4").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars4').mouseout(function() {
    $("#stars1").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars5').mouseover(function() {
    $("#stars1").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1").attr('class', 'glyphicon glyphicon-star')
    $("#stars2").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star')
    $("#stars3").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star')
    $("#stars4").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4").attr('class', 'glyphicon glyphicon-star')
    $("#stars5").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars5").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars5').mouseout(function() {
    $("#stars1").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars5").attr('class', 'glyphicon glyphicon-star-empty')
});

const CAT_VACANCES = [
    'Locations & Gîtes',
    'Chambres d\'hôtes',
    'Campings',
    'Hébergements insolites',
    'Hôtels'
];

const CAT_EMPLOI = [
    'Offres d\'emploi',
    'Formations Professionnelles'
];

const CAT_VEHICULES = [
    'Voitures',
    'Motos',
    'Caravaning',
    'Utilitaires',
    'Nautisme',
    'Équipement auto',
    'Équipement moto',
    'Équipement caravaning',
    'Équipement nautisme'
];

const CAT_IMMOBILIER = [
    'Ventes immobilières',
    'Locations',
    'Colocations',
    'Bureaux & Commerces'
];

const CAT_MODE = [
    'Vêtements',
    'Chaussures',
    'Accessoires & Bagagerie',
    'Montres & Bijoux',
    'Équipement bébé',
    'Vêtements bébé'
];

const CAT_MAISON = [
    'Ameublement',
    'Électroménager',
    'Arts de la table',
    'Décoration',
    'Linge de maison',
    'Bricolage',
    'Jardinage'
];

const CAT_MULTIMEDIA = [
    'Informatique',
    'Consoles & Jeux vidéo',
    'Image & Son',
    'Téléphonie'
];

const CAT_lOISIRS = [
    'DVD - Films',
    'CD - Musique',
    'Image & Son',
    'Livres',
    'Vélos',
    'Sports & Hobbies',
    'Instruments de musique',
    'Collection',
    'Jeux & Jouets',
    'Vins & Gastronomie'
];

const CAT_ANIMAUX = [
    'Animaux',
];

const CAT_MATERIEL_PROFESSIONNEL = [
    'Matériel agricole',
    'Transport - Manutention',
    'BTP - Chantier gros-oeuvre',
    'Outillage - Matériaux 2nd-oeuvre',
    'Équipements industriels',
    'Restauration - Hôtellerie',
    'Fournitures de bureau',
    'Commerces & Marchés',
    'Matériel médical'
];

const CAT_SERVICES = [
    'Prestations de services',
    'Billetterie',
    'Évènements',
    'Cours particuliers',
    'Covoiturage'
];

function Category(param) {

    if (param == 0) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_VACANCES.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_VACANCES[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 1) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_EMPLOI.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_EMPLOI[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 2) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_VEHICULES.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_VEHICULES[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 3) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_IMMOBILIER.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_IMMOBILIER[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 4) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_MODE.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_MODE[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 5) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_MAISON.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_MAISON[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 6) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_MULTIMEDIA.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_MULTIMEDIA[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 7) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_lOISIRS.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_lOISIRS[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 8) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_ANIMAUX.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_ANIMAUX[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 9) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_MATERIEL_PROFESSIONNEL.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_MATERIEL_PROFESSIONNEL[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else if (param == 10) {

        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_SERVICES.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_SERVICES[i] + "</option>";

        }

        selects.innerHTML = yroptions;

    } else {
        var selects = document.getElementById("sousCategory"),
            yroptions = "<option disabled selected>Affiner la catégorie</option>";

        for (var i = 0; i < CAT_VACANCES.length; i++) {

            yroptions += "<option value='" + i + "'>" + CAT_VACANCES[i] + "</option>";

        }

        selects.innerHTML = yroptions;
    }

}

$(function() {

    var // Define maximum number of files.
        max_file_number = 4,
        // Define your form id or class or just tag.
        $form = $('form'),
        // Define your upload field class or id or tag.
        $file_upload = $('#pictureFiles', $form),
        // Define your submit class or id or tag.
        $button = $('#submit', $form);

    // Disable submit button on page ready.
    $button.prop('disabled', 'disabled');

    $file_upload.on('change', function() {
        var number_of_images = $(this)[0].files.length;
        if (number_of_images > max_file_number) {
            alert(`Vous avez uploader plus de ${max_file_number} images.`);
            $(this).val('');
            $button.prop('disabled', 'disabled');
        } else {
            $button.prop('disabled', false);
        }
    });
});

// Vote user //

$('#vote-stars1b').mouseover(function() {
    $("#stars1b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1b").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars1b').mouseout(function() {
    $("#stars1b").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars2b').mouseover(function() {
    $("#stars1b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1b").attr('class', 'glyphicon glyphicon-star')
    $("#stars2b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars2b').mouseout(function() {
    $("#stars1b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars3b').mouseover(function() {
    $("#stars1b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1b").attr('class', 'glyphicon glyphicon-star')
    $("#stars2b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star')
    $("#stars3b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars3b').mouseout(function() {
    $("#stars1b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars4b').mouseover(function() {
    $("#stars1b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1b").attr('class', 'glyphicon glyphicon-star')
    $("#stars2b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star')
    $("#stars3b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star')
    $("#stars4b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4b").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars4b').mouseout(function() {
    $("#stars1b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4b").attr('class', 'glyphicon glyphicon-star-empty')
});

$('#vote-stars5b').mouseover(function() {
    $("#stars1b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars1b").attr('class', 'glyphicon glyphicon-star')
    $("#stars2b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star')
    $("#stars3b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star')
    $("#stars4b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4b").attr('class', 'glyphicon glyphicon-star')
    $("#stars5b").removeAttr('class', 'glyphicon glyphicon-star-empty')
    $("#stars5b").attr('class', 'glyphicon glyphicon-star')
});

$('#vote-stars5b').mouseout(function() {
    $("#stars1b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars2b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars3b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars4b").attr('class', 'glyphicon glyphicon-star-empty')
    $("#stars5b").attr('class', 'glyphicon glyphicon-star-empty')
});