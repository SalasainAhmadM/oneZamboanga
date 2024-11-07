const bellIcon = document.getElementById('bell-icon');
const actFeed = document.querySelector('.actFeed');
const notifShow = document.querySelector('.actFeed.notif');
const actHeader = document.querySelector('.actHeader');
const notifHeader = document.querySelector('.notifHeader h4');
const actIcon = document.getElementById('act-icon');


// const notifBox = document.querySelector('.notif');
    
bellIcon.addEventListener('click', function() {
    // if (notifBox.style.display === 'block') {
    //     notifBox.style.display = 'none';
    // } else {
    //     notifBox.style.display = 'block';
    // }

    actFeed.style.display = 'none';
    actHeader.style.display = 'none';
    bellIcon.style.display = 'none';

    actIcon.style.display = 'block';
    notifHeader.style.display = 'block';
    notifShow.style.display = 'block';
});

actIcon.addEventListener('click', function() {
    actIcon.style.display = 'none';
    notifHeader.style.display = 'none';
    notifShow.style.display = 'none';

    bellIcon.style.display = 'block';
    actFeed.style.display = 'block';
    actHeader.style.display = 'block';
});
