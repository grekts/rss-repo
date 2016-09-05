$('[class ^= main-menu-button]').click(showMainMenu);

function showMainMenu() {
    mainMenu = $('ul[class ^= main-menu]');
    if(mainMenu.css('display') === 'none') {
        mainMenu.show();
        $('.header-height').css('height', '160px');
    } else {
        mainMenu.hide();
        $('.header-height').css('height', '80px');
    }
}