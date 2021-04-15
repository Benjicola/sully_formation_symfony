// The better option is to use addEntry() to point to a JavaScript file,
// then require the CSS needed from inside of that.
import '../css/app.css';

// Import Jquery.js, Bootstrap.js and font-awesome.js from node_modules/
import $ from 'jquery';
import 'bootstrap';
import '@fortawesome/fontawesome-free';

$(document).ready(function () {
    // Bootstrap JS dependency
    $('[data-toggle="popover"]').popover();

    // Always show Bootstrap modal for flash messages
    $('.modal').modal('show');
});
