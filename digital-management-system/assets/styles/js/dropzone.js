'use strict';

import $ from 'jquery';
import '../css/dropzone.min.css';
import Dropzone from './components/dropzone.min.js';

$(document).ready(function() {
    //je récupère l'action où sera traité l'upload en PHP
    var _actionToDropZone = $("#form_snippet_image").attr('action');

    //je définis ma zone de drop grâce à l'ID de ma div citée plus haut.
    Dropzone.autoDiscover = false;
    var myDropzone = new Dropzone("#form_snippet_image", { url: _actionToDropZone });
    console.log(myDropzone);
    console.log('alllo')
});
