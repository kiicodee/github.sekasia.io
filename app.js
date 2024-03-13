const ham = document.getElementById("#ham-btn");
const navBar = document.getElementById("#nav-menu");

const services = document.getElementById("#services");
const menuServices = document.getElementById("#menu-services");



services.addEventListener('click', (e)=> {
    if(menuServices.classList.contains('md:hidden')) {
        menuServices.classList.remove('md:hidden');
    } else {
        menuServices.classList.add('md:hidden');
    }
})


const partners = document.getElementById("#partners");
const menuPartners = document.getElementById("#menu-partners");



partners.addEventListener('click', (e)=> {
    if(menuPartners.classList.contains('hidden')) {
        menuPartners.classList.remove('hidden');
    } else {
        menuPartners.classList.add('hidden');
    }
})



const clients = document.getElementById("#clients");
const menuClients = document.getElementById("#menu-clients");



clients.addEventListener('click', (e)=> {
    if(menuClients.classList.contains('hidden')) {
        menuClients.classList.remove('hidden');
    } else {
        menuClients.classList.add('hidden');
    }
})







ham.addEventListener('click', (e)=> {
    if(navBar.classList.contains('hidden')) {
        navBar.classList.remove('hidden');
    } else {
        navBar.classList.add('hidden');
    }
})
