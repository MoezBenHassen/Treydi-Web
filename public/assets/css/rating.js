// $(function(){
//     console.log('ready&');
//     var labelWasClicked = function labelWasClicked(){
//         console.log('labelWasClicked');
//         var input = $(this).siblings().filter('input');
//         if (input.attr('disabled')) {
//             return;
//         }
//         console.log('not disabled');false
//         input.val($(this).attr('data-value'));
//         /*console log the input value*/
//         console.log("value: "+input.val());
//     }
//
//     var turnToStar = function turnToStar(){
//         console.log('turnToStar');
//         if ($(this).find('input').attr('disabled')) {
//         console.log('disabled turnTOStar');
//             return;
//         }
//         console.log('not disabled turnTOStar');
//         var labels = $(this).find('div');
//         labels.removeClass();
//         labels.addClass('star');
//     }
//
//     var turnStarBack = function turnStarBack(){
//         console.log('turnStarBack');
//         var rating = parseInt($(this).find('input').val());
//         if (rating > 0) {
//             console.log('rating > 0');
//             var selectedStar = $(this).children().filter('#rating_star_'+rating)
//             var prevLabels = $(selectedStar).nextAll();
//             prevLabels.removeClass();
//             prevLabels.addClass('star-full');
//             selectedStar.removeClass();
//             selectedStar.addClass('star-empty');
//         }
//         console.log('rating = 0');
//     }
// console.log('ready');
//     $('.star, .rating-well').click(labelWasClicked);
//     /*print the inputs value in console*/
//     $('.star, .rating-well').click(function(){
//         console.log($(this).find('input').val());
//     });
//     $('.rating-well').each(turnStarBack);
//     $('.rating-well').hover(turnToStar,turnStarBack);
//
//     document.addEventListener('DOMContentLoaded', function() {
//         const stars = document.querySelectorAll('.star');
//
//         stars.forEach(function(star) {
//             star.addEventListener('click', function() {
//                 const rating = star.getAttribute('value');
//                 document.getElementById('article_rating').value = rating;
//             });
//         });
//     });
//
// });