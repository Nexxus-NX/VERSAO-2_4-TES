document.querySelectorAll('.divP1').forEach(function (draggableDiv) {
  draggableDiv.addEventListener('mousedown', function (e) {
    let startX, startY, startWidth, startHeight;
    let currentDiv;
    function doDrag(e) {
      currentDiv.style.width = (startWidth + e.clientX - startX) + 'px';
      currentDiv.style.height = (startHeight + e.clientY - startY) + 'px';
    }
    function stopDrag() {
      document.documentElement.removeEventListener('mousemove', doDrag, false);
      document.documentElement.removeEventListener('mouseup', stopDrag, false);
      gravPosition(draggableDiv);
      console.log(currentDiv.id);
    }
    if (enable === 2) {
      e.preventDefault();
      startX = e.clientX;
      startY = e.clientY;
      startWidth = parseInt(document.defaultView.getComputedStyle(draggableDiv).width, 10);
      startHeight = parseInt(document.defaultView.getComputedStyle(draggableDiv).height, 10);
      currentDiv = draggableDiv;
      document.documentElement.addEventListener('mousemove', doDrag, false);
      document.documentElement.addEventListener('mouseup', stopDrag, false);
    }
  }, false);
});