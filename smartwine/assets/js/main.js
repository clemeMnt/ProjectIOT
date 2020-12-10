
window.onscroll = function() {stickyNavbar()};

var header = document.getElementById("myHeader");
var sticky = header.offsetTop;

function stickyNavbar() {
  if (window.pageYOffset > sticky) {
    header.classList.add("sticky");
  } else {
    header.classList.remove("sticky");
  }
}