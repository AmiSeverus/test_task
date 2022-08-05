document.querySelectorAll('.card__like').forEach(like => {
    like.addEventListener('click', (e) => {
        e.target.classList.toggle('card__like_liked')
    })
});

document.querySelectorAll('.card__star').forEach(star => {
    star.addEventListener('click', (e) => {
        clicked_star = e.currentTarget;
        stars = clicked_star.parentNode.querySelectorAll('.card__star')
        flag = true;
        stars.forEach(star => {
            if (flag) {
                star.classList.add('card__star_stared')
            } else {
                star.classList.remove('card__star_stared')
            }
            if (star === clicked_star)
            {
                flag = false
            }
        })
    })
})

document.querySelectorAll('.filter-option__icon').forEach(icon=>{
    icon.addEventListener('click', (e) => {
        e.target.parentNode.remove();
    })
})