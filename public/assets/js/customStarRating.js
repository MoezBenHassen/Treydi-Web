var avgRating = document.getElementsByClassName('avgRatingInput')[0].value;

const avgRatingValue = parseInt(avgRating);
const avgRatingStars = document.querySelectorAll(`.rating .star[data-note="${avgRatingValue}"]`);
/*avgRatingStars.forEach(star => star.classList.add('hover'));*/

const ratings = document.querySelectorAll('.rating');

ratings.forEach(rating =>
    rating.addEventListener('mouseleave', ratingHandler)
);

const stars = document.querySelectorAll('.rating .star');
stars.forEach(star => {
    star.addEventListener('mouseover', starSelection);
    star.addEventListener('mouseleave', starSelection);
    star.addEventListener('click', activeSelect);
});


/*
* The ratingHandler function is called when the mouse leaves a rating element.
* Its purpose is to remove the "hover" class from all stars that have not been checked, and add the "hover" class to the
* star that matches the average rating value.
* This is done to provide visual feedback to the user when they move their mouse away from the rating element.
* */
function ratingHandler(e) {
    /*alert('ratingHandler');*/
    const childStars = e.target.children;
    for (let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i)
        if (star.dataset.checked === "true") {
            star.classList.add('hover');
        } else {
            star.classList.remove('hover');
        }
        // Add 'hover' class to the avgRatingValue star
  /*      if (star.dataset.note === avgRatingValue.toString()) {
            star.classList.add('hover');
        }*/
    }
}


function starSelection(e) {
    const parent = e.target.parentElement;
    const childStars = parent.children;
    const dataset = e.target.dataset;
    const note = +dataset.note; // Convert note (string) to note (number)

    for (let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i);

        if (+star.dataset.note > note) {
            star.classList.remove('hover');
        } else {
            star.classList.add('hover');
        }
    }

    // Add 'hover' class to the avgRatingValue star

  /*  avgRatingStars.forEach(star => star.classList.add('hover'));*/
}


var labelWasClicked = function labelWasClicked() {
    console.log('labelWasClicked');
    var input = $(this).siblings().filter('input');
    if (input.attr('disabled')) {
        return;
    }
    console.log('not disabled');
    false
    input.val($(this).attr('data-value'));
    console.log("value: "+input.val());
}

function activeSelect(e) {
    const parent = e.target.parentElement
    const childStars = parent.children;
    const dataset = e.target.dataset;
    const note = +dataset.note; // Convert note (string) to note (number)
    for (let i = 0; i < childStars.length; i++) {
        const star = childStars.item(i)
        if (+star.dataset.note > note) {
            star.classList.remove('ratingStar');
            star.dataset.checked = "false";
        } else {
            star.classList.add('ratingStar');
            star.dataset.checked = "true";
        }
    }

    const noteTextElement = parent.parentElement.lastElementChild.children.item(0)
    console.log("note: "+note);
    noteTextElement.innerText = `Note: ${note}`;

    /*UPDATE THE HIDDEN INPUT VALUE AFTER RATING CHANGE  BY USER*/
    document.getElementsByClassName('avgRatingInput')[0].value = note;
}
