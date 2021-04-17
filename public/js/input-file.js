'use strict';

var InputFile = function() {
    // Set options
    if (arguments[0] && typeof arguments[0] === 'object') {
        this.options = arguments[0];
    }

    // Get all the file input fields
    var fields = document.querySelectorAll('input[type="file"]');
    for (var i = 0; i < fields.length; i++) {
        this.createField(fields[i]);
    }
};

InputFile.prototype.createField = function(field) {
    var options = this.options || {};

    // Create drop area
    var dropArea = document.createElement('div');
    dropArea.className = 'inf__drop-area';
    field.parentNode.insertBefore(dropArea, field);
    dropArea.appendChild(field);

    // Create button
    var btn = document.createElement('span');

    dropArea.insertBefore(btn, field);

    // Create hint element
    var hint = document.createElement('span');
    hint.className = 'inf__hint';
    hint.style = 'text-align: center;';
    hint.innerText = options.hint || 'Cliquer ou faites glisser et déposez des fichiers ici';
    dropArea.insertBefore(hint, field);

    // Highlight drag area
    addMultiEventListener(field, 'dragenter click focus', function() {
        dropArea.classList.add('is-active');
    });

    // Back to normal state
    addMultiEventListener(field, 'dragleave drop blur', function() {
        dropArea.classList.remove('is-active');
    });

    // Update inner text
    field.addEventListener('change', function() {
        var filesCount = field.files.length;
        if (filesCount === 1) {
            hint.innerText = field.value.split('\\').pop();
        } else {
            hint.innerText = filesCount + ' ' + (options.message || 'fichier choisi');
        }
    });
};

// Listens to multiple events
function addMultiEventListener(el, e, fn) {
    var events = e.split(' ');
    for (var i = 0; i < events.length; i++) {
        el.addEventListener(events[i], fn, false);
    }
}