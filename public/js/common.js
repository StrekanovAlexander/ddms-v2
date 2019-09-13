;
(function() {

  const alert = document.querySelector('.alert');
  if (alert) {
    alert.querySelector('.fas').addEventListener('click', function() {
      alert.style = 'display: none';
    })
  }

})();