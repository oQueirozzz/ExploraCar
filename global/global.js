function toggleMenu() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
}
 
$(function () {
    $(".menu-link").click(function () {
     $(".menu-link").removeClass("is-active");
     $(this).addClass("is-active");
    });
   });
   
   $(function () {
    $(".main-header-link").click(function () {
     $(".main-header-link").removeClass("is-active");
     $(this).addClass("is-active");
    });
   });
   function toggleInfoTab() {
    const infoTab = document.getElementById('info-tab');
    infoTab.style.display = infoTab.style.display === 'block' ? 'none' : 'block';
   }

function toggleInfoTab() {
    const infoTab = document.getElementById('info-tab');
    infoTab.style.display = infoTab.style.display === 'block' ? 'none' : 'block';
}

function toggleHelpTab() {
    const helpTab = document.getElementById('help-tab');
    helpTab.style.display = helpTab.style.display === 'block' ? 'none' : 'block';
}

function toggleLogoutTab() {
    const logoutTab = document.getElementById('logout-tab');
    logoutTab.style.display = logoutTab.style.display === 'block' ? 'none' : 'block';
}