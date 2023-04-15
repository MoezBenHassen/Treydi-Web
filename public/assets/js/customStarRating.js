
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

    function ratingHandler(e) {

    const childStars = e.target.children;
    for(let i = 0; i < childStars.length; i++) {
    const star = childStars.item(i)
    if (star.dataset.checked === "true") {
    star.classList.add('hover');
}
    else {
    star.classList.remove('hover');
}
}
}

    function starSelection(e) {

    const parent = e.target.parentElement
    const childStars = parent.children;
    const dataset = e.target.dataset;
    const note = +dataset.note; // Convert note (string) to note (number)
    for (let i = 0; i < childStars.length; i++) {
    const star = childStars.item(i)
    if (+star.dataset.note > note) {
    star.classList.remove('hover');
} else {
    star.classList.add('hover');
}
}
}
    var labelWasClicked = function labelWasClicked(){
        console.log('labelWasClicked');
        var input = $(this).siblings().filter('input');
        if (input.attr('disabled')) {
            return;
        }
        console.log('not disabled');false
        input.val($(this).attr('data-value'));
        /*console log the input value*/
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
                star.classList.remove('hover');
                star.dataset.checked = "false";
            } else {
                star.classList.add('hover');
                star.dataset.checked = "true";
            }
        }

        const noteTextElement = parent.parentElement.lastElementChild.children.item(0)
        console.log("note: "+note);
        noteTextElement.innerText = `Note: ${note}`;
        /*add  note to the input with the id article_avgRating*/
        document.getElementById('article_avgRating').value = note;


    }

    /*################################################"""*/