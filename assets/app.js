/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';
import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/css/bootstrap-grid.min.css';
import 'bootstrap/dist/js/bootstrap.js';
require('bootstrap');
require('startbootstrap-sb-admin-2/css/sb-admin-2.css');
require('startbootstrap-sb-admin-2/js/sb-admin-2.js');
require('startbootstrap-sb-admin-2/vendor/fontawesome-free/css/all.css');
require('startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.css');
require('startbootstrap-sb-admin-2/vendor/datatables/dataTables.bootstrap4.js');
require('startbootstrap-sb-admin-2/vendor/datatables/jquery.dataTables.js');
require('startbootstrap-sb-admin-2/vendor/jquery/jquery.js');

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';


console.log('Hello Webpack Encore! Edit me in assets/app.js');
