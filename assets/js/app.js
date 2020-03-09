/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';

// const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
// import './ConsentCookies.js'
// import './ConsentCookies-2.js'
// import './fullpage.js'
// import  'upload-image-file.js'
// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// <!-- Scroll up and down detection/movement -->


$(document).ready(function () {

    // if( $("#menu_detaille_form_photo").parent('.custom-file').find('.custom-file-label').text() !== "Choisir une image")
    if ($("#menu_detaille_form_photo").attr('value') !== "") {
        let value = $("#menu_detaille_form_photo").attr('value');
        $("#image-detaille-P").attr('src', "/images/" + value);
        $("#menu_detaille_form_photo").parent('.custom-file').find('.custom-file-label').text(value);
    }


    for (let i = 1; i <= 4; i++)
        if ($("#menu_detaille_form_photo" + i).attr('value') !== "") {
            let value = $("#menu_detaille_form_photo" + i).attr('value');
            $("#image" + i).attr('src', "/images/" + value);
            $("#menu_detaille_form_photo" + i).parent('.custom-file').find('.custom-file-label').text(value);
        }


});
function activate_Flex(){
    if($( ".navbar-burger" ).is(':hidden') ) {
        $('#navbarBasicExample').removeAttr('style');
        $('#navbarBasicExample').removeClass('is-burger-activate');
        $('.user').css('display','block!important');
        $('.navbar').addClass('is-flex');
    }
    if($( ".navbar-burger" ).is(':visible') ){
        $('#navbarBasicExample').css('display', 'none');
        $('.base-nav').removeClass('is-flex');
        $('.user').css('display','none!important');
    }
}
$('.navbar-burger').click(function () {

    $('#navbarBasicExample').toggle()
        .toggleClass("is-burger-activate");
    $('.navbar').css('display',"block");
 });
$( window ).on('resize',function() {
    activate_Flex();
});
// function readURL(input, image) {
//     if (input.files && $input.files[0]) {
//         let files = input.files;
//         var reader = new FileReader();
//         reader.onload = function (e) {
//             image.attr('src', e.target.result);
//             input.parent('.custom-file').find('.custom-file-label').text(files[0].name);
//             // $('.custom-file-input').attr('title',input.files[0].name);
//             // $('.custom-file-input').attr('label',$('.custom-file-label').text());
//         }
//         reader.readAsDataURL(input.files[0]);
//     }
// }


$("#menu_form_photo").change(function (e) {
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image-P').attr('src', e.target.result);
            $("#menu_form_photo").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

$("#menu_detaille_form_photo").change(function (e) {
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image-detaille-P').attr('src', e.target.result);
            $("#menu_detaille_form_photo").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
$("#menu_detaille_form_photo2").change(function (e) {
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image2').attr('src', e.target.result);
            $("#menu_detaille_form_photo2").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
$("#menu_detaille_form_photo3").change(function (e) {
    // readURL("menu_detaille_form_photo2", "image2");
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image3').attr('src', e.target.result);
            $("#menu_detaille_form_photo3").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
$("#menu_detaille_form_photo1").change(function (e) {
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image1').attr('src', e.target.result);
            $("#menu_detaille_form_photo1").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});
$("#menu_detaille_form_photo4").change(function (e) {
    if (this.files && this.files[0]) {
        let files = this.files;
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image4').attr('src', e.target.result);
            $("#menu_detaille_form_photo4").parent('.custom-file').find('.custom-file-label').text(files[0].name);
        }
        reader.readAsDataURL(this.files[0]);
    }
});

// $("#custom-file-input").on('label-change',function(e){
//     alert('coucou');
//     $('#image-P').attr('src', 'images'+$('.custom-file-input').name);
//     $('.custom-file-label').text(input.files[0].name);
//  });


// $(document).on('change', '.custom-file-input', function (e) {
//     let fileName = $(this).val().replace(/\\/g, '/').replace(/.*\//, '');
//     $(this).parent('.custom-file').find('.custom-file-label').text(fileName);
//     $('#image-P').attr('src', e.target.result);
// });
document.addEventListener('DOMContentLoaded', function () {

    // Get all "navbar-burger" elements
    var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {

                // Get the target from the "data-target" attribute
                var target = $el.dataset.target;
                var $target = document.getElementById(target);

                // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');

            });
        });
    }

});

bulmaCarousel.attach('#carousel-demo', {
    slidesToScroll: 1,
    slidesToShow: 4
});
// Initialize all div with carousel class
var carousels = bulmaCarousel.attach('.carousel', options);

// Loop on each carousel initialized
for(var i = 0; i < carousels.length; i++) {
    // Add listener to  event
    carousels[i].on('before:show', state => {
        console.log(state);
    });
}

// Access to bulmaCarousel instance of an element
var element = document.querySelector('#my-element');
if (element && element.bulmaCarousel) {
    // bulmaCarousel instance is available as element.bulmaCarousel
    element.bulmaCarousel.on('before-show', function(state) {
        console.log(state);
    });
}

//Gestion de la souris scroll/wheel
var pageHeight = window.innerHeight;
var isAnimating = false;
document.body.style.transform = 'translate3d(0px,0px,0px)';

document.addEventListener('scroll', function(e){
    document.body.scrollTop = 0;
});
document.addEventListener('wheel', wheelListener);

function wheelListener(e) {
    if(e.deltaY > 0) {
        scrollPage(-pageHeight);
    } else {
        scrollPage(+pageHeight);
    }
}

function scrollPage(scrollSize) {
    if(isAnimating){
        return;
    }
    isAnimating = true;
    var yPos = getNewYPos(scrollSize);
    document.body.style.transform = 'translate3d(0px,'+ yPos + ',0px)';
}

function getNewYPos(add){
    var oldYPos = document.body.style.transform.split(',')[1];
    oldYPos = parseInt(oldYPos.replace(/px/,''));
    var newYPos = oldYPos + add;
    if(newYPos > 0){
        isAnimating = false;
    }
    return Math.min(0, newYPos) + 'px';
}


document.body.addEventListener('transitionend', function(){
    setTimeout(function(){ isAnimating = false; }, 500);
    document.addEventListener('wheel', wheelListener);
})


console.log('Hello Webpack Encore! Edit me in assets/js/app.js');
require('../css/app.scss');
// require the JavaScript
// require('bootstrap');
// require('bootstrap-star-rating');
// // require 2 CSS files needed
// require('bootstrap-star-rating/css/star-rating.css');
// require('bootstrap-star-rating/themes/krajee-svg/theme.css');
