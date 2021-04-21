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