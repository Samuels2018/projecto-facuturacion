function getContainerMargin() { 
  // var p = document.querySelector(".main-content > .container");
  var p = document.querySelector(".main-content");
  var style = p.currentStyle || window.getComputedStyle(p);

  let sidenav = document.querySelector('.sidenav')
  if(sidenav && sidenav.length > 0){
    sidenav.style.right = style.marginRight;
    sidenav.style.display = 'block';
  }

}
window.addEventListener('load',getContainerMargin,false);
window.addEventListener("resize", getContainerMargin);